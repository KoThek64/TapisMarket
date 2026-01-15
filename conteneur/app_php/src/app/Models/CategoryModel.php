<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Entities\Category;

class CategoryModel extends Model
{
    protected $table = 'categories';
    protected $primaryKey = 'id';
    protected $returnType = Category::class;

    protected $allowedFields = ['name', 'alias', 'description', 'image_url'];

    protected $validationRules = [
        'name' => 'required|min_length[3]|max_length[100]|is_unique[categories.name,id,{id}]',
        'alias' => 'is_unique[categories.alias,id,{id}]',
        'id' => 'permit_empty|is_natural_no_zero',
    ];

    protected $beforeInsert = ['generateAlias'];
    protected $beforeUpdate = ['generateAlias'];


    // Retrieve all categories sorted with pagination
    public function getAllCategoriesPaginated(int $perPage = 10)
    {
        return $this->orderBy('name', 'ASC')->paginate($perPage);
    }

    // Automatic generation
    protected function generateAlias(array $data)
    {
        if (isset($data['data']['name']) && empty($data['data']['alias'])) {
            $data['data']['alias'] = url_title($data['data']['name'], '-', true);
        }
        return $data;
    }
}
