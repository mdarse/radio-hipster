<?php

namespace RH\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'song' table.
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
class SongTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'RH.Model.map.SongTableMap';

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
        $this->setName('song');
        $this->setPhpName('Song');
        $this->setClassname('RH\\Model\\Song');
        $this->setPackage('RH.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('name', 'Name', 'VARCHAR', true, 255, null);
        $this->addColumn('path', 'Path', 'VARCHAR', true, 255, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('PlayItem', 'RH\\Model\\PlayItem', RelationMap::ONE_TO_MANY, array('id' => 'song_id', ), null, null, 'PlayItems');
    } // buildRelations()

} // SongTableMap
