<?php

namespace App\Controllers\Admin;

use App\Entities\Category;
use Exception;

class Categories extends AdminBaseController
{
    // Affichage de la liste des catégories
    public function index()
    {
        $data = array_merge($this->adminData, [
            'title' => 'Gestion des catégories',
            'subtitle' => 'Organisation du catalogue',
            'categories' => $this->categoryModel->getAllCategoriesPaginated(10),
            'pager' => $this->categoryModel->pager,
        ]);

        return view('pages/admin/categories/index', $data);
    }

    // Formulaire de création d'une nouvelle catégorie
    public function new()
    {
        $data = array_merge($this->adminData, [
            'title' => 'Nouvelle Catégorie',
            'category' => new Category(),
            'action' => 'create'
        ]);

        return view('pages/admin/categories/form', $data);
    }

    // Traitement de la création
    public function create()
    {
        $data = [
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
        ];

        if ($this->categoryModel->save($data)) {
            return redirect()->to('admin/categories')->with('success', 'Catégorie créée avec succès.');
        } else {
            return redirect()->back()->withInput()->with('errors', $this->categoryModel->errors());
        }
    }

    // Formulaire d'édition d'une catégorie
    public function edit($id)
    {
        $category = $this->categoryModel->find($id);

        if (!$category) {
            return redirect()->to('admin/categories')->with('error', 'Catégorie introuvable.');
        }

        $data = array_merge($this->adminData, [
            'title' => 'Éditer la catégorie : ' . $category->name,
            'category' => $category,
            'action' => 'edit'
        ]);

        return view('pages/admin/categories/form', $data);
    }

    // Traitement de la mise à jour
    public function update($id)
    {
        $data = [
            'id' => $id,
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
        ];

        if ($this->categoryModel->save($data)) {
            return redirect()->to('admin/categories')->with('success', 'Catégorie mise à jour.');
        } else {
            return redirect()->back()->withInput()->with('errors', $this->categoryModel->errors());
        }
    }

    // Suppression d'une catégorie
    public function delete($id)
    {
        if ($this->categoryModel->find($id)) {
            try {
                $this->categoryModel->delete($id);
                return redirect()->back()->with('success', 'Catégorie supprimée.');
            } catch (Exception $e) {
                return redirect()->back()->with('error', 'Impossible de supprimer (contient des produits).');
            }
        }
        return redirect()->back()->with('error', 'Catégorie introuvable.');
    }
}
