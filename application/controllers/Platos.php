<?php

/*
 * Controladora para la gestion de los platos.
 */

class Platos extends CI_Controller {

		/*
		Muestra el gestor de los platos
		*/
		function index()
		{
            if (!$this->seguridad()){
                die();
            }
            $data['ruta_url'] = '../../../';
            $this->load->view('menus/admin_menu', $data);

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
		Genera un genera un json que provee los datos para el gestor de los platos
		*/
        
        function lista_platos_fecha($dia,$mes,$anno,$sede,$turno){
            
            $fecha = $dia.'/'.$mes.'/'.$anno;
            
            $this->load->library('Herramientas');
            
            $int_fecha = $anno.'-'.$mes.'-'.$dia;
            
            
            $this->load->model('Model_platos');
            
            $jsonData = $this->Model_platos->lista_platos_fecha($int_fecha,$sede,$turno);
            
            
            echo json_encode($jsonData);
        }

        function actualizar_precios_platos(){

            $this->load->model('Model_platos');

            $ids_precio_mod=explode(',', $_POST['ids_precio']);

            if(count($ids_precio_mod) > 0){

                $this->Model_platos->actualizar_precios_platos($ids_precio_mod);
            }
        }

        /*
        Listamos los menús teniendo en cuenta sede y fecha en la vista de crear el menú por platos.
        */        
        function lista_platos($dia,$mes,$anno,$sede,$turno){
            
            $fecha = $dia.'/'.$mes.'/'.$anno;

            $this->load->library('Herramientas');
            
            $int_fecha = $anno.'-'.$mes.'-'.$dia;
            
            
            $this->load->model('Model_platos');
            
            $page = isset($_POST['page']) ? $_POST['page'] : 1;
            $rp = isset($_POST['rp']) ? $_POST['rp'] : 10;
            $sortname = isset($_POST['sortname']) ? $_POST['sortname'] : 'nombreplato';
            $sortorder = isset($_POST['sortorder']) ? $_POST['sortorder'] : 'asc';
            $query = isset($_POST['query']) ? $_POST['query'] : false;
            $qtype = isset($_POST['qtype']) ? $_POST['qtype'] : false;     
            
            $posicion = ($page-1) * $rp;
            
            if($qtype && $query){
                
                $consulta = $this->Model_platos->lista_platos_rango_filtro($rp, $posicion, $sortname, $sortorder,$query,$qtype, $int_fecha,$sede,$turno);
                $rows = $consulta['rows'];
                $total = $consulta['count'];
            } else {
                $consulta = $this->Model_platos->lista_platos_rango($rp, $posicion, $sortname, $sortorder, $int_fecha,$sede,$turno);
                $rows = $consulta['rows'];
                $total = $consulta['count'];
            }    
            
            
            $si_no = array('Trabajador','Vendedor','Administrador');
            
            header("Content-type: application/json");
            $jsonData = array('page'=>$page,'total'=>$total,'rows'=>array());
            foreach($rows AS $row){
                if ($row['cantidad'] == number_format($row['cantidad'],0)){
                    $mycantidad = $row['cantidad'];
                } else {
                    $mycantidad = number_format($row['cantidad'],2);
                }
                $entry = array('id'=>$row['idplato'],
                    'cell'=>array(
                        'idplato'=>$row['idplato'],
                        'nombre'=>$row['nombreplato'],
                        'precio'=> '$ '.number_format($row['precio'],2),
                        'cantidad'=>$mycantidad . ' ' . $row['simbolo'] ,
                        'tipocantidad'=>$row['nombrecatidad'],
                        'fecha'=>$row['fecha'],
                        'cal_precio'=>number_format($row['precio'],2),
                    ),
                );
                $jsonData['rows'][] = $entry;
            }
            echo json_encode($jsonData);            
        }
        
        
		/*
		Genera el formulario para añadir un plato
		*/
        function agregar_plato_dlg($sede,$turno){
            $data['ruta_url'] = '../../../';
            $this->load->model('Model_tipocantidad');
            
            $this->config->load('ticketscfg');
            $template_prof = $this->config->item('template_prof');
            $this->config->load('templates');
            $templates = $this->config->item('t_templates');
            $data['iCheckStyleCB'] = $templates[$template_prof]['iCheckStyleCB'];
            $data['iCheckStyleRB'] = $templates[$template_prof]['iCheckStyleRB'];
            
            $data['tipocantidades'] = $this->Model_tipocantidad->lista_tipocantidad();
            
            /*
            Agragamos al formulario donde se agregan los Platos la información de la Sede seleccionada 
            a la que pertenece el menú.
            Se retorna tanto el nombre como el id para manejarlo desde el formulario.
            */
            $this->load->model('Model_sedes');    
            $data['nombresedes']=$this->Model_sedes->buscar_sede($sede)[0]['nombre'];
            $data['listasedes']=$sede;
            $data['turno']=$turno;
            /**/

            $this->load->view('platos/agregar_plato', $data);
            
        }
        
		/*
		Añade un plato los valores se reciben por POST en el modelo
		*/
        function agregar_plato(){
            if (!$this->seguridad()){
                die();
            }
            
            $this->load->library('Herramientas');
            $this->load->model('Model_tipocantidad');
            $this->load->model('Model_trabajadores');
            $this->load->model('Model_tickets');
            
            $tipocantidad = $this->Model_tipocantidad->buscar_tipocantidad($_POST['tipocantidad_idtipocantidad']);
            $turno_="";
            if($_POST['turno']==1){
                $turno_="desayuno";
            }else if($_POST['turno']==2){
                $turno_="almuerzo";
            }else{
                $turno_="comida";
            }
            
            $texto = 'Añadió el plato "'.$_POST['nombre'].' $ '.$_POST['precio'].' '.$_POST['cantidad'].' '.$tipocantidad[0]['simbolo'].'" al '.$turno_.' del día '.$_POST["fecha"].'.';
            
            $_POST["fecha"] = $this->herramientas->Cambia_Formato_Fecha_mysql($_POST["fecha"]);
            
            $this->load->model('Model_platos');
            $idplato = $this->Model_platos->insertar_plato();
            $this->load->library('lib_logs');
            $this->lib_logs->registra_log(time(), $this->session->userdata('usuario'), $_SERVER['REMOTE_ADDR'], $texto, true);            
            
        }

		/*
		Genera el formulario para editar un plato
		*/
        function editar_plato_dlg($idplato){
            $data['ruta_url'] = '../../../';
            
            $this->load->model('Model_platos');
            $this->load->model('Model_tipocantidad');
            $data['tipocantidades'] = $this->Model_tipocantidad->lista_tipocantidad();
            
            $plato = $this->Model_platos->buscar_plato($idplato);
            
            $plato[0]['nombreplato'] = quotes_to_entities($plato[0]['nombreplato']);
            
            $data['plato']=$plato[0];
            
            $this->load->view('platos/editar_plato', $data);
            
        }
        
		/*
		Edita un plato los valores se reciben por POST en el modelo
		*/
        function editar_plato(){
            if (!$this->seguridad()){
                die();
            }
            $this->load->model('Model_platos');
            $this->load->model('Model_tipocantidad');
            $tipocantidad = $this->Model_tipocantidad->buscar_tipocantidad($_POST['tipocantidad_idtipocantidad']);
            $plato = $this->Model_platos->buscar_plato($_POST['idplato']);
            $turno_="";
            if($_POST['turno']==1){
                $turno_="desayuno";
            }else if($_POST['turno']==2){
                $turno_="almuerzo";
            }else{
                $turno_="comida";
            }
            $texto = 'Editó el plato "'.$plato[0]['idplato'].' '.$plato[0]['nombreplato'].' $ '.$plato[0]['precio'].' '.$plato[0]['cantidad'].' '.$plato[0]['simbolo'].' por ';
            $texto .= $_POST['nombre'].' $ '.$_POST['precio'].' '.$_POST['cantidad'].' '.$tipocantidad[0]['simbolo'].'" en el '.$turno_.' del día '.$_POST["fecha"].'.';
            
            $this->Model_platos->actualizar_plato();
            
            $this->load->library('lib_logs');
            $this->lib_logs->registra_log(time(), $this->session->userdata('usuario'), $_SERVER['REMOTE_ADDR'], $texto, true);
        }
        
		/*
		Elimina un plato
		*/
        function eliminar_plato(){
            if (!$this->seguridad()){
                die();
            }
            $this->load->model('Model_platos');
            $this->load->model('Model_tickets');
            
            $plato = $this->Model_platos->buscar_plato($_POST['idplato']);
            
            //Verificar si el plato es el unico del menu y poner como prereservacion el plato.
            
            $cantidadplatos = $this->Model_platos->cantidad_platos_fecha($plato[0]['fecha']);
            
            if($cantidadplatos == 1){
                
                $tickets = $this->Model_tickets->lista_tickets_plato($_POST['idplato']);
                
                $this->load->model('Model_prereservaciones');
                
                foreach ($tickets as $ticket){
                    $this->Model_prereservaciones->insertar_prereservacion_codigo($ticket["trabajador_idtrabajador"],$plato[0]['fecha'],$_POST["sede_idsede"],$_POST["turno"]);
                }
                
            }
            
            //Eliminar el plato de los que los que tienen reservaciones
            $this->Model_tickets->eliminar_tickets_plato($_POST['idplato']);
            
           
            
            $texto = 'Eliminó el plato "'.$plato[0]['idplato'].' '.$plato[0]['nombreplato'].' $ '.$plato[0]['precio'].' '.$plato[0]['cantidad'].' '.$plato[0]['simbolo'];
            $texto .= '" del día '.$plato[0]['fecha'].'.';
            
            $this->Model_platos->eliminar_plato();
            
            $this->load->library('lib_logs');
            $this->lib_logs->registra_log(time(), $this->session->userdata('usuario'), $_SERVER['REMOTE_ADDR'], $texto, true);
        }
        
    }

    ?>
