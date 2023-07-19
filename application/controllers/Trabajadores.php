<?php
/*
 * Controladora para la gestion de los trabajadores.
 */

class Trabajadores extends CI_Controller {

		/*
		Muestra el gestor de los trabajadores
		*/
		function index()
		{
            if (!$this->seguridad()){
                die();
            }
            $data['ruta_url'] = '../../../';
            $this->load->view('trabajadores/main', $data);

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

		/*
		Genera un genera un json que provee los datos para el gestor de los trabajadores
		*/
        function lista_trabajadores(){
            
            if (!$this->seguridad()){
                die();
            }
            
            $this->load->model('Model_trabajadores');
            
            $page = isset($_POST['page']) ? $_POST['page'] : 1;
            $rp = isset($_POST['rp']) ? $_POST['rp'] : 10;
            $sortname = isset($_POST['sortname']) ? $_POST['sortname'] : 'apellidos';
            $sortorder = isset($_POST['sortorder']) ? $_POST['sortorder'] : 'asc';
            $query = isset($_POST['query']) ? $_POST['query'] : false;
            $qtype = isset($_POST['qtype']) ? $_POST['qtype'] : false;     
            
            $posicion = ($page-1) * $rp;
            
            if($qtype && $query){
                $rows = $this->Model_trabajadores->lista_trabajadores_rango_filtro($rp, $posicion, $sortname, $sortorder,$query,$qtype);
                $total = $this->Model_trabajadores->cantidad_trabajadores_filtro($query,$qtype);
            } else {
                $rows = $this->Model_trabajadores->lista_trabajadores_rango($rp, $posicion, $sortname, $sortorder);
                $total = $this->Model_trabajadores->cantidad_trabajadores();
            }    
            
            
            $estado = array('Activo','Dado de baja');
            header("Content-type: application/json");
            $jsonData = array('page'=>$page,'total'=>$total,'rows'=>array());
            foreach($rows AS $row){
                $entry = array('id'=>$row['idtrabajador'],
                    'cell'=>array(
                        'idtrabajador'=>$row['idtrabajador'],
                        'ci'=>$row['ci'],
                        'nombres'=>$row['nombres'],
                        'apellidos'=>$row['apellidos'],
                        'codigo'=>$row['codigo'],
                        'usuario'=>$row['usuario'],
                        'email'=>$row['email'],
                        'becado'=>$row['becado'],
                    ),
                );
                $jsonData['rows'][] = $entry;
            }
            
            echo json_encode($jsonData);
        }
        
		/*
		Verifica si existe un trabajador 
		*/
        function existe_trabajador($usuario){
         
            $this->load->model('Model_trabajadores');
            
            $resultado = $this->Model_trabajadores->existe_trabajador($usuario);
            
            if (count($resultado) > 0){
                echo (1);
            } else {
                echo (0);
            }
        }

        /*
        Se obtienen los datos trabajador 
        */
        function datos_trabajador($usuario){
         
            $this->load->model('Model_trabajadores');
            
            $resultado = $this->Model_trabajadores->existe_trabajador($usuario);
            
            echo json_encode($resultado);
        }
        
        
		/*
		Genera el formulario para añadir un trabajador
		*/
        function agregar_trabajador_dlg(){
            
            $this->load->view('trabajadores/agregar_trabajador');
            
        }
        
		/*
		Añade un trabajador los valores se reciben por POST en el modelo
		*/
        function agregar_trabajador(){
            
            if (!$this->seguridad()){
                die();
            }
            
            $this->load->model('Model_trabajadores');
            $this->Model_trabajadores->insertar_trabajador();
        }

		/*
		Genera el formulario para editar un trabajador
		*/
        function editar_trabajador_dlg($idtrabajador){
            
            $this->load->model('Model_trabajadores');
            
            
            $trabajador = $this->Model_trabajadores->buscar_trabajador($idtrabajador);
            
            $trabajador[0]['nombres'] = quotes_to_entities($trabajador[0]['nombres']);
            $trabajador[0]['apellidos'] = quotes_to_entities($trabajador[0]['apellidos']);
            
            $data['trabajador']=$trabajador[0];
            
            $this->load->view('trabajadores/editar_trabajador', $data);
            
        }
        
		/*
		Edita un trabajador los valores se reciben por POST en el modelo
		*/
        function editar_trabajador(){
            
            if (!$this->seguridad()){
                die();
            }
            
            $this->load->model('Model_trabajadores');
            $this->Model_trabajadores->actualizar_trabajador();
        }

		
        
		/*
		Elimina un trabajador
		*/
        
      

     function eliminar_trabajador(){
        
        if (!$this->seguridad()){
            die();
        }
        
        $this->load->model('Model_trabajadores');
        $this->load->model('Model_tickets');
        $this->load->model('Model_prereservaciones');

        $idtrabajador = $_POST['idtrabajador'];
        $this->Model_trabajadores->eliminar_trabajador();
        
        $this->load->library('lib_logs');            
        $trabajador = $this->Model_trabajadores->buscar_trabajador($idtrabajador);
        $texto = 'Le dio baja al trabajador ('.$trabajador[0]['codigo'].' - '.$trabajador[0]['usuario'].' - '.$trabajador[0]['nombres'].' '.$trabajador[0]['apellidos'].').';
        $this->lib_logs->registra_log(time(), $this->session->userdata('usuario'), $_SERVER['REMOTE_ADDR'], $texto, true);
        
    }
    
        //Metodos para sincronizar contra un servidor ldap
    
    function ldap_sincronizar_dlg(){
        $this->load->view('trabajadores/sincronizar_dlg');
    }
    
    
    
    function cerrar_sesion(){
        $this->session->set_userdata('autenticado_front',FALSE);
        $this->load->library('lib_logs');
        $this->lib_logs->registra_log(time(), $this->session->userdata('usuario_f'), $_SERVER['REMOTE_ADDR'], 'Cerró sesión desde el panel principal.', false);
    }  
    

}

?>
