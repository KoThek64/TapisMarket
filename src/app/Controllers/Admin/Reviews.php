<?php

namespace App\Controllers\Admin;

class Reviews extends AdminBaseController
{
    // Affiche la gestion des avis
    public function index()
    {
        $filter = $this->request->getGet('filter');

        $data = array_merge($this->adminData, [
            'title' => 'Gestion des Avis',
            'subtitle' => 'Modération des commentaires publiés',
            'reviews' => $this->reviewModel->getReviewsByFilter($filter, 10),
            'pager' => $this->reviewModel->pager,
            'currentFilter' => $filter,
            'criticalCount' => $this->reviewModel->countCriticalReviews(),
        ]);

        return view('admin/reviews/index', $data);
    }

    // Change le statut d'un avis (publié ou refusé)
    public function changeStatus($id, $newStatus)
    {
        if (!in_array($newStatus, [REVIEW_PUBLISHED, REVIEW_REFUSED])) {
            return redirect()->back()->with('error', 'Statut invalide.');
        }

        $this->reviewModel->moderateReview($id, $newStatus);
        return redirect()->back()->with('success', 'Statut mis à jour.');
    }

    // Supprime définitivement un avis
    public function delete($id)
    {
        if ($this->reviewModel->find($id)) {
            $this->reviewModel->delete($id);
            return redirect()->back()->with('success', 'Avis supprimé définitivement.');
        }

        return redirect()->back()->with('error', 'Avis introuvable.');
    }
}
