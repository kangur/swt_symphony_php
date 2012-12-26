<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1356536069.
 * Generated on 2012-12-26 16:34:29 
 */
class PropelMigration_1356536069
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

ALTER TABLE `event_position` DROP PRIMARY KEY;

ALTER TABLE `event_position` CHANGE `user_id` `user_id` INTEGER;

ALTER TABLE `event_position` CHANGE `event_id` `event_id` INTEGER;

ALTER TABLE `event_position`
    ADD `id` INTEGER NOT NULL AUTO_INCREMENT FIRST;

ALTER TABLE `event_position` ADD PRIMARY KEY (`id`);

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

ALTER TABLE `event_position` DROP PRIMARY KEY;

ALTER TABLE `event_position` CHANGE `user_id` `user_id` INTEGER DEFAULT 0 NOT NULL;

ALTER TABLE `event_position` CHANGE `event_id` `event_id` INTEGER DEFAULT 0 NOT NULL;

ALTER TABLE `event_position` DROP `id`;

ALTER TABLE `event_position` ADD PRIMARY KEY (`user_id`,`event_id`);

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}