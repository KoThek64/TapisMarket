<?php

namespace App\Controllers\Admin;

class Users extends AdminBaseController
{
    public function index()
    {
        $roleFilter = $this->request->getGet('role');

        $data = array_merge($this->adminData, [
            'title' => 'Gestion des Utilisateurs',
            'subtitle' => 'Gestion des comptes clients et vendeurs',
            'pendingSellers' => $this->sellerModel->getSellersPendingValidation(5),
            'allUsers' => $this->userModel->getAllUsersPaginated(10, $roleFilter),
            'pagerSellers' => $this->sellerModel->pager,
            'pagerUsers' => $this->userModel->pager,
            'currentRole' => $roleFilter,
            'pendingSellersCount' => $this->sellerModel->countSellersPendingValidation()
        ]);

        return view('admin/users/index', $data);
    }

    public function approveSeller($id)
    {
        try {
            $this->sellerModel->validateSeller($id);
            return redirect()->to('admin/users')->with('success', 'Compte vendeur validé.');
        } catch (\Exception $e) {
            return redirect()->to('admin/users')->with('error', 'Erreur lors de la validation.');
        }
    }

    public function refuseSeller($id)
    {
        $reason = trim((string) $this->request->getVar('reason'));

        if (empty($reason)) {
            $reason = "Dossier incomplet ou non conforme.";
        }

        try {
            $this->sellerModel->rejectSeller($id, $reason);
            return redirect()->back()->with('warning', 'Compte refusé (Motif : ' . $reason . ')');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur : ' . $e->getMessage());
        }
    }

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
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Impossible de supprimer.');
        }
    }
}
