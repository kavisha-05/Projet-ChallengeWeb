<?php

namespace Streamflix;

class UserModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function createUser($nom, $email, $motDePasse, $role = 'user')
    {
        // Créer d'abord un panier pour l'utilisateur
        $this->db->query("INSERT INTO Panier (date_creation) VALUES (CURRENT_TIMESTAMP)");
        $idPanier = $this->db->lastInsertId('panier_id_panier_seq');

        // Créer l'utilisateur
        $this->db->query(
            "INSERT INTO Utilisateur (nom, email, mot_de_passe, role, id_panier) VALUES (?, ?, ?, ?, ?)",
            [$nom, $email, password_hash($motDePasse, PASSWORD_DEFAULT), $role, $idPanier]
        );

        return $this->db->lastInsertId();
    }

    public function getUserByEmail($email)
    {
        return $this->db->fetchOne(
            "SELECT * FROM Utilisateur WHERE email = ?",
            [$email]
        );
    }

    public function getUserById($id)
    {
        return $this->db->fetchOne(
            "SELECT * FROM Utilisateur WHERE id_utilisateur = ?",
            [$id]
        );
    }

    public function verifyPassword($email, $password)
    {
        $user = $this->getUserByEmail($email);
        if ($user && password_verify($password, $user['mot_de_passe'])) {
            return $user;
        }
        return false;
    }

    public function getUserPanierId($userId)
    {
        $user = $this->getUserById($userId);
        return $user ? $user['id_panier'] : null;
    }
}

