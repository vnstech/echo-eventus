SET foreign_key_checks = 0;

DROP TABLE IF EXISTS users, events, users_events, participants;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    encrypted_password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_admin BOOLEAN 
);

CREATE TABLE events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    owner_id INT NOT NULL,
    status VARCHAR(20),
    name VARCHAR(100) NOT NULL,
    description VARCHAR(300),
    start_date DATETIME,
    finish_date DATETIME,
    location VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    logo_name VARCHAR(60),
    background_name VARCHAR(60),
    category VARCHAR(100),
    2fa_check BOOLEAN ,
    FOREIGN KEY (owner_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE users_events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    users_id INT NOT NULL,
    events_id INT NOT NULL,
    UNIQUE KEY unique_user_event (users_id, events_id),
    FOREIGN KEY (users_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (events_id) REFERENCES events(id) ON DELETE CASCADE
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