<?php 

session_start();
header('Content-Type: charset=utf-8');
date_default_timezone_set("America/Sao_Paulo");

const SITE_ROOT = __DIR__;

require_once __DIR__."/vendor/autoload.php";
require_once __DIR__."/route/App.php";

