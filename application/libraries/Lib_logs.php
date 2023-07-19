<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class Lib_Logs {
    function registra_log($fecha_hora, $usuario, $ip, $texto, $admin = false){
        //Logear
        
        require_once(dirname(__FILE__) . '/../../system/helpers/file_helper.php');
        
            if(!is_dir('./logs/')){
             mkdir('./logs/', 0777);        
        }

                $data = date('Y_m_d_H_i_s',$fecha_hora)."|".$ip."|".$usuario."|".$texto."\n";
               
                if ($admin){                
                    $archivo = fopen('./logs/'.  date('Y_m_d',$fecha_hora).'_admin.log', 'a+');
                    //die($archivo);
                } else {
                    $archivo = fopen('./logs/'.  date('Y_m_d',$fecha_hora).'_front.log', 'a+');
                }    
                
                fwrite($archivo, $data);
                
                fclose($archivo);
    }
}
?>
