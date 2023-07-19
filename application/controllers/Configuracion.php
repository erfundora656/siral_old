<?php


class configuracion extends CI_Controller {

	function __construct()
	{
	    parent::__construct();

        if (!$this->seguridad()){
                echo ("Acceso denegado");
                die;
        }
	}
                
    private function seguridad(){
        if($this->session->userdata('autenticado')==true){
            if ($this->session->userdata('rol') >= 2){
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
	}
        
    private function createFolder($carpeta) {
        if(!is_dir($carpeta)){
             mkdir($carpeta, 0777);
        }
    }
	
	function index(){
        $data['ruta_url'] = '../../../';
        $this->load->view('configuracion/configuracion',$data);
    }

    function configuracion_ldap (){
        if (!$this->seguridad()){
            die();
        }
        $data['ruta_url'] = '../../../';
        // Obtener configuracion ldap
        $this->config->load('ldapcfg');
        $data['use_ldap'] = $this->config->item('use_ldap');
        $data['servidor'] = $this->config->item('domain_controllers');
        $data['sufijo'] = $this->config->item('account_suffix');
        $data['base_dn'] = $this->config->item('base_dn');
        $data['admin_user'] = $this->config->item('admin_user');
        $data['admin_pass'] = $this->config->item('admin_pass');
        $data['grupos'] = $this->config->item('grupos');
        $data['grupos_e'] = $this->config->item('grupos_e');
        //
        $data["usuario"] = '';
        $this->load->view('configuracion/configuracion_ldap', $data);
	}
            
        
	function actualizar_ldap(){
        if (!$this->seguridad()){
            die();
        }

        $data['ruta_url'] = '../../../';
        // obtener datos

        if ($_POST['use_ldap'] == 'checked'){
            $use_ldap = 'true';
        } else {
            $use_ldap = 'false';
        }
        $servidor = $_POST['servidor'];
        $sufijo = $_POST['sufijo'];
        $base_dn = $_POST['base_dn'];
        $admin_user = $_POST['admin_user'];
        $admin_pass = $_POST['admin_pass'];

        $grupos = str_replace(',', "','", $_POST['grupos']);
        $grupos_e = str_replace(',', "','", $_POST['grupos_e']);

        // Escribir archivo


        $this->load->helper('file');

        $data = "<?php
            \$config['use_ldap'] = $use_ldap;
            \$config['domain_controllers'] = '$servidor';
            \$config['account_suffix'] = '$sufijo';
            \$config['base_dn'] = '$base_dn';
            \$config['admin_user'] = '$admin_user';
            \$config['admin_pass'] = '$admin_pass';
            \$config['grupos'] = array('$grupos');
            \$config['grupos_e'] = array('$grupos_e');
        ?>";

        if ( ! write_file('./application/config/ldapcfg.php', $data))
        {
            echo 'No se puede escribir el archivo';
        }
        else
        {
            echo 'Se escribió el archivo!';
        }
        //fin
	}
        
    function copia_serguridad(){
        if (!$this->seguridad()){
          die();
        }
        $data['ruta_url'] = '../../../';
        $this->load->view('configuracion/copias_seguridad',$data);
    }
      
    function lista_copia_serguridad(){
        if (!$this->seguridad()){
          die();
        }
        $data['ruta_url'] = '../../../';
        //Obtener la lista de archivos en la carpeta CSeguridad
        $this->load->helper('file');

        //$data['archivos'] = directory_map('./CSeguridad/');

        $data['archivos'] = get_dir_file_info('CSeguridad/');

        //print_r($data['archivos'] );
        $this->load->view('configuracion/lista_copias_seguridad', $data);

    }

    function eliminar_copia_serguridad(){
        if (!$this->seguridad()){
            die();
        }
        //Obtener la lista de archivos en la carpeta CSeguridad
        $this->load->helper('file');

        $archivo = $_POST['archivo'];

        unlink('./CSeguridad/'.$archivo);

    }
      
      
    function salva_BD(){
        if (!$this->seguridad()){
            die();
        }
        $this->load->helper('date');
        $datestring = "%Y_%m_%d_%h_%i_%s";

        $time = time();
        $fecha = mdate($datestring, $time);

        //echo $fecha;
        $nombreArchivo = 'tickets_'.$fecha.'.sql';
        // Cargar la clase de utilidades de BD
        $this->load->dbutil();
        // Hacer copia de respaldo para la BD entera y asignarla a una variable

        $prefs = array(
        // gzip, zip, txt
        'format'
        => 'txt',
        // Nombre de archivo - NECESARIO SOLAMENTE CON ARCHIVOS ZIP
        'filename' => $nombreArchivo,
        // Si agrega sentencias DROP TABLE al archivo de copia de respaldo
        'add_drop' => TRUE,
        // Si agrega datos INSERT al archivo de copia de respaldo
        'add_insert'=> TRUE,
        'foreign_key_checks' => FALSE
        );

        $backup =& $this->dbutil->backup($prefs);
        // Cargar el helper file y escribir el archivo en el servidor
        $this->load->helper('file');

        $this->createFolder("./CSeguridad/");

        write_file('./CSeguridad/'.$nombreArchivo, $backup);

        // Cargar el helper download y enviar el archivo a su escritorio
        $this->load->helper('download');

        //force_download($nombreArchivo, $backup);
    }
      
    function descargar_archivo(){
        $this->load->helper('download');
        $archivo = $_REQUEST['archivo'];
        $data = file_get_contents($archivo); // Lee el contenido del archivo $name = 'myphoto.jpg';
        $nombre = $_REQUEST['nombre'];
        force_download($nombre,$data);
    }

    function general(){
        if (!$this->seguridad()){
            die();
        }
        $data['ruta_url'] = '../../../';
        // Obtener configuracion ldap
        $this->config->load('ticketscfg');
        $template_prof = $this->config->item('template_prof');
        $this->config->load('templates');
        $templates = $this->config->item('t_templates');
        $data['iCheckStyleCB'] = $templates[$template_prof]['iCheckStyleCB'];
        $data['iCheckStyleRB'] = $templates[$template_prof]['iCheckStyleRB'];

        $data['menu_platos'] = $this->config->item('menu_platos');
        $data['hora_cierre'] = $this->config->item('hora_cierre');
        $data['minutos_cierre'] = $this->config->item('minutos_cierre');
        $data['incio_semana'] = $this->config->item('incio_semana');
        $data['cierra_viernes'] = $this->config->item('cierra_viernes');
        $data['template_prof'] = $this->config->item('template_prof');
        $data['sede_defecto'] = $this->config->item('sede_defecto');
        $data['forma_pago_trab'] = $this->config->item('forma_pago_trab');
        $data['acceso_front'] = $this->config->item('acceso_front');
        $data['acceso_admin'] = $this->config->item('acceso_admin');
        $data['templates'] = $templates;

        $this->load->model('Model_sedes');   
            /*Controlamos que los administradores solo generen menus en sus sedes*/
            if($this->session->userdata('rol') == 2){ 
            $data['listasedes']="";               
            $nombre_sede=$this->Model_sedes->buscar_sede($this->session->userdata('sede_idsede'))[0]['nombre'];
            $id_sede=$this->Model_sedes->buscar_sede($this->session->userdata('sede_idsede'))[0]['idsede'];
            $data['sede_pertenece'] =$nombre_sede;
            $data['id_sede']=$id_sede;
            }else{                               
                $data['listasedes'] = $this->Model_sedes->lista_sedes();
            }

        $this->load->view('configuracion/general', $data);

	}

    function actualizar_general(){
        if (!$this->seguridad()){
            die();
        }

        $data['ruta_url'] = '../../../';
        // obtener datos

        if ($_POST['menu_platos'] == 'checked'){
            $menu_platos = 'true';
        } else {
            $menu_platos = 'false';
        }

        if ($_POST['cierra_viernes'] == 'checked'){
            $cierra_viernes = 'true';
        } else {
            $cierra_viernes = 'false';
        }
                
        $hora_cierre = $_POST['hora_cierre'];
        $minutos_cierre = $_POST['minutos_cierre'];
        $incio_semana = $_POST['incio_semana'];        
        $template_prof = $_POST['template_prof'];
        $sede_defecto = $_POST['sede_defecto'];
        $forma_pago_trab = $_POST['forma_pago_trab'];
        $acceso_front = $_POST['acceso_front'];
        $acceso_admin = $_POST['acceso_admin'];

        // Escribir archivo


        $this->load->helper('file');

        $data = "<?php
            \$config['menu_platos'] = $menu_platos;
            \$config['hora_cierre'] = $hora_cierre;
            \$config['minutos_cierre'] = $minutos_cierre;
            \$config['cierra_viernes'] = $cierra_viernes;
            \$config['incio_semana'] = $incio_semana;
            \$config['template_prof'] = $template_prof;
            \$config['sede_defecto'] = $sede_defecto;
            \$config['forma_pago_trab'] = $forma_pago_trab;
            \$config['acceso_front'] = '$acceso_front';
            \$config['acceso_admin'] = '$acceso_admin';
        ?>";

        if ( ! write_file('./application/config/ticketscfg.php', $data))
        {
            echo 'No se puede escribir el archivo';
        }
        else
        {
            echo 'Se escribió el archivo!';
        }        
	}
        
    function documentos(){
        if (!$this->seguridad()){
            die();
        }
        $data['ruta_url'] = '../../../';
        // Obtener configuracion ldap
        $this->config->load('reportescfg');
        $data['nombre_entidad'] = $this->config->item('nombre_entidad');
        $data['organismo'] = $this->config->item('organismo');
        $data['cargo_firma'] = $this->config->item('cargo_firma');
        $data['nombre_firma'] = $this->config->item('nombre_firma');

        $this->load->view('configuracion/documentos', $data);

	}
      
    function actualizar_documentos(){
        if (!$this->seguridad()){
            die();
        }
        $data['ruta_url'] = '../../../';
        // obtener datos

        $nombre_entidad = str_replace("'", "\'", $_POST['nombre_entidad']);
        $organismo = str_replace("'", "\'", $_POST['organismo']);
        $cargo_firma = str_replace("'", "\'", $_POST['cargo_firma']);
        $nombre_firma =  str_replace("'", "\'", $_POST['nombre_firma']);

        // Escribir archivo


        $this->load->helper('file');

        $data = "<?php
    \$config['nombre_entidad'] = '$nombre_entidad';
    \$config['organismo'] = '$organismo';
    \$config['cargo_firma'] = '$cargo_firma';
    \$config['nombre_firma'] = '$nombre_firma';
?>";

        if ( ! write_file('./application/config/reportescfg.php', $data))
        {
            echo 'No se puede escribir el archivo';
        }
        else
        {
            echo 'Se escribió el archivo!';
        }
        //fin
    }
}

?>
