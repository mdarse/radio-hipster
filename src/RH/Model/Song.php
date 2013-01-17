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
        if (null !== $this->file) {
            // generate unique name
            $filename = sha1(uniqid(mt_rand(), true));
            $this->setPath($filename.'.'.$this->file->guessExtension());

            // Do we need to access path with getPath() ?

            // DEBUG
            // $this->path = $this->file->getClientOriginalName();
            
            return true;
        }
    }

    public function postSave(PropelPDO $con = null)
    {
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
