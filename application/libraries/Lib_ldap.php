<?php if ( ! defined('BASEPATH')) exit('No se permite acceso directo al script');

class Lib_LDAP {
	public function autentica($usuario, $contrasenna, $servidor, $sufijo, $base_dn, $grupos){

		$config = array(
		    'domain_controllers' => array($servidor),
		    'account_suffix' => $sufijo, 
		    'base_dn' => $base_dn 
		);

		require_once(dirname(__FILE__) . '/ldap/adLDAP.php');

		$adldap = new adLDAP($config);
		$adldap->connect();
		
		$existeusuario = $adldap->authenticate($usuario, $contrasenna);
                
                $salida = array();
                
                if ($existeusuario == 0){
                    $salida['code'] = 0;
                    $salida['mensaje'] = 'El usuario y la contraseña no coinciden';
                } else {
                    $salida['code'] = 0;
                    $salida['mensaje'] = 'El usuario no es un docente.';


                    // Verificar si pertenecie al grupo docentes

		    $estrabajador = false;
			
		    foreach($grupos as $grupo){
	                if ($adldap->user()->inGroup($usuario, $grupo, true)){
        	                $estrabajador = true;
               	     	}
		    }

                    if ($estrabajador){
                        $salida['code'] += 1;
                        $salida['mensaje'] = 'Bienvenido.';
                    } 
                    
                }
                
                return $salida;
                
	}
        
        public function lista_trabajadores ($usuario, $contrasenna, $servidor, $sufijo, $base_dn, $grupos){
            $config = array(
                'domain_controllers' => array($servidor),
                'account_suffix' => $sufijo,
                'base_dn' => $base_dn
            );

            require_once(dirname(__FILE__) . '/ldap/adLDAP.php');

            $adldap = new adLDAP($config);

            $adldap->setAdminUsername($usuario);
            $adldap->setAdminPassword($contrasenna);
            $adldap->connect();

            $lista_usuarios = array();
            foreach ($grupos as $grupo){
                $resultado = $adldap->group()->members($grupo, true);
                if (is_array($resultado)){
                    foreach ($resultado as $username){
                        if(in_array($username, $lista_usuarios) == false){
                            $lista_usuarios[] = $username;
                        }
                    }
                }
            }

            return $lista_usuarios;
        }

        public function lista_estudiantes ($usuario, $contrasenna, $servidor, $sufijo, $base_dn, $grupos){
            $config = array(
                'domain_controllers' => array($servidor),
                'account_suffix' => $sufijo,
                'base_dn' => $base_dn
            );

            require_once(dirname(__FILE__) . '/ldap/adLDAP.php');

            $adldap = new adLDAP($config);

            $adldap->setAdminUsername($usuario);
            $adldap->setAdminPassword($contrasenna);
            $adldap->connect();
                
            $lista_usuarios = array();
            foreach ($grupos as $grupo){
                $resultado = $adldap->group()->members($grupo, true);
                if (is_array($resultado)){
                    foreach ($resultado as $username){
                        if(in_array($username, $lista_usuarios) == false){
                            $lista_usuarios[] = $username;
                        }
                    }
                }
            }

            return $lista_usuarios;
                
        }

        public function datos_usuario($usuario, $contrasenna,$usuario_buscar, $campos  = NULL, $servidor, $sufijo, $base_dn){

            $config = array(
                'domain_controllers' => array($servidor),
                'account_suffix' => $sufijo,
                'base_dn' => $base_dn
            );

            require_once(dirname(__FILE__) . '/ldap/adLDAP.php');

            $adldap = new adLDAP($config);

            $adldap->setAdminUsername($usuario);
            $adldap->setAdminPassword($contrasenna);
            $adldap->connect();

            $uu = $adldap->user()->info($usuario_buscar, $campos);
            return $uu;
        }
        
}

