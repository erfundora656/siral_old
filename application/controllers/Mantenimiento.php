<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mantenimiento extends CI_Controller {

	public function index()
	{
            if (!$this->seguridad()){
                die();
            }
            $data['ruta_url'] = '../../../';
            $this->load->view('mantenimiento/main',$data);
	}

       private function seguridad(){
                if($this->session->userdata('autenticado')==true){
                    if ($this->session->userdata('rol') == 3){
                        return true;
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }            
        }        
        
        public function revisar_trabajadores(){
            if (!$this->seguridad()){
                die();
            }

            $data['ruta_url'] = '../../../';
            
            //Obtener la lista de las sedes
            $this->load->model('Model_sedes');
            $data['lista_sedes'] = $this->Model_sedes->lista_sedes_filtro(1, 'activa');
            $data['tipo_usuario'] = 'Trabajadores';
            $this->load->view('mantenimiento/revisar',$data);
            
        }

        public function lista_trabajadores(){
            if (!$this->seguridad()){
                die();
            }
            
            $this->load->model('Model_trabajadores');
            $lista_trabajadores = $this->Model_trabajadores->lista_trabajadores();
            echo json_encode($lista_trabajadores);
        }
        
       /* public function lista_trabajadores_sede($idsede){
            if (!$this->seguridad()){
                die();
            }
            
            $this->load->model('Model_trabajadores');
            //$lista_trabajadores = $this->Model_trabajadores->lista_trabajadores_filtro('sede_idsede',$idsede);
            $lista_trabajadores = $this->Model_trabajadores->lista_trabajadores_filtro('sede_idsede',$idsede);
            echo json_encode($lista_trabajadores);
        }*/
        
       /* public function existe_trabajador_ldap($usuario){
            if (!$this->seguridad()){
                die();
            }
            
            $this->load->model('Model_sedes');
            $sede = $this->Model_sedes->buscar_sede($idsede);
            $this->config->load('ldapcfg');
            $servidor = $sede[0]['servidorldap']; 
            $sufijo = $sede[0]['sufijo']; 
            $base_dn = $sede[0]['dnbase'];
            $usuario_a = $sede[0]['usuario'];
            
            $grupos = explode(',',$sede[0]['gtrabajadores']);

            $this->load->library('lib_ldap');
            
            $datos_usuario = $this->lib_ldap->datos_usuario($usuario_a,$usuario,array("*"), $servidor, $sufijo, $base_dn);
            
            if($datos_usuario != false){
                $salida = true;
            } else {
                $salida = false;
            }
            
            echo json_encode($salida);
        }*/
        
        public function dias_trabajador($idtrabajador){
            if (!$this->seguridad()){
                die();
            }

            $this->load->model('Model_trabajadores');
            $dias_trabajador_d = $this->Model_trabajadores->fechas_trabajador($idtrabajador,1);
            $dias_trabajador_a = $this->Model_trabajadores->fechas_trabajador($idtrabajador,2);
            $dias_trabajador_c = $this->Model_trabajadores->fechas_trabajador($idtrabajador,3);
            $salida = array();
            $salida['desayunos'] = count($dias_trabajador_d);
            $salida['almuerzos'] = count($dias_trabajador_a);
            $salida['comidas'] = count($dias_trabajador_c);
            
            //Buscar Prereservaciones
            $this->load->model('Model_prereservaciones');
            $pre_trabajador_d = $this->Model_prereservaciones->fechas_prereservacion($idtrabajador,1);
            $pre_trabajador_a = $this->Model_prereservaciones->fechas_prereservacion($idtrabajador,2);
            $pre_trabajador_c = $this->Model_prereservaciones->fechas_prereservacion($idtrabajador,3);
            
            $salida['desayunos'] += count($pre_trabajador_d);
            $salida['almuerzos'] += count($pre_trabajador_a);
            $salida['comidas'] += count($pre_trabajador_c);
            
            echo json_encode($salida);
        }
        
        public function mover_tickets_trabajador(){
            
            if (!$this->seguridad()){
                die();
            }
            
            $this->load->model('Model_tickets');
            $this->load->model('Model_ticket_comidas');
            $this->load->model('Model_prereservaciones');
            $this->load->model('Model_prereservacion_comidas');
            
            $idtrabajador_origen = $_POST["idusuario_origen"];
            $idtrabajador_destino = $_POST["idusuario_destino"];
            
            $this->Model_tickets->mover_tickets($idtrabajador_origen,$idtrabajador_destino);
            $this->Model_ticket_comidas->mover_ticket_comidas($idtrabajador_origen,$idtrabajador_destino);
            
            //Mover prereservaciones
            $this->Model_prereservaciones->mover_prereservaciones($idtrabajador_origen,$idtrabajador_destino);
            $this->Model_prereservacion_comidas->mover_prereservaciones($idtrabajador_origen,$idtrabajador_destino);
            
            
            
        }
        
        //Estudiantes
        
        public function revisar_estudiantes(){
            if (!$this->seguridad()){
                die();
            }

            $data['ruta_url'] = '../../../';
            
            //Obtener la lista de las sedes
            $this->load->model('Model_sedes');
            $data['lista_sedes'] = $this->Model_sedes->lista_sedes_filtro(1, 'activa');
            $data['tipo_usuario'] = 'Estudiantes';
            $this->load->view('mantenimiento/revisar',$data);
            
        }
        
        public function lista_estudiantes_sede($idsede){
            if (!$this->seguridad()){
                die();
            }
            
            $this->load->model('Model_estudiantes');
            $lista_estudiantes = $this->Model_estudiantes->lista_estudiantes_filtro('sede_idsede',$idsede);
            echo json_encode($lista_estudiantes);
        }
        
        public function existe_estudiante_ldap($idsede,$usuario){
            if (!$this->seguridad()){
                die();
            }

            $this->load->model('Model_sedes');
            $sede = $this->Model_sedes->buscar_sede($idsede);
            $this->config->load('ldapcfg');
            $servidor = $sede[0]['servidorldap']; 
            $sufijo = $sede[0]['sufijo']; 
            $base_dn = $sede[0]['dnbase'];
            $usuario_a = $sede[0]['usuario'];
            $grupos = explode(',',$sede[0]['gtrabajadores']);

            $this->load->library('lib_ldap');
            
            $datos_usuario = $this->lib_ldap->datos_usuario($usuario_a,$usuario,array("*"), $servidor, $sufijo, $base_dn);
            
            if($datos_usuario != false){
                $salida = true;
            } else {
                $salida = false;
            }
            
            echo json_encode($salida);
        }
        
        public function dias_estudiante($idtrabajador){
            if (!$this->seguridad()){
                die();
            }
            $this->load->model('Model_estudiantes');
            $dias_estudiante = $this->Model_estudiantes->fechas_estudiante($idtrabajador);
            $dias_estudiante_c = $this->Model_estudiantes->fechas_estudiante_c($idtrabajador);
            $salida = array();
            $salida['almuerzos'] = count($dias_estudiante);
            $salida['comidas'] = count($dias_estudiante_c);
            echo json_encode($salida);
        }
        
        public function mover_tickets_estudiante(){
            
            if (!$this->seguridad()){
                die();
            }
            
            $this->load->model('Model_tickets_est_a');
            $this->load->model('Model_tickets_est_c');
            $idestudiante_origen = $_POST["idusuario_origen"];
            $idestudiante_destino = $_POST["idusuario_destino"];
            
            $this->Model_tickets_est_a->mover_tickets($idestudiante_origen,$idestudiante_destino);
            $this->Model_tickets_est_c->mover_tickets($idestudiante_origen,$idestudiante_destino);
            
        }
        
        //Acciones en lote
        
        public function acciones_lote(){
            $data['ruta_url'] = '../../../';
            
            //Obtener la lista de las sedes
            $this->load->model('Model_sedes');
            $data['lista_sedes'] = $this->Model_sedes->lista_sedes_filtro(1, 'activa');
            $this->load->view('mantenimiento/acciones_lote',$data);
        }
        
        public function borrar_almuerzos_trabajadores($dia_ini,$mes_ini,$anno_ini,$dia_fin,$mes_fin,$anno_fin){
            if (!$this->seguridad()){
                die();
            }
            
            $this->load->model('Model_tickets');
            $this->load->model('Model_platos');
            
            $fecha_ini = $anno_ini.'-'.$mes_ini.'-'.$dia_ini;
            $fecha_fin = $anno_fin.'-'.$mes_fin.'-'.$dia_fin;
            
            //Obtener lista de platos
            
            $listaplatos = $this->Model_platos->lista_platos_fechas($fecha_ini,$fecha_fin);
            
            //Borrar las reservaciones para cada plato.
            
            foreach ($listaplatos as $plato){
                $this->Model_tickets->eliminar_tickets_plato($plato['idplato']);
            }
            
        }
        
        public function borrar_comidas_trabajadores($dia_ini,$mes_ini,$anno_ini,$dia_fin,$mes_fin,$anno_fin){
            if (!$this->seguridad()){
                die();
            }
            
            $this->load->model('Model_ticket_comidas');
            $this->load->model('Model_plato_comidas');
            
            $fecha_ini = $anno_ini.'-'.$mes_ini.'-'.$dia_ini;
            $fecha_fin = $anno_fin.'-'.$mes_fin.'-'.$dia_fin;
            
            //Obtener lista de platos
            
            $listaplatos = $this->Model_plato_comidas->lista_plato_comidas_fechas($fecha_ini,$fecha_fin);
            
            //Borrar las reservaciones para cada plato.
            
            foreach ($listaplatos as $plato){
                $this->Model_ticket_comidas->eliminar_ticket_comidas_plato($plato['idplato_comida']);
            }
            
        }

        public function borrar_pre_almuerzos_trabajadores($dia_ini,$mes_ini,$anno_ini,$dia_fin,$mes_fin,$anno_fin){
            if (!$this->seguridad()){
                die();
            }
            
            $this->load->model('Model_prereservaciones');
            
            $fecha_ini = $anno_ini.'-'.$mes_ini.'-'.$dia_ini;
            $fecha_fin = $anno_fin.'-'.$mes_fin.'-'.$dia_fin;
            
            $this->Model_prereservaciones->eliminar_prereservacion_fechas($fecha_ini,$fecha_fin);
            
        }

        public function borrar_pre_comidas_trabajadores($dia_ini,$mes_ini,$anno_ini,$dia_fin,$mes_fin,$anno_fin){
            if (!$this->seguridad()){
                die();
            }
            
            $this->load->model('Model_prereservacion_comidas');
            
            $fecha_ini = $anno_ini.'-'.$mes_ini.'-'.$dia_ini;
            $fecha_fin = $anno_fin.'-'.$mes_fin.'-'.$dia_fin;
            
            $this->Model_prereservacion_comidas->eliminar_prereservacion_comida_fechas($fecha_ini,$fecha_fin);
            
        }

        /*public function borrar_almuerzos_estudiantes($dia_ini,$mes_ini,$anno_ini,$dia_fin,$mes_fin,$anno_fin){
            if (!$this->seguridad()){
                die();
            }
            
            $this->load->model('Model_tickets_est_a');
            
            $fecha_ini = $anno_ini.'-'.$mes_ini.'-'.$dia_ini;
            $fecha_fin = $anno_fin.'-'.$mes_fin.'-'.$dia_fin;
            
            $this->Model_tickets_est_a->eliminar_tickets_est_a_fechas($fecha_ini,$fecha_fin);
            
        }
        
        public function borrar_comidas_estudiantes($dia_ini,$mes_ini,$anno_ini,$dia_fin,$mes_fin,$anno_fin){
            if (!$this->seguridad()){
                die();
            }
            
            $this->load->model('Model_tickets_est_c');
            
            $fecha_ini = $anno_ini.'-'.$mes_ini.'-'.$dia_ini;
            $fecha_fin = $anno_fin.'-'.$mes_fin.'-'.$dia_fin;
            
            $this->Model_tickets_est_c->eliminar_tickets_est_c_fechas($fecha_ini,$fecha_fin);
            
        }

        public function borrar_dirigidos_almuerzos($dia_ini,$mes_ini,$anno_ini,$dia_fin,$mes_fin,$anno_fin){
            if (!$this->seguridad()){
                die();
            }
            
            $this->load->model('Model_dirigidos');
            
            $fecha_ini = $anno_ini.'-'.$mes_ini.'-'.$dia_ini;
            $fecha_fin = $anno_fin.'-'.$mes_fin.'-'.$dia_fin;
            
            $this->Model_dirigidos->eliminar_dirigido_fechas($fecha_ini,$fecha_fin);
            
        }
        
        public function borrar_dirigidos_comidas($dia_ini,$mes_ini,$anno_ini,$dia_fin,$mes_fin,$anno_fin){
            if (!$this->seguridad()){
                die();
            }
            
            $this->load->model('Model_dirigido_comidas');
            
            $fecha_ini = $anno_ini.'-'.$mes_ini.'-'.$dia_ini;
            $fecha_fin = $anno_fin.'-'.$mes_fin.'-'.$dia_fin;
            
            $this->Model_dirigido_comidas->eliminar_dirigido_comida_fechas($fecha_ini,$fecha_fin);
            
        }*/
        
}

