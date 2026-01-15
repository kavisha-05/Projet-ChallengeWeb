<?php

namespace Streamflix;

class CartModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function addToCart($idPanier, $idFilm)
    {
        // Vérifier si le film n'est pas déjà dans le panier
        $existing = $this->db->fetchOne(
            "SELECT * FROM Panier_Film WHERE id_panier = ? AND id_film = ?",
            [$idPanier, $idFilm]
        );

        if (!$existing) {
            $this->db->query(
                "INSERT INTO Panier_Film (id_panier, id_film) VALUES (?, ?)",
                [$idPanier, $idFilm]
            );
        }
    }

    public function removeFromCart($idPanier, $idFilm)
    {
        $this->db->query(
            "DELETE FROM Panier_Film WHERE id_panier = ? AND id_film = ?",
            [$idPanier, $idFilm]
        );
    }

    public function getCartItems($idPanier)
    {
        return $this->db->fetchAll(
            "SELECT f.* FROM Film f
             INNER JOIN Panier_Film pf ON f.id_film = pf.id_film
             WHERE pf.id_panier = ?",
            [$idPanier]
        );
    }

    public function clearCart($idPanier)
    {
        $this->db->query(
            "DELETE FROM Panier_Film WHERE id_panier = ?",
            [$idPanier]
        );
    }
}

