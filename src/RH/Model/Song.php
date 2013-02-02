<?php

namespace RH\Model;

use \PropelPDO;
use RH\Model\om\BaseSong;


/**
 * Skeleton subclass for representing a row from the 'song' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    propel.generator.RH.Model
 */
class Song extends BaseSong
{
    protected $file;
    private $isExtractingMetadata = false;

    public function getFile()
    {
        return $this->file;
    }

    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }

    public function preSave(PropelPDO $con = null)
    {
        if($this->isExtractingMetadata)
            return true;
        if (null !== $this->file) {

            // generate unique name
            $filename = sha1(uniqid(mt_rand(), true));
            $this->setPath($filename.'.'.$this->file->guessExtension());



            // Do we need to access path with getPath() ?

            // DEBUG
            // $this->path = $this->file->getClientOriginalName();

            return true;
        }
        
        return parent::preSave();
        
    }
    
    public function extractID3() {
        
        if ($this->isExtractingMetadata) return;
        
        $this->isExtractingMetadata = true;
        
        $getID3 = new \getID3();
        $filesInfos = $getID3->analyze($this->getAbsolutePath());
        
        //Time Gestion
        if(isset($filesInfos['playtime_string']))
         $this->setTime($filesInfos['playtime_string']);
        
        if(isset($filesInfos['tags']['id3v2'])){ //TODO : Change. It can change.
            
            //Year Gestion
            $this->setYear($filesInfos['tags']['id3v2']['year'][0]);
            
            //If there is a new artist, we create a new album
            $newArtist = false;
            
            //Artist gestion
            $nameArtist = $filesInfos['tags']['id3v2']['artist'][0];
            $arrayArt = ArtistQuery::create()->findByName($nameArtist);
            if (count($arrayArt) == 0)
            {
                $artist = new Artist();
                $artist->setName($nameArtist);
                $artist->save();
                $newArtist = true;
            }
            else
            {
                $artist = $arrayArt[0];
            }
            
            $this->setArtist($artist);
            
            
            
            //Album gestion
            $nameAlbum = $filesInfos['tags']['id3v2']['album'][0];
            $array = AlbumQuery::create()->findByName($nameAlbum);
            if (count($array) == 0 || $newArtist)
            {
                $album = new Album();
                $album->setName($nameAlbum);
                $album->save();
            }
            else
            {
                $album = $array[0];
            }
            $this->setAlbum($album);

            
            
        }


        $this->save();
        
        $this->isExtractingMetadata = FALSE;

    }

    public function postSave(PropelPDO $con = null)
    {
        
        if($this->isExtractingMetadata) return;
        
        if (null === $this->file) {
            return;
        }

        // if there is an error when moving the file, an exception will
        // be automatically thrown by move(). This will properly prevent
        // the entity from being persisted to the database on error
        // 
        $this->file->move($this->getUploadRootDir(), $this->path);

        unset($this->file);
    }

    public function postDelete(PropelPDO $con = null)
    {
        if ($file = $this->getAbsolutePath()) {
            unlink($file);
        }
    }

    public function getAbsolutePath()
    {
        return null === $this->path
            ? null
            : $this->getUploadRootDir().'/'.$this->path;
    }

    public function getWebPath()
    {
        return null === $this->path
            ? null
            : $this->getUploadDir().'/'.$this->path;
    }

    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__.'/../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return '/uploads';
    }
}
