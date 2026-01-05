<?php

namespace App\Traits;

trait ImageTrait
{
    //gere l'image d'un produit
    public function getUrlImage(?string $nomFichier, string $placeholderUrl = null): string
    {
        if ($placeholderUrl === null) {
            $placeholderUrl = 'https://images.unsplash.com/photo-1600166898405-da9535204843?q=80&w=400';
        }

        if (empty($nomFichier)) {
            return $placeholderUrl;
        }

        if (strpos($nomFichier, 'http') === 0) {
            return $nomFichier;
        }

        return base_url('images/' . $nomFichier);
    }
}