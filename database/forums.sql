CREATE TABLE IF NOT EXISTS Sections (
    `id` TINYINT NOT NULL UNIQUE PRIMARY KEY AUTO_INCREMENT,
    `name` VARCHAR(50) NOT NULL UNIQUE,
    `datetime_created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) AUTO_INCREMENT = 1;

CREATE TABLE IF NOT EXISTS Forums (
    `forum_id` INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    `forum_name` VARCHAR(50) NOT NULL,
    `forum_description` TEXT NOT NULL,
    `section_id` TINYINT NOT NULL,
    `datetime_created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (section_id) REFERENCES Sections(id)  
) AUTO_INCREMENT = 1;

CREATE TABLE IF NOT EXISTS Posts (
    `post_id` INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    `creator_id` INT,
    `title` VARCHAR(50) NOT NULL,
    `content` TEXT NOT NULL,
    `views` INT DEFAULT 0,
    `parent_forum_id` INT NOT NULL,
    `datetime_created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (creator_id) REFERENCES Users(user_id),
    FOREIGN KEY (parent_forum_id) REFERENCES Forums(forum_id)
) AUTO_INCREMENT = 1;


CREATE TABLE IF NOT EXISTS Comments (
    `comment_id` INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    `creator_id` INT NOT NULL,
    `content` TEXT NOT NULL,
    `post_id` INT NOT NULL,
    `datetime_created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (creator_id) REFERENCES Users(user_id),
    FOREIGN KEY (post_id) REFERENCES Posts(post_id)
) AUTO_INCREMENT = 1;
