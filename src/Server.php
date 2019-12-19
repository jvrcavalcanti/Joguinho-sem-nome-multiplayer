<?php

namespace App;

use Exception;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;
use React\EventLoop\LoopInterface;
use SplObjectStorage;

class Server implements MessageComponentInterface{
    protected $clients;
    private $state;

    public function __construct(LoopInterface $loop, Game $state)
    {
        $this->clients = new SplObjectStorage();
        $this->state = $state;

        $loop->addPeriodicTimer(3, function() {
            $this->state->addFruit();
        });
    }

    public function onOpen(ConnectionInterface $connection)
    {
        $this->clients->attach($connection);
        $connection->send(json_encode($this->state->addPlayer()));
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        $client = null;
        foreach($this->clients as $cli) {
            if($cli == $from) {
                $client = $cli;
            }
        }

        $msg = json_decode($msg);

        if($msg->action == "state"){
            $client->send(json_encode($this->state));
        }

        if($msg->action == "move"){
            $player = $this->state->searchPlayer($msg->id);
            $player->move($msg->key);
            // movePlayer($msg->id, $msg->key);
        }

        if($msg->action == "close") {
            $this->state->deletePlayer($msg->id);
            // deleteObject($msg->id, "players");
        }
    }

    public function onClose(ConnectionInterface $connection)
    {
        $this->clients->detach($connection);
    }

    public function onError(ConnectionInterface $connection, Exception $e)
    {

    }
}