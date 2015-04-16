<?php

return call_user_func(function(){

    $collections = array();
    $collectionFiles = scandir(dirname(__FILE__) . '/Collections');

    foreach($collectionFiles as $collectionFile){
        $pathinfo = pathinfo($collectionFile);
        if($pathinfo['extension'] === 'php'){
            $collections[] = include(dirname(__FILE__) .'/Collections/' . $collectionFile);
        }
    }

    return $collections;
});