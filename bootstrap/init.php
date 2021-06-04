<?php

require 'vendor/autoload.php';

define('root_path', __DIR__.'/../');

$dotenv = Dotenv\Dotenv::createImmutable(root_path);
$dotenv->load();
