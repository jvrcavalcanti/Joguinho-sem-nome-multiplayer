<?php

namespace App;

class Game
{
    public $screen; 
    public $players = [];
    public $fruits = [];

    public function __construct($width, $height)
    {
        $this->screen = [
            "width" => $width,
            "height" => $height
        ];
    }

    public function addPlayer()
    {
        $player = new Player($this);
        $this->players[] = $player;
        array_multisort($this->players);

        return $player->id;
    }

    public function addFruit()
    {
        $this->fruits[] = new Fruit($this);
        array_multisort($this->fruits);
    }

    public function deletePlayer($id)
    {
        $player = $this->searchPlayer($id);
        $key = array_search($player, $this->players);
        
        unset($this->players[$key]);
        array_multisort($this->players);
    }

    public function deleteFruit($id)
    {
        $fruit = $this->searchFruit($id);
        $key = array_search($fruit, $this->fruits);
        
        unset($this->fruits[$key]);
        array_multisort($this->fruits);
    }

    public function searchPlayer($id)
    {
        foreach($this->players as $pl) {
            if($pl->id == $id) {
                return $pl;
            }
        }
        return null;
    }
    
    public function searchFruit($id)
    {
        foreach($this->fruits as $ft) {
            if($ft->id == $id) {
                return $ft;
            }
        }
        return null;
    }

    public function __get($name)
    {
        return $this->$name;
    }

    public function __set($name, $value)
    {
        $this->$name = $value;
    }
}