    <?php

    class Mobile extends CI_Controller {

       private function actualiza_prereservacion($idtrabajador, $int_fecha,$sede,$turno){

        $this->load->model('Model_platos');
        $this->load->model('Model_tickets');
        $this->load->model('Model_prereservaciones');

        $n_prereservacion = $this->Model_prereservaciones->buscar_prereservacion($idtrabajador, $int_fecha,$sede,$turno);

        if (count($n_prereservacion) > 0){

            $menus = $this->Model_platos->lista_platos_fecha($int_fecha,$sede,$turno);
            foreach ($menus as $plato) {                        

                $compro = $this->Model_tickets->buscar_ticket($idtrabajador,$int_fecha ,$plato['idplato'],$sede,$turno);
                if ($compro== 0){
                    $this->Model_tickets->insertar_ticket_codigo($idtrabajador,$plato['idplato'],$int_fecha,$sede,$turno);
                } 
            }

            if(count($menus) > 0){
                $this->Model_prereservaciones->eliminar_prereservacion_codigo($idtrabajador, $int_fecha);
            }    

        }

    }

    

    private function seguridad_id($id){
        if($this->session->userdata('autenticado_front')==true){
            if ($this->session->userdata('tipo_usuario') == 'Trabajador'){
                if ($this->session->userdata('idtrabajador') == $id){
                    return true;
                } else {
                    return false;
                }
            } else {
                if ($this->session->userdata('idestudiante') == $id){
                    return true;
                } else {
                    return false;
                }
            }
        } else {
            return false;
        }
    }

    private function seguridad(){
        if($this->session->userdata('autenticado_front')==true){
            return true;
        } else {
            return false;
        }
    }


    function index(){


        $this->load->library('user_agent');
        $this->load->model('Model_sedes');
        $this->load->helper('url');

        if (!$this->agent->is_mobile())    {

            header('Location: ../../');

        }else{
            $carpeta_views = 'mobile/';

            $data['ruta_url'] = '../../';
            $data['titulo'] = 'SiRAl - UCLV';

            $data['accion'] = 'HOME';
            $data["autenticado"]= $this->session->userdata('autenticado_front');
            $data["sede_idsede"]= $this->session->userdata('sede_idsede');
            $data["tipo_usuario"]= $this->session->userdata('tipo_usuario');
            $this->config->load('reportescfg');
            $data['nombre_entidad'] = $this->config->item('nombre_entidad');

            $this->config->load('ticketscfg');
            $data['template_prof'] = $this->config->item('template_prof');
        //$data['template_est'] = $this->config->item('template_est');
            $data['sede_defecto'] = $this->config->item('sede_defecto');
            $this->config->load('templates');
            $data['templates'] = $this->config->item('t_templates');
            $sede_inicial= $this->config->item('sede_defecto');


            $this->load->view($carpeta_views.'header', $data);

            if($this->session->userdata('autenticado_front')==true){

                $data["nombre_apellidos"] = $this->session->userdata('nombre_apellidos_front');

            } else {

                $data["tipo_usuario"]="";
                $data["nombre_apellidos"] = "";
            }

            $data['ip_address'] = $_SERVER['REMOTE_ADDR'];

            $this->load->view($carpeta_views.'body_cabecera', $data);

            if($this->session->userdata('autenticado_front')!=true){               

                $data['listasedes'] = $this->Model_sedes->lista_sedes();

                $this->load->view($carpeta_views.'autenticar', $data);
            //}

            } else {

                //Obtener el menu de hoy

                $this->load->library('Herramientas');
                $this->load->model('Model_platos');
                $this->load->model('Model_tickets');
                $this->load->model('Model_trabajadores');
                $this->config->load('ticketscfg');


                $fecha_hoy = now();
                $dia_semana = mdate('%N', $fecha_hoy);

                $inicio_semana = human_to_unix(mdate('%Y-%m-%d', $fecha_hoy - (($dia_semana - 1) * 3600*24)).' 00:00 AM');

                $rangoFecha = 3600*(24 - $this->config->item('hora_cierre') - 1) + 60 * (60 -$this->config->item('minutos_cierre'));
                $data['fecha_admisible'] = $fecha_hoy + $rangoFecha;
                
                $sede=$data['sede_idsede'];

                $int_fecha = human_to_unix(mdate('%d/%m/%Y',$fecha_hoy).' 00:00 AM');
                $data['menu_hoy_d'] = $this->Model_platos->lista_platos_fecha(mdate('%Y-%m-%d',$int_fecha),$sede_inicial,"1");
                $data['menu_hoy_a'] = $this->Model_platos->lista_platos_fecha(mdate('%Y-%m-%d',$int_fecha),$sede_inicial,"2");
                $data['menu_hoy_c'] = $this->Model_platos->lista_platos_fecha(mdate('%Y-%m-%d',$int_fecha),$sede_inicial,"3");
                $data['fecha_hoy'] = $fecha_hoy;
                $data['inicio_semana'] = $inicio_semana;

                if($data["tipo_usuario"]=='Trabajador'){
                    $data['idtrabajador'] = $this->session->userdata('idtrabajador');  
                    $data['sede_idsede'] = $this->session->userdata('sede_idsede');                    
                }


                $this->load->view($carpeta_views.'inicio', $data);
            }    

            $this->load->view($carpeta_views.'body_abajo', $data);

        }
    }

    private function carga_header($data){
        $this->load->view('mobile/header',$data);
    }

    private function carga_body_cabecera($data){
        $this->load->view('mobile/body_cabecera',$data);
    }

    private function carga_body_abajo($data){
        $this->load->view('mobile/body_abajo',$data);
    }



    function reservar_desayunos($fecha_ver=null){
        $this->load->helper('url');

        if (!$this->seguridad()){
            header("Location:".base_url()."index.php/mobile/");
        }
        
        $data["tipo_usuario"]='Trabajador';
        $data['titulo'] = 'Desayunos';

        $idtrabajador = $this->session->userdata('idtrabajador');
        $sede=$this->session->userdata('sede_idsede');


        if($fecha_ver == null){           
            $fecha = now();           
            $data['ruta_url'] = '../../../';
        } else {
            $fecha = $fecha_ver;
            $data['ruta_url'] = '../../../../';
        }

        $data['idtrabajador'] = $idtrabajador;
        $this->carga_header($data);
        $this->carga_body_cabecera($data);

        $this->load->library('Herramientas');
        $this->load->model('Model_platos');
        $this->load->model('Model_tickets');
        $this->load->model('Model_prereservaciones');
        $this->load->model('Model_trabajadores');

        $this->config->load('ticketscfg');
        $data['menu_platos'] = $this->config->item('menu_platos');
        $data['cierra_viernes'] = $this->config->item('cierra_viernes');
        $data['incio_semana'] = $this->config->item('incio_semana');
        $template_prof = $this->config->item('template_prof');
        $this->config->load('templates');
        $templates = $this->config->item('t_templates');
        $data['iCheckStyleCB'] = $templates[$template_prof]['iCheckStyleCB'];
        $data['iCheckStyleRB'] = $templates[$template_prof]['iCheckStyleRB'];

        $data['fecha'] = $fecha;


                    //Calcular hasta que fecha es admisible la reservación

        $fecha_hoy = now();
        $rangoFecha = 3600*(24 - $this->config->item('hora_cierre') - 1) + 60 * (60 -$this->config->item('minutos_cierre'));
        $data['fecha_admisible'] = $fecha_hoy + $rangoFecha;


                    //Obtener los menus por días
        $menus = array();
        $seleccionado = array();
        $prereservacion = array();

        $dias_semana = array('lunes','martes','miercoles','jueves','viernes','sabado','domingo');

        $dia_semana = mdate('%N', $fecha);

        $inicio_semana = human_to_unix(mdate('%Y-%m-%d', $fecha).' 00:00 AM') - (($dia_semana - 1) * 3600*24);

        for($i=0; $i < count($dias_semana); $i++){
           $nombre_dia = $dias_semana[$i];    
           $int_fecha = $inicio_semana + 3600*24*$i;

           /*$n_prereservacion = $this->Model_prereservaciones->buscar_prereservacion($idtrabajador, mdate('%Y-%m-%d',$int_fecha),$sede,1);

           if (count($n_prereservacion) > 0){

            $this->actualiza_prereservacion($idtrabajador, mdate('%Y-%m-%d',$int_fecha),$sede,1);

        }*/


        $tiene_tickets_fecha[$i]=$this->Model_tickets->tiene_tickets($idtrabajador,mdate('%Y-%m-%d',$int_fecha),$sede,1);

        $prereservacion[$i] = $n_prereservacion; 

        $menus[$nombre_dia] = $this->Model_platos->lista_platos_fecha(mdate('%Y-%m-%d',$int_fecha),$sede,1);
        $seleccionado[$nombre_dia] = array();

        foreach ($menus[$nombre_dia] as $plato) {

            $compro = $this->Model_tickets->buscar_ticket_selecc($idtrabajador,mdate('%Y-%m-%d',$int_fecha), $plato['idplato'],$sede,1);

            if (count($compro)>0){
                $seleccionado[$nombre_dia][] = true;
            } else {
                $seleccionado[$nombre_dia][] = false;
            }
        }

    }

    $data['menus'] = $menus;
    $data['seleccionado'] = $seleccionado;
    $data['idtrabajador'] = $idtrabajador;
    $data['inicio_semana'] = $inicio_semana;
    $data['prereservacion'] = $prereservacion;
    $data['sede_idsede'] = $sede;
    $data['turno'] = 1;
    $data['tiene_tickets_fecha'] = $tiene_tickets_fecha;

    $this->load->view('mobile/reservacion', $data);

    $this->carga_body_abajo($data);
}

function reservar_almuerzos($fecha_ver=null){
        $this->load->helper('url');

        if (!$this->seguridad()){
            header("Location:".base_url()."index.php/mobile/");
        }
        
        $data["tipo_usuario"]='Trabajador';
        $data['titulo'] = 'Almuerzos';

        $idtrabajador = $this->session->userdata('idtrabajador');
        $sede=$this->session->userdata('sede_idsede');


        if($fecha_ver == null){           
            $fecha = now();           
            $data['ruta_url'] = '../../../';
        } else {
            $fecha = $fecha_ver;
            $data['ruta_url'] = '../../../../';
        }

        $data['idtrabajador'] = $idtrabajador;
        $this->carga_header($data);
        $this->carga_body_cabecera($data);

        $this->load->library('Herramientas');
        $this->load->model('Model_platos');
        $this->load->model('Model_tickets');
        $this->load->model('Model_prereservaciones');
        $this->load->model('Model_trabajadores');

        $this->config->load('ticketscfg');
        $data['menu_platos'] = $this->config->item('menu_platos');
        $data['cierra_viernes'] = $this->config->item('cierra_viernes');
        $data['incio_semana'] = $this->config->item('incio_semana');
        $template_prof = $this->config->item('template_prof');
        $this->config->load('templates');
        $templates = $this->config->item('t_templates');
        $data['iCheckStyleCB'] = $templates[$template_prof]['iCheckStyleCB'];
        $data['iCheckStyleRB'] = $templates[$template_prof]['iCheckStyleRB'];

        $data['fecha'] = $fecha;


                    //Calcular hasta que fecha es admisible la reservación

        $fecha_hoy = now();
        $rangoFecha = 3600*(24 - $this->config->item('hora_cierre') - 1) + 60 * (60 -$this->config->item('minutos_cierre'));
        $data['fecha_admisible'] = $fecha_hoy + $rangoFecha;


                    //Obtener los menus por días
        $menus = array();
        $seleccionado = array();
        $prereservacion = array();

        $dias_semana = array('lunes','martes','miercoles','jueves','viernes','sabado','domingo');

        $dia_semana = mdate('%N', $fecha);

        $inicio_semana = human_to_unix(mdate('%Y-%m-%d', $fecha).' 00:00 AM') - (($dia_semana - 1) * 3600*24);

        for($i=0; $i < count($dias_semana); $i++){
           $nombre_dia = $dias_semana[$i];    
           $int_fecha = $inicio_semana + 3600*24*$i;

           /*$n_prereservacion = $this->Model_prereservaciones->buscar_prereservacion($idtrabajador, mdate('%Y-%m-%d',$int_fecha),$sede,2);

           if (count($n_prereservacion) > 0){

            $this->actualiza_prereservacion($idtrabajador, mdate('%Y-%m-%d',$int_fecha),$sede,2);

        }*/


        $tiene_tickets_fecha[$i]=$this->Model_tickets->tiene_tickets($idtrabajador,mdate('%Y-%m-%d',$int_fecha),$sede,2);

        $prereservacion[$i] = $n_prereservacion; 

        $menus[$nombre_dia] = $this->Model_platos->lista_platos_fecha(mdate('%Y-%m-%d',$int_fecha),$sede,2);
        $seleccionado[$nombre_dia] = array();

        foreach ($menus[$nombre_dia] as $plato) {

            $compro = $this->Model_tickets->buscar_ticket_selecc($idtrabajador,mdate('%Y-%m-%d',$int_fecha), $plato['idplato'],$sede,2);

            if (count($compro)>0){
                $seleccionado[$nombre_dia][] = true;
            } else {
                $seleccionado[$nombre_dia][] = false;
            }
        }

    }

    $data['menus'] = $menus;
    $data['seleccionado'] = $seleccionado;
    $data['idtrabajador'] = $idtrabajador;
    $data['inicio_semana'] = $inicio_semana;
    $data['prereservacion'] = $prereservacion;
    $data['sede_idsede'] = $sede;
    $data['turno'] = 2;
    $data['tiene_tickets_fecha'] = $tiene_tickets_fecha;

    $this->load->view('mobile/reservacion', $data);

    $this->carga_body_abajo($data);
}

function reservar_comidas($fecha_ver=null){
        $this->load->helper('url');

        if (!$this->seguridad()){
            header("Location:".base_url()."index.php/mobile/");
        }
        
        $data["tipo_usuario"]='Trabajador';
        $data['titulo'] = 'Comidas';

        $idtrabajador = $this->session->userdata('idtrabajador');
        $sede=$this->session->userdata('sede_idsede');


        if($fecha_ver == null){           
            $fecha = now();           
            $data['ruta_url'] = '../../../';
        } else {
            $fecha = $fecha_ver;
            $data['ruta_url'] = '../../../../';
        }

        $data['idtrabajador'] = $idtrabajador;
        $this->carga_header($data);
        $this->carga_body_cabecera($data);

        $this->load->library('Herramientas');
        $this->load->model('Model_platos');
        $this->load->model('Model_tickets');
        $this->load->model('Model_prereservaciones');
        $this->load->model('Model_trabajadores');

        $this->config->load('ticketscfg');
        $data['menu_platos'] = $this->config->item('menu_platos');
        $data['cierra_viernes'] = $this->config->item('cierra_viernes');
        $data['incio_semana'] = $this->config->item('incio_semana');
        $template_prof = $this->config->item('template_prof');
        $this->config->load('templates');
        $templates = $this->config->item('t_templates');
        $data['iCheckStyleCB'] = $templates[$template_prof]['iCheckStyleCB'];
        $data['iCheckStyleRB'] = $templates[$template_prof]['iCheckStyleRB'];

        $data['fecha'] = $fecha;


                    //Calcular hasta que fecha es admisible la reservación

        $fecha_hoy = now();
        $rangoFecha = 3600*(24 - $this->config->item('hora_cierre') - 1) + 60 * (60 -$this->config->item('minutos_cierre'));
        $data['fecha_admisible'] = $fecha_hoy + $rangoFecha;


                    //Obtener los menus por días
        $menus = array();
        $seleccionado = array();
        $prereservacion = array();

        $dias_semana = array('lunes','martes','miercoles','jueves','viernes','sabado','domingo');

        $dia_semana = mdate('%N', $fecha);

        $inicio_semana = human_to_unix(mdate('%Y-%m-%d', $fecha).' 00:00 AM') - (($dia_semana - 1) * 3600*24);

        for($i=0; $i < count($dias_semana); $i++){
           $nombre_dia = $dias_semana[$i];    
           $int_fecha = $inicio_semana + 3600*24*$i;

           /*$n_prereservacion = $this->Model_prereservaciones->buscar_prereservacion($idtrabajador, mdate('%Y-%m-%d',$int_fecha),$sede,3);

           if (count($n_prereservacion) > 0){

            $this->actualiza_prereservacion($idtrabajador, mdate('%Y-%m-%d',$int_fecha),$sede,3);

        }*/


        $tiene_tickets_fecha[$i]=$this->Model_tickets->tiene_tickets($idtrabajador,mdate('%Y-%m-%d',$int_fecha),$sede,3);

        $prereservacion[$i] = $n_prereservacion; 

        $menus[$nombre_dia] = $this->Model_platos->lista_platos_fecha(mdate('%Y-%m-%d',$int_fecha),$sede,3);
        $seleccionado[$nombre_dia] = array();

        foreach ($menus[$nombre_dia] as $plato) {

            $compro = $this->Model_tickets->buscar_ticket_selecc($idtrabajador,mdate('%Y-%m-%d',$int_fecha), $plato['idplato'],$sede,3);

            if (count($compro)>0){
                $seleccionado[$nombre_dia][] = true;
            } else {
                $seleccionado[$nombre_dia][] = false;
            }
        }

    }

    $data['menus'] = $menus;
    $data['seleccionado'] = $seleccionado;
    $data['idtrabajador'] = $idtrabajador;
    $data['inicio_semana'] = $inicio_semana;
    $data['prereservacion'] = $prereservacion;
    $data['sede_idsede'] = $sede;
    $data['turno'] = 3;
    $data['tiene_tickets_fecha'] = $tiene_tickets_fecha;

    $this->load->view('mobile/reservacion', $data);

    $this->carga_body_abajo($data);
}

    

    function calendario_t($inicio_semana,$idtrabajador,$sede){
        $this->load->helper('url');
        if (!$this->seguridad()){
            header("location:".base_url()."index.php/mobile/");
        }

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
                $desayuno = '-';                                
                
                $n_prereservacion = $this->Model_prereservaciones->buscar_prereservacion($idtrabajador, $fecha_texto,$sede,"1");

                if (count($n_prereservacion) > 0){

                    //$this->actualiza_prereservacion($idtrabajador, $fecha_texto,$sede,"1");

                    $desayuno = '(D)';                    
                }

                $importe = $this->Model_trabajadores->importe_trabajador_fecha($fecha_texto, $idtrabajador,$sede,"1");
                if($importe != ''){
                    $desayuno = '$'.number_format($importe, 2);                    
                }
                $gastos_dias[$i] .= $desayuno.'';


                /*
                Para Almuerzos del mes
                */
                $almuerzo = '-';
                
                
                
                $n_prereservacion = $this->Model_prereservaciones->buscar_prereservacion($idtrabajador, $fecha_texto,$sede,"2");

                if (count($n_prereservacion) > 0){

                    //$this->actualiza_prereservacion($idtrabajador, $fecha_texto,$sede,"2");

                    $almuerzo = '(A)';                    
                }

                $importe = $this->Model_trabajadores->importe_trabajador_fecha($fecha_texto, $idtrabajador,$sede,"2");
                if($importe != ''){
                    $almuerzo = '$'.number_format($importe, 2);                    
                }
                $gastos_dias[$i] .= '<br/>'.$almuerzo.'';
                
                /*
                Para Comidas del mes
                */
                $comida = '-';
                

                $n_prereservacion = $this->Model_prereservaciones->buscar_prereservacion($idtrabajador, $fecha_texto,$sede,"3");

                if (count($n_prereservacion) > 0){

                    //$this->actualiza_prereservacion($idtrabajador, $fecha_texto,$sede,"3");

                    $comida = '(C)';
                }                        


                $importe = $this->Model_trabajadores->importe_trabajador_fecha($fecha_texto, $idtrabajador,$sede,"3");
                if($importe != ''){
                    $comida = '$'.number_format($importe, 2);                    
                } 
                $gastos_dias[$i] .= '<br/>'.$comida.'';
                
                $gastos_dias[$i] .='</p>';
                
            }


            // Calendario
        $prefs['start_day']='monday';
        $prefs['day_type']='abr';
        $prefs['template'] = '
        {table_open}<table width="100%" cellspacing="0" class="calendario ui-widget ui-widget-content">{/table_open}
        {heading_row_start}<tr>{/heading_row_start}
        {heading_previous_cell}<th><a id="b_ant_mes" onclick="javascript:actualiza_calendario(\''.$fecha_mes_ant.'\',\''.$idtrabajador.'\',\''.$sede.'\')"><</a></th>{/heading_previous_cell}
        {heading_title_cell}<th colspan="{colspan}"><h2>{heading}</h2></th>{/heading_title_cell}
        {heading_next_cell}<th><a id="b_prox_mes" onclick="javascript:actualiza_calendario(\''.$fecha_mes_prox.'\',\''.$idtrabajador.'\',\''.$sede.'\')">></a></th>{/heading_next_cell}
        {heading_row_end}</tr>{/heading_row_end}
        {week_row_start}<tr>{/week_row_start}
        {week_day_cell}<td>{week_day}</td>{/week_day_cell}
        {week_row_end}</tr>{/week_row_end}
        {cal_row_start}<tr>{/cal_row_start}
        {cal_cell_start}<td>{/cal_cell_start}
        {cal_cell_content}<strong>{day}</strong><br /><div style="text-align:justify;width:100%;">{content}</div>{/cal_cell_content}
        {cal_cell_content_today}<strong><div style="border: solid 1px #0078AE;background-color:#0078AE;color:#FFF;font-weight:bold;">{day}</strong><br /><div style="text-align:justify;width:100%;">{content}</div></div>{/cal_cell_content_today}
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

    

                function calendario($fecha_ver = null){
                    $this->load->helper('url');
                    if (!$this->seguridad()){
                        header("location:".base_url()."index.php/mobile/");
                    }

                    $data["tipo_usuario"]= $this->session->userdata('tipo_usuario');

                    $data['titulo'] = 'Calendario';
                    $data['sede_idsede'] = $this->session->userdata('sede_idsede');

                    if($data["tipo_usuario"]=='Trabajador'){
                        $data['idtrabajador'] = $this->session->userdata('idtrabajador');
                    }

                   
                    if($fecha_ver == null){
                        $fecha = now();
                        $data['ruta_url'] = '../../../';
                    } else {
                        $fecha = $fecha_ver;
                        $data['ruta_url'] = '../../../../';
                    }


                    $fecha_hoy = now();
                    $dia_semana = mdate('%N', $fecha_hoy);

                    $inicio_semana = human_to_unix(mdate('%Y-%m-%d', $fecha_hoy - (($dia_semana - 1) * 3600*24)).' 00:00 AM');

                    $int_fecha = human_to_unix(mdate('%d/%m/%Y',$fecha_hoy).' 00:00 AM');
                    $data['fecha_hoy'] = $fecha_hoy;
                    $data['inicio_semana'] = $inicio_semana;

                    $this->carga_header($data);
                    $this->carga_body_cabecera($data);

                    $this->load->view('mobile/calendario', $data);

                    $this->carga_body_abajo($data);

                }

                function multiples(){
                    $this->load->helper('url');
                    if (!$this->seguridad()){
                        header("location:".base_url()."index.php/mobile/");
                    }

                    $data["tipo_usuario"]= $this->session->userdata('tipo_usuario');
                    $data["sede_idsede"]= $this->session->userdata('sede_idsede');

                    $data['titulo'] = 'Asistente';

                    if($data["tipo_usuario"]=='Trabajador'){
                        $data['idtrabajador'] = $this->session->userdata('idtrabajador');
                    }                   

                    $data['ruta_url'] = '../../../';
                    $this->carga_header($data);
                    $this->carga_body_cabecera($data);

                    $this->load->library('Herramientas');
                    $this->config->load('ticketscfg');

                    $fecha_hoy = now();
                    $dia_semana = mdate('%N', $fecha_hoy);

                    $inicio_semana = human_to_unix(mdate('%Y-%m-%d', $fecha_hoy - (($dia_semana - 1) * 3600*24)).' 00:00 AM');

                    $rangoFecha = 3600*(24 - $this->config->item('hora_cierre') - 1) + 60 * (60 -$this->config->item('minutos_cierre'));
                    $data['fecha_admisible'] = $fecha_hoy + $rangoFecha;

                    $int_fecha = human_to_unix(mdate('%d/%m/%Y',$fecha_hoy).' 00:00 AM');
                    $data['fecha_hoy'] = $fecha_hoy;
                    $data['inicio_semana'] = $inicio_semana;

                    $this->load->view('mobile/multiples', $data);

                    $this->carga_body_abajo($data);

                }
      
    }
    ?>
