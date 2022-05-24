CREATE TABLE IF NOT EXISTS Tokens (
    `id` INT NOT NULL UNIQUE PRIMARY KEY,
    `token` VARCHAR(255) NULL,
    `added_time` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id) REFERENCES Users(user_id)
) AUTO_INCREMENT = 1;

DELIMITER $$
CREATE TRIGGER `before_insert_Tokens` 
  BEFORE INSERT 
  ON `Tokens` FOR EACH ROW
  BEGIN
    IF new.token IS NULL THEN
      SET new.token = uuid();
    END IF;
END$$