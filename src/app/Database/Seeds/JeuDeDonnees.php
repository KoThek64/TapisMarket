<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class JeuDeDonnees extends Seeder
{
    public function run()
    {
        $this->db->disableForeignKeyChecks();
        $tables = ['avis', 'ligne_commande', 'commande', 'ligne_panier', 'panier', 'photo_produit', 'produit', 'categorie', 'adresse', 'vendeur', 'client', 'administrateur', 'utilisateur'];
        foreach ($tables as $table) {
            $this->db->table($table)->truncate();
        }
        $this->db->enableForeignKeyChecks();

        $idAdmin = $this->creerUtilisateur('admin@tapis.com', 'Boss', 'Admin', 'ADMIN');
        $this->db->table('administrateur')->insert(['id_utilisateur' => $idAdmin]);

        $idVendeur1 = $this->creerUtilisateur('vendeur@tapis.com', 'Marchand', 'Pierre', 'VENDEUR');
        $this->db->table('vendeur')->insert([
            'id_utilisateur' => $idVendeur1,
            'nom_boutique'   => 'Tapis d\'Orient',
            'siret'          => '12345678901234',
            'statut'         => 'VALIDE',
            'description_boutique' => 'Expert depuis 1990.',
            'date_creation'  => date('Y-m-d H:i:s')
        ]);

        $idVendeur2 = $this->creerUtilisateur('nouveau@vendeur.com', 'Dubois', 'Jean', 'VENDEUR');
        $this->db->table('vendeur')->insert([
            'id_utilisateur' => $idVendeur2,
            'nom_boutique'   => 'Boutique Louche',
            'siret'          => '98765432109876',
            'statut'         => 'EN_ATTENTE_VALIDATION',
            'date_creation'  => date('Y-m-d H:i:s')
        ]);

        $idClient = $this->creerUtilisateur('alice@mail.com', 'Merveille', 'Alice', 'CLIENT');
        $this->db->table('client')->insert([
            'id_utilisateur' => $idClient,
            'telephone'      => '0612345678'
        ]);

        $cats = [
            ['nom' => 'Salon Moderne', 'alias' => 'salon-moderne'],
            ['nom' => 'Chambre Enfant', 'alias' => 'chambre-enfant'],
            ['nom' => 'Extérieur', 'alias' => 'exterieur'],
        ];
        $catIds = [];
        foreach ($cats as $cat) {
            $this->db->table('categorie')->insert($cat);
            $catIds[] = $this->db->insertID();
        }

        $pIds = [];
        $pIds[] = $this->creerProduit($idVendeur1, $catIds[0], "Tapis Persan Royal", 450.00, 50, 'APPROUVE');
        $pIds[] = $this->creerProduit($idVendeur1, $catIds[0], "Tapis Rouge Vintage", 120.00, 2, 'APPROUVE');
        $this->creerProduit($idVendeur1, $catIds[2], "Tapis Volant Prototype", 9999.00, 1, 'EN_ATTENTE_VALIDATION');

        $this->creerCommande($idClient, $pIds[0], 1, 450.00, '-1 month', 'PAYEE');
        $this->creerCommande($idClient, $pIds[1], 2, 120.00, 'now', 'PAYEE');

        $this->db->table('avis')->insert([
            'id_client' => $idClient,
            'id_produit' => $pIds[0],
            'note' => 5,
            'commentaire' => 'Super qualité !',
            'statut_moderation' => 'PUBLIE',
            'date_publication' => date('Y-m-d H:i:s')
        ]);
    }


    private function creerUtilisateur($email, $nom, $prenom, $role) {
        $this->db->table('utilisateur')->insert([
            'email' => $email,
            'mot_de_passe' => password_hash('123456', PASSWORD_DEFAULT),
            'nom' => $nom,
            'prenom' => $prenom,
            'role' => $role,
            'date_inscription' => date('Y-m-d H:i:s')
        ]);
        return $this->db->insertID();
    }

    private function creerProduit($vendeur, $cat, $titre, $prix, $stock, $statut) {
        $data = [
            'id_vendeur' => $vendeur, 
            'id_categorie' => $cat,
            'titre' => $titre, 
            'alias' => url_title($titre, '-', true),
            'description_courte' => "Petite description pour $titre", 
            'description_longue' => "Grande description détaillée pour $titre.",
            'prix' => $prix, 
            'stock_disponible' => $stock,
            'dimensions' => '200x300', 
            'statut_produit' => $statut,
            'date_creation' => date('Y-m-d H:i:s')
        ];
        $this->db->table('produit')->insert($data);
        $id = $this->db->insertID();
        $this->db->table('photo_produit')->insert(['id_produit' => $id, 'nom_fichier' => 'default.jpg', 'ordre_affichage' => 1]);
        return $id;
    }

    private function creerCommande($client, $prod, $qte, $prix, $dateStr, $statut) {
    $dateSQL = date('Y-m-d H:i:s', strtotime($dateStr));
    
    $total = $qte * $prix;

    $this->db->table('commande')->insert([
        'id_client'       => $client, 
        'reference'       => 'CMD-' . strtoupper(uniqid()),
        'date_commande'   => $dateSQL, 
        'statut'          => $statut, 
        'total_ttc'       => $total, 
        'frais_port'      => 0,
        'mode_livraison'  => 'Standard', 
        'adresse_liv_rue' => 'Rue du Test',
        'adresse_liv_cp'  => '75000', 
        'adresse_liv_ville'=> 'Paris', 
        'adresse_liv_pays' => 'France'
    ]);
    
    $idCmd = $this->db->insertID();

    $this->db->table('ligne_commande')->insert([
        'id_commande'   => $idCmd, 
        'id_produit'    => $prod,
        'quantite'      => $qte, 
        'prix_unitaire' => $prix
    ]);
}
}