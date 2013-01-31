
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- artist
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `artist`;

CREATE TABLE `artist`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- album
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `album`;

CREATE TABLE `album`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- song
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `song`;

CREATE TABLE `song`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `path` VARCHAR(255) NOT NULL,
    `year` INTEGER,
    `time` VARCHAR(255),
    `artist_id` INTEGER,
    `listen_count` INTEGER NOT NULL,
    `album_id` INTEGER,
    PRIMARY KEY (`id`),
    INDEX `song_FI_1` (`artist_id`),
    INDEX `song_FI_2` (`album_id`),
    CONSTRAINT `song_FK_1`
        FOREIGN KEY (`artist_id`)
        REFERENCES `artist` (`id`),
    CONSTRAINT `song_FK_2`
        FOREIGN KEY (`album_id`)
        REFERENCES `album` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- playitem
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `playitem`;

CREATE TABLE `playitem`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `order` INTEGER NOT NULL,
    `song_id` INTEGER NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `playitem_FI_1` (`song_id`),
    CONSTRAINT `playitem_FK_1`
        FOREIGN KEY (`song_id`)
        REFERENCES `song` (`id`)
        ON DELETE CASCADE
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
