-- Table structure for users
DROP TABLE IF EXISTS users;
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    firstname VARCHAR(250) NOT NULL DEFAULT ' ',
    lastname VARCHAR(250) NOT NULL DEFAULT ' ',
    password VARCHAR(255) NOT NULL,
    email VARCHAR(50) NOT NULL DEFAULT ' ',
    role VARCHAR(50) NOT NULL DEFAULT ' ',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Table structure for contacts
DROP TABLE IF EXISTS contacts;
CREATE TABLE contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(50) NOT NULL DEFAULT ' ',
    firstname VARCHAR(250) NOT NULL DEFAULT ' ',
    lastname VARCHAR(250) NOT NULL DEFAULT ' ',
    email VARCHAR(50) NOT NULL DEFAULT ' ',
    telephone VARCHAR(15) NOT NULL DEFAULT ' ',
    company VARCHAR(250) NOT NULL DEFAULT ' ',
    type VARCHAR(15) NOT NULL DEFAULT ' ',
    assigned_to INT NOT NULL,
    created_by INT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (assigned_to) REFERENCES users(id),
    FOREIGN KEY (created_by) REFERENCES users(id)
);

-- Table structure for notes
DROP TABLE IF EXISTS notes;
CREATE TABLE notes(
    id INT AUTO_INCREMENT PRIMARY KEY,
    contact_id INT NOT NULL,
    comment TEXT NOT NULL DEFAULT ' ',
    created_by INT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id)
);