<?php

    require_once __DIR__ . '/routes/Router.php';
    require_once __DIR__ . '/configs/constantes.php';
    
    $router = new Router(__url);
    $router->dispatch();