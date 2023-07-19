<?php

class Seguridad extends CI_Controller {

    function index(){

    }

    /**
     * Permite el control de sección en la autenticación de un trabajador.
     * 1-Trabajador regidtrador ya en la BD.
     * 2-Inserción de un uevo trabajador.
     */
    function autenticar_trab(){

        $this->load->library('lib_logs');
        $this->load->model('Model_sedes');
        $usuario = $_POST['usuario'];
        $contrasenna = $_POST['contrasenna'];
        $idsede = $_POST['idsede'];
        $resultado = [];
        $nombreSede=$this->Model_sedes->buscar_sede($idsede)[0]['nombre'];


        if($usuario!="" && $contrasenna!=""){
            $authentication_data = $this->authenticate($usuario, $contrasenna);
            if (is_array($authentication_data)) { 
             $resultado = $this->autenticar_local($usuario);

         // Si el usuario existe se actualizan los registros
             if($resultado['code'] > 0){
                $this->session->set_userdata('autenticado',FALSE);
                $this->session->set_userdata('nombre_apellidos_front', $authentication_data['nombre'].' '.$authentication_data['apellidos']);
                $this->session->set_userdata('usuario_f', $usuario);
                $this->session->set_userdata('autenticado_front', TRUE);
                $this->session->set_userdata('tipo_usuario', 'Trabajador');
                $this->session->set_userdata('idtrabajador', $resultado['idtrabajador']);              
                $this->session->set_userdata('sede_idsede', $idsede);


                $this->Model_trabajadores->actualizar_trabajador_autenticado($resultado['idtrabajador'],$authentication_data['ci'],$authentication_data['nombre'], $authentication_data['apellidos'], $authentication_data['idexpediente'], $usuario, $authentication_data['email'],$authentication_data['becado']);

                $resultado['mensaje'] ='Bienvenido '.$authentication_data['nombre'].' '.$authentication_data['apellidos'].' .';
                
                $this->lib_logs->registra_log(time(), $usuario, $_SERVER['REMOTE_ADDR'], 'Inició sesión para reservar en el comedor.'+$nombreSede, false);

            } else if ($resultado['code'] == 0) {

                $inserto=$this->Model_trabajadores->insertar_trabajador_codigo($authentication_data['ci'],$authentication_data['nombre'], $authentication_data['apellidos'], $authentication_data['idexpediente'], $usuario, $authentication_data['email'],$authentication_data['becado']);

                $resultado = $this->autenticar_local($usuario);
                
                if($inserto){
                // Si el usuario no existe se crea el registro
                    $this->session->set_userdata('autenticado',FALSE);
                    $this->session->set_userdata('nombre_apellidos_front', $authentication_data['nombre'].' '.$authentication_data['apellidos']);
                    $this->session->set_userdata('usuario_f', $usuario);
                    $this->session->set_userdata('autenticado_front', TRUE);
                //$this->session->set_userdata('autenticado_registro', FALSE);
                    $this->session->set_userdata('tipo_usuario', 'Trabajador');
                    $this->session->set_userdata('idtrabajador',$resultado['idtrabajador']);          
                    $this->session->set_userdata('sede_idsede', $idsede);

                    $resultado['mensaje'] = 'Bienvenido '.$authentication_data['nombre'].' '.$authentication_data['apellidos'].' .';
                    $this->lib_logs->registra_log(time(), $usuario, $_SERVER['REMOTE_ADDR'], 'Se registró e inició sección en el comedor '+$nombreSede, false);
                }
            }
        } else {
            $resultado['mensaje'] = 'Credenciales incorrectas. Por favor intente nuevamente.';
        }
    }else{        
        $resultado['mensaje'] ='Campos icompletos de usuario o contraseña.';
    }

    echo $resultado['mensaje'];
}

/**
     * Permite la autenticación deun usuario registrado en el panel administrativo
     */

function autenticar_admin(){

    $this->load->library('lib_logs');
    $this->load->model('Model_usuarios');


    $usuario = $_POST['usuario'];            
    $contrasenna = $_POST['contrasenna'];


    if($usuario!="" && $contrasenna!=""){
        $authentication_data = $this->authenticate($usuario, $contrasenna);
    if (is_array($authentication_data) || ($usuario == getenv('USER_OFFLINE') && $contrasenna == getenv('PASS_OFFLINE'))) {
        
        $resultado = $this->Model_usuarios->autenticar($usuario);

        if (count($resultado) > 0){
         $this->session->set_userdata('autenticado_front',FALSE);
                   //$this->session->set_userdata('autenticado_registro',FALSE);
         $this->session->set_userdata('autenticado',TRUE);
         $this->session->set_userdata('tipo_usuario','Trabajador');
         $myusuario = $resultado[0];
         $this->session->set_userdata('nombre_apellidos', $myusuario['nombres'] . ' ' . $myusuario['apellidos']);
         $this->session->set_userdata('usuario', $usuario);
         $this->session->set_userdata('rol', $myusuario['rol']);
         $this->session->set_userdata('sede_idsede', $myusuario['sede_idsede']);

         echo ('Bienvenido ' . $myusuario['nombres'] . ' ' . $myusuario['apellidos']. ' .');

         $this->lib_logs->registra_log(time(), $usuario, $_SERVER['REMOTE_ADDR'], 'Inició sesión desde el panel de administración.', true);
     } else {                 
         echo ('No está registrado su usuario para administrar el sitio.');
     }

 }else{            
    echo ('El usuario y contraseña no coinciden.');
}
}else{
    echo ('Campos icompletos de usuario o contraseña.');
}
}

protected function authenticate ($username, $password) {
    $token = getenv('CUENTAS_TOKEN');

    $curl = curl_init();
    $payload = json_encode(['username' => $username, 'password' => $password]);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($curl, CURLOPT_URL, "https://cuentas.uclv.cu/api/v1/authenticate/employee");
    curl_setopt($curl, CURLOPT_POSTFIELDS, $payload );
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curl, CURLOPT_HTTPHEADER, ['Accept: application/json', 'Content-Type:application/json', 'Authorization: Bearer ' . $token]);
    curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
    curl_setopt( $curl, CURLOPT_TIMEOUT, 30 );
    $result = curl_exec($curl);
    $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    curl_close($curl);
    
    if (!$result) {
        return "Connection Failure";
    }

    if ($code == 200) {
        // 'username' => username
        // 'name' =>
        // 'ci' =>
        // 'sur_name' => apellidos
        // 'beca' => false
        // 'email' =>
        $data = json_decode($result);

        return [
            'idexpediente' => $data->id_employee,
            'ci' => $data->ci,
            'nombre' => $data->name,
            'apellidos' => $data->sur_name,
            'becado' => !!$data->beca,
            'email' => $data->email
        ];
    } else {
        switch ($code) {
            case 401: return "Credenciales Incorrectas"; break;
            case 403: return "No activo o no trabajador"; break;
            case 503: return "Servicio no disponible"; break;
            case 500: return "Error interno del sistema"; break;
            default: return "Credenciales Incorrectas";
        }
    }
}

/**
 * Cierra una sección trabajador.
 */
function cerrar_sesion_trab(){
    $this->session->set_userdata('autenticado_front',FALSE);
    //$this->session->set_userdata('autenticado_registro', FALSE);
    $this->load->library('lib_logs');
    $this->lib_logs->registra_log(time(), $this->session->userdata('usuario_f'), $_SERVER['REMOTE_ADDR'], 'Cerró sesión desde el panel principal de reserva.', false);
}

/**
 * Cierra una sección del panel de administración.
 */
function cerrar_sesion_admin(){
 $this->session->set_userdata('autenticado',FALSE);
 $this->load->library('lib_logs');
 $this->lib_logs->registra_log(time(), $this->session->userdata('usuario'), $_SERVER['REMOTE_ADDR'], 'Cerró sesión desde el panel de administración.', true);
}

/**
 * Verifica si está activa o no una sección trabajador.
 */
function verificar_sesion_f(){
    if($this->session->userdata('autenticado_front')==true){
        $salida = true;
    } else {
        $salida = false;
    }
    echo(json_encode($salida));
}

/**
 * Verifica si está activa o no una sección del panel de administración.
 */
function verificar_sesion_a(){
    if($this->session->userdata('autenticado')==true){
        $salida = true;
    } else {
        $salida = false;
    }
    echo(json_encode($salida));
}

        /**
         * Autentica un usuario los datos se reciben por POST y establecen los datos de sesión.
        */
        

        private function autenticar_local($usuario){
            $this->load->model('Model_trabajadores');
            $existe = $this->Model_trabajadores->autenticar($usuario);
            $salida = array();

            if(count($existe) > 0){
                /*$this->session->set_userdata('usuario_f', $usuario);
                $this->session->set_userdata('autenticado_front', TRUE);
                $this->session->set_userdata('autenticado_registro', FALSE);*/
                //$this->session->set_userdata('idtrabajador', $existe[0]['idtrabajador']);
                /*$salida['mensaje'] = 'Bienvenido '.$existe[0]['nombres'].' '.$existe[0]['apellidos'].'.';*/
                $salida['code'] = $existe[0]['codigo'];
                $salida['nombres'] = $existe[0]['nombres'];
                $salida['apellidos'] = $existe[0]['apellidos'];
                $salida['idtrabajador'] = $existe[0]['idtrabajador'];
                $salida['usuario'] = $existe[0]['usuario'];
                //$salida['sede_idsede'] = $idsede;
                $salida['ci'] = $existe[0]['ci'];

            } else {
                $salida['mensaje'] = 'El usuario y contraseña no coinciden.';
                $salida['code'] = 0;
            }
            return $salida;
        }

        public function existe_trabajador_ldap($usuario){
            $this->load->library('lib_logs');            
            if($this->bind_uclv_active_user($usuario)){
                $salida = true;
            } else {
                $salida = false;
            }            
            echo json_encode($salida);
        }


/**
 * Permite validar el registro de un trabajador en el sistema.
 */

/*function validar_registro(){
    $this->load->library('lib_logs');

    $nombre = $_POST['nombre'];           
    $apellidos = $_POST['apellidos'];
    $usuario = $_POST['usuario'];           
    $ci = $_POST['ci'];   
    $idsede=$_POST['idsede'];
    $email=$_POST['email'];
    $codigo="";

    $info_trabajador = $this->get_user_info ($ci);

    $this->load->model('Model_trabajadores');

    if($info_trabajador){
        $codigo=$info_trabajador['idexpediente'];

        if( $ci == $info_trabajador['ci'] && strtoupper($nombre) == strtoupper($info_trabajador['nombre']) && strtoupper($apellidos) == strtoupper($info_trabajador['apellidos']) ){

            $inserto=$this->Model_trabajadores->insertar_trabajador_codigo($info_trabajador['ci'],$info_trabajador['nombre'], $info_trabajador['apellidos'], $codigo, $usuario, $email,$info_trabajador['becado']);
            if($inserto){
                $this->session->set_userdata('autenticado',FALSE);
                $this->session->set_userdata('nombre_apellidos_front', $nombre.' '.$apellidos);
                $this->session->set_userdata('usuario_f', $usuario);
                $this->session->set_userdata('autenticado_front', TRUE);
                $this->session->set_userdata('autenticado_registro', FALSE);
                $this->session->set_userdata('tipo_usuario', 'Trabajador');
                $this->session->set_userdata('idtrabajador', $codigo);
                $this->session->set_userdata('sede_idsede', $idsede);

                echo ('Bienvenido '.$nombre.' '.$apellidos.' .');
                $this->lib_logs->registra_log(time(), $usuario, $_SERVER['REMOTE_ADDR'], 'Inició sesión.', true);
            }
        }else{

            $this->session->set_userdata('autenticado_registro', FALSE);
            echo ('No se ha podido obtener su registro a partir de los datos introducidos.');
            $this->lib_logs->registra_log(time(), $usuario, $_SERVER['REMOTE_ADDR'], 'Error de validación de los datos.', true);
        }
    } else {
        $this->lib_logs->registra_log(time(), $usuario, $_SERVER['REMOTE_ADDR'], 'Error de validación de los datos.', true);
    }
}*/



/**
 * Se obtiene la iformación gestionada por el asset de un trabajador dado el CI 
*/
/*protected function get_user_info ($ci) {
    $token = getenv('RECHUM_TOKEN');

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($curl, CURLOPT_URL, "https://rechum.uclv.cu/api/v1/asset/empleados?ci=" . $ci);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curl, CURLOPT_HTTPHEADER, [
        'Accept: application/json',
        'Authorization: Bearer ' . $token
    ]);
    curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
    curl_setopt( $curl, CURLOPT_TIMEOUT, 30 );
    $result = curl_exec($curl);

    curl_close($curl);

    if (!$result) {
        die("Connection Failure");
    }

    $data = json_decode($result);

    if (!is_array($data) || !count($data)) {
        die('Su carné de identidad no coincide con nuestro registros.');
    }

    return [
        'idexpediente' => trim($data[0]->Id_Expediente),
        'ci' => trim($data[0]->No_CI),
        'nombre' => trim($data[0]->Nombre),
        'apellidos' => trim($data[0]->Apellido_1) . ' ' . trim($data[0]->Apellido_2),
        'becado' => trim($data[0]->Albergado)
    ];
}*/

    /**
     * Indica si la cuenta está activa.
    */ 
    /*protected function bind_uclv_active_user($username)
    {

        $host = 'ldap://ido.uclv.edu.cu';
        $port = '389';
        $base = 'OU=_Usuarios,DC=uclv,DC=edu,DC=cu';
        $aut = "ldap-squid@uclv.edu.cu"; // tu usuario
        $pass = "tequiero"; // tu contraseña
        
        
        $conn = @\ldap_connect($host, $port)
        or die("<div class='label-danger text-center'>No es posible conectar con el servidor de Authentificaci&oacute;n de usuarios.</div>");

        \ldap_set_option($conn, LDAP_OPT_PROTOCOL_VERSION, 3);
        \ldap_set_option($conn, LDAP_OPT_REFERRALS, 0);
        \ldap_bind($conn, $aut, $pass) or die('Problemas de comunicación con el LDAP');
        
        $result = \ldap_search($conn, $base, 'samaccountname=' . $username);
        return \ldap_count_entries($conn, $result) ? true : false;
    }*/


    /**
     * Indica si el usuario y la contraseña son correctos.
     */
    
   /*protected function bind_uclv($username, $password)
    {
        $host = 'ldap://ido.uclv.edu.cu';
        $port = '389';
        $base = 'OU=_Usuarios,DC=uclv,DC=edu,DC=cu';
        $dom = 'uclv.edu.cu';
        $aut = $username . '@' . $dom;

        $conn = @\ldap_connect($host, $port)
        or die("<div class='label-danger text-center'>No es posible conectar con el servidor de Authentificaci&oacute;n de usuarios.</div>");

        \ldap_set_option($conn, LDAP_OPT_PROTOCOL_VERSION, 3);
        \ldap_set_option($conn, LDAP_OPT_REFERRALS, 0);
        if (!@\ldap_bind($conn, $aut, $password))
            return FALSE;
        $result = \ldap_search($conn, $base, 'samaccountname=' . $username);
        if (\ldap_count_entries($conn, $result)) {
            $info = \ldap_get_entries($conn, $result);
            if ($info)
                if (@\ldap_bind($conn, $info[0]['dn'], $password)) {
                    if (isset($info[0]['distinguishedname'][0])) {
                        return true;
                    }
                }
            }

            return false;
        }*/

    /**
     * Devuelve los parametros del usaurio.     
     */

    /*protected function bind_uclv_params_user($username,$param)
    {
        $host = 'ldap://ido.uclv.edu.cu';
        $port = '389';
        $base = 'DC=uclv,DC=edu,DC=cu';
        $aut = "ldap-squid@uclv.edu.cu"; // tu usuario
        $pass = "tequiero"; // tu contraseÒa

        $conn = @\ldap_connect($host, $port)
        or die("<div class='label-danger text-center'>No es posible conectar con el servidor de Authentificaci&oacute;n de usuarios.</div>");

        \ldap_set_option($conn, LDAP_OPT_PROTOCOL_VERSION, 3);
        \ldap_set_option($conn, LDAP_OPT_REFERRALS, 0);
        if (!\ldap_bind($conn, $aut, $pass)) return "";
        $result = \ldap_search($conn, $base, 'samaccountname=' . $username);
        if (\ldap_count_entries($conn, $result)) {
            $info = \ldap_get_entries($conn, $result);
            if ($info)
            {
                if ( $param == 'fullname' ) return isset($info[0]['displayname'][0]) ? $info[0]['displayname'][0] : '';
                elseif ( $param == 'id_card' ) return isset($info[0]['extensionattribute1'][0]) ? $info[0]['extensionattribute1'][0] : '';
                elseif ( $param == 'email' ) return isset($info[0]['mail'][0]) ? $info[0]['mail'][0] : '';
                elseif ( $param == 'dn' ) return isset($info[0]['distinguishedname'][0]) ? $info[0]['distinguishedname'][0] : '';
                elseif ( $param == 'lastname' ) return isset($info[0]['sn'][0]) ? trim($info[0]['sn'][0]) : '';
                elseif ( $param == 'name' ) return isset($info[0]['givenname'][0]) ? trim($info[0]['givenname'][0]) : '';
            }
            return $info[0]['distinguishedname'][0];
        } else
        return "";

        return "";
    }*/

    



}
?>
