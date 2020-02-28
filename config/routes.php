<?php

use Cake\Routing\Router;

Router::plugin('DataCenter', function ($routes) {
    $routes->fallbacks('InflectedRoute');
});
