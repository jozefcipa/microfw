<?php

// load helpers
require __DIR__ . '/app/Support/helpers.php';

// load config
require __DIR__ . '/app/config.php';

// load routes
require __DIR__ . '/app/routes.php';


// initialize Router
$router->start();
