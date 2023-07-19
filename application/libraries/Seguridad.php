<?php if (!defined('BASEPATH')) exit('No permitir el acceso directo al script');

class Seguridad {

    function getRealIP()
    {

            if( $_SERVER['HTTP_X_FORWARDED_FOR'] != '' )
            {
              $client_ip =
                 ( !empty($_SERVER['REMOTE_ADDR']) ) ?
                    $_SERVER['REMOTE_ADDR']
                    :
                    ( ( !empty($_ENV['REMOTE_ADDR']) ) ?
                       $_ENV['REMOTE_ADDR']
                       :
                       "unknown" );

              // los proxys van añadiendo al final de esta cabecera
              // las direcciones ip que van "ocultando". Para localizar la ip real
              // del usuario se comienza a mirar por el principio hasta encontrar
              // una dirección ip que no sea del rango privado. En caso de no
              // encontrarse ninguna se toma como valor el REMOTE_ADDR

              $entries = split('[, ]', $_SERVER['HTTP_X_FORWARDED_FOR']);

              reset($entries);
              while (list(, $entry) = each($entries))
              {
                 $entry = trim($entry);
                 if ( preg_match("/^([0-9]+\.[0-9]+\.[0-9]+\.[0-9]+)/", $entry, $ip_list) )
                 {
                    // http://www.faqs.org/rfcs/rfc1918.html
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
                    $_SERVER['REMOTE_ADDR']
                    :
                    ( ( !empty($_ENV['REMOTE_ADDR']) ) ?
                       $_ENV['REMOTE_ADDR']
                       :
                       "unknown" );
            }

            return $client_ip;

    }

    function ipPertenece($ip,$red,$mascara) {  
            // Dividimos en octetos
            if(trim($ip)=='::1'){
              $ip = '127.0.0.1';
              $red = '127.0.0.0';
              $mascara = '255.255.255.0';
            }  
            $octip = explode(".",$ip);  
            $octnet = explode(".",$red);  
            $octmask = explode(".",$mascara);  
            // Comparamos con AND binario  
              for ($i=0;$i<4;$i++) {  
                    $a = (int)$octip[$i] & (int)$octmask[$i];  
                    $b = (int)$octnet[$i] & (int)$octmask[$i];  
                    if ($a != $b) return(0);  
            }  
            return(1);  
    }  

    function revisa_front($redes_permitidas,$ip_cliente){
        $redes = explode(',', $redes_permitidas);
        
        //$ip_cliente = $this->getRealIP();
        
        $salida = FALSE;
        
        foreach ($redes as $red){
            //Saber si esuna red o ip
            if (strpos($red,'/') == FALSE){
                //Es un IP
                if(trim($red)==$ip_cliente){
                    $salida = TRUE;
                }
            } else {
                
                $rango_mascara = explode('/', $red);
                $numero_masc = trim($rango_mascara[1]);
                
                $mascara = '';
                
                $binario = '';
                for($i=1;$i<=8;$i++){
                    if($i <= $numero_masc){
                        $binario .= '1';
                    } else {
                        $binario .= '0';
                    }
                }
                
                $mascara .= bindec($binario);

                $binario = '';
                for($i=9;$i<=16;$i++){
                    if($i <= $numero_masc){
                        $binario .= '1';
                    } else {
                        $binario .= '0';
                    }
                }
                
                $mascara .= '.'.bindec($binario);
                
                $binario = '';
                for($i=17;$i<=24;$i++){
                    if($i <= $numero_masc){
                        $binario .= '1';
                    } else {
                        $binario .= '0';
                    }
                }
                
                $mascara .= '.'.bindec($binario);
                
                $binario = '';
                for($i=25;$i<=36;$i++){
                    if($i <= $numero_masc){
                        $binario .= '1';
                    } else {
                        $binario .= '0';
                    }
                }
                
                $mascara .= '.'.bindec($binario);
                
                if($this->ipPertenece($ip_cliente, trim($red), $mascara)){
                    $salida = TRUE;
                }
            }
        }
        
        return $salida;
    }
    
}

?>
