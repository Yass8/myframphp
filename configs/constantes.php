<?php
    define("BASE_URL", "/myframphp");

    define("DB_NAME", getenv('DB_NAME') ?: "test");

    define("DB_USER", getenv('DB_USER') ?: "root");

    define("DB_PASSWORD", getenv('DB_PASSWORD') ?: "");

    define("__models", "app/models/");

    define("__controllers", "app/controllers/");

    define("__views", "app/views/");

    $url = isset($_GET['url']) && !empty($_GET['url']) ? $_GET['url'] : 'home';

    define("__url", $url);


    

