<?php

use App\Game;
use App\Server;
use Ratchet\App;
use React\EventLoop\Factory;

require "./vendor/autoload.php";

$state = new Game(20, 20);

$loop = Factory::create();

$app = new App("localhost", 8080, "127.0.0.1", $loop);
$app->route("/", new Server($loop, $state));
$app->run();