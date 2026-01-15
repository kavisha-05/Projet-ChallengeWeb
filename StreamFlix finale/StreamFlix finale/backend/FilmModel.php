<?php

namespace Streamflix;

class FilmModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function createFilm($titre, $description, $prix, $urlVideo, $affiche)
    {
        $this->db->query(
            "INSERT INTO Film (titre, description, prix, url_video, affiche) VALUES (?, ?, ?, ?, ?)",
            [$titre, $description, $prix, $urlVideo, $affiche]
        );
        return $this->db->lastInsertId();
    }

    public function getFilmById($id)
    {
        return $this->db->fetchOne(
            "SELECT * FROM Film WHERE id_film = ?",
            [$id]
        );
    }

    public function getTopFilms($limit = 10)
    {
        return $this->db->fetchAll(
            "SELECT * FROM Film ORDER BY nb_vues DESC LIMIT ?",
            [$limit]
        );
    }

    public function getFilmsByGenre($idGenre)
    {
        return $this->db->fetchAll(
            "SELECT f.* FROM Film f
             INNER JOIN Film_Genre fg ON f.id_film = fg.id_film
             WHERE fg.id_genre = ?",
            [$idGenre]
        );
    }

    public function incrementViews($idFilm)
    {
        $this->db->query(
            "UPDATE Film SET nb_vues = nb_vues + 1 WHERE id_film = ?",
            [$idFilm]
        );
    }

    public function addGenreToFilm($idFilm, $idGenre)
    {
        $existing = $this->db->fetchOne(
            "SELECT * FROM Film_Genre WHERE id_film = ? AND id_genre = ?",
            [$idFilm, $idGenre]
        );

        if (!$existing) {
            $this->db->query(
                "INSERT INTO Film_Genre (id_film, id_genre) VALUES (?, ?)",
                [$idFilm, $idGenre]
            );
        }
    }

    public function getFilmGenres($idFilm)
    {
        return $this->db->fetchAll(
            "SELECT g.* FROM Genre g
             INNER JOIN Film_Genre fg ON g.id_genre = fg.id_genre
             WHERE fg.id_film = ?",
            [$idFilm]
        );
    }

    public function searchFilms($query)
    {
        return $this->db->fetchAll(
            "SELECT * FROM Film WHERE titre ILIKE ? OR description ILIKE ?",
            ["%{$query}%", "%{$query}%"]
        );
    }
}

