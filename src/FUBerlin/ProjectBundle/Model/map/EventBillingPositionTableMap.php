<?php

namespace FUBerlin\ProjectBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'event_billing_position' table.
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
class EventBillingPositionTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.FUBerlin.ProjectBundle.Model.map.EventBillingPositionTableMap';

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
        $this->setName('event_billing_position');
        $this->setPhpName('EventBillingPosition');
        $this->setClassname('FUBerlin\\ProjectBundle\\Model\\EventBillingPosition');
        $this->setPackage('src.FUBerlin.ProjectBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('ID', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('USER_ID', 'UserId', 'INTEGER', 'user', 'ID', false, null, null);
        $this->addForeignKey('EVENT_ID', 'EventId', 'INTEGER', 'event', 'ID', false, null, null);
        $this->addColumn('AMOUNT', 'Amount', 'DECIMAL', false, 10, null);
        $this->addColumn('PAID', 'Paid', 'BOOLEAN', false, 1, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('User', 'FUBerlin\\ProjectBundle\\Model\\User', RelationMap::MANY_TO_ONE, array('user_id' => 'id', ), 'CASCADE', 'CASCADE');
        $this->addRelation('Event', 'FUBerlin\\ProjectBundle\\Model\\Event', RelationMap::MANY_TO_ONE, array('event_id' => 'id', ), 'CASCADE', 'CASCADE');
    } // buildRelations()

} // EventBillingPositionTableMap
