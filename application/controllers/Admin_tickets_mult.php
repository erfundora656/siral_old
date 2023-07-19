<?php

/*
 * Controladora para la gestion de los usuarios.
 */

class Admin_tickets_mult extends CI_Controller {

		function index()
		{
            
		}

        private function createFolder($carpeta)    {
            if(!is_dir($carpeta)){
               mkdir($carpeta, 0777);        
           }
       }    
       private function seguridad($id){
        if($this->session->userdata('autenticado')==true){
            return true;
        } else {
            if($this->session->userdata('tipo_usuario')=='Trabajador'){
                $sesion_id = $this->session->userdata('idtrabajador');
                        }/* else {
                            $sesion_id = $this->session->userdata('idestudiante');
                        }*/
                        if($this->session->userdata('autenticado_front')==true && $sesion_id == $id){
                            return true;
                        } else {
                            return false;
                        }    
                    }
                }        
                
                function mult_trabajadores(){
                    
                    if (!$this->seguridad(null)){
                        die();
                    }
                    
                    $this->config->load('ticketscfg');
                    $template_prof = $this->config->item('template_prof');
                    $this->config->load('templates');
                    $templates = $this->config->item('t_templates');
                    $data['iCheckStyleCB'] = $templates[$template_prof]['iCheckStyleCB'];
                    $data['iCheckStyleRB'] = $templates[$template_prof]['iCheckStyleRB'];

                    $data['menu_platos'] = $this->config->item('menu_platos');
                    $data['cierra_viernes'] = $this->config->item('cierra_viernes');

                //Calcular hasta que fecha es admisible la reservación

                    $fecha_hoy = now();
                    $fecha_inicio_hoy = human_to_unix(mdate('%Y-%m-%d',$fecha_hoy).' 00:00 AM');
                    $rangoFecha = 3600*($this->config->item('hora_cierre')) + 60 * ($this->config->item('minutos_cierre'));
                    $data['fecha_admisible'] = $fecha_hoy  + 3600*24*2 - $rangoFecha;
                    $dia_semana = mdate('%N', $fecha_hoy);

                    if ($dia_semana > 4 && $data['cierra_viernes'] == true) {
                        
                        if($dia_semana == 7 && ($fecha_inicio_hoy + $rangoFecha)<($fecha_hoy)){
                            $data['fecha_admisible'] += (3600*24)*(7 - $dia_semana);
                        } else {
                            $data['fecha_admisible'] += (3600*24)*(8 - $dia_semana);
                        }
                    }
                    
                    
                    $data['tipo_usuario'] = 'Trabajadores';
                    $data['ruta_url'] = '../../../';
                    $this->load->model('Model_sedes');
            //Lista de las Sedes
                    $data['lista_sedes'] = array();

                    $sedes = $this->Model_sedes->lista_sedes_filtro(1, 'activa');

                    foreach ($sedes as $sede) {
                        $sede['nombre'] = quotes_to_entities($sede['nombre']);
                        $data['lista_sedes'][] = $sede;
                    }

                    $this->load->view('multiples/main', $data);
                }

                function lista_trabajadores_sede(){
                    if (!$this->seguridad(null)){
                        die();
                    }
                    
                    $this->load->model('Model_trabajadores');
                    $lista_trabajadores = $this->Model_trabajadores->lista_trabajadores();
                    echo json_encode($lista_trabajadores);
                }
                
                
                function acciones_trabajador(){
                    $idtrabajador = $_POST['id'];
                    if (!$this->seguridad($idtrabajador)){
                        die();
                    }
                    
                    $this->load->model('Model_platos');
                    $this->load->model('Model_tickets');
                    $this->load->model('Model_prereservaciones');
                    $this->load->model('Model_trabajadores');
                    $this->load->library('lib_logs');
                    $this->config->load('ticketscfg');
                    
                    $idtrabajador = $_POST['id'];
                    $accion = $_POST['accion'];
                    $actividad = $_POST['actividad'];
                    $fecha_ini = explode('/', $_POST['fecha_ini']);
                    $fecha_fin = explode('/', $_POST['fecha_fin']);
                    
                    $sede= $_POST['sede_idsede'];
                    $dia_ini = human_to_unix($fecha_ini[2].'-'.$fecha_ini[1].'-'.$fecha_ini[0].' 00:00 AM');
                    $dia_fin = human_to_unix($fecha_fin[2].'-'.$fecha_fin[1].'-'.$fecha_fin[0].' 00:00 AM');
                    
            //Validar fecha incial
                    
                    $fecha_hoy = now();
                    $fecha_inicio_hoy = human_to_unix(mdate('%Y-%m-%d',$fecha_hoy).' 00:00 AM');
                    $rangoFecha = 3600*($this->config->item('hora_cierre')) + 60 * ($this->config->item('minutos_cierre'));
                    $fecha_admisible = $fecha_hoy  + 3600*24*2 - $rangoFecha;
                    $dia_semana = mdate('%N', $fecha_hoy);

                    if ($dia_semana > 4 && $this->config->item('cierra_viernes') == true) {
                        echo (mdate('%Y-%m-%d %H:%i:%s',$fecha_inicio_hoy + $rangoFecha).' - '.mdate('%Y-%m-%d %H:%i:%s',$fecha_hoy));
                        
                        if($dia_semana == 7 && ($fecha_inicio_hoy + $rangoFecha)<($fecha_hoy)){
                            $fecha_admisible += (3600*24)*(7 - $dia_semana);
                        } else {
                            $fecha_admisible += (3600*24)*(8 - $dia_semana);
                        }
                    }

                    if ($dia_ini < $fecha_admisible){
                        $dia_ini = human_to_unix(mdate('%Y-%m-%d',$fecha_admisible).' 00:00 AM'); 
                    }
                    
                    $int_fecha = $dia_ini;
                    
                    $trabajador = $this->Model_trabajadores->buscar_trabajador($idtrabajador);
                    
                    if ($accion == 0) {

                        while($int_fecha <= $dia_fin){               

                            $dia_semana = mdate('%N', $int_fecha);
                            
                            if($_POST['finde']==1 || $dia_semana < 6){
                                
                                /*Accion (0-Reservar)/ actividad (Desayuno-Todos)*/
                                if($actividad == 0 || $actividad == 3){
                                    $menus = $this->Model_platos->lista_platos_fecha(mdate('%Y-%m-%d',$int_fecha),$sede,"1");
                                    foreach ($menus as $plato) {                        
                                        $compro = $this->Model_tickets->buscar_ticket($idtrabajador, mdate('%Y-%m-%d',$int_fecha),$plato['idplato'],$sede,"1");
                                        if ($compro== 0){
                                            $this->Model_tickets->insertar_ticket_codigo($idtrabajador,$plato['idplato'],mdate('%Y-%m-%d',$int_fecha),$sede,"1");
                                            //Logs

                                            if($this->session->userdata('autenticado_front')==true){
                                                $texto = 'Reservó "'.$plato['nombreplato'].' $ '.$plato['precio'].' para el desayuno del día '.$plato["fecha"].'.';
                                                $this->lib_logs->registra_log(time(), $this->session->userdata('usuario_f'), $_SERVER['REMOTE_ADDR'], $texto, false);
                                            }

                                            if($this->session->userdata('autenticado')==true){
                                                $texto = 'Le reservó "'.$plato['nombreplato'].' $ '.$plato['precio'].' para el desayuno del día '.$plato["fecha"].' al trabajador ('.$trabajador[0]['codigo'].' - '.$trabajador[0]['nombres'].' '.$trabajador[0]['apellidos'].').';
                                                $this->lib_logs->registra_log(time(), $this->session->userdata('usuario'), $_SERVER['REMOTE_ADDR'], $texto, true);
                                            }

                                        }
                                    }

                                    if(count($menus) == 0){
                                        $existe_prereservacion = $this->Model_prereservaciones->buscar_prereservacion($idtrabajador, mdate('%Y-%m-%d',$int_fecha),$sede,"1");
                                        if(count($existe_prereservacion) == 0){
                                            $this->Model_prereservaciones->insertar_prereservacion_codigo($idtrabajador, mdate('%Y-%m-%d',$int_fecha),$sede,"1");

                                            if($this->session->userdata('autenticado_front')==true){
                                                $texto = 'Prereservó para el desayuno del día '.mdate('%Y-%m-%d',$int_fecha).'.';
                                                $this->lib_logs->registra_log(time(), $this->session->userdata('usuario_f'), $_SERVER['REMOTE_ADDR'], $texto, false);
                                            }

                                            if($this->session->userdata('autenticado')==true){
                                                $texto = 'Le prereservó para el desayuno del día '.mdate('%Y-%m-%d',$int_fecha).' al trabajador ('.$trabajador[0]['codigo'].' - '.$trabajador[0]['nombres'].' '.$trabajador[0]['apellidos'].').';
                                                $this->lib_logs->registra_log(time(), $this->session->userdata('usuario'), $_SERVER['REMOTE_ADDR'], $texto, true);
                                            }

                                        }    
                                    }    

                                }
                                /*Fin Primera*/
                                /*Accion (0-Reservar)/ actividad (Almuerzo-Todos)*/
                                if($actividad == 1 || $actividad == 3){

                                    $menus = $this->Model_platos->lista_platos_fecha(mdate('%Y-%m-%d',$int_fecha),$sede,"2");
                                    foreach ($menus as $plato) {                        
                                        $compro = $this->Model_tickets->buscar_ticket($idtrabajador, mdate('%Y-%m-%d',$int_fecha),$plato['idplato'],$sede,"2");
                                        if ($compro== 0){
                                            $this->Model_tickets->insertar_ticket_codigo($idtrabajador,$plato['idplato'],mdate('%Y-%m-%d',$int_fecha),$sede,"2");
                                            //Logs

                                            if($this->session->userdata('autenticado_front')==true){
                                                $texto = 'Reservó "'.$plato['nombreplato'].' $ '.$plato['precio'].' para el almuerzo del día '.$plato["fecha"].'.';
                                                $this->lib_logs->registra_log(time(), $this->session->userdata('usuario_f'), $_SERVER['REMOTE_ADDR'], $texto, false);
                                            }

                                            if($this->session->userdata('autenticado')==true){
                                                $texto = 'Le reservó "'.$plato['nombreplato'].' $ '.$plato['precio'].' para el almuerzo del día '.$plato["fecha"].' al trabajador ('.$trabajador[0]['codigo'].' - '.$trabajador[0]['nombres'].' '.$trabajador[0]['apellidos'].').';
                                                $this->lib_logs->registra_log(time(), $this->session->userdata('usuario'), $_SERVER['REMOTE_ADDR'], $texto, true);
                                            }

                                        } 
                                    }

                                    if(count($menus) == 0){
                                        $existe_prereservacion = $this->Model_prereservaciones->buscar_prereservacion($idtrabajador, mdate('%Y-%m-%d',$int_fecha),$sede,"2");
                                        if(count($existe_prereservacion) == 0){
                                            $this->Model_prereservaciones->insertar_prereservacion_codigo($idtrabajador, mdate('%Y-%m-%d',$int_fecha),$sede,"2");

                                            if($this->session->userdata('autenticado_front')==true){
                                                $texto = 'Prereservó para el almuerzo del día '.mdate('%Y-%m-%d',$int_fecha).'.';
                                                $this->lib_logs->registra_log(time(), $this->session->userdata('usuario_f'), $_SERVER['REMOTE_ADDR'], $texto, false);
                                            }

                                            if($this->session->userdata('autenticado')==true){
                                                $texto = 'Le prereservó para el almuerzo del día '.mdate('%Y-%m-%d',$int_fecha).' al trabajador ('.$trabajador[0]['codigo'].' - '.$trabajador[0]['nombres'].' '.$trabajador[0]['apellidos'].').';
                                                $this->lib_logs->registra_log(time(), $this->session->userdata('usuario'), $_SERVER['REMOTE_ADDR'], $texto, true);
                                            }

                                        }    
                                    }    

                                }
                                /*Fin segunda*/
                                /*Accion (0-Reservar)/ actividad (Comida-Todos)*/
                                if($actividad == 2 || $actividad == 3){

                                    $menus = $this->Model_platos->lista_platos_fecha(mdate('%Y-%m-%d',$int_fecha),$sede,"3");
                                    foreach ($menus as $plato) {                        
                                        $compro = $this->Model_tickets->buscar_ticket($idtrabajador, mdate('%Y-%m-%d',$int_fecha),$plato['idplato'],$sede,"3");
                                        if ($compro== 0){
                                            $this->Model_tickets->insertar_ticket_codigo($idtrabajador,$plato['idplato'],mdate('%Y-%m-%d',$int_fecha),$sede,"3");
                                            //Logs

                                            if($this->session->userdata('autenticado_front')==true){
                                                $texto = 'Reservó "'.$plato['nombreplato'].' $ '.$plato['precio'].' para el comida del día '.$plato["fecha"].'.';
                                                $this->lib_logs->registra_log(time(), $this->session->userdata('usuario_f'), $_SERVER['REMOTE_ADDR'], $texto, false);
                                            }

                                            if($this->session->userdata('autenticado')==true){
                                                $texto = 'Le reservó "'.$plato['nombreplato'].' $ '.$plato['precio'].' para el comida del día '.$plato["fecha"].' al trabajador ('.$trabajador[0]['codigo'].' - '.$trabajador[0]['nombres'].' '.$trabajador[0]['apellidos'].').';
                                                $this->lib_logs->registra_log(time(), $this->session->userdata('usuario'), $_SERVER['REMOTE_ADDR'], $texto, true);
                                            }

                                        } 
                                    }

                                    if(count($menus) == 0){
                                        $existe_prereservacion = $this->Model_prereservaciones->buscar_prereservacion($idtrabajador, mdate('%Y-%m-%d',$int_fecha),$sede,"3");
                                        if(count($existe_prereservacion) == 0){
                                            $this->Model_prereservaciones->insertar_prereservacion_codigo($idtrabajador, mdate('%Y-%m-%d',$int_fecha),$sede,"3");

                                            if($this->session->userdata('autenticado_front')==true){
                                                $texto = 'Prereservó para el comida del día '.mdate('%Y-%m-%d',$int_fecha).'.';
                                                $this->lib_logs->registra_log(time(), $this->session->userdata('usuario_f'), $_SERVER['REMOTE_ADDR'], $texto, false);
                                            }

                                            if($this->session->userdata('autenticado')==true){
                                                $texto = 'Le prereservó para el comida del día '.mdate('%Y-%m-%d',$int_fecha).' al trabajador ('.$trabajador[0]['codigo'].' - '.$trabajador[0]['nombres'].' '.$trabajador[0]['apellidos'].').';
                                                $this->lib_logs->registra_log(time(), $this->session->userdata('usuario'), $_SERVER['REMOTE_ADDR'], $texto, true);
                                            }

                                        }    
                                    }    

                                }

                                /*Fin Tercera*/

                            }

                            $int_fecha += 3600*24;
                            
                        }
                        
                    } else {

                        while($int_fecha <= $dia_fin){
                            
                            $dia_semana = mdate('%N', $int_fecha);
                            
                            if($_POST['finde']==1 || $dia_semana < 6){
                                /*Accion (1-Reservar)/ actividad (Desayuno-Todos)*/
                                if($actividad == 0 || $actividad == 3){

                                    $menus = $this->Model_platos->lista_platos_fecha(mdate('%Y-%m-%d',$int_fecha),$sede,"1");
                                    foreach ($menus as $plato) {                        

                                        $compro = $this->Model_tickets->buscar_ticket($idtrabajador, mdate('%Y-%m-%d',$int_fecha),$plato['idplato'],$sede,"1");
                                        if ($compro!= 0){
                                            $this->Model_tickets->eliminar_ticket_codigo($idtrabajador,$plato['idplato']);

                                            if($this->session->userdata('autenticado_front')==true){
                                                $texto = 'Canceló "'.$plato['nombreplato'].' $ '.$plato['precio'].' para el desayuno del día '.$plato["fecha"].'.';
                                                $this->lib_logs->registra_log(time(), $this->session->userdata('usuario_f'), $_SERVER['REMOTE_ADDR'], $texto, false);
                                            }

                                            if($this->session->userdata('autenticado')==true){
                                                $texto = 'Le canceló "'.$plato['nombreplato'].' $ '.$plato['precio'].' para el desayuno del día '.$plato["fecha"].' al trabajador ('.$trabajador[0]['codigo'].' - '.$trabajador[0]['nombres'].' '.$trabajador[0]['apellidos'].').';
                                                $this->lib_logs->registra_log(time(), $this->session->userdata('usuario'), $_SERVER['REMOTE_ADDR'], $texto, true);
                                            }

                                        } 
                                    }

                                    if(count($menus) == 0){
                                        $existe_prereservacion = $this->Model_prereservaciones->buscar_prereservacion($idtrabajador, mdate('%Y-%m-%d',$int_fecha),$sede,"1");
                                        if(count($existe_prereservacion) > 0){
                                            $this->Model_prereservaciones->eliminar_prereservacion_codigo($idtrabajador, mdate('%Y-%m-%d',$int_fecha));

                                            if($this->session->userdata('autenticado_front')==true){
                                                $texto = 'Canceló la prereservación para el desayuno del día '.mdate('%Y-%m-%d',$int_fecha).'.';
                                                $this->lib_logs->registra_log(time(), $this->session->userdata('usuario_f'), $_SERVER['REMOTE_ADDR'], $texto, false);
                                            }

                                            if($this->session->userdata('autenticado')==true){
                                                $texto = 'Le canceló la prereservación para el desayuno del día '.mdate('%Y-%m-%d',$int_fecha).' al trabajador ('.$trabajador[0]['codigo'].' - '.$trabajador[0]['nombres'].' '.$trabajador[0]['apellidos'].').';
                                                $this->lib_logs->registra_log(time(), $this->session->userdata('usuario'), $_SERVER['REMOTE_ADDR'], $texto, true);
                                            }

                                        }    
                                    }   

                                }
                                /*Fin Primera*/
                                /*Accion (1-Reservar)/ actividad (Almuerzo-Todos)*/
                                if($actividad == 1 || $actividad == 3){

                                    $menus = $this->Model_platos->lista_platos_fecha(mdate('%Y-%m-%d',$int_fecha),$sede,"2");
                                    foreach ($menus as $plato) {                        

                                        $compro = $this->Model_tickets->buscar_ticket($idtrabajador, mdate('%Y-%m-%d',$int_fecha),$plato['idplato'],$sede,"2");
                                        if ($compro!= 0){
                                            $this->Model_tickets->eliminar_ticket_codigo($idtrabajador,$plato['idplato']);

                                            if($this->session->userdata('autenticado_front')==true){
                                                $texto = 'Canceló "'.$plato['nombreplato'].' $ '.$plato['precio'].' para el almuerzo del día '.$plato["fecha"].'.';
                                                $this->lib_logs->registra_log(time(), $this->session->userdata('usuario_f'), $_SERVER['REMOTE_ADDR'], $texto, false);
                                            }

                                            if($this->session->userdata('autenticado')==true){
                                                $texto = 'Le canceló "'.$plato['nombreplato'].' $ '.$plato['precio'].' para el almuerzo del día '.$plato["fecha"].' al trabajador ('.$trabajador[0]['codigo'].' - '.$trabajador[0]['nombres'].' '.$trabajador[0]['apellidos'].').';
                                                $this->lib_logs->registra_log(time(), $this->session->userdata('usuario'), $_SERVER['REMOTE_ADDR'], $texto, true);
                                            }

                                        } 
                                    }

                                    if(count($menus) == 0){
                                        $existe_prereservacion = $this->Model_prereservaciones->buscar_prereservacion($idtrabajador, mdate('%Y-%m-%d',$int_fecha),$sede,"2");
                                        if(count($existe_prereservacion) > 0){
                                            $this->Model_prereservaciones->eliminar_prereservacion_codigo($idtrabajador, mdate('%Y-%m-%d',$int_fecha));

                                            if($this->session->userdata('autenticado_front')==true){
                                                $texto = 'Canceló la prereservación para el almuerzo del día '.mdate('%Y-%m-%d',$int_fecha).'.';
                                                $this->lib_logs->registra_log(time(), $this->session->userdata('usuario_f'), $_SERVER['REMOTE_ADDR'], $texto, false);
                                            }

                                            if($this->session->userdata('autenticado')==true){
                                                $texto = 'Le canceló la prereservación para el almuerzo del día '.mdate('%Y-%m-%d',$int_fecha).' al trabajador ('.$trabajador[0]['codigo'].' - '.$trabajador[0]['nombres'].' '.$trabajador[0]['apellidos'].').';
                                                $this->lib_logs->registra_log(time(), $this->session->userdata('usuario'), $_SERVER['REMOTE_ADDR'], $texto, true);
                                            }

                                        }    
                                    }   

                                }
                                /*Fin Segunda*/
                                /*Accion (1-Reservar)/ actividad (Comida-Todos)*/
                                if($actividad == 2 || $actividad == 3){

                                    $menus = $this->Model_platos->lista_platos_fecha(mdate('%Y-%m-%d',$int_fecha),$sede,"3");
                                    foreach ($menus as $plato) {                        

                                        $compro = $this->Model_tickets->buscar_ticket($idtrabajador, mdate('%Y-%m-%d',$int_fecha),$plato['idplato'],$sede,"3");
                                        if ($compro!= 0){
                                            $this->Model_tickets->eliminar_ticket_codigo($idtrabajador,$plato['idplato']);

                                            if($this->session->userdata('autenticado_front')==true){
                                                $texto = 'Canceló "'.$plato['nombreplato'].' $ '.$plato['precio'].' para el comida del día '.$plato["fecha"].'.';
                                                $this->lib_logs->registra_log(time(), $this->session->userdata('usuario_f'), $_SERVER['REMOTE_ADDR'], $texto, false);
                                            }

                                            if($this->session->userdata('autenticado')==true){
                                                $texto = 'Le canceló "'.$plato['nombreplato'].' $ '.$plato['precio'].' para el comida del día '.$plato["fecha"].' al trabajador ('.$trabajador[0]['codigo'].' - '.$trabajador[0]['nombres'].' '.$trabajador[0]['apellidos'].').';
                                                $this->lib_logs->registra_log(time(), $this->session->userdata('usuario'), $_SERVER['REMOTE_ADDR'], $texto, true);
                                            }

                                        } 
                                    }

                                    if(count($menus) == 0){
                                        $existe_prereservacion = $this->Model_prereservaciones->buscar_prereservacion($idtrabajador, mdate('%Y-%m-%d',$int_fecha),$sede,"3");
                                        if(count($existe_prereservacion) > 0){
                                            $this->Model_prereservaciones->eliminar_prereservacion_codigo($idtrabajador, mdate('%Y-%m-%d',$int_fecha));

                                            if($this->session->userdata('autenticado_front')==true){
                                                $texto = 'Canceló la prereservación para el comida del día '.mdate('%Y-%m-%d',$int_fecha).'.';
                                                $this->lib_logs->registra_log(time(), $this->session->userdata('usuario_f'), $_SERVER['REMOTE_ADDR'], $texto, false);
                                            }

                                            if($this->session->userdata('autenticado')==true){
                                                $texto = 'Le canceló la prereservación para el comida del día '.mdate('%Y-%m-%d',$int_fecha).' al trabajador ('.$trabajador[0]['codigo'].' - '.$trabajador[0]['nombres'].' '.$trabajador[0]['apellidos'].').';
                                                $this->lib_logs->registra_log(time(), $this->session->userdata('usuario'), $_SERVER['REMOTE_ADDR'], $texto, true);
                                            }

                                        }    
                                    }   

                                }
                                /*Fin Tercera*/

                            }
                            
                            $int_fecha += 3600*24;
                            
                        }
                        
                    }
                    
                }               
                
            }

            ?>
