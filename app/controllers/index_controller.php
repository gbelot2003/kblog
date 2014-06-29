<?php

/**
 * Controller por defecto si no se usa el routes
 * 
 */

class IndexController extends AppController
{
    public function index()
    {
    	$this->rand =  rand(1,60);
        Capcha::create_image();
        Session::get('rand');
        unset($this->rand);
    }

    public function test()
    {
    	$this->values = Input::post("rands");

    	if($this->values != Session::get('rand')){
    		Flash::error("Nada que ver");
    	} else {
    		Flash::valid("Correcto");
    		Session::delete('rand');
    	}
    }

    public function image(){

    }
}
