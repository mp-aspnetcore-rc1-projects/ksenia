<?php

namespace Controllers ;

use Phalcon\Mvc\Micro\Collection as MicroCollection;
/**
 * Robot API
 */
class RobotsController{

    //Retrieves all robots
    function index(){
        echo "index";
    }

    //Searches for robots with $name in their name
    function searchByName($name){
        echo "search robot with name $name";
    }

    //Retrieves robots based on primary key
    function read($id){
        echo "robot with id = $id";
    }
    //Adds a new robot
    function create() {

    }
    //Updates robots based on primary key
    function update($id) {

    }
    // delete robot
    function remove($id){

    }
    static function connect($app){
        # http://docs.phalconphp.com/en/latest/reference/tutorial-rest.html
        $coll = new MicroCollection();
        $coll->setHandler('Controllers\RobotsController',true);
        $coll->get('/api/robots','index');
        $coll->get('/api/robots/search/{name}','searchByName');
        $coll->get('/api/robots/{id:[0-9]+}','read');
        $coll->post('/api/robots', 'create');
        $coll->put('/api/robots/{id:[0-9]+}',"update");
        $coll->delete('/api/robots',"remove");
        $app->mount($coll);
        /*

         */
    }
}


