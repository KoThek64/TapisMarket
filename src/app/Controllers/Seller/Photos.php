<?php


namespace App\Controllers\Seller;

use Exception;
use App\Models\ProductPhotoModel;
use Config\Services;

class Photos extends SellerBaseController
{
    protected $photoModel;

    public function __construct()
    {
        $this->photoModel = model(ProductPhotoModel::class);
    }

    public function create($productId)
    {
        $productId = (int)$productId;
        $files = $this->request->getFiles()['photos'] ?? [];

        $result = $this->processUploads($productId, $files);

        if (!empty($result['errors'])) {
            return $this->response->setStatusCode(400)->setJSON(['errors' => $result['errors']]);
        }

        return $this->response->setStatusCode(201)->setJSON(['uploaded' => $result['uploaded']]);
    }

    public function update($productId, $photoId)
    {
        $productId = (int)$productId;
        $setAsMain = $this->request->getPost('set_as_main') === '1';

        $photo = $this->photoModel->find($photoId);
        if (!$photo || $photo->product_id != $productId) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Photo non trouvée.']);
        }

        if ($setAsMain) {
            $this->photoModel->setMain($photoId, $productId);
        }

        return $this->response->setStatusCode(200)->setJSON(['message' => 'Photo mise à jour avec succès.']);
    }

    public function delete($productId, $photoId)
    {
        $productId = (int)$productId;

        $photo = $this->photoModel->find($photoId);
        if (!$photo || $photo->product_id != $productId) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Photo non trouvée.']);
        }

        if ($this->deletePhoto([
            'id'         => $photo->id,
            'product_id' => $photo->product_id,
            'file_name'  => $photo->file_name
        ])) {
            return $this->response->setStatusCode(200)->setJSON(['message' => 'Photo supprimée avec succès.']);
        } else {
            return $this->response->setStatusCode(500)->setJSON(['error' => 'Échec de la suppression de la photo.']);
        }
    }

    /**
     * Compte le nombre de photos pour un produit
     */
    private function countPhotos(int $productId): int
    {
        return $this->photoModel->where('product_id', $productId)->countAllResults();
    }

    //gestion de l'upload des images
    private function processUploads(int $productId, array $files): array
    {
        $uploadedData = [];
        $errors = [];
        $productDir = PATH_PRODUCTS . $productId . '/';

        // Vérification du nombre max de photos (Business Rule)
        $currentCount = $this->countPhotos($productId);
        $validFileCount = 0;
        foreach ($files as $f) {
            if ($f->isValid() && !$f->hasMoved()) $validFileCount++;
        }

        if (($currentCount + $validFileCount) > 5) {
            return [
                'uploaded' => [],
                'errors' => ["Limite de 5 photos atteinte. Vous en avez $currentCount et tentez d'en ajouter $validFileCount."]
            ];
        }

        // Crée le répertoire du produit s'il n'existe pas
        if (!is_dir($productDir)) {
            mkdir($productDir, 0777, true);
        }

        foreach ($files as $file) {
            if (!$file->isValid() || $file->hasMoved()) {
                $errors[] = $file->getClientName() . ': ' . $file->getErrorString();
                continue;
            }

            //Validation du type Mime
            $mime = $file->getMimeType();
            $allowedMimes = explode(',', ALLOWED_IMAGE_TYPES);

            if (!in_array($mime, $allowedMimes)) {
                $errors[] = $file->getClientName() . ": Type de fichier non autorisé ($mime).";
                continue;
            }

            //validation de la taille
            $sizeMB = $file->getSizeByUnit('mb');
            $maxSizeMB = MAX_UPLOAD_SIZE / 1024;

            if ($sizeMB > $maxSizeMB) {
                $errors[] = $file->getClientName() . ": Fichier trop volumineux (>{$maxSizeMB}MB).";
                continue;
            }

            $newName = $file->getRandomName();
            $finalPath = $productDir . $newName;

            try {
                // On essaye de déplacer le fichier et de l'optimiser
                $file->move($productDir, $newName);

                // met les perms
                @chmod($finalPath, 0666);
                @chmod($productDir, 0777);

                $this->optimizeImage($finalPath);

                //on check quelle numero de photo on doit lui donner
                $displayOrder = $this->photoModel->getNextDisplayOrder($productId);

                $photoId = $this->photoModel->insert([
                    'product_id'    => $productId,
                    'file_name'     => $newName,
                    'display_order' => $displayOrder
                ]);

                if (!$photoId) {
                    throw new Exception("Erreur DB: Insertion échouée.");
                }

                // On ajoute les infos de la photo uploadée à la liste
                $uploadedData[] = [
                    'id'   => $photoId,
                    'name' => $newName,
                    'url'  => base_url('uploads/products/' . $productId . '/' . $newName)
                ];

            } catch (Exception $e) {
                // En cas d'erreur, on supprime le fichier s'il a été déplacé
                if (file_exists($finalPath)) {
                    @unlink($finalPath);
                }
                $errors[] = $file->getClientName() . ': ' . $e->getMessage();
            }
        }

        return ['uploaded' => $uploadedData, 'errors' => $errors];
    }

    // supprime une photo spécifique
    private function deletePhoto(array $photo): bool
    {
        $path = PATH_PRODUCTS . $photo['product_id'] . '/' . $photo['file_name'];

        if (file_exists($path)) {
            // le @ sert a eviter les problèmes si jamais y'a un probleme de droit
            @unlink($path);
        }

        return $this->photoModel->delete($photo['id']);
    }

    // supprime les entrées de la base de données pour les fichiers manquants
    private function cleanupMissingFiles(int $productId): void
    {
        $dbPhotos = $this->photoModel->where('product_id', $productId)->asArray()->findAll();
        $productDir = PATH_PRODUCTS . $productId . '/';

        foreach ($dbPhotos as $photo) {
            if (empty($photo['file_name'])) continue;
            if (!file_exists($productDir . $photo['file_name'])) {
                $this->photoModel->delete($photo['id']);
            }
        }
    }

    // --- Private Helpers ---

    private function optimizeImage(string $path)
    {
        try {
            $image = Services::image();
            $image->withFile($path);

            $info = $image->getFile()->getProperties(true);
            if (($info['width'] ?? 0) > MAX_IMAGE_DIMENSION) {
                $image->resize(MAX_IMAGE_DIMENSION, MAX_IMAGE_DIMENSION, true, 'height');
                $image->save($path, 85);
            }
        } catch (Exception $e) {
            log_message('error', 'Image optimization failed: ' . $e->getMessage());
        }
    }

    private function remove_directory(string $dir): bool
    {
        if (!is_dir($dir)) {
            return false;
        }

        // On scanne le dossier en ignorant . et ..
        $items = array_diff(scandir($dir), ['.', '..']);

        foreach ($items as $item) {
            $path = $dir . DIRECTORY_SEPARATOR . $item;

            // Si c'est un dossier, on rappelle la fonction ou sinon on supprime le fichier
            is_dir($path) ? remove_directory($path) : unlink($path);
        }
        // Une fois vide, on supprime le dossier parent
        return rmdir($dir);
    }
}
