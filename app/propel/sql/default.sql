
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- user
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `username` VARCHAR(100),
    `first_name` VARCHAR(100),
    `last_name` VARCHAR(100),
    `email` VARCHAR(100),
    `password` VARCHAR(100),
    PRIMARY KEY (`id`),
    UNIQUE INDEX `user_U_1` (`email`)
) ENGINE=InnoDB CHARACTER SET='utf8';

-- ---------------------------------------------------------------------
-- event
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `event`;

CREATE TABLE `event`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `owner_id` INTEGER,
    `title` VARCHAR(100),
    `place` VARCHAR(100),
    `date` DATE,
    `require_receipt` TINYINT(1) DEFAULT 0,
    `billed` TINYINT(1) DEFAULT 0,
    PRIMARY KEY (`id`),
    INDEX `event_FI_1` (`owner_id`),
    CONSTRAINT `event_FK_1`
        FOREIGN KEY (`owner_id`)
        REFERENCES `user` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE
) ENGINE=InnoDB CHARACTER SET='utf8';

-- ---------------------------------------------------------------------
-- event_member
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `event_member`;

CREATE TABLE `event_member`
(
    `user_id` INTEGER NOT NULL,
    `event_id` INTEGER NOT NULL,
    PRIMARY KEY (`user_id`,`event_id`),
    INDEX `event_member_FI_2` (`event_id`),
    CONSTRAINT `event_member_FK_1`
        FOREIGN KEY (`user_id`)
        REFERENCES `user` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT `event_member_FK_2`
        FOREIGN KEY (`event_id`)
        REFERENCES `event` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE
) ENGINE=InnoDB CHARACTER SET='utf8';

-- ---------------------------------------------------------------------
-- event_position
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `event_position`;

CREATE TABLE `event_position`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `user_id` INTEGER,
    `event_id` INTEGER,
    `title` VARCHAR(100),
    `amount` DECIMAL(10,2),
    `receipt_path` VARCHAR(255),
    PRIMARY KEY (`id`),
    INDEX `event_position_FI_1` (`user_id`),
    INDEX `event_position_FI_2` (`event_id`),
    CONSTRAINT `event_position_FK_1`
        FOREIGN KEY (`user_id`)
        REFERENCES `user` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT `event_position_FK_2`
        FOREIGN KEY (`event_id`)
        REFERENCES `event` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE
) ENGINE=InnoDB CHARACTER SET='utf8';

-- ---------------------------------------------------------------------
-- event_comment
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `event_comment`;

CREATE TABLE `event_comment`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `user_id` INTEGER,
    `event_id` INTEGER,
    `comment` TEXT,
    `timestamp` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `event_comment_FI_1` (`user_id`),
    INDEX `event_comment_FI_2` (`event_id`),
    CONSTRAINT `event_comment_FK_1`
        FOREIGN KEY (`user_id`)
        REFERENCES `user` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT `event_comment_FK_2`
        FOREIGN KEY (`event_id`)
        REFERENCES `event` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE
) ENGINE=InnoDB CHARACTER SET='utf8';

-- ---------------------------------------------------------------------
-- event_billing_position
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `event_billing_position`;

CREATE TABLE `event_billing_position`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `user_id` INTEGER,
    `event_id` INTEGER,
    `amount` DECIMAL(10,2),
    `paid` TINYINT(1),
    PRIMARY KEY (`id`),
    INDEX `event_billing_position_FI_1` (`user_id`),
    INDEX `event_billing_position_FI_2` (`event_id`),
    CONSTRAINT `event_billing_position_FK_1`
        FOREIGN KEY (`user_id`)
        REFERENCES `user` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT `event_billing_position_FK_2`
        FOREIGN KEY (`event_id`)
        REFERENCES `event` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE
) ENGINE=InnoDB CHARACTER SET='utf8';

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
