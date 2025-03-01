#!/usr/bin/env php
<?php

require __DIR__ . '/../vendor/autoload.php';

use Bortsaykin\Progression\Controller;

$controller = new Controller();
$controller->startGame();