<?php

namespace App\Controllers\Client;

use App\Entities\Customer;

class Profile extends ClientBaseController
{
    // Affichage du profil client
    public function index()
    {
        $user = $this->getCurrentUser();

        $data = array_merge($this->clientData, [
            'title' => 'Mon Profil',
            'subtitle' => 'Gérez vos informations personnelles',
            'theme' => 'accent',
            'initial' => substr($user->firstname ?? 'C', 0, 1),
            'stats' => [
                'Membre depuis' => date('Y', strtotime($user->created_at))
            ],
            'addresses' => $this->addressModel->where('user_id', $user->user_id)->findAll()
        ]);

        return view('client/profile/index', $data);
    }

    // Mise à jour du profil
    public function update()
    {
        $userId = user_id();
        
        $user = $this->userModel->find($userId);
        $customer = $this->customerModel->find($userId);

        if (!$user) {
            return redirect()->back()->with('error', 'Utilisateur introuvable.');
        }

        $userInput = $this->request->getPost(['firstname', 'lastname']);
        $user->fill($userInput);

        if (!$customer) {
            $customer = new Customer();
            $customer->user_id = $userId;
        }

        $customerInput = $this->request->getPost(['phone', 'birth_date']);

        // Nettoyage des données vides
        $customerInput['phone'] = empty($customerInput['phone']) ? null : $customerInput['phone'];
        $customerInput['birth_date'] = empty($customerInput['birth_date']) ? null : $customerInput['birth_date'];

        // Évite de marquer comme "changé" si la date est identique
        if ($customer->birth_date && $customerInput['birth_date'] === $customer->birth_date->format('Y-m-d')) {
            unset($customerInput['birth_date']);
        }

        $customer->fill($customerInput);

        // Vérification des changements
        if (!$user->hasChanged() && !$customer->hasChanged()) {
            return redirect()->back()->with('message', 'Aucune modification détectée.');
        }

        // Sauvegarde
        $successUser = true;
        if ($user->hasChanged()) {
            $successUser = $this->userModel->save($user);
        }

        $successCustomer = true;
        if ($customer->hasChanged()) {
            $successCustomer = $this->customerModel->save($customer);
        }

        if ($successUser && $successCustomer) {
            return redirect()->back()->with('message', 'Profil mis à jour.');
        }

        return redirect()->back()->withInput()->with('error', 'Erreur lors de la mise à jour.');
    }

    public function export()
    {
        $userId = user_id();

        $user = $this->userModel->find($userId);
        
        $customer = $this->customerModel->find($userId);

        $addresses = $this->addressModel->where('user_id', $userId)->findAll();

        $exportData = [
            'compte' => [
                'id' => $user->id,
                'email' => $user->email,
                'nom' => $user->lastname,
                'prenom' => $user->firstname,
                'cree_le' => $user->created_at->toDateTimeString(),
            ],
            'infos_personnelles' => [
                'telephone' => $customer ? $customer->phone : null,
                'date_naissance' => $customer && $customer->birth_date ? $customer->birth_date->format('Y-m-d') : null,
            ],
            'adresses' => []
        ];

        foreach ($addresses as $addr) {
            $exportData['adresses'][] = [
                'numero' => $addr->number,
                'rue' => $addr->street,
                'code_postal' => $addr->postal_code,
                'ville' => $addr->city,
                'pays' => $addr->country,
                'contact' => $addr->contact_phone
            ];
        }

        // Génération du fichier JSON
        $filename = 'export_donnees_tapis_' . date('Y-m-d') . '.json';
        
        return $this->response->download(
            $filename, 
            json_encode($exportData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );
    }

}
