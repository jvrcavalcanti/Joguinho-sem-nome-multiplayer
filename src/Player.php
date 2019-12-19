<?php

namespace App;

use App\Game;

class Player
{
    public $id;
    public $x;
    public $y;
    public $points = 0;
    private $acceptedMoves;
    private $state;

    public function __construct(Game $state)
    {
        $this->state = $state;
        $this->id = md5(microtime());
        $this->x = rand(0, $state->screen['width'] - 1);
        $this->y = rand(0, $state->screen['height'] - 1);

        $this->acceptedMoves = [
            "ArrowUp" => function($state, $player) {
                $player->y = max($player->y - 1, 0);
            },
            "ArrowDown" => function($state, $player) {
                $player->y = min($player->y + 1, $state->screen['height'] - 1);
            },
            "ArrowRight" => function($state, $player) {
                $player->x = min($player->x + 1, $state->screen['width'] - 1);
            },
            "ArrowLeft" => function($state, $player) {
                $player->x = max($player->x - 1, 0);
            }
        ];    
    }

    public function move($key)
    {
        if(isset($this->acceptedMoves[$key])) {
            $this->acceptedMoves[$key]($this->state, $this);
            $this->collisionFruit();
        }
    }

    public function collisionFruit()
    {
        foreach($this->state->fruits as $fruit) {
            if($this->x == $fruit->x && $this->y == $fruit->y) {
                $this->state->deleteFruit($fruit->id);
                $this->points ++;
            }
        }
    }
}