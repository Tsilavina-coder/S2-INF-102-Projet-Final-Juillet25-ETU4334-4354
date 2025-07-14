CREATE DATABASE IF NOT EXISTS emprunt;
USE emprunt;

CREATE TABLE membre (
  id_membre INT PRIMARY KEY AUTO_INCREMENT,
  nom VARCHAR(100) NOT NULL,
  date_naissance DATE NOT NULL,
  genre ENUM('M', 'F', 'Autre') NOT NULL,
  email VARCHAR(100) NOT NULL,
  ville VARCHAR(100),
  mdp VARCHAR(255) NOT NULL,
  image_profil VARCHAR(255)
);

CREATE TABLE categorie_objet (
  id_categorie INT PRIMARY KEY AUTO_INCREMENT,
  nom_categorie VARCHAR(100) NOT NULL
);

CREATE TABLE objet (
  id_objet INT PRIMARY KEY AUTO_INCREMENT,
  nom_objet VARCHAR(100) NOT NULL,
  id_categorie INT NOT NULL,
  id_membre INT NOT NULL,
  FOREIGN KEY (id_categorie) REFERENCES categorie_objet(id_categorie),
  FOREIGN KEY (id_membre) REFERENCES membre(id_membre)
);

CREATE TABLE images_objet (
  id_image INT PRIMARY KEY AUTO_INCREMENT,
  id_objet INT NOT NULL,
  nom_image VARCHAR(255) NOT NULL,
  FOREIGN KEY (id_objet) REFERENCES objet(id_objet)
);

CREATE TABLE emprunt (
  id_emprunt INT PRIMARY KEY AUTO_INCREMENT,
  id_objet INT NOT NULL,
  id_membre INT NOT NULL,
  date_emprunt DATE NOT NULL,
  date_retour DATE,
  FOREIGN KEY (id_objet) REFERENCES objet(id_objet),
  FOREIGN KEY (id_membre) REFERENCES membre(id_membre)
);



INSERT INTO membre (nom, date_naissance, genre, email, ville, mdp, image_profil) VALUES
('Rija', '1980-05-15', 'M', 'Rija@example.com', 'Paris', 'mdp1', 'Rija.jpg'),
('Anja', '1990-08-22', 'F', 'Anja@example.com', 'Lyon', 'mdp2', 'Anja.jpg'),
('Rivo', '1985-12-03', 'M', 'Rivo@example.com', 'Marseille', 'mdp3', 'Rivo.jpg'),
('Fara', '1992-07-19', 'F', 'Fara@example.com', 'Toulouse', 'mdp4', 'Fara.jpg');

INSERT INTO categorie_objet (nom_categorie) VALUES
('esthétique'),
('bricolage'),
('mécanique'),
('cuisine');


INSERT INTO objet (nom_objet, id_categorie, id_membre) VALUES
('Crème hydratante', 1, 1),
('Parfum', 1, 1),
('Vernis à ongles', 1, 1),
('Pinceau maquillage', 1, 1),
('Tournevis', 2, 1),
('Marteau', 2, 1),
('Perceuse', 2, 1),
('Clé à molette', 2, 1),
('Clé à molette', 3, 1),
('Couteau de cuisine', 4, 1),

('Fond de teint', 1, 2),
('Lisseur', 1, 2),
('Sèche-cheveux', 1, 2),
('Pince à épiler', 1, 2),
('Scie', 2, 2),
('Niveau à bulle', 2, 2),
('Pince coupante', 2, 2),
('Clé anglaise', 2, 2),
('Clé à molette', 3, 2),
('Poêle', 4, 2),

('Roulement à billes', 3, 3),
('Fil électrique', 3, 3),
('Pompe à eau', 3, 3),
('Compresseur', 3, 3),
('Casserole', 4, 3),
('Mixeur', 4, 3),
('Four', 4, 3),
('Grille-pain', 4, 3),
('Clé à molette', 2, 3),
('Tournevis', 2, 3),

('Crème solaire', 1, 4),
('Gel coiffant', 1, 4),
('Brosse à cheveux', 1, 4),
('Lime à ongles', 1, 4),
('Perceuse', 2, 4),
('Marteau', 2, 4),
('Scie sauteuse', 2, 4),
('Clé à molette', 2, 4),
('Couteau de cuisine', 4, 4),
('Poêle', 4, 4);


INSERT INTO emprunt (id_objet, id_membre, date_emprunt, date_retour) VALUES
(1, 2, '2023-06-01', '2023-06-10'),
(5, 3, '2023-06-05', '2023-06-15'),
(10, 1, '2023-06-07', '2023-06-20'),
(15, 4, '2023-06-10', '2023-06-25'),
(20, 1, '2023-06-12', '2023-06-22'),
(25, 2, '2023-06-15', '2023-06-30'),
(30, 3, '2023-06-18', '2023-06-28'),
(35, 4, '2023-06-20', '2023-07-05'),
(40, 1, '2023-06-22', '2023-07-01'),
(3, 2, '2023-06-25', '2023-07-10');


UPDATE membre
SET email = REPLACE(email, '@example.com', '@gmail.com')
WHERE email LIKE '%@example.com';


ALTER TABLE objet ADD COLUMN image_objet VARCHAR(255) DEFAULT NULL;
