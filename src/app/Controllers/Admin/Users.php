<?php

namespace App\Controllers\Admin;
use Exception;

class Users extends AdminBaseController
{
    // Affichage de la liste des utilisateurs
    public function index()
    {
        $roleFilter = $this->request->getGet('role');

        $data = array_merge($this->adminData, [
            'title' => 'Gestion des Utilisateurs',
            'subtitle' => 'Gestion des comptes clients et vendeurs',
            'pendingSellers' => $this->sellerModel->getSellersPendingValidation(5),
            'allUsers' => $this->userModel->getAdminAllUsersPaginated(10, $roleFilter),
            'pagerSellers' => $this->sellerModel->pager,
            'pagerUsers' => $this->userModel->pager,
            'currentRole' => $roleFilter,
        ]);

        return view('admin/users/index', $data);
    }

    // Approuver un compte vendeur
    public function approveSeller($id)
    {
        try {
            $this->sellerModel->validateSeller($id);
            return redirect()->to('admin/users')->with('success', 'Compte vendeur validé.');
        } catch (\Exception $e) {
            return redirect()->to('admin/users')->with('error', 'Erreur lors de la validation.');
        }
    }

    // Rejeter un compte vendeur avec une raison
    public function rejectSeller($id)
    {
        $reason = trim((string) $this->request->getVar('reason'));

        if (empty($reason)) {
            $reason = "Dossier incomplet ou non conforme.";
        }

        try {
            $this->sellerModel->rejectSeller($id, $reason);
            return redirect()->back()->with('warning', 'Compte refusé (Motif : ' . $reason . ')');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Erreur : ' . $e->getMessage());
        }
    }

    // Suppression d'un utilisateur
    public function delete($id)
    {
        $user = $this->userModel->find($id);

        if (!$user) {
            return redirect()->back()->with('error', 'Utilisateur introuvable.');
        }

        try {
            if ($this->sellerModel->find($id)) {
                $this->sellerModel->delete($id);
            }
            $this->userModel->delete($id);

            return redirect()->back()->with('success', 'Utilisateur supprimé.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Impossible de supprimer.');
        }
    }
}
