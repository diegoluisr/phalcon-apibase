<?php
/**
 * Collections let us define groups of routes that will all use the same controller.
 * We can also set the handler to be lazy loaded.  Collections can share a common prefix.
 * @var $collection
 */

// This is an Immeidately Invoked Function in php.  The return value of the
// anonymous function will be returned to any file that "includes" it.
// e.g. $collection = include('example.php');
return call_user_func(function(){

    $collection = new \Phalcon\Mvc\Micro\Collection();

    $collection
        ->setPrefix('/users')
        ->setHandler('\Api\Modules\Account\Controllers\UsersController')
        ->setLazy(true);

    $collection->options('/', 'optionsBase');
    $collection->options('/{id}', 'optionsOne');

    $collection->get('/', 'index');
    $collection->head('/', 'index');

    $collection->get('/{id:[a-zA-Z0-9]+}', 'view');
    $collection->head('/{id:[a-zA-Z0-9]+}', 'view');
    $collection->post('/', 'add');
    $collection->delete('/{id:[a-zA-Z0-9+}', 'delete');
    $collection->put('/{id:[a-zA-Z0-9]+}', 'edit');
    $collection->patch('/{id:[a-zA-Z0-9]+}', 'patch');

    return $collection;
});