-- Script de création de la base de données Streamflix
-- Base de données : streamflix

-- Table Panier
CREATE TABLE IF NOT EXISTS Panier (
    id_panier SERIAL PRIMARY KEY,
    date_creation TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- Table Genre
CREATE TABLE IF NOT EXISTS Genre (
    id_genre SERIAL PRIMARY KEY,
    nom VARCHAR(50) NOT NULL UNIQUE
);

-- Table Film
CREATE TABLE IF NOT EXISTS Film (
    id_film SERIAL PRIMARY KEY,
    titre VARCHAR(100) NOT NULL,
    description TEXT,
    prix DECIMAL(15,2) NOT NULL,
    url_video VARCHAR(255) NOT NULL,
    affiche VARCHAR(255),
    nb_vues INT NOT NULL DEFAULT 0
);

-- Table Utilisateur (après Panier car il y a une référence)
CREATE TABLE IF NOT EXISTS Utilisateur (
    id_utilisateur SERIAL PRIMARY KEY,
    nom VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL,
    role VARCHAR(20) NOT NULL DEFAULT 'user',
    id_panier INT NOT NULL,
    FOREIGN KEY (id_panier) REFERENCES Panier(id_panier) ON DELETE CASCADE
);

-- Table Achat
CREATE TABLE IF NOT EXISTS Achat (
    id_achat SERIAL PRIMARY KEY,
    date_achat TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    id_utilisateur INT NOT NULL,
    id_film INT NOT NULL,
    FOREIGN KEY (id_utilisateur) REFERENCES Utilisateur(id_utilisateur) ON DELETE CASCADE,
    FOREIGN KEY (id_film) REFERENCES Film(id_film) ON DELETE CASCADE
);

-- Table Abonnement
CREATE TABLE IF NOT EXISTS Abonnement (
    id_abonnement SERIAL PRIMARY KEY,
    type VARCHAR(20) NOT NULL,
    date_debut DATE NOT NULL,
    date_fin DATE NOT NULL,
    id_utilisateur INT NOT NULL UNIQUE,
    FOREIGN KEY (id_utilisateur) REFERENCES Utilisateur(id_utilisateur) ON DELETE CASCADE
);

-- Table de liaison Panier_Film
CREATE TABLE IF NOT EXISTS Panier_Film (
    id_panier INT NOT NULL,
    id_film INT NOT NULL,
    PRIMARY KEY (id_panier, id_film),
    FOREIGN KEY (id_panier) REFERENCES Panier(id_panier) ON DELETE CASCADE,
    FOREIGN KEY (id_film) REFERENCES Film(id_film) ON DELETE CASCADE
);

-- Table de liaison Film_Genre
CREATE TABLE IF NOT EXISTS Film_Genre (
    id_film INT NOT NULL,
    id_genre INT NOT NULL,
    PRIMARY KEY (id_film, id_genre),
    FOREIGN KEY (id_film) REFERENCES Film(id_film) ON DELETE CASCADE,
    FOREIGN KEY (id_genre) REFERENCES Genre(id_genre) ON DELETE CASCADE
);

-- Index pour améliorer les performances
CREATE INDEX IF NOT EXISTS idx_utilisateur_email ON Utilisateur(email);
CREATE INDEX IF NOT EXISTS idx_utilisateur_panier ON Utilisateur(id_panier);
CREATE INDEX IF NOT EXISTS idx_achat_utilisateur ON Achat(id_utilisateur);
CREATE INDEX IF NOT EXISTS idx_achat_film ON Achat(id_film);
CREATE INDEX IF NOT EXISTS idx_abonnement_utilisateur ON Abonnement(id_utilisateur);
CREATE INDEX IF NOT EXISTS idx_panier_film_panier ON Panier_Film(id_panier);
CREATE INDEX IF NOT EXISTS idx_panier_film_film ON Panier_Film(id_film);
CREATE INDEX IF NOT EXISTS idx_film_genre_film ON Film_Genre(id_film);
CREATE INDEX IF NOT EXISTS idx_film_genre_genre ON Film_Genre(id_genre);
CREATE INDEX IF NOT EXISTS idx_film_nb_vues ON Film(nb_vues DESC);

-- Insertion de quelques genres de base
INSERT INTO Genre (nom) VALUES 
    ('Comédie'),
    ('Horreur'),
    ('Science Fiction'),
    ('Action'),
    ('Drame'),
    ('Animation')
ON CONFLICT (nom) DO NOTHING;
