<?php

/*
 * Controladora para la gestion de los usuarios.
 */

class Usuarios extends CI_Controller {

		/*
		Muestra el gestor de los usuarios
		*/
		function index()
		{
            if (!$this->seguridad()){
                die();
            }
            $data['ruta_url'] = '../../../';
            $this->load->view('usuarios/main', $data);

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
        Añade un usuario los valores se reciben por POST en el modelo
        */
        function agregar_usuario(){
            if (!$this->seguridad()){
                die();
            }
            
            $this->load->model('Model_usuarios');
            $this->Model_usuarios->insertar_usuario();
        } 

        /*
        Genera el formulario para añadir un usuario
        */
        function agregar_usuario_dlg(){
            $this->load->model('Model_sedes');
            $data['listasedes'] = $this->Model_sedes->lista_sedes();
            $this->load->view('usuarios/agregar_usuario',$data);
            
        }    

        /*
        Edita un usuario los valores se reciben por POST en el modelo
        */
        function editar_usuario(){
            if (!$this->seguridad()){
                die();
            }
            $this->load->model('Model_usuarios');
            $this->Model_usuarios->actualizar_usuario();
        }

        /*
        Elimina un usuario
        */
        function eliminar_usuario(){
            if (!$this->seguridad()){
                die();
            }
            $this->load->model('Model_usuarios');
            $cantidad_usuarios=$this->Model_usuarios->cantidad_usuarios();
            
            if($cantidad_usuarios > 1){
               $this->Model_usuarios->eliminar_usuario();
           }

       }

     /*
        Verifica si existe un usuario 
        */
        function existe_usuario($usuario){

            $this->load->model('Model_usuarios');
            
            $resultado = $this->Model_usuarios->existe_usuario($usuario);
            
            if (count($resultado) > 0){
                echo (1);
            } else {
                echo (0);
            }
        }


        /*******************Revisado*********************************************/


        /*
		Genera un genera un json que provee los datos para el gestor de los usuarios
		*/
        function lista_usuarios(){

            if (!$this->seguridad()){
                die();
            }
            
            $this->load->model('Model_usuarios');
            
            $page = isset($_POST['page']) ? $_POST['page'] : 1;
            $rp = isset($_POST['rp']) ? $_POST['rp'] : 10;
            $sortname = isset($_POST['sortname']) ? $_POST['sortname'] : 'usuario';
            $sortorder = isset($_POST['sortorder']) ? $_POST['sortorder'] : 'asc';
            $query = isset($_POST['query']) ? $_POST['query'] : false;
            $qtype = isset($_POST['qtype']) ? $_POST['qtype'] : false;     
            
            $posicion = ($page-1) * $rp;
            
            if($qtype && $query){
                $rows = $this->Model_usuarios->lista_usuarios_rango_filtro($rp, $posicion, $sortname, $sortorder,$query,$qtype);
                $total = $this->Model_usuarios->cantidad_usuarios_filtro($query,$qtype);
            } else {
                $rows = $this->Model_usuarios->lista_usuarios_rango($rp, $posicion, $sortname, $sortorder);
                $total = $this->Model_usuarios->cantidad_usuarios();
            }    
            
            
            $si_no = array('Vendedor','Gestor Económico','Administrador', 'Super Administrador','Jefe Departamento','Planificador');
            
            header("Content-type: application/json");
            $jsonData = array('page'=>$page,'total'=>$total,'rows'=>array());
            foreach($rows AS $row){
                $entry = array('id'=>$row['idusuario'],
                    'cell'=>array(
                        'idusuario'=>$row['idusuario'],
                        'usuario'=>$row['usuario'],
                        'nombres'=>$row['nombres'],
                        'apellidos'=>$row['apellidos'],
                        'rol'=>$si_no[$row['rol']]
                    ),
                );
                $jsonData['rows'][] = $entry;
            }
            
            echo json_encode($jsonData);
            

        }
        

        

        


		/*
		Genera el formulario para editar un usuario
		*/
        function editar_usuario_dlg($idusuario){

            $this->load->model('Model_usuarios');
            
            $usuario = $this->Model_usuarios->buscar_usuario($idusuario);
            
            $usuario[0]['nombres'] = quotes_to_entities($usuario[0]['nombres']);
            
            $data['usuario']=$usuario[0];
            $this->load->model('Model_sedes');
            $data['listasedes'] = $this->Model_sedes->lista_sedes();
            $this->load->view('usuarios/editar_usuario', $data);
            
        }
        


		/*
		Genera el formulario para cambiar la contraseña un usuario
		*/
        function cambiar_contrasenna_dlg($idusuario){

            $this->load->model('Model_usuarios');
            
            $usuario = $this->Model_usuarios->buscar_usuario($idusuario);
            
            $usuario[0]['nombres'] = quotes_to_entities($usuario[0]['nombres']);
            $usuario[0]['apellidos'] = quotes_to_entities($usuario[0]['apellidos']);
            
            $data['usuario']=$usuario[0];
            
            $this->load->view('usuarios/cambiar_contrasenna', $data);
            
        }
        
		/*
		Cambia la contraseña de una usuario
		*/
        function cambiar_contrasenna(){
            if (!$this->seguridad()){
                die();
            }
            $this->load->model('Model_usuarios');
            $this->Model_usuarios->cambiar_contrasenna();
        }
        


    }

    ?>
