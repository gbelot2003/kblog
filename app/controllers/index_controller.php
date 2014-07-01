<?php

/**
 * Controller por defecto si no se usa el routes
 * 
 */

class IndexController extends AppController
{
    public function index()
    {

    }

    public function login()
    {	
    	
		if(Input::hasPost("user", "password")):
			$user = Input::Post('user');
			$pass = Input::Post('password');
			$pws  = sha1($pass);
			$auth = new Auth("model", "class: users", "user: $user", "password: $pws");
			if($auth->authenticate()):
				Router::redirect("/");
			else:
				Flash::valid("<strong>Atención, La información de usuario no coinside !!</strong>");
			endif;
		endif;
    }

    public function logout(){
    	Auth::destroy_identity();
        Router::redirect("/");
    }
}
