<?php

namespace FUBerlin\ProjectBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'user' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.src.FUBerlin.ProjectBundle.Model.map
 */
class UserTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.FUBerlin.ProjectBundle.Model.map.UserTableMap';

    /**
     * Initialize the table attributes, columns and validators
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws PropelException
     */
    public function initialize()
    {
        // attributes
        $this->setName('user');
        $this->setPhpName('User');
        $this->setClassname('FUBerlin\\ProjectBundle\\Model\\User');
        $this->setPackage('src.FUBerlin.ProjectBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('ID', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('USERNAME', 'Username', 'VARCHAR', false, 100, null);
        $this->addColumn('FIRST_NAME', 'FirstName', 'VARCHAR', false, 100, null);
        $this->addColumn('LAST_NAME', 'LastName', 'VARCHAR', false, 100, null);
        $this->addColumn('EMAIL', 'Email', 'VARCHAR', false, 100, null);
        $this->addColumn('PASSWORD', 'Password', 'VARCHAR', false, 100, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('EventOwner', 'FUBerlin\\ProjectBundle\\Model\\Event', RelationMap::ONE_TO_MANY, array('id' => 'owner_id', ), 'CASCADE', 'CASCADE', 'EventOwners');
        $this->addRelation('EventMember', 'FUBerlin\\ProjectBundle\\Model\\EventMember', RelationMap::ONE_TO_MANY, array('id' => 'user_id', ), 'CASCADE', 'CASCADE', 'EventMembers');
        $this->addRelation('EventPosition', 'FUBerlin\\ProjectBundle\\Model\\EventPosition', RelationMap::ONE_TO_MANY, array('id' => 'user_id', ), 'CASCADE', 'CASCADE', 'EventPositions');
        $this->addRelation('EventComment', 'FUBerlin\\ProjectBundle\\Model\\EventComment', RelationMap::ONE_TO_MANY, array('id' => 'user_id', ), 'CASCADE', 'CASCADE', 'EventComments');
        $this->addRelation('EventBillingPosition', 'FUBerlin\\ProjectBundle\\Model\\EventBillingPosition', RelationMap::ONE_TO_MANY, array('id' => 'user_id', ), 'CASCADE', 'CASCADE', 'EventBillingPositions');
        $this->addRelation('Event', 'FUBerlin\\ProjectBundle\\Model\\Event', RelationMap::MANY_TO_MANY, array(), 'CASCADE', 'CASCADE', 'Events');
    } // buildRelations()

} // UserTableMap
