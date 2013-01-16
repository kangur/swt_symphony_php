<?php

namespace FUBerlin\ProjectBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'event' table.
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
class EventTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.FUBerlin.ProjectBundle.Model.map.EventTableMap';

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
        $this->setName('event');
        $this->setPhpName('Event');
        $this->setClassname('FUBerlin\\ProjectBundle\\Model\\Event');
        $this->setPackage('src.FUBerlin.ProjectBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('ID', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('OWNER_ID', 'OwnerId', 'INTEGER', 'user', 'ID', false, null, null);
        $this->addColumn('TITLE', 'Title', 'VARCHAR', false, 100, null);
        $this->addColumn('PLACE', 'Place', 'VARCHAR', false, 100, null);
        $this->addColumn('REQUIRE_RECEIPT', 'RequireReceipt', 'BOOLEAN', false, 1, false);
        $this->addColumn('BILLED', 'Billed', 'BOOLEAN', false, 1, false);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('OwnerUser', 'FUBerlin\\ProjectBundle\\Model\\User', RelationMap::MANY_TO_ONE, array('owner_id' => 'id', ), 'CASCADE', 'CASCADE');
        $this->addRelation('EventMember', 'FUBerlin\\ProjectBundle\\Model\\EventMember', RelationMap::ONE_TO_MANY, array('id' => 'event_id', ), 'CASCADE', 'CASCADE', 'EventMembers');
        $this->addRelation('EventPosition', 'FUBerlin\\ProjectBundle\\Model\\EventPosition', RelationMap::ONE_TO_MANY, array('id' => 'event_id', ), 'CASCADE', 'CASCADE', 'EventPositions');
        $this->addRelation('EventComment', 'FUBerlin\\ProjectBundle\\Model\\EventComment', RelationMap::ONE_TO_MANY, array('id' => 'event_id', ), 'CASCADE', 'CASCADE', 'EventComments');
        $this->addRelation('EventBillingPosition', 'FUBerlin\\ProjectBundle\\Model\\EventBillingPosition', RelationMap::ONE_TO_MANY, array('id' => 'event_id', ), 'CASCADE', 'CASCADE', 'EventBillingPositions');
        $this->addRelation('MemberUser', 'FUBerlin\\ProjectBundle\\Model\\User', RelationMap::MANY_TO_MANY, array(), 'CASCADE', 'CASCADE', 'MemberUsers');
    } // buildRelations()

} // EventTableMap
