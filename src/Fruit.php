<?php

namespace App;

class Fruit
{
    public $id;
    public $x;
    public $y;
    private $state;

    public function __construct($state)
    {
        $this->state = $state;
        $this->id = md5(microtime());
        $this->x = rand(0, $this->state->screen['width']);
        $this->y = rand(0, $this->state->screen['height']);
    }
}