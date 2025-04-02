SET FOREIGN_KEY_CHECKS=0;
DROP DATABASE IF EXISTS brazil_dances;
CREATE DATABASE IF NOT EXISTS brazil_dances;
USE brazil_dances;

CREATE TABLE users_form (
  id int(255) NOT NULL PRIMARY KEY,
  username varchar(255) NOT NULL,
  password varchar(255) NOT NULL,
  user_type varchar(255) NOT NULL DEFAULT 'user',
    reset_token varchar(64) NULL DEFAULT NULL,
    email varchar(255) not null,
    reset_token_expires datetime null default null,
    unique (reset_token_expires)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


INSERT INTO users_form (id, username, password, user_type, email) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'admin', 'admin@email.com'),
(2, 'user', 'ee11cbb19052e40b07aac0ca060c23ee', 'user', 'user@email.com'),
(3, 'bob', '9f9d51bc70ef21ca5c14f307980a29d8', 'user', 'bob@email.com'),
(4, 'tim', 'b15d47e99831ee63e3f47cf3d4478e9a', 'admin', 'tim@email.com');

ALTER TABLE users_form
  MODIFY id int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;


CREATE TABLE IF NOT EXISTS dance_categories (
    category_id INT PRIMARY KEY AUTO_INCREMENT,
    category_name VARCHAR(100) NOT NULL UNIQUE
);

INSERT INTO dance_categories (category_name) VALUES 
('Traditional'), ('Festival'), ('Partner'), ('Pop');

CREATE TABLE IF NOT EXISTS media (
    media_id INT PRIMARY KEY AUTO_INCREMENT,
    media_url VARCHAR(255) UNIQUE,
    alttext VARCHAR(255)
);

INSERT INTO media (media_url, alttext) VALUES 
('assets/images/samba_img.jpg', 'Samba dance image'),
('assets/images/forro_img.jpg', 'Forro dance image'),
('assets/images/frevo_img.jpg', 'Frevo dance image'),
('assets/images/axe_img.jpg', 'Axé dance image'),
('assets/images/bossa_img.jpg', 'Bossa Nova dance image');

CREATE TABLE IF NOT EXISTS dances (
    dance_id INT PRIMARY KEY AUTO_INCREMENT,
    dance_name VARCHAR(100) NOT NULL UNIQUE,
    category_id INT,
    description TEXT,
    media_id INT,
    region VARCHAR(100),
    user_id INT,
    FOREIGN KEY (user_id) REFERENCES users_form(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES dance_categories(category_id) ON DELETE CASCADE,
    FOREIGN KEY (media_id) REFERENCES media(media_id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS region (
    region_key INT PRIMARY KEY AUTO_INCREMENT,
    region_name VARCHAR(100)
);

INSERT INTO region (region_name, region_key) VALUES
('Rio de Janeiro', 1),
('Northeastern Brazil', 2),
('Pernambuco', 3),
('Bahia', 4);

INSERT INTO dances (dance_name, category_id, description, media_id, region, user_id) VALUES
('Samba', 1, 'A lively, rhythmical dance with origins in Afro-Brazilian communities, performed at the Carnival.', 1, 1, 1),
('Forró', 3, 'A close-partner dance from Northeastern Brazil with accordion-driven rhythms.', 2, 2, 1),
('Frevo', 2, 'An energetic, acrobatic dance performed with colorful umbrellas during Carnival.', 3, 3, 1),
('Axé', 4, 'A vibrant dance style from Bahia with upbeat moves, popular at parties and festivals.', 4, 4, 1),
('Bossa Nova', 4, 'A smooth, intimate dance style with subtle sway, paired with jazzy Bossa Nova music.', 5, 1, 1);


CREATE TABLE IF NOT EXISTS preferences (
    preference_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    dance_id INT,
    FOREIGN KEY (user_id) REFERENCES users_form(id) ON DELETE CASCADE,
    FOREIGN KEY (dance_id) REFERENCES dances(dance_id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS feedback (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    fname VARCHAR(255) NOT NULL,
    lname VARCHAR(255) NOT NULL,
    continent VARCHAR(255) NOT NULL,
    feedback_text VARCHAR(300) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

SET FOREIGN_KEY_CHECKS=1;
