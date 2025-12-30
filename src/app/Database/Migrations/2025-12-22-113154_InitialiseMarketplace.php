<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class InitialiseMarketplace extends Migration
{
    public function up()
    {
        $this->db->disableForeignKeyChecks();

        // UTILISATEUR
        $this->forge->addField([
            'id_utilisateur'    => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'email'             => ['type' => 'VARCHAR', 'constraint' => 255],
            'mot_de_passe'      => ['type' => 'VARCHAR', 'constraint' => 255],
            'nom'               => ['type' => 'VARCHAR', 'constraint' => 100],
            'prenom'            => ['type' => 'VARCHAR', 'constraint' => 100],
            'date_inscription'  => ['type' => 'DATETIME', 'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP')],
            'role'              => ['type' => 'ENUM', 'constraint' => ['ADMIN', 'VENDEUR', 'CLIENT'], 'default' => 'CLIENT'],
        ]);
        $this->forge->addKey('id_utilisateur', true);
        $this->forge->addUniqueKey('email');
        $this->forge->createTable('utilisateur', true);

        // CLIENT
        $this->forge->addField([
            'id_utilisateur'    => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'telephone'         => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'date_naissance'    => ['type' => 'DATE', 'null' => true],
        ]);
        $this->forge->addKey('id_utilisateur', true);
        $this->forge->addForeignKey('id_utilisateur', 'utilisateur', 'id_utilisateur', 'CASCADE', 'CASCADE');
        $this->forge->createTable('client', true);

        // VENDEUR
        $this->forge->addField([
            'id_utilisateur'      => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'nom_boutique'        => ['type' => 'VARCHAR', 'constraint' => 100],
            'description_boutique'=> ['type' => 'TEXT', 'null' => true],
            'siret'               => ['type' => 'CHAR', 'constraint' => 14],
            'statut'              => ['type' => 'ENUM', 'constraint' => ['EN_ATTENTE_VALIDATION', 'VALIDE', 'REFUSE', 'SUSPENDU'], 'default' => 'EN_ATTENTE_VALIDATION'],
            'motif_refus'         => ['type' => 'TEXT', 'null' => true],
            'date_creation'       => ['type' => 'DATETIME', 'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP')],
        ]);
        $this->forge->addKey('id_utilisateur', true);
        $this->forge->addKey('statut');
        $this->forge->addUniqueKey('siret');
        $this->forge->addForeignKey('id_utilisateur', 'utilisateur', 'id_utilisateur', 'CASCADE', 'CASCADE');
        $this->forge->createTable('vendeur', true);

        // ADMINISTRATEUR
        $this->forge->addField([
            'id_utilisateur' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
        ]);
        $this->forge->addKey('id_utilisateur', true);
        $this->forge->addForeignKey('id_utilisateur', 'utilisateur', 'id_utilisateur', 'CASCADE', 'CASCADE');
        $this->forge->createTable('administrateur', true);

        // ADRESSE
        $this->forge->addField([
            'id_adresse'        => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'id_utilisateur'    => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'numero'            => ['type' => 'VARCHAR', 'constraint' => 10, 'null' => true],
            'rue'               => ['type' => 'VARCHAR', 'constraint' => 255],
            'code_postal'       => ['type' => 'VARCHAR', 'constraint' => 10],
            'ville'             => ['type' => 'VARCHAR', 'constraint' => 100],
            'pays'              => ['type' => 'VARCHAR', 'constraint' => 100],
            'telephone_contact' => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
        ]);
        $this->forge->addKey('id_adresse', true);
        $this->forge->addForeignKey('id_utilisateur', 'utilisateur', 'id_utilisateur', 'CASCADE', 'CASCADE');
        $this->forge->createTable('adresse', true);

        // CATEGORIE
        $this->forge->addField([
            'id_categorie' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'nom'          => ['type' => 'VARCHAR', 'constraint' => 100],
            'alias'        => ['type' => 'VARCHAR', 'constraint' => 120],
            'description'  => ['type' => 'TEXT', 'null' => true],
            'image_url'    => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
        ]);
        $this->forge->addKey('id_categorie', true);
        $this->forge->addKey('alias');
        $this->forge->createTable('categorie', true);

        // PRODUIT
        $this->forge->addField([
            'id_produit'        => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'id_vendeur'        => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'id_categorie'      => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'titre'             => ['type' => 'VARCHAR', 'constraint' => 150],
            'alias'             => ['type' => 'VARCHAR', 'constraint' => 150],
            'description_courte'=> ['type' => 'VARCHAR', 'constraint' => 255],
            'description_longue'=> ['type' => 'TEXT'],
            'prix'             => ['type' => 'DECIMAL', 'constraint' => '10,2', 'unsigned' => true], 
            'stock_disponible' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'dimensions'        => ['type' => 'VARCHAR', 'constraint' => 50],
            'matiere'           => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'statut_produit'    => ['type' => 'ENUM', 'constraint' => ['EN_ATTENTE_VALIDATION', 'APPROUVE', 'REFUSE', 'HORS_LIGNE', 'NON_DISPONIBLE'], 'default' => 'EN_ATTENTE_VALIDATION'],
            'date_creation'     => ['type' => 'DATETIME', 'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP')],
            'motif_refus'       => ['type' => 'TEXT', 'null' => true],
        ]);
        $this->forge->addKey('id_produit', true);
        $this->forge->addKey(['statut_produit', 'date_creation']);
        $this->forge->addKey('titre');      
        $this->forge->addKey('prix');  
        $this->forge->addUniqueKey('alias');
        $this->forge->addForeignKey('id_vendeur', 'vendeur', 'id_utilisateur', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_categorie', 'categorie', 'id_categorie', 'RESTRICT', 'CASCADE'); 
        $this->forge->createTable('produit', true);

        // PHOTO_PRODUIT
        $this->forge->addField([
            'id_photo'       => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'id_produit'     => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'nom_fichier'    => ['type' => 'VARCHAR', 'constraint' => 255],
            'ordre_affichage'=> ['type' => 'INT', 'default' => 0],
        ]);
        $this->forge->addKey('id_photo', true);
        $this->forge->addKey(['id_produit', 'ordre_affichage']);
        $this->forge->addForeignKey('id_produit', 'produit', 'id_produit', 'CASCADE', 'CASCADE');
        $this->forge->createTable('photo_produit', true);

        // PANIE
        $this->forge->addField([
            'id_panier'         => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'id_client'         => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'unique' => true],
            'date_creation'     => ['type' => 'DATETIME', 'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP')],
            'date_modification' => ['type' => 'DATETIME', 'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP')],
            'total'             => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0.00, 'unsigned' => true],
        ]);
        $this->forge->addKey('id_panier', true);
        $this->forge->addForeignKey('id_client', 'client', 'id_utilisateur', 'CASCADE', 'CASCADE');
        $this->forge->createTable('panier', true);

        // LIGNE_PANIER
        $this->forge->addField([
            'id_ligne'   => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'id_panier'  => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'id_produit' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'quantite'   => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
        ]);
        $this->forge->addKey('id_ligne', true);
        $this->forge->addUniqueKey(['id_panier', 'id_produit']);
        $this->forge->addForeignKey('id_panier', 'panier', 'id_panier', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_produit', 'produit', 'id_produit', 'CASCADE', 'CASCADE');
        $this->forge->createTable('ligne_panier', true);

        // COMMANDE
        $this->forge->addField([
            'id_commande'       => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'id_client'         => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'reference'         => ['type' => 'VARCHAR', 'constraint' => 50, 'unique' => true],
            'date_commande'     => ['type' => 'DATETIME', 'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP')],
            'statut'            => ['type' => 'ENUM', 'constraint' => ['EN_COURS_VALIDATION', 'PAYEE', 'EN_PREPARATION', 'EXPEDIEE', 'LIVREE', 'ANNULEE'], 'default' => 'EN_COURS_VALIDATION'],
            'mode_livraison'    => ['type' => 'VARCHAR', 'constraint' => 100],
            'adresse_liv_rue'   => ['type' => 'VARCHAR', 'constraint' => 255],
            'adresse_liv_cp'    => ['type' => 'VARCHAR', 'constraint' => 10],
            'adresse_liv_ville' => ['type' => 'VARCHAR', 'constraint' => 100],
            'adresse_liv_pays'  => ['type' => 'VARCHAR', 'constraint' => 100],
            'total_ttc'         => ['type' => 'DECIMAL', 'constraint' => '10,2','unsigned' => true],
            'frais_port'        => ['type' => 'DECIMAL', 'constraint' => '10,2','unsigned' => true],
        ]);
        $this->forge->addKey('id_commande', true);
        $this->forge->addForeignKey('id_client', 'client', 'id_utilisateur', 'RESTRICT', 'CASCADE');
        $this->forge->createTable('commande', true);

        // LIGNE_COMMANDE
        $this->forge->addField([
            'id_ligne_commande'      => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'id_commande'   => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'id_produit'    => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'quantite'      => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true], 
            'prix_unitaire' => ['type' => 'DECIMAL', 'constraint' => '10,2', 'unsigned' => true],
        ]);
        $this->forge->addKey('id_ligne_commande', true);
        $this->forge->addUniqueKey(['id_commande', 'id_produit']);
        $this->forge->addForeignKey('id_commande', 'commande', 'id_commande', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_produit', 'produit', 'id_produit', 'RESTRICT', 'CASCADE');
        $this->forge->createTable('ligne_commande', true);

        // AVIS
        $this->forge->addField([
            'id_avis'          => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'id_client'        => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'id_produit'       => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'note'             => ['type' => 'TINYINT', 'constraint' => 1, 'unsigned' => true],
            'commentaire'      => ['type' => 'TEXT', 'null' => true],
            'date_publication' => ['type' => 'DATETIME', 'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP')],
            'statut_moderation' => ['type' => 'ENUM', 'constraint' => ['PUBLIE', 'REFUSE'], 'default' => 'PUBLIE'],
        ]);
        $this->forge->addKey('id_avis', true);
        $this->forge->addKey('id_produit');
        $this->forge->addForeignKey('id_client', 'client', 'id_utilisateur', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_produit', 'produit', 'id_produit', 'CASCADE', 'CASCADE');
        $this->forge->addUniqueKey(['id_client', 'id_produit']);
        $this->forge->createTable('avis', true);

        $this->db->enableForeignKeyChecks();
    }

    public function down()
    {
        $this->db->disableForeignKeyChecks();
        $tables = ['avis', 'ligne_commande', 'commande', 'ligne_panier', 'panier', 'photo_produit', 'produit', 'categorie', 'adresse', 'administrateur', 'vendeur', 'client', 'utilisateur'];
        foreach ($tables as $table) {
            $this->forge->dropTable($table, true);
        }
        $this->db->enableForeignKeyChecks();
    }
}