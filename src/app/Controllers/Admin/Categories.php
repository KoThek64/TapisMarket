<?php

namespace App\Controllers\Admin;

use App\Entities\Category;

class Categories extends AdminBaseController
{
    public function index()
    {
        $data = array_merge($this->adminData, [
            'title'      => 'Gestion des catégories',
            'categories' => $this->categoryModel->getAllCategoriesPaginated(10),
            'pager'      => $this->categoryModel->pager,
        ]);

        return view('admin/categories/index', $data);
    }

    public function new()
    {
        $data = array_merge($this->adminData, [
            'title'    => 'Nouvelle Catégorie',
            'category' => new Category(),
            'action'   => 'create'
        ]);
        
        return view('admin/categories/form', $data);
    }

    public function create()
    {
        $data = [
            'name'        => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
        ];

        if ($this->categoryModel->save($data)) {
            return redirect()->to('admin/categories')->with('success', 'Catégorie créée avec succès.');
        } else {
            return redirect()->back()->withInput()->with('errors', $this->categoryModel->errors());
        }
    }

    public function edit($id)
    {
        $category = $this->categoryModel->find($id);

        if (!$category) {
            return redirect()->to('admin/categories')->with('error', 'Catégorie introuvable.');
        }

        $data = array_merge($this->adminData, [
            'title'    => 'Éditer la catégorie : ' . $category->name,
            'category' => $category,
            'action'   => 'edit'
        ]);

        return view('admin/categories/form', $data);
    }

    public function update($id)
    {
        $data = [
            'id'          => $id,
            'name'        => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
        ];

        if ($this->categoryModel->save($data)) {
            return redirect()->to('admin/categories')->with('success', 'Catégorie mise à jour.');
        } else {
            return redirect()->back()->withInput()->with('errors', $this->categoryModel->errors());
        }
    }

    public function delete($id)
    {
        if ($this->categoryModel->find($id)) {
            try {
                $this->categoryModel->delete($id);
                return redirect()->back()->with('success', 'Catégorie supprimée.');
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Impossible de supprimer (contient des produits).');
            }
        }
        return redirect()->back()->with('error', 'Catégorie introuvable.');
    }
}
