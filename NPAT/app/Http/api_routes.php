<?php

$api = app('Dingo\Api\Routing\Router');
$api->version('v1', function ($api) {

    $api->group(['namespace' => 'App\Http\Controllers\Api\v1'], function ($api) {

        $api->get('/user/{id}', 'UserApiController@showUser');
        $api->get('/user-hierarchical/{userid}', 'UserApiController@showUserHierarchicalList');
        $api->get('/userApi/{userid?}', 'UserApiController@showUserDetailsBasedOnHashKey');
        $api->post('/filter', 'UserApiController@showFilterResultsOnUser');
    });

});




