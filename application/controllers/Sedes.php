<?php

/*
 * Controladora para la gestion de los sedes.
 */

class Sedes extends CI_Controller {

		/*
		Muestra el gestor de los sedes
		*/
		function index()
		{
            if (!$this->seguridad()){
                die();
            }
            $data['ruta_url'] = '../../../';
            $this->load->view('sedes/main', $data);

        }
        
        
        private function seguridad(){
            if($this->session->userdata('autenticado')==true){
                if ($this->session->userdata('rol') >= 3){
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }            
        }        
        
		/*
		Genera un genera un json que provee los datos para el gestor de los sedes
		*/
        function lista_sedes(){
            
            if (!$this->seguridad()){
                die();
            }
            
            $this->load->model('Model_sedes');
            
            $page = isset($_POST['page']) ? $_POST['page'] : 1;
            $rp = isset($_POST['rp']) ? $_POST['rp'] : 10;
            $sortname = isset($_POST['sortname']) ? $_POST['sortname'] : 'sede';
            $sortorder = isset($_POST['sortorder']) ? $_POST['sortorder'] : 'asc';
            $query = isset($_POST['query']) ? $_POST['query'] : false;
            $qtype = isset($_POST['qtype']) ? $_POST['qtype'] : false;     
            
            $posicion = ($page-1) * $rp;
            
            if($qtype && $query){
                $rows = $this->Model_sedes->lista_sedes_rango_filtro($rp, $posicion, $sortname, $sortorder,$query,$qtype);
                $total = $this->Model_sedes->cantidad_sedes_filtro($query,$qtype);
            } else {
                $rows = $this->Model_sedes->lista_sedes_rango($rp, $posicion, $sortname, $sortorder);
                $total = $this->Model_sedes->cantidad_sedes();
            }    
            
            
            $si_no = array('No','Si');
            
            header("Content-type: application/json");
            $jsonData = array('page'=>$page,'total'=>$total,'rows'=>array());
            foreach($rows AS $row){
                $entry = array('id'=>$row['idsede'],
                    'cell'=>array(
                        'idsede'=>$row['idsede'],
                        'nombre'=>$row['nombre'],
                        'activa' =>$si_no[$row['activa']]                        
                    ),
                );
                $jsonData['rows'][] = $entry;
            }
            
            echo json_encode($jsonData);
            

        }
        
        function lista_sedes_json(){
            $this->load->model('Model_sedes');
            $lista_sedes = $this->Model_sedes->lista_sedes_filtro('1', 'activa');
            echo(json_encode($lista_sedes));
        }
		/*
		Verifica si existe un sede 
		*/
        function existe_sede($sede){
         
            $this->load->model('Model_sedes');
            
            $resultado = $this->Model_sedes->existe_sede($sede);
            
            if (count($resultado) > 0){
                echo (1);
            } else {
                echo (0);
            }
        }
        
		/*
		Genera el formulario para añadir un sede
		*/
        function agregar_sede_dlg(){
            
            $this->load->view('sedes/agregar_sede');
            
        }
        
		/*
		Añade un sede los valores se reciben por POST en el modelo
		*/
        function agregar_sede(){
            if (!$this->seguridad()){
                die();
            }
            
            $this->load->model('Model_sedes');
            $this->Model_sedes->insertar_sede();
        }

		/*
		Genera el formulario para editar un sede
		*/
        function editar_sede_dlg($idsede){
            
            $this->load->model('Model_sedes');
            
            $sede = $this->Model_sedes->buscar_sede($idsede);
            
            $sede[0]['nombre'] = quotes_to_entities($sede[0]['nombre']);
            
            $data['sede']=$sede[0];
            
            $this->load->view('sedes/editar_sede', $data);
            
        }
        
		/*
		Edita un sede los valores se reciben por POST en el modelo
		*/
        function editar_sede(){
            if (!$this->seguridad()){
                die();
            }

            $this->load->model('Model_sedes');
            $this->Model_sedes->actualizar_sede();
        }
		/*
		Elimina un sede
		*/
        function eliminar_sede(){
            if (!$this->seguridad()){
                die();
            }
            $this->load->model('Model_sedes');

            $cantidad_sedes=$this->Model_sedes->cantidad_sedes();
            if($cantidad_sedes > 1){
                $this->Model_sedes->eliminar_sede();    
            }
            
        }
        
        function activar_sede(){
            if (!$this->seguridad()){
                die();
            }
            $this->load->model('Model_sedes');
            $this->Model_sedes->activar_sede();
        }

        function desactivar_sede(){
            if (!$this->seguridad()){
                die();
            }
            $this->load->model('Model_sedes');
            $this->Model_sedes->desactivar_sede();
        }
        
    }

    ?>
