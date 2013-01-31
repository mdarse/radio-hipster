<?php

namespace RH\Model;

use RH\Model\om\BaseArtistQuery;


/**
 * Skeleton subclass for performing query and update operations on the 'artist' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    propel.generator.RH.Model
 */
class ArtistQuery extends BaseArtistQuery
{
    public function filterByPattern($pattern)
    {
        return $this->filterByName('%' . $pattern . '%');
    }
    
    public function findByPattern($pattern)
    {
        return $this->filterByPattern($pattern)->find();
    }
}
