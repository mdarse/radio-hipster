<?php

namespace RH\Model;

use RH\Model\om\BaseSongQuery;
use RH\Model\AlbumQuery;
use RH\Model\ArtistQuery;


/**
 * Skeleton subclass for performing query and update operations on the 'song' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    propel.generator.RH.Model
 */
class SongQuery extends BaseSongQuery
{
    public function filterByPattern($pattern)
    {
        return $this->filterByName('%' . $pattern . '%');
    }
    
    public function findByPattern($pattern)
    {
        return $this->filterByPattern($pattern)->find();
    }
    
    public function findByAlbumNamePattern($pattern)
    {
//          Pourquoi ça marche pas?
//        $songsFromAlbum = SongQuery::create()
//                ->useAlbumQuery()
//                    ->filterByName('%' . $data['search'] . '%')
//                ->endUse()
//                ->find();
        
        $albums = AlbumQuery::create()
                ->findByPattern($pattern);
        
        $songsOnAlbum = array();
        foreach ($albums as $album) {
            $songsOnAlbum[] = $this->filterByAlbum($album)->find();
        }
        
        return $songsOnAlbum;
    
    }
    
        public function findByArtistNamePattern($pattern)
    {
        $albums = ArtistQuery::create()
                ->findByPattern($pattern);
        
        $songsOnAlbum = array();
        foreach ($albums as $album) {
            $songsOnAlbum[] = $this->filterByArtist($album)->find();
        }
        
        return $songsOnAlbum;
    
    }
}
