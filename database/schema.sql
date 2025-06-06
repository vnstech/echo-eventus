SET foreign_key_checks = 0;

DROP TABLE IF EXISTS users, events, users_events, participants;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    encrypted_password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    avatar_name VARCHAR(60),
    is_admin BOOLEAN 
);

CREATE TABLE events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    owner_id INT NOT NULL,
    status VARCHAR(20),
    name VARCHAR(100) NOT NULL,
    description VARCHAR(300),
    start_date DATETIME NOT NULL,
    finish_date DATETIME,
    location_name VARCHAR(100),
    address VARCHAR(200),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    -- logo_name VARCHAR(60),
    -- background_name VARCHAR(60),
    category VARCHAR(100),
    two_fa_check_attendance BOOLEAN,
    FOREIGN KEY (owner_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE users_events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    event_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE
);

CREATE TABLE participants (
    id INT AUTO_INCREMENT PRIMARY KEY,
    event_id INT NOT NULL,
    email VARCHAR(100) NOT NULL,
    name VARCHAR(100),
    check_in BOOLEAN ,
    check_out BOOLEAN ,
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE
);


SET foreign_key_checks = 1;