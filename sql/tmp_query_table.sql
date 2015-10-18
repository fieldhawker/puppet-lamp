CREATE TABLE following(
    user_id INTEGER,
    following_id INTEGER,
    PRIMARY KEY(user_id, following_id)
) ENGINE = INNODB;

ALTER TABLE following ADD FOREIGN KEY (user_id) REFERENCES user(id);
ALTER TABLE following ADD FOREIGN KEY (following_id) REFERENCES user(id);
ALTER TABLE status ADD FOREIGN KEY (user_id) REFERENCES user(id);
