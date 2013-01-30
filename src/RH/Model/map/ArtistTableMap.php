<?php

namespace RH\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'artist' table.
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
class ArtistTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'RH.Model.map.ArtistTableMap';

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
        $this->setName('artist');
        $this->setPhpName('Artist');
        $this->setClassname('RH\\Model\\Artist');
        $this->setPackage('RH.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('name', 'Name', 'VARCHAR', true, 255, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Song', 'RH\\Model\\Song', RelationMap::ONE_TO_MANY, array('id' => 'artist_id', ), null, null, 'Songs');
    } // buildRelations()

} // ArtistTableMap
