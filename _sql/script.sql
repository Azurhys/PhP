DROP DATABASE IF EXISTS videogames; 

CREATE DATABASE videogames;

CREATE TABLE videogames.game (
  id TINYINT PRIMARY KEY,
  title VARCHAR(255),
  description TEXT,
  release_date DATE,
  poster VARCHAR(255),
  price DECIMAL(5,2)
);

INSERT INTO videogames.game (id, title, description, release_date, poster, price) VALUES
(1, 'Super Mario Bros', 'Jeu video de plateforme', '1985-09-13', 'https://example.com/super-mario-bros.jpg', 59.99),
(2, 'The Legend of Zelda: Ocarina of Time', 'Jeu video d\'action-aventure', '1998-11-23', 'https://example.com/zelda-ocarina-of-time.jpg', 49.99),
(3, 'Grand Theft Auto V', 'Jeu video d\'action-aventure', '2013-09-17', 'https://example.com/gta5.jpg', 29.99);

CREATE TABLE videogames.admin (
  id TINYINT PRIMARY KEY,
  email VARCHAR(255) UNIQUE,
  password VARCHAR(255)
);

INSERT INTO videogames.admin (id, email, password)
VALUES (1, 'admin@example.com', '013ece159bcb3142e0ee60181be5b23b');
