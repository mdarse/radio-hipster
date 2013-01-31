<?php

namespace RH;

use RH\Model\PlayItem;

class Playlist
{
    private $items;
    private $baseUrl;

    public function __construct(array $items, $baseUrl = '')
    {
        $this->items = $items;
        $this->baseUrl = $baseUrl;
    }

    public function toArray()
    {
        return array_map(array($this, 'makeItem'), $this->items);
    }

    private function makeItem(PlayItem $playItem)
    {
        $song = $playItem->getSong();
        $attributes = array(
            'id'             => $playItem->getId(),
            'order'          => $playItem->getOrder(),
            'song_id'        => $song->getId(),
            'song_name'      => $song->getName(),
            'song_media_url' => $this->baseUrl.$song->getWebPath()
        );
        if ($artist = $song->getArtiste()) {
            $attributes['song_artist_id'] = $artist->getId();
            $attributes['song_artist']    = $artist->getName();
        }
        if ($album = $song->getAlbum()) {
            $attributes['song_album_id'] = $album->getId();
            $attributes['song_album']    = $album->getName();
        }

        return $attributes;
    }
}
