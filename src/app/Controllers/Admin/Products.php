<?php

namespace App\Controllers\Admin;

class Products extends AdminBaseController
{
    public function index()
    {
        $data = array_merge($this->adminData, [
            'title' => 'Modération des Produits',
            'subtitle' => 'Validation et Conformité du catalogue',
            'pendingProducts' => $this->productModel->getPendingProductsPaginated(5),
            'allProducts' => $this->productModel->getAllProductsPaginated(10),
            'pager' => $this->productModel->pager,
            'pendingProductsCount' => $this->productModel->countPendingProducts()
        ]);

        return view('admin/products/index', $data);
    }

    public function approve($id)
    {
        if (!$this->productModel->find($id)) {
            return redirect()->back()->with('error', 'Produit introuvable.');
        }
        $this->productModel->validateProduct($id);
        return redirect()->to('admin/products')->with('success', "Produit validé et mis en ligne.");
    }

    public function reject($id)
    {
        $reason = trim((string) $this->request->getVar('reason'));

        if (!$this->productModel->find($id)) {
            return redirect()->back()->with('error', 'Produit introuvable.');
        }

        if ($reason === '') {
            $reason = "Ne respecte pas la charte.";
        }

        try {
            $this->productModel->rejectProduct($id, $reason);
            return redirect()->back()->with('warning', 'Produit refusé (Raison : ' . $reason . ')');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur SQL : ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        if ($this->productModel->find($id)) {
            $this->productModel->delete($id);
            return redirect()->back()->with('success', 'Produit supprimé.');
        }
        return redirect()->back()->with('error', 'Produit introuvable.');
    }
}
