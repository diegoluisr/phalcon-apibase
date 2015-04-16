<?php

/**
 * api/Config/routes.php
 * 
 * PHP version 5.3
 * 
 * @category   File - Config
 * @author     Diego Luis Restrepo  <diegoluisr@gmail.com>
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    GIT: $Id$
 * @since      0.0.1
*/

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