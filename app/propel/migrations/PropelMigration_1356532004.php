<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1356532004.
 * Generated on 2012-12-26 15:26:44 
 */
class PropelMigration_1356532004
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

CREATE TABLE `event`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `owner_id` INTEGER,
    `title` VARCHAR(100),
    `place` VARCHAR(100),
    `require_receipt` TINYINT(1),
    `billed` TINYINT(1) DEFAULT 0,
    PRIMARY KEY (`id`),
    INDEX `event_FI_1` (`owner_id`),
    CONSTRAINT `event_FK_1`
        FOREIGN KEY (`owner_id`)
        REFERENCES `user` (`id`)
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

DROP TABLE IF EXISTS `event`;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}