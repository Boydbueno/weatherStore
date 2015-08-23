<?php

$app->get('api/weather', 'WeatherController@retrieve');
$app->post('api/weather', 'WeatherController@store');
