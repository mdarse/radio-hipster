<?php

namespace RH\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'playitem' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.RH.Model.map
 */
class PlayItemTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'RH.Model.map.PlayItemTableMap';

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
        $this->setName('playitem');
        $this->setPhpName('PlayItem');
        $this->setClassname('RH\\Model\\PlayItem');
        $this->setPackage('RH.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('order', 'Order', 'INTEGER', true, null, null);
        $this->addForeignKey('song_id', 'SongId', 'INTEGER', 'song', 'id', true, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Song', 'RH\\Model\\Song', RelationMap::MANY_TO_ONE, array('song_id' => 'id', ), 'CASCADE', null);
    } // buildRelations()

} // PlayItemTableMap
