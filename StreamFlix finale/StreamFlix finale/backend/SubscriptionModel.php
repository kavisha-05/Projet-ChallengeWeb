<?php

namespace Streamflix;

class SubscriptionModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function createSubscription($idUtilisateur, $type, $dateDebut, $dateFin)
    {
        // Vérifier si l'utilisateur a déjà un abonnement actif
        $existing = $this->db->fetchOne(
            "SELECT * FROM Abonnement WHERE id_utilisateur = ? AND date_fin >= CURRENT_DATE",
            [$idUtilisateur]
        );

        if ($existing) {
            // Mettre à jour l'abonnement existant
            $this->db->query(
                "UPDATE Abonnement SET type = ?, date_debut = ?, date_fin = ? WHERE id_utilisateur = ?",
                [$type, $dateDebut, $dateFin, $idUtilisateur]
            );
            return $existing['id_abonnement'];
        } else {
            // Créer un nouvel abonnement
            $this->db->query(
                "INSERT INTO Abonnement (type, date_debut, date_fin, id_utilisateur) VALUES (?, ?, ?, ?)",
                [$type, $dateDebut, $dateFin, $idUtilisateur]
            );
            return $this->db->lastInsertId();
        }
    }

    public function getActiveSubscription($idUtilisateur)
    {
        return $this->db->fetchOne(
            "SELECT * FROM Abonnement WHERE id_utilisateur = ? AND date_fin >= CURRENT_DATE",
            [$idUtilisateur]
        );
    }

    public function getUserSubscriptions($idUtilisateur)
    {
        return $this->db->fetchAll(
            "SELECT * FROM Abonnement WHERE id_utilisateur = ? ORDER BY date_debut DESC",
            [$idUtilisateur]
        );
    }
}

