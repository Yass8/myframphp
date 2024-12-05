<?php

    require_once __DIR__ . '/core/Router.php';
    require_once __DIR__ . '/configs/constantes.php';
    
    $rooter = new Router(__url);
    $rooter->dispatch();