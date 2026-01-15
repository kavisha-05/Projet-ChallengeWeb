<?php

namespace Streamflix;

class PurchaseModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function createPurchase($idUtilisateur, $idFilms)
    {
        // Créer un achat pour chaque film
        $purchaseIds = [];
        foreach ($idFilms as $idFilm) {
            $this->db->query(
                "INSERT INTO Achat (date_achat, id_utilisateur, id_film) VALUES (CURRENT_TIMESTAMP, ?, ?)",
                [$idUtilisateur, $idFilm]
            );
            $purchaseIds[] = $this->db->lastInsertId();
        }
        
        return $purchaseIds;
    }

    public function getUserPurchases($idUtilisateur)
    {
        return $this->db->fetchAll(
            "SELECT 
                a.id_achat,
                a.date_achat,
                f.id_film,
                f.titre,
                f.affiche,
                f.prix
             FROM Achat a
             INNER JOIN Film f ON a.id_film = f.id_film
             WHERE a.id_utilisateur = ?
             ORDER BY a.date_achat DESC",
            [$idUtilisateur]
        );
    }

    public function getPurchaseById($idAchat, $idUtilisateur)
    {
        return $this->db->fetchOne(
            "SELECT 
                a.id_achat,
                a.date_achat,
                f.id_film,
                f.titre,
                f.description,
                f.affiche,
                f.prix,
                f.url_video
             FROM Achat a
             INNER JOIN Film f ON a.id_film = f.id_film
             WHERE a.id_achat = ? AND a.id_utilisateur = ?",
            [$idAchat, $idUtilisateur]
        );
    }

    public function getPurchasesByDate($idUtilisateur, $date)
    {
        return $this->db->fetchAll(
            "SELECT 
                a.id_achat,
                a.date_achat,
                f.id_film,
                f.titre,
                f.affiche,
                f.prix
             FROM Achat a
             INNER JOIN Film f ON a.id_film = f.id_film
             WHERE a.id_utilisateur = ? AND DATE(a.date_achat) = ?
             ORDER BY a.id_achat",
            [$idUtilisateur, $date]
        );
    }
}

