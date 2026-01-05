<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Entities\Categorie;

class CategorieModel extends Model
{
    protected $table            = 'categorie';
    protected $primaryKey       = 'id_categorie';
    protected $returnType       = Categorie::class;
    
    protected $allowedFields    = ['nom', 'alias', 'description', 'image_url'];

    protected $validationRules  = [
        'nom'   => 'required|min_length[3]|max_length[100]|is_unique[categorie.nom,id_categorie,{id_categorie}]',
        'alias' => 'is_unique[categorie.alias,id_categorie,{id_categorie}]',
    ];

    protected $beforeInsert = ['genererAlias'];
    protected $beforeUpdate = ['genererAlias'];


    public function getCategoriesMenu()
    {
        return $this->orderBy('nom', 'ASC')->findAll();
    }

    // Génération automatique 
    protected function genererAlias(array $data)
    {
        if (isset($data['data']['nom']) && empty($data['data']['alias'])) {
            $data['data']['alias'] = url_title($data['data']['nom'], '-', true);
        }
        return $data;
    }
}