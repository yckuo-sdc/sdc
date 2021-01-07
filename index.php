<?php 
if(isset($_GET['sid']))	session_id($_GET['sid']);	# fetch and update Session ID with sso_vision.php
session_start(); 
require __DIR__ . '/vendor/autoload.php';
require 'init.php';
require 'route.php';
