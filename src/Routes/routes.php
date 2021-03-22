<?php

function routes(){
    $routes = [
        '/track-package' => ['method' => 'POST', 'controller' => 'TrackPackageController@resource']
    ];

    return $routes;
}
