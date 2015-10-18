CREATE TABLE address (
  id         INTEGER AUTO_INCREMENT,
  name       VARCHAR(20),
  address    VARCHAR(255),
  created_at DATETIME,
  updated_at DATETIME,
  created_by INTEGER,
  updated_by INTEGER,
  PRIMARY KEY (id),
  INDEX address_name_index(name)
)
  ENGINE = INNODB;