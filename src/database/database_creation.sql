SET FOREIGN_KEY_CHECKS=0;
DROP DATABASE IF EXISTS brazil_dances;
CREATE DATABASE IF NOT EXISTS brazil_dances CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE brazil_dances;

CREATE TABLE users_form (
                            id INT(255) NOT NULL PRIMARY KEY AUTO_INCREMENT,
                            username VARCHAR(255) NOT NULL,
                            password VARCHAR(255) NOT NULL,
                            user_type VARCHAR(255) NOT NULL DEFAULT 'user',
                            reset_token VARCHAR(64) DEFAULT NULL,
                            email VARCHAR(255) NOT NULL,
                            reset_token_expires DATETIME DEFAULT NULL,
                            UNIQUE (reset_token_expires)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO users_form (id, username, password, user_type, email) VALUES
                                                                      (1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'admin', 'admin@email.com'),
                                                                      (2, 'user', 'ee11cbb19052e40b07aac0ca060c23ee', 'user', 'user@email.com'),
                                                                      (3, 'bob', '9f9d51bc70ef21ca5c14f307980a29d8', 'user', 'bob@email.com'),
                                                                      (4, 'tim', 'b15d47e99831ee63e3f47cf3d4478e9a', 'admin', 'tim@email.com');

CREATE TABLE dance_categories (
                                  category_id INT PRIMARY KEY AUTO_INCREMENT,
                                  category_name VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO dance_categories (category_name) VALUES
                                                 ('Traditional'), ('Festival'), ('Partner'), ('Pop');

CREATE TABLE media (
                       media_id INT PRIMARY KEY AUTO_INCREMENT,
                       media_url VARCHAR(255) UNIQUE,
                       alttext VARCHAR(255)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO media (media_url, alttext) VALUES
                                           ('assets/images/samba_img.jpg', 'Samba dance image'),
                                           ('assets/images/forro_img.jpg', 'Forro dance image'),
                                           ('assets/images/frevo_img.jpg', 'Frevo dance image'),
                                           ('assets/images/axe_img.jpg', 'Axé dance image'),
                                           ('assets/images/bossa_img.jpg', 'Bossa Nova dance image');

CREATE TABLE dances (
                        dance_id INT PRIMARY KEY AUTO_INCREMENT,
                        dance_name VARCHAR(100) NOT NULL UNIQUE,
                        category_id INT,
                        description TEXT,
                        media_id INT,
                        region VARCHAR(100),
                        x INT,
                        y INT,
                        user_id INT,
                        approved BOOL,
                        FOREIGN KEY (user_id) REFERENCES users_form(id) ON DELETE CASCADE,
                        FOREIGN KEY (category_id) REFERENCES dance_categories(category_id) ON DELETE CASCADE,
                        FOREIGN KEY (media_id) REFERENCES media(media_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE region (
                        region_key INT PRIMARY KEY AUTO_INCREMENT,
                        region_name VARCHAR(100)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO region (region_name, region_key) VALUES
                                                 ('Rio de Janeiro', 1),
                                                 ('Northeastern Brazil', 2),
                                                 ('Pernambuco', 3),
                                                 ('Bahia', 4);

INSERT INTO dances (dance_name, category_id, description, media_id, region, user_id, approved, x, y) VALUES
                                                                                                         ('Samba', 1, 'A lively, rhythmical dance with origins in Afro-Brazilian communities, performed at the Carnival.', 1, 1, 1, 1, 240, 282),
                                                                                                         ('Forró', 3, 'A close-partner dance from Northeastern Brazil with accordion-driven rhythms.', 2, 2, 1, 1, 360, 300),
                                                                                                         ('Frevo', 2, 'An energetic, acrobatic dance performed with colorful umbrellas during Carnival.', 3, 3, 1, 1, 330, 300),
                                                                                                         ('Axé', 4, 'A vibrant dance style from Bahia with upbeat moves, popular at parties and festivals.', 4, 4, 1, 1, 318, 348),
                                                                                                         ('Bossa Nova', 4, 'A smooth, intimate dance style with subtle sway, paired with jazzy Bossa Nova music.', 5, 1, 1, 1, 270, 360);

CREATE TABLE preferences (
                             preference_id INT PRIMARY KEY AUTO_INCREMENT,
                             user_id INT,
                             dance_id INT,
                             FOREIGN KEY (user_id) REFERENCES users_form(id) ON DELETE CASCADE,
                             FOREIGN KEY (dance_id) REFERENCES dances(dance_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE feedback (
                          id INT AUTO_INCREMENT PRIMARY KEY,
                          username VARCHAR(255) NOT NULL,
                          fname VARCHAR(255) NOT NULL,
                          lname VARCHAR(255) NOT NULL,
                          continent VARCHAR(255) NOT NULL,
                          feedback_text VARCHAR(300) NOT NULL,
                          created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

SET FOREIGN_KEY_CHECKS=1;
