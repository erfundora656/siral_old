<?php

class Principal extends CI_Controller {

  function actualiza_prereservacion(){

    $this->load->model('Model_platos');
    $this->load->model('Model_tickets');
    $this->load->model('Model_prereservaciones');
    $this->load->library('Herramientas');

  $int_fecha=mdate('%Y-%d-%m',strtotime($_POST['fecha_p']));

    $n_prereservacion = $this->Model_prereservaciones->buscar_prereservacion_fecha($int_fecha,$_POST['sede_idsede'],$_POST['turno']);
 
    
    if (count($n_prereservacion) > 0){

        $menus = $this->Model_platos->lista_platos_fecha($int_fecha,$_POST['sede_idsede'],$_POST['turno']);
        foreach ($n_prereservacion as $idtrabajador) {  
        foreach ($menus as $plato) {                        

            $compro = $this->Model_tickets->buscar_ticket($idtrabajador['trabajador_idtrabajador'],$int_fecha ,$plato['idplato'],$_POST['sede_idsede'],$_POST['turno']);
            if ($compro== 0){
                $this->Model_tickets->insertar_ticket_codigo($idtrabajador['trabajador_idtrabajador'],$plato['idplato'],$int_fecha ,$_POST['sede_idsede'],$_POST['turno']);

                //echo $this->enviar_correo();
                
            } 
        }
    

        if(count($menus) > 0){
            $this->Model_prereservaciones->eliminar_prereservacion_codigo($idtrabajador['trabajador_idtrabajador'], $int_fecha );
        }
        }    

    }


}

function index(){

    $this->load->library('user_agent');
    $this->load->model('Model_sedes');
    $this->load->helper('url');

    if ($this->agent->is_mobile())    {

        header('Location: index.php/mobile/');
        
    }else{
       
        $carpeta_views = '';

       $data['ruta_url'] = '';

        $data['accion'] = 'HOME';
        $data["autenticado"]= $this->session->userdata('autenticado_front');
        $data["tipo_usuario"]= $this->session->userdata('tipo_usuario');
        $this->config->load('reportescfg');
        $data['nombre_entidad'] = $this->config->item('nombre_entidad');

        $this->config->load('ticketscfg');
        $data['template_prof'] = $this->config->item('template_prof');
        
    
        $sede_inicial= $this->config->item('sede_defecto');


        $acceso_front = $this->config->item('acceso_front');
        $this->load->library('Seguridad');

        $data['ip_permitido'] = $this->seguridad->revisa_front($acceso_front,$_SERVER['REMOTE_ADDR']);

        $this->config->load('templates');
        $data['templates'] = $this->config->item('t_templates');

        $this->load->library('Herramientas');
        $this->load->model('Model_tickets');                
        $this->load->model('Model_trabajadores');
        $this->config->load('ticketscfg');

            //Obtener el menu de hoy

        $this->load->model('Model_platos');                

        $this->load->view($carpeta_views.'header', $data);

        $fecha_hoy = now();
        $int_fecha = human_to_unix(mdate('%d/%m/%Y',$fecha_hoy).' 00:00 AM');

        if($this->session->userdata('autenticado_front')==true){

            $data['nombre_apellidos'] = $this->session->userdata('nombre_apellidos_front');

        } else {
            $data['nombre_apellidos'] = '';
        }

        $data['menu_hoy_d'] = $this->Model_platos->lista_platos_fecha(mdate('%Y-%m-%d',$int_fecha),$sede_inicial,"1");
        $data['menu_hoy_a'] = $this->Model_platos->lista_platos_fecha(mdate('%Y-%m-%d',$int_fecha),$sede_inicial,"2");
        $data['menu_hoy_c'] = $this->Model_platos->lista_platos_fecha(mdate('%Y-%m-%d',$int_fecha),$sede_inicial,"3");

        $data['ip_address'] = $_SERVER['REMOTE_ADDR'];

        $this->load->view($carpeta_views.'body_cabecera', $data);


        if($this->session->userdata('autenticado_front') !== TRUE){


            if($this->session->userdata('autenticado_registro') == TRUE){
                $data['autenticado_registro']=$this->session->userdata('autenticado_registro');
                $data['nombre']=$this->session->userdata('nombre_registro');
                $data['apellidos']=$this->session->userdata('apellidos_registro');
                $data['usuario']=$this->session->userdata('usuario_registro');
                $data['passw']=$this->session->userdata('passw_registro');
                $data['idsede']=$this->session->userdata('sede_registro');
                $data['email']=$this->session->userdata('email');
                $this->load->view($carpeta_views.'registrar', $data);     
            }else{
                $data['listasedes'] = $this->Model_sedes->lista_sedes_a();
                $this->load->view($carpeta_views.'autenticar', $data);      
            }

        }else {

            $dia_semana = mdate('%N', $fecha_hoy);

            $inicio_semana = human_to_unix(mdate('%Y-%m-%d', $fecha_hoy - (($dia_semana - 1) * 3600*24)).' 00:00 AM');

            $rangoFecha = 3600*(24 - $this->config->item('hora_cierre') - 1) + 60 * (60 -$this->config->item('minutos_cierre'));
            $data['fecha_admisible'] = $fecha_hoy + $rangoFecha;

            $data['fecha_hoy'] = $fecha_hoy;
            $data['inicio_semana'] = $inicio_semana;

            if($data['tipo_usuario']=='Trabajador'){
                $data['idtrabajador'] = $this->session->userdata('idtrabajador');
            }


            $this->load->model('Model_sedes');
            $sede_seleccionada= $this->session->userdata('sede_idsede');
            $data['nombre_idsede']=$this->Model_sedes->buscar_sede($sede_seleccionada)[0]['nombre'];
            $data['sede_idsede']= $sede_seleccionada;
            $this->load->view($carpeta_views.'inicio', $data);
        }    

        $this->load->view($carpeta_views.'body_abajo', $data);

    }
    

    
    
}

function almuerzo_hoy(){
    $this->load->model('Model_platos');
    $this->load->library('Herramientas');
    $fecha_hoy = time();
    $int_fecha = date('Y-m-d',$fecha_hoy);
    $data['menu_hoy'] = $this->Model_platos->lista_platos_fecha($int_fecha,"2");
    $this->load->view('inicio/menu', $data);
}

function comida_hoy(){
    $this->load->model('Model_plato_comidas');
    $this->load->library('Herramientas');
    $fecha_hoy = time();
    $int_fecha = date('Y-m-d',$fecha_hoy);
    $data['menu_hoy'] = $this->Model_plato_comidas->lista_plato_comidas_fecha($int_fecha,"3");
    $this->load->view('inicio/menu_c', $data);
}


/*TODO: Acá gestiona los datos iniciales de la vista principal para el trabajador */

function inicio(){
    $data['ruta_url'] = '';
    
    if($this->session->userdata('autenticado_front')==true){

        $this->load->library('Herramientas');
        $this->load->model('Model_platos');                
        $this->load->model('Model_tickets');                
        $this->load->model('Model_trabajadores');

        $sede_seleccionada= $this->session->userdata('sede_idsede');
        
        $this->config->load('ticketscfg');
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


        $data["tipo_usuario"]= $this->session->userdata('tipo_usuario');
        
        if($data["tipo_usuario"]=='Trabajador'){
            $data['idtrabajador'] = $this->session->userdata('idtrabajador');
        }
        

                $this->config->load('ticketscfg');
                $data['menu_platos'] = $this->config->item('menu_platos');

                $fecha_hoy = now();
                $dia_del_mes = mdate('%j',$fecha_hoy);
                
                $inicio_mes = human_to_unix(mdate('%Y-%m-%d',$fecha_hoy - (3600*24*($dia_del_mes-1))).' 00:00 AM');
                
                $int_fecha = mdate('%Y-%m-%d',$fecha_hoy);
                $data['fecha_hoy'] = $fecha_hoy;
                $data['inicio_mes'] = $inicio_mes;

                $int_fecha = human_to_unix(mdate('%d/%m/%Y',$fecha_hoy).' 00:00 AM');
                $data['menu_hoy_d'] = $this->Model_platos->lista_platos_fecha(mdate('%Y-%m-%d',$int_fecha),$sede_seleccionada,"1");
                $data['menu_hoy_a'] = $this->Model_platos->lista_platos_fecha(mdate('%Y-%m-%d',$int_fecha),$sede_seleccionada,"2");
                $data['menu_hoy_c'] = $this->Model_platos->lista_platos_fecha(mdate('%Y-%m-%d',$int_fecha),$sede_seleccionada,"3");
                
                $data['sede_idsede']=$sede_seleccionada;
                $this->load->view('inicio/inicio', $data);
            }
        }
        
        function estadisticas($dia_i,$mes_i,$anno_i,$dia_f,$mes_f,$anno_f){

            $this->load->library('Herramientas');
            $data["tipo_usuario"]= $this->session->userdata('tipo_usuario');
            
            if($data["tipo_usuario"]=='Trabajador'){
                $data['idtrabajador'] = $this->session->userdata('idtrabajador');
            }

            $data['ruta_url'] = '';

            if($dia_i == 0 || $mes_i == 0 || $anno_i == 0){
                //Calcular el mes actual
                $fecha = now();
                $dia_i = 26;
                $mes_i = mdate('%n', $fecha);
                $anno_i = mdate('%Y', $fecha);
                $mes_i--;
                if ($mes_i < 1){
                    $mes_i = 12;
                    $anno_i--;
                }
            }
            if($dia_f == 0 || $mes_f == 0 || $anno_f == 0){
                //Calcular el mes actual
                $fecha = now();
                $dia_f = 25;
                $mes_f = mdate('%n', $fecha);
                $anno_f = mdate('%Y', $fecha);
            }
            $data['ruta_url'] = '';

            $data['dia_i'] = $dia_i;
            $data['mes_i'] = $mes_i;
            $data['anno_i'] = $anno_i;

            $data['dia_f'] = $dia_f;
            $data['mes_f'] = $mes_f;
            $data['anno_f'] = $anno_f;

            

            $data['fechas'] = $this->herramientas->lista_fechas_d($dia_i,$mes_i,$anno_i,$dia_f,$mes_f,$anno_f);


            $this->load->view('estadisticas/estadisticas_home', $data);
        }

        /*TODO: Actualiza la vista del calendario (inicio/inicio(v))*/
        function calendario($inicio_semana,$idtrabajador,$sede){
            $this->load->library('Herramientas');
            $this->load->model('Model_trabajadores');                    
            $this->load->model('Model_prereservaciones');

            $fecha_separada = $this->herramientas->Separa_fecha(mdate('%d/%m/%Y',$inicio_semana));

            //Calcular los gastos cada día

            $this->load->helper('date');
            
            
            $dias_mes = days_in_month($fecha_separada['mes'],$fecha_separada['anno']);
            $dia_del_mes = mdate('%j',$inicio_semana);
            $inicio_mes = $inicio_semana - (3600*24*($dia_del_mes-1));

            //
            
            if($fecha_separada['mes'] > 1){
                $dias_mes_ant = days_in_month($fecha_separada['mes'] - 1,$fecha_separada['anno']);
            } else {
                $dias_mes_ant = days_in_month(12,$fecha_separada['anno'] - 5);
            }    

            $fecha_mes_ant = human_to_unix(mdate('%Y-%m-%d', $inicio_mes - (($dias_mes_ant-1)*3600*24)).' 00:00 AM'); 

            if($fecha_separada['mes'] < 12){
                $dias_mes_prox = days_in_month($fecha_separada['mes'] + 1,$fecha_separada['anno']);
            } else {
                $dias_mes_prox = days_in_month(1,$fecha_separada['anno'] + 1);
            }    

            $fecha_mes_prox = human_to_unix(mdate('%Y-%m-%d', $inicio_mes + (($dias_mes_prox + 5)*3600*24)).' 00:00 AM');
            
            $gastos_dias = array();
            $total_mes = 0;
            for ($i=1; $i <= $dias_mes; $i++){
                $gastos_dias[$i] = '<p>';
                
                if ($i < 10){
                    $dia = '0'.$i;
                } else {
                    $dia = $i;
                } 
                $fecha_texto = $fecha_separada['anno'].'-'.$fecha_separada['mes'].'-'.$dia;
                 /*
                Para Desayunos del mes
                */
                $desayuno = '(NR)';
                $check = '';                
                
                $n_prereservacion = $this->Model_prereservaciones->buscar_prereservacion($idtrabajador, $fecha_texto,$sede,"1");

                if (count($n_prereservacion) > 0){

                    //$this->actualiza_prereservacion($idtrabajador, $fecha_texto,$sede,"1");

                    $desayuno = '(PR)';
                    $check = 'checked';
                }

                $importe = $this->Model_trabajadores->importe_trabajador_fecha($fecha_texto, $idtrabajador,$sede,"1");
                if($importe != ''){
                    $desayuno = '$'.number_format($importe, 2);
                    $check = 'checked';
                }
                $gastos_dias[$i] .= '<input class="ck_a_m" type="checkbox" '.$check.' disabled/> D '.$desayuno.'';


                /*
                Para Almuerzos del mes
                */
                $almuerzo = '(NR)';
                $check = '';
                
                
                $n_prereservacion = $this->Model_prereservaciones->buscar_prereservacion($idtrabajador, $fecha_texto,$sede,"2");

                if (count($n_prereservacion) > 0){

                    //$this->actualiza_prereservacion($idtrabajador, $fecha_texto,$sede,"2");

                    $almuerzo = '(PR)';
                    $check = 'checked';
                }

                $importe = $this->Model_trabajadores->importe_trabajador_fecha($fecha_texto, $idtrabajador,$sede,"2");
                if($importe != ''){
                    $almuerzo = '$'.number_format($importe, 2);
                    $check = 'checked';
                }
                $gastos_dias[$i] .= '<br/><input class="ck_a_m" type="checkbox" '.$check.' disabled/> A '.$almuerzo.'';
                
                /*
                Para Comidas del mes
                */
                $comida = '(NR)';
                $check = '';

                $n_prereservacion = $this->Model_prereservaciones->buscar_prereservacion($idtrabajador, $fecha_texto,$sede,"3");

                if (count($n_prereservacion) > 0){

                    //$this->actualiza_prereservacion($idtrabajador, $fecha_texto,$sede,"3");

                    $comida = '(PR)';
                    
                    $check = 'checked';
                }                        


                $importe = $this->Model_trabajadores->importe_trabajador_fecha($fecha_texto, $idtrabajador,$sede,"3");
                if($importe != ''){
                    $comida = '$'.number_format($importe, 2);
                    $check = 'checked';
                } 
                $gastos_dias[$i] .= '<br/><input class="ck_a_m" type="checkbox" '.$check.' disabled/> C '.$comida.'';
                
                $gastos_dias[$i] .='</p>';
                
            }
            
            // Calendario
            $prefs['start_day']='monday';
            $prefs['day_type']='long';
            $prefs['template'] = '
            {table_open}<table width="100%" cellspacing="0" class="calendario ui-widget ui-widget-content">{/table_open}
            {heading_row_start}<tr>{/heading_row_start}
            {heading_previous_cell}<th><button id="b_ant_mes" onclick="javascript:actualiza_calendario(\''.$fecha_mes_ant.'\',\''.$idtrabajador.'\',\''.$sede.'\')">Anterior</button></th>{/heading_previous_cell}
            {heading_title_cell}<th colspan="{colspan}"><h2>{heading}</h2></th>{/heading_title_cell}
            {heading_next_cell}<th><button id="b_prox_mes" onclick="javascript:actualiza_calendario(\''.$fecha_mes_prox.'\',\''.$idtrabajador.'\',\''.$sede.'\')">Próximo</button></th>{/heading_next_cell}
            {heading_row_end}</tr>{/heading_row_end}
            {week_row_start}<tr>{/week_row_start}
            {week_day_cell}<td>{week_day}</td>{/week_day_cell}
            {week_row_end}</tr>{/week_row_end}
            {cal_row_start}<tr>{/cal_row_start}
            {cal_cell_start}<td>{/cal_cell_start}
            {cal_cell_content}<strong>{day}</strong><br /><div style="text-align:justify;width:100%;">{content}</div>{/cal_cell_content}
            {cal_cell_content_today}<strong><div class="calendario_home_hoy">{day}</strong><br /><div style="text-align:justify;width:100%;">{content}</div></div>{/cal_cell_content_today}
            {cal_cell_no_content}{day}{/cal_cell_no_content}
            {cal_cell_no_content_today}{day}{/cal_cell_no_content_today}
            {cal_cell_blank}&nbsp;{/cal_cell_blank}
            {cal_cell_end}</td>{/cal_cell_end}
            {cal_row_end}</tr>{/cal_row_end}
            {table_close}</table>{/table_close}';
            $prefs['show_next_prev'] = true;
            $this->load->library('calendar', $prefs);
            echo $this->calendar->generate($fecha_separada['anno'],$fecha_separada['mes'], $gastos_dias);
        }   

        

        function admin()
        {

            $data['ruta_url'] = '../../../';

            $data['accion'] = 'ADMIN';

            $this->config->load('reportescfg');
            $data['nombre_entidad'] = $this->config->item('nombre_entidad');

            $this->config->load('ticketscfg');
            $data['menu_platos'] = $this->config->item('menu_platos');
            $acceso_admin = $this->config->item('acceso_admin');
            $this->load->library('Seguridad');

            $data['ip_permitido'] = $this->seguridad->revisa_front($acceso_admin,$_SERVER['REMOTE_ADDR']);

                // Leer cabeceras

                //$data['titulo_sistema'] = $this->lang->line('general_titulo');
            $data["autenticado"]= $this->session->userdata('autenticado');
            $data["tipo_usuario"]= $this->session->userdata('tipo_usuario');

            $this->config->load('ticketscfg');
            $data['template_prof'] = $this->config->item('template_prof');
            
            $this->config->load('templates');
            $data['templates'] = $this->config->item('t_templates');

            $this->load->view('header', $data);

            if($this->session->userdata('autenticado')==true){

                $data["rol"] = $this->session->userdata('rol');
                $data["nombre_apellidos"] = $this->session->userdata('nombre_apellidos');

            } else {
                $data["nombre_apellidos"] = "";
            }
            $data['ip_address'] = $_SERVER['REMOTE_ADDR'];

            $this->load->view('body_cabecera', $data);

            $this->load->view('body_tabs', $data);

            $this->load->view('body_abajo', $data);

        }
        
        function admin_inicio($sede_){

            $this->load->library('Herramientas');
            $this->load->model('Model_platos');
            $this->load->model('Model_tickets');
            $this->load->model('Model_trabajadores');
            $this->load->model('Model_dirigidos');
            $this->load->model('Model_sedes');

            $this->config->load('ticketscfg');
            $data['menu_platos'] = $this->config->item('menu_platos');

            $data['trabajadores'] = $this->Model_trabajadores->lista_trabajadores();

            $data['ruta_url'] = '../../../';
            $data["autenticado"]= $this->session->userdata('autenticado');

            
            if($this->session->userdata('autenticado')==true){

                $data["rol"] = $this->session->userdata('rol');
                $data["nombre_apellidos"] = $this->session->userdata('nombre_apellidos');


                if($sede_==0){
                    /*Listado de sedes*/      
                    if($this->session->userdata('rol') == 5){ 
                        $data['listasedes']="";               
                        $nombre_sede=$this->Model_sedes->buscar_sede($this->session->userdata('sede_idsede'))[0]['nombre'];
                        $id_sede=$this->Model_sedes->buscar_sede($this->session->userdata('sede_idsede'))[0]['idsede'];
                        $data['sede_pertenece'] =$nombre_sede;
                        $data['id_sede']=$id_sede;
                        $sede=$id_sede;
                    }else{                               
                        $data['listasedes'] = $this->Model_sedes->lista_sedes();
                        $sede=$data['listasedes'][0]['idsede'];
                        $data['sede_pertenece']=$data['listasedes'][0]['nombre'];
                    }

                }else{
                    $data['listasedes'] = $this->Model_sedes->lista_sedes();
                    $sede=$sede_;
                    $data['sede_pertenece']=$this->Model_sedes->buscar_sede($sede)[0]['nombre'];
                }
            } else {
                $data["rol"] = 0;
                $data["nombre_apellidos"] = "";

                $data['listasedes']="";
                $id_sede=$this->config->item('sede_defecto');
                $nombre_sede=$this->Model_sedes->buscar_sede($id_sede)[0]['nombre'];
                $data['sede_pertenece'] =$nombre_sede;
                $data['id_sede']=$id_sede;
                $sede=$id_sede;
            }

            $fecha_hoy = now();
            $int_fecha = mdate('%Y-%m-%d',$fecha_hoy);
            $data['total_trabajadores'] = $this->Model_trabajadores->cantidad_trabajadores();    

            for ($i=1; $i < 4; $i++) { 
                $data['menu_hoy_'.$i] = $this->Model_platos->lista_platos_fecha_ini($int_fecha,$sede,$i);
               // Saber cuantos almuerzan hoy
                $data['trabajadores_reservacion_'.$i] = $this->Model_trabajadores->cantidad_trabajadores_fecha($int_fecha,$sede,$i);    
                $data['dirigidos_'.$i] = $this->Model_dirigidos->suma_dirigidos_fecha($int_fecha,$sede,$i);
            }

            $this->load->view('admin_inicio', $data);
        }


       

        function admin_menus_d(){
            /*DESAYUNO*/
            $data['ruta_url'] = '../../../';
            $this->load->model('Model_sedes');   
            /*Controlamos que los administradores solo generen menus en sus sedes*/
            if($this->session->userdata('rol') == 5){ 
                $data['listasedes']="";               
                $nombre_sede=$this->Model_sedes->buscar_sede($this->session->userdata('sede_idsede'))[0]['nombre'];
                $id_sede=$this->Model_sedes->buscar_sede($this->session->userdata('sede_idsede'))[0]['idsede'];
                $data['sede_pertenece'] =$nombre_sede;
                $data['id_sede']=$id_sede;
            }else{                               
                $data['listasedes'] = $this->Model_sedes->lista_sedes();
            }
            /*Este turno responde a 1-Desayuno 2-Almuerzo 3-Comida*/
            $data['turno']='1';

            $this->load->view('menus/admin_menu', $data);

        }
        function admin_menus_a(){
            /*ALMUERZO*/
            $data['ruta_url'] = '../../../';
            $this->load->model('Model_sedes');   
            /*Controlamos que los administradores solo generen menus en sus sedes*/
            if($this->session->userdata('rol') == 5){ 
                $data['listasedes']="";               
                $nombre_sede=$this->Model_sedes->buscar_sede($this->session->userdata('sede_idsede'))[0]['nombre'];
                $id_sede=$this->Model_sedes->buscar_sede($this->session->userdata('sede_idsede'))[0]['idsede'];
                $data['sede_pertenece'] =$nombre_sede;
                $data['id_sede']=$id_sede;
            }else{                               
                $data['listasedes'] = $this->Model_sedes->lista_sedes();
            }
            /*Este turno responde a 1-Desayuno 2-Almuerzo 3-Comida*/
            $data['turno']='2';

            $this->load->view('menus/admin_menu', $data);

        }
        function admin_menus_c(){
            /*COMIDA*/
            $data['ruta_url'] = '../../../';
            $this->load->model('Model_sedes');   
            /*Controlamos que los administradores solo generen menus en sus sedes*/
            if($this->session->userdata('rol') == 5){ 
                $data['listasedes']="";               
                $nombre_sede=$this->Model_sedes->buscar_sede($this->session->userdata('sede_idsede'))[0]['nombre'];
                $id_sede=$this->Model_sedes->buscar_sede($this->session->userdata('sede_idsede'))[0]['idsede'];
                $data['sede_pertenece'] =$nombre_sede;
                $data['id_sede']=$id_sede;
            }else{                               
                $data['listasedes'] = $this->Model_sedes->lista_sedes();
            }
            /*Este turno responde a 1-Desayuno 2-Almuerzo 3-Comida*/
            $data['turno']='3';

            $this->load->view('menus/admin_menu', $data);

        }
        function dirigidos_d(){
            /*Dirigidos*/
            $data['ruta_url'] = '../../../';
            $this->load->model('Model_sedes');   
            /*Controlamos que los administradores solo generen menus en sus sedes*/
            if($this->session->userdata('rol') == 5){ 
                $data['listasedes']="";               
                $nombre_sede=$this->Model_sedes->buscar_sede($this->session->userdata('sede_idsede'))[0]['nombre'];
                $id_sede=$this->Model_sedes->buscar_sede($this->session->userdata('sede_idsede'))[0]['idsede'];
                $data['sede_pertenece'] =$nombre_sede;
                $data['id_sede']=$id_sede;
            }else{                               
                $data['listasedes'] = $this->Model_sedes->lista_sedes();
            }
            /*Este turno responde a 1-Desayuno 2-Almuerzo 3-Comida*/
            $data['turno']='1';
            $this->load->view('dirigidos/main', $data);
        }

        function dirigidos_a(){
            /*Dirigidos*/
            $data['ruta_url'] = '../../../';
            $this->load->model('Model_sedes');   
            /*Controlamos que los administradores solo generen menus en sus sedes*/
            if($this->session->userdata('rol') == 5){ 
                $data['listasedes']="";               
                $nombre_sede=$this->Model_sedes->buscar_sede($this->session->userdata('sede_idsede'))[0]['nombre'];
                $id_sede=$this->Model_sedes->buscar_sede($this->session->userdata('sede_idsede'))[0]['idsede'];
                $data['sede_pertenece'] =$nombre_sede;
                $data['id_sede']=$id_sede;
            }else{                               
                $data['listasedes'] = $this->Model_sedes->lista_sedes();
            }
            /*Este turno responde a 1-Desayuno 2-Almuerzo 3-Comida*/
            $data['turno']='2';

            $this->load->view('dirigidos/main', $data);

        }

        function dirigidos_c(){
            /*Dirigidos*/
            $data['ruta_url'] = '../../../';
            $this->load->model('Model_sedes');   
            /*Controlamos que los administradores solo generen menus en sus sedes*/
            if($this->session->userdata('rol') == 5){ 
                $data['listasedes']="";               
                $nombre_sede=$this->Model_sedes->buscar_sede($this->session->userdata('sede_idsede'))[0]['nombre'];
                $id_sede=$this->Model_sedes->buscar_sede($this->session->userdata('sede_idsede'))[0]['idsede'];
                $data['sede_pertenece'] =$nombre_sede;
                $data['id_sede']=$id_sede;
            }else{                               
                $data['listasedes'] = $this->Model_sedes->lista_sedes();
            }
            /*Este turno responde a 1-Desayuno 2-Almuerzo 3-Comida*/
            $data['turno']='3';
            $this->load->view('dirigidos/main', $data);

        }

        function gest_reserva(){
            /*Gestionar Reservaciones almuerzos*/                                      
            $this->load->model('Model_sedes');           
            $data['ruta_url'] = '../../../';            
            $data['listasedes'] = $this->Model_sedes->lista_sedes();
            /*Este turno responde a 1-Desayuno 2-Almuerzo 3-Comida*/
            $data['turno']='2';

            $this->load->view('admin_tickets/main', $data);

        }

        function gest_reserva_d(){
            /*Gestionar Reservaciones desayunos*/                                  
            $this->load->model('Model_sedes');           
            $data['ruta_url'] = '../../../';            
            $data['listasedes'] = $this->Model_sedes->lista_sedes();
            /*Este turno responde a 1-Desayuno 2-Almuerzo 3-Comida*/
            $data['turno']='1';

            $this->load->view('admin_tickets/main', $data);

        }

        function gest_reserva_c(){
            /*Gestionar Reservaciones comidas*/


            $data['ruta_url'] = '../../../'; 
            $this->load->model('Model_sedes');            
            $data['listasedes'] = $this->Model_sedes->lista_sedes();
            /*Este turno responde a 1-Desayuno 2-Almuerzo 3-Comida*/
            $data['turno']='3';

            $this->load->view('admin_tickets/main', $data);

        }

 

   function enviar_correo(){
   
  //Load email library
$this->load->library('email');

//SMTP & mail configuration
$config = array(
    'protocol'  => getenv('PROTOCOL'),
    'smtp_host' => getenv('SMTP_HOST'),
    'smtp_port' => getenv('SMTP_PORT'),
    'smtp_user' => getenv('SMTP_USER'),
    'smtp_pass' => getenv('SMTP_PASS'),
    'mailtype'  => getenv('MAILTYPE'),
    'charset'   => getenv('CHARSET')
);
$this->email->initialize($config);
$this->email->set_mailtype("html");
$this->email->set_newline("rn");

//Email content
$htmlContent='<div align="center"><img width="50" id="imgUser" src="reservation.png" /></div>';
$htmlContent.='<table  id="tabla_ticket" class="ui-widget ui-widget-content" cellpadding="5" cellspacing="0" style="border-color: black;"></table> ';

$htmlContent.='<tr><td align="center" style="border-bottom-style: double;"><strong><label>Ticket - SIRAL</label></strong></td></tr>';
        $htmlContent.='<tr><td><label>Trabajador:</label>&nbsp;<label id="nombres"></label></td></tr>';
        $htmlContent.='<tr><td><strong><strong><label>Menú</label></strong></td></tr> ';
        

$this->email->to('erfundora@uclv.cu');
$this->email->from('efundora656@gmail.com','SiRAl');
$this->email->subject('SiRAl');
$this->email->message($htmlContent);

//Send email
//$this->email->send();

if($this->email->send(FALSE)){
         echo "enviado<br/>";
         echo $this->email->print_debugger(array('headers'));
     }else {
         echo "fallo <br/>";
         echo "error: ".$this->email->print_debugger(array('headers'));
     }
}
   }
    ?>
