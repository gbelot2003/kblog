<?php 

/**
 * Sistema de Control de Permisos 
 */
class Permisos {
  
    public static function getIP(){
     
       if(isset($_SERVER['HTTP_X_FORWARDED_FOR']) != '' && ($_SERVER['HTTP_X_FORWARDED_FOR'] != '' ))
       {
          $client_ip = ( !empty($_SERVER['REMOTE_ADDR']) ) ? $_SERVER['REMOTE_ADDR']  : 
                ((!empty($_ENV['REMOTE_ADDR'])) ? $_ENV['REMOTE_ADDR'] : "unknown" );
     
          // los proxys van añadiendo al final de esta cabecera
          // las direcciones ip que van "ocultando". Para localizar la ip real
          // del usuario se comienza a mirar por el principio hasta encontrar 
          // una dirección ip que no sea del rango privado. En caso de no 
          // encontrarse ninguna se toma como valor el REMOTE_ADDR
     
          $entries = explode('/[,]/', $_SERVER['HTTP_X_FORWARDED_FOR']);
     
          reset($entries);
          while (list(,$entry) = each($entries)) 
          {
             $entry = trim($entry);
             if (preg_match("/^([0-9]+\.[0-9]+\.[0-9]+\.[0-9]+)/", $entry, $ip_list) )
             {
                $private_ip = array(
                      '/^0\./', 
                      '/^127\.0\.0\.1/', 
                      '/^192\.168\..*/', 
                      '/^172\.((1[6-9])|(2[0-9])|(3[0-1]))\..*/', 
                      '/^10\..*/');
     
                $found_ip = preg_replace($private_ip, $client_ip, $ip_list[1]);
     
                if ($client_ip != $found_ip)
                {
                   $client_ip = $found_ip;
                   break;
                }
             }
          }
       }
       else
       {
          $client_ip = 
             ( !empty($_SERVER['REMOTE_ADDR']) ) ? 
                $_SERVER['REMOTE_ADDR'] : ( ( !empty($_ENV['REMOTE_ADDR']) ) ? 
                   $_ENV['REMOTE_ADDR'] : "unknown" );
       }
     
       return $client_ip;
    }

    public static function validador(){
        $ip = Permisos::getIP();
        $control = Router::get('controller');
        $act = Router::get('action');
        $controllerAction = $control."_".$act;
        $accion = "Intento Acceso restringido a ".$controllerAction." desde | ".$ip;
        
        if(!Auth::is_valid()):
            $rol = "Anonimo";
            $user = "Anonimo";
            $validador = NULL;

            Acciones::grabarAccesos($user, $accion, NULL);
            flash::error("No tienes acesso");
            Router::redirect("/");
            return $validador;
        else:
            $rol = Auth::get('rol_id');
            $user = Auth::get('usuario');
            $conditions = "conditions: rol_id = $rol AND accion = '$controllerAction'";
            if($accesos = Load::Model('accesos')->find($conditions)):
                $validador = $accesos;
                return $validador;
            else:
              $rol = Auth::get('rol_id');
              $user = Auth::get('usuario');
              
              Acciones::grabarAccesos($user, $accion, NULL);
              flash::error("No tienes acesso");
              Router::redirect("/");
            endif;
        endif;


    }
}