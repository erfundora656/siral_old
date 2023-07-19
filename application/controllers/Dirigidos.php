<?php

/*
 * Controladora para la gestion de los dirigidos.
 */

class Dirigidos extends CI_Controller {

		/*
		Muestra el gestor de los dirigidos
		*/
		function index()
		{            
            $data['ruta_url'] = '../../../';             
            $this->load->view('dirigidos/main', $data);

        }

        private function seguridad(){
            if($this->session->userdata('autenticado')==true){
                if ($this->session->userdata('rol') != 0){
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }            
        }        


		/*
		Genera un genera un json que provee los datos para el gestor de los dirigidos
		*/

        function lista_dirigidos_fecha($dia,$mes,$anno,$sede,$turno){

            $fecha = $dia.'/'.$mes.'/'.$anno;
            
            $this->load->library('Herramientas');
            
            $int_fecha = $anno.'-'.$mes.'-'.$dia;
            
            
            $this->load->model('Model_dirigidos');

            $jsonData = $this->Model_dirigidos->lista_dirigidos_fecha($int_fecha,$sede,$turno);
            
            
            echo json_encode($jsonData);
        }

        function lista_dirigidos($dia,$mes,$anno,$sede,$turno){

            $fecha = $dia.'/'.$mes.'/'.$anno;
            
            $this->load->library('Herramientas');
            
            $int_fecha = $anno.'-'.$mes.'-'.$dia;
            
            
            $this->load->model('Model_dirigidos');

            $page = isset($_POST['page']) ? $_POST['page'] : 1;
            $rp = isset($_POST['rp']) ? $_POST['rp'] : 10;
            $sortname = isset($_POST['sortname']) ? $_POST['sortname'] : 'detalles';
            $sortorder = isset($_POST['sortorder']) ? $_POST['sortorder'] : 'asc';
            $query = isset($_POST['query']) ? $_POST['query'] : false;
            $qtype = isset($_POST['qtype']) ? $_POST['qtype'] : false;     
            
            $posicion = ($page-1) * $rp;
            
            if($qtype && $query){

                $consulta = $this->Model_dirigidos->lista_dirigidos_rango_filtro($rp, $posicion, $sortname, $sortorder,$query,$qtype, $int_fecha,$sede,$turno);
                $rows = $consulta['rows'];
                $total = $consulta['count'];
            } else {
                $consulta = $this->Model_dirigidos->lista_dirigidos_rango($rp, $posicion, $sortname, $sortorder, $int_fecha,$sede,$turno);
                $rows = $consulta['rows'];
                $total = $consulta['count'];
            }    
            
            
            $si_no = array('Trabajador','Vendedor','Administrador');
            
            header("Content-type: application/json");
            $jsonData = array('page'=>$page,'total'=>$total,'rows'=>array());
            foreach($rows AS $row){
                $entry = array('id'=>$row['iddirigido'],
                    'cell'=>array(
                        'iddirigido'=>$row['iddirigido'],
                        'detalles'=>$row['detalles'],
                        'cantidad'=>$row['cantidad'] ,
                        'fecha'=>$row['fecha']
                    ),
                );
                $jsonData['rows'][] = $entry;
            }
            
            echo json_encode($jsonData);
            

        }

        
		/*
		Genera el formulario para añadir un dirigido
		*/
        function agregar_dirigido_dlg($sede){
            $data['ruta_url'] = '../../../';
            $this->load->model('Model_tipocantidad');
            $data['tipocantidades'] = $this->Model_tipocantidad->lista_tipocantidad(); 

            /*
            Agragamos al formulario donde se agregan los Platos la información de la Sede seleccionada 
            a la que pertenece el menú.
            Se retorna tanto el nombre como el id para manejarlo desde el formulario.
            */
            $this->load->model('Model_sedes');    
            $data['nombresedes']=$this->Model_sedes->buscar_sede($sede)[0]['nombre'];
            $data['listasedes']=$sede;
            /**/
            $this->load->view('dirigidos/agregar_dirigido', $data);
            
        }
        
		/**
         * OK
         * */
        function agregar_dirigido(){
            if (!$this->seguridad()){
                die();
            }
            
            $this->load->library('Herramientas');
            $turno_="";
            if($_POST['turno']==1){
                $turno_="desayuno";
            }else if($_POST['turno']==2){
                $turno_="almuerzo";
            }else{
                $turno_="comida";
            }

            $texto = 'Añadió '.$_POST['cantidad'].' dirigidos para ('.$_POST['detalles'].') en el '.$turno_.'del '.$_POST['fecha'];
            
            $_POST["fecha"] = $this->herramientas->Cambia_Formato_Fecha_mysql($_POST["fecha"]);
            
            $this->load->model('Model_dirigidos');
            $this->Model_dirigidos->insertar_dirigido();
            $this->load->library('lib_logs');
            $this->lib_logs->registra_log(time(), $this->session->userdata('usuario'), $_SERVER['REMOTE_ADDR'], $texto, true);
        }

		/*
		Genera el formulario para editar un dirigido
		*/
        function editar_dirigido_dlg($iddirigido){
            $data['ruta_url'] = '../../../';
            
            $this->load->model('Model_dirigidos');
            
            $dirigido = $this->Model_dirigidos->buscar_dirigido($iddirigido);
            
            $dirigido[0]['detalles'] = quotes_to_entities($dirigido[0]['detalles']);
            
            $data['dirigido']=$dirigido[0];
            
            $this->load->view('dirigidos/editar_dirigido', $data);
            
        }
        
		/*
		Edita un dirigido los valores se reciben por POST en el modelo
		*/
        function editar_dirigido(){
            if (!$this->seguridad()){
                die();
            }
            $this->load->model('Model_dirigidos');

            $turno_="";
            if($_POST['turno']==1){
                $turno_="desayuno";
            }else if($_POST['turno']==2){
                $turno_="almuerzo";
            }else{
                $turno_="comida";
            }
            $dirigido = $this->Model_dirigidos->buscar_dirigido($_POST['iddirigido']);
            
            $texto = 'Editó el dirigido ('.$dirigido[0]['iddirigido'].','.$dirigido[0]['cantidad'].','.$dirigido[0]['detalles'].') por (';
            $texto .=$_POST['cantidad'].','.$_POST['detalles'].') en el '.$turno_.' del '.$dirigido[0]['fecha'];
            $this->Model_dirigidos->actualizar_dirigido();

            $this->load->library('lib_logs');
            $this->lib_logs->registra_log(time(), $this->session->userdata('usuario'), $_SERVER['REMOTE_ADDR'], $texto, true);

        }

		/*
		Elimina un dirigido
		*/
        function eliminar_dirigido(){
            if (!$this->seguridad()){
                die();
            }
            $this->load->model('Model_dirigidos');
            $turno_="";
            if($_POST['turno']==1){
                $turno_="desayuno";
            }else if($_POST['turno']==2){
                $turno_="almuerzo";
            }else{
                $turno_="comida";
            }
            
            $dirigido = $this->Model_dirigidos->buscar_dirigido($_POST['iddirigido']);
            
            $texto = 'Eliminó el dirigido ('.$dirigido[0]['iddirigido'].','.$dirigido[0]['cantidad'].','.$dirigido[0]['detalles'].') de el '.$turno_.' del '.$dirigido[0]['fecha'];
            
            $this->Model_dirigidos->eliminar_dirigido();
            $this->load->library('lib_logs');
            $this->lib_logs->registra_log(time(), $this->session->userdata('usuario'), $_SERVER['REMOTE_ADDR'], $texto, true);
            
        }
        
    }

    ?>
