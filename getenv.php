<?php
require 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

//echo database.host;
echo $_ENV['DB_USERNAME'];
echo $_ENV['DB_HOST'];
