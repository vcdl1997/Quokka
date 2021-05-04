<?php
    if(session_status() !== PHP_SESSION_ACTIVE) session_start();
    if(!isset($_SESSION['user'])) $_SESSION['user'] = array(); 

    // $_SESSION['user'] = [ 'id' => 1, 'name' => "Vinicius" ];

    require_once 'route/Route.php';

    require_once Route::calling($_GET);