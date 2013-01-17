
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- song
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `song`;

CREATE TABLE `song`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `path` VARCHAR(255) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=MyISAM;

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
    INDEX `playitem_FI_1` (`song_id`)
) ENGINE=MyISAM;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
