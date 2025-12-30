<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;
use CodeIgniter\I18n\Time;

class Avis extends Entity
{
    protected $dates = ['date_publication'];
    
    protected $casts = [
        'note'       => 'integer',
        'id_produit' => 'integer',
        'id_client'  => 'integer'
    ];

    // Retourne une représentation html en étoiles de la note
    public function getEtoiles(): string
    {
        $note = $this->attributes['note'];
        $note = max(0, min(5, $note));
        $pleines = str_repeat('★', $note);
        $vides = str_repeat('<span style="color:#ddd;">★</span>', 5 - $note);

        return '<span style="color:#f1c40f; font-size:1.2em;">' . $pleines . $vides . '</span>';
    }

    // Retourne un extrait du commentaire
    public function getExtraitCommentaire(int $longueur = 50): string
    {
        $texte = $this->attributes['commentaire'] ?? '';

        if (mb_strlen($texte) > $longueur) {
            return mb_substr($texte, 0, $longueur) . '...';
        }
        return $texte;
    }

    // Retourne la date de publication au format relatif
    public function getDateRelative(): string
    {
        return $this->date_publication->humanize();
    }

    // Vérifie si l'avis est publié ca pourra etre utilis pour l'admin
    public function estPublie(): bool
    {
        return $this->attributes['statut_moderation'] === 'PUBLIE';
    }

}