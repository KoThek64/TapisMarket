<?php

namespace App\Controllers\Client;

class Addresses extends ClientBaseController
{
    // Lister les adresses
    public function index()
    {
        return redirect()->to('client/profile');
    }

    // Formulaire de création
    public function new()
    {
        $data = array_merge($this->clientData, [
            'title' => 'Nouvelle adresse',
            'subtitle' => 'Gérez vos coordonnées de livraison',
            'theme' => 'accent',
            'initial' => 'A',
            'address' => null
        ]);

        return view('client/addresses/form', $data);
    }

    // Traitement de la création
    public function create()
    {
        $userId = user_id();
        $data = $this->request->getPost();
        $data['user_id'] = $userId;

        // $this->addressModel est chargé automatiquement grâce à ta méthode __get()
        if ($this->addressModel->save($data)) {
            return redirect()->to('client/profile')->with('message', 'Adresse ajoutée avec succès.');
        }

        return redirect()->back()
            ->withInput()
            ->with('errors', $this->addressModel->errors());
    }

    // Formulaire de modification
    public function edit($id = null)
    {
        $userId = user_id();

        $address = $this->addressModel->where('user_id', $userId)->find($id);

        if (!$address) {
            return redirect()->to('client/profile')->with('error', 'Adresse introuvable.');
        }

        $data = array_merge($this->clientData, [
            'title' => 'Modifier l\'adresse',
            'subtitle' => 'Gérez vos coordonnées de livraison',
            'theme' => 'accent',
            'initial' => 'A',
            'address' => $address
        ]);

        return view('client/addresses/form', $data);
    }

    // Traitement de la modification
    public function update($id = null)
    {
        $userId = user_id();

        $address = $this->addressModel->where('user_id', $userId)->find($id);
        if (!$address) {
            return redirect()->to('client/profile')->with('error', 'Adresse introuvable.');
        }

        $data = $this->request->getPost();
        $data['id'] = $id;
        $data['user_id'] = $userId;

        if ($this->addressModel->save($data)) {
            return redirect()->to('client/profile')->with('message', 'Adresse mise à jour.');
        }

        return redirect()->back()
            ->withInput()
            ->with('errors', $this->addressModel->errors());
    }

    // Suppression
    public function delete($id = null)
    {
        $userId = user_id();

        $deleted = $this->addressModel->where('id', $id)
            ->where('user_id', $userId)
            ->delete();

        if ($deleted) {
            return redirect()->to('client/profile')->with('message', 'Adresse supprimée.');
        }

        return redirect()->to('client/profile')->with('error', 'Impossible de supprimer cette adresse.');
    }
}
