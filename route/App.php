<?php 

$uri = explode('/',$_SERVER["REQUEST_URI"])[1];

if($uri == 'api')
    require_once __DIR__."/api/Api.php";
if($uri == 'admin')
    require_once __DIR__."/web/Admin.php";
    
if($uri != 'api' && $uri != 'admin')
    require_once __DIR__.'/web/Web.php';
    
