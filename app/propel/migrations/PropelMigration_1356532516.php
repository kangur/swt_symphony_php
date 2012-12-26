<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1356532516.
 * Generated on 2012-12-26 15:35:16 
 */
class PropelMigration_1356532516
{

    public function preUp($manager)
    {
        // add the pre-migration code here
    }

    public function postUp($manager)
    {
        // add the post-migration code here
    }

    public function preDown($manager)
    {
        // add the pre-migration code here
    }

    public function postDown($manager)
    {
        // add the post-migration code here
    }

    /**
     * Get the SQL statements for the Up migration
     *
     * @return array list of the SQL strings to execute for the Up migration
     *               the keys being the datasources
     */
    public function getUpSQL()
    {
        return array (
  'default' => '
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

ALTER TABLE `event` DROP FOREIGN KEY `event_FK_1`;

ALTER TABLE `event` ADD CONSTRAINT `event_FK_1`
    FOREIGN KEY (`owner_id`)
    REFERENCES `user` (`id`)
    ON UPDATE CASCADE
    ON DELETE CASCADE;

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
) ENGINE=InnoDB CHARACTER SET=\'utf8\';

CREATE TABLE `event_position`
(
    `user_id` INTEGER NOT NULL,
    `event_id` INTEGER NOT NULL,
    `title` VARCHAR(100),
    `amount` DECIMAL,
    `receipt_path` VARCHAR(255),
    PRIMARY KEY (`user_id`,`event_id`),
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
) ENGINE=InnoDB CHARACTER SET=\'utf8\';

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

    /**
     * Get the SQL statements for the Down migration
     *
     * @return array list of the SQL strings to execute for the Down migration
     *               the keys being the datasources
     */
    public function getDownSQL()
    {
        return array (
  'default' => '
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `event_member`;

DROP TABLE IF EXISTS `event_position`;

ALTER TABLE `event` DROP FOREIGN KEY `event_FK_1`;

ALTER TABLE `event` ADD CONSTRAINT `event_FK_1`
    FOREIGN KEY (`owner_id`)
    REFERENCES `user` (`id`);

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}