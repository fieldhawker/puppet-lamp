CREATE TABLE user(
    id INTEGER AUTO_INCREMENT,
    email VARCHAR(256) NOT NULL,
    password VARCHAR(40) NOT NULL,
    created_at DATETIME,
    PRIMARY KEY(id),
    UNIQUE KEY email_index(email)
) ENGINE = INNODB;
