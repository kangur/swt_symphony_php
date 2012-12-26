<?php

namespace FUBerlin\ProjectBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'event_member' table.
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
class EventMemberTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.FUBerlin.ProjectBundle.Model.map.EventMemberTableMap';

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
        $this->setName('event_member');
        $this->setPhpName('EventMember');
        $this->setClassname('FUBerlin\\ProjectBundle\\Model\\EventMember');
        $this->setPackage('src.FUBerlin.ProjectBundle.Model');
        $this->setUseIdGenerator(false);
        $this->setIsCrossRef(true);
        // columns
        $this->addForeignPrimaryKey('USER_ID', 'UserId', 'INTEGER' , 'user', 'ID', true, null, null);
        $this->addForeignPrimaryKey('EVENT_ID', 'EventId', 'INTEGER' , 'event', 'ID', true, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('MemberUser', 'FUBerlin\\ProjectBundle\\Model\\User', RelationMap::MANY_TO_ONE, array('user_id' => 'id', ), 'CASCADE', 'CASCADE');
        $this->addRelation('Event', 'FUBerlin\\ProjectBundle\\Model\\Event', RelationMap::MANY_TO_ONE, array('event_id' => 'id', ), 'CASCADE', 'CASCADE');
    } // buildRelations()

} // EventMemberTableMap
