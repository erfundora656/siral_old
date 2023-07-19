<?php

/*
 * Controladora para la gestion de los tickets revisado OK.
 */

class Admin_tickets extends CI_Controller {

		function index()
		{                   
            $data['ruta_url'] = '../../../';                   
            $this->load->view('admin_tickets/main', $data);
        }

        private function createFolder($carpeta)    {
            if(!is_dir($carpeta)){
             mkdir($carpeta, 0777);        
         }
     }    
     private function seguridad($idtrabajador){
        if($this->session->userdata('autenticado')==true){
            return true;
        } else {
            if($this->session->userdata('autenticado_front')==true && $this->session->userdata('idtrabajador') == $idtrabajador){
                return true;
            } else {
                return false;
            }    
        }
    }   	

    function tabla_semana($fecha, $idtrabajador,$admin,$sede,$turno){
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
            
            $n_prereservacion = $this->Model_prereservaciones->buscar_prereservacion($idtrabajador, mdate('%Y-%m-%d',$int_fecha),$sede,$turno);
            
            /*if (count($n_prereservacion) > 0){

                $this->actualiza_prereservacion($idtrabajador, mdate('%Y-%m-%d',$int_fecha),$sede,$turno);

            } */          

            $tiene_tickets_fecha[$i]=$this->Model_tickets->tiene_tickets($idtrabajador,mdate('%Y-%m-%d',$int_fecha),$sede,$turno);

            $prereservacion[$i] = $n_prereservacion; 

            $menus[$nombre_dia] = $this->Model_platos->lista_platos_fecha(mdate('%Y-%m-%d',$int_fecha),$sede,$turno);
            $seleccionado[$nombre_dia] = array();

            
            foreach ($menus[$nombre_dia] as $plato) {

                $compro = $this->Model_tickets->buscar_ticket_selecc($idtrabajador,mdate('%Y-%m-%d',$int_fecha), $plato['idplato'],$sede,$turno);

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
        $data['turno'] = $turno;
        $data['tiene_tickets_fecha'] = $tiene_tickets_fecha;
        if($admin == 1) {
            $data['ruta_url'] = '../../../';
        } else {
            $data['ruta_url'] = '';
        }
        
        $this->load->view('admin_tickets/tabla_fechas', $data);
        
    }

    function calendario_gastos($inicio_semana,$idtrabajador,$sede,$turno){
        $this->load->library('Herramientas');
        $this->load->model('Model_trabajadores');                    
        $this->load->model('Model_prereservaciones');
        
                    // Calendario
        $prefs['start_day']='monday';
        $prefs['template'] = '
        {table_open}<table width="100%" cellspacing="0" class="calendario ui-widget ui-widget-content">{/table_open}
        {heading_row_start}<tr>{/heading_row_start}
        {heading_previous_cell}<th><a href="{previous_url}">&lt;&lt;</a></th>{/heading_previous_cell}
        {heading_title_cell}<th colspan="{colspan}">{heading}</th>{/heading_title_cell}
        {heading_next_cell}<th><a href="{next_url}">&gt;&gt;</a></th>{/heading_next_cell}
        {heading_row_end}</tr>{/heading_row_end}
        {week_row_start}<tr>{/week_row_start}
        {week_day_cell}<td>{week_day}</td>{/week_day_cell}
        {week_row_end}</tr>{/week_row_end}
        {cal_row_start}<tr>{/cal_row_start}
        {cal_cell_start}<td>{/cal_cell_start}
        {cal_cell_content}<strong>{day}</strong><br />{content}{/cal_cell_content}
        {cal_cell_content_today}<strong><div style="border: solid 1px #777777;">{day}</strong><br />{content}</div>{/cal_cell_content_today}
        {cal_cell_no_content}{day}{/cal_cell_no_content}
        {cal_cell_no_content_today}{day}{/cal_cell_no_content_today}
        {cal_cell_blank}&nbsp;{/cal_cell_blank}
        {cal_cell_end}</td>{/cal_cell_end}
        {cal_row_end}</tr>{/cal_row_end}
        {table_close}</table>{/table_close}';
        
        $this->load->library('calendar', $prefs);
        
        $fecha_separada = $this->herramientas->Separa_fecha(mdate('%d/%m/%Y',$inicio_semana));

                    //Calcular los gastos cada día
        
        $this->load->helper('date');

        $dias_mes = days_in_month($fecha_separada['mes'],$fecha_separada['anno']);
        $dia_del_mes = mdate('%j',$inicio_semana);
        $inicio_mes = $inicio_semana - (3600*24*($dia_del_mes-1));
        
        $gastos_dias = array();
        $total_mes = 0;
        for ($i=1; $i <= $dias_mes; $i++){
            $gastos_dias[$i] = '-';
            
            if ($i < 10){
                $dia = '0'.$i;
            } else {
                $dia = $i;
            }

            $fecha_texto = $fecha_separada['anno'].'-'.$fecha_separada['mes'].'-'.$dia;
            
            $n_prereservacion = $this->Model_prereservaciones->buscar_prereservacion($idtrabajador, $fecha_texto,$sede,$turno);
            
            if (count($n_prereservacion) > 0){

                //$this->actualiza_prereservacion($idtrabajador, $fecha_texto,$sede,$turno);

                $gastos_dias[$i] = 'P';
            }                      
            
            $importe = $this->Model_trabajadores->importe_trabajador_fecha($fecha_texto, $idtrabajador,$sede,$turno);
            if($importe != ''){
                $gastos_dias[$i] = '$'.number_format($importe, 2);
            }
        }
        
        echo $this->calendar->generate($fecha_separada['anno'],$fecha_separada['mes'], $gastos_dias);
    }

    function gastos_mes($fecha,$idtrabajador,$sede){
        $this->load->library('Herramientas');
        $this->load->model('Model_trabajadores'); 
        
        $dia_mes = mdate('%j', $fecha);
        $dias_mes = mdate('%t', $fecha);
        
        $inicio_mes = human_to_unix(mdate('%Y-%m-%d', $fecha - (($dia_mes - 1) * 3600*24)).' 00:00 AM');
        $fin_mes = human_to_unix(mdate('%Y-%m-%d', $inicio_mes + (($dias_mes - 1) * 3600*24)).' 00:00 AM'); 

        $importe_t=0;
        for ($i=1; $i < 4 ; $i++) { 
            $importe_mes['saldo_'.$i] = $this->Model_trabajadores->importe_trabajador_fechas(mdate('%Y-%m-%d',$inicio_mes),mdate('%Y-%m-%d',$fin_mes), $idtrabajador,$sede,$i);
            $importe_t+=$importe_mes['saldo_'.$i];
            $importe_mes['saldo_r_'.$i] = number_format($importe_mes['saldo_'.$i], 2);            
        }
        //$importe_mes['saldo_t']=number_format($importe_t,2);
        echo json_encode($importe_mes);
    }
    
    function agregar_plato(){
        if (!$this->seguridad($_POST['trabajador_idtrabajador'])){
            die();
        }
        $this->load->model('Model_tickets');
        $this->load->model('Model_platos');
        $this->load->model('Model_trabajadores');
        $this->load->library('lib_logs');
        $turno_="";
        if($_POST['turno']==1){
            $turno_="desayuno";
        }else if($_POST['turno']==2){
            $turno_="almuerzo";
        }else{
            $turno_="comida";
        }
        $agregado=true;
                    //Logs
        $plato = $this->Model_platos->buscar_plato($_POST['plato_idplato']);
        
        
        if($this->session->userdata('autenticado_front')==true){
            $texto = 'Reservó "'.$plato[0]['nombreplato'].' $ '.$plato[0]['precio'].' para el '.$turno_.' del día '.$plato[0]["fecha"].'.';
            $this->lib_logs->registra_log(time(), $this->session->userdata('usuario_f'), $_SERVER['REMOTE_ADDR'], $texto, false);
        }
        
        if($this->session->userdata('autenticado')==true){
            $trabajador = $this->Model_trabajadores->buscar_trabajador($_POST['trabajador_idtrabajador']);
            $texto = 'Le reservó "'.$plato[0]['nombreplato'].' $ '.$plato[0]['precio'].' para el '.$turno_.' del día '.$plato[0]["fecha"].' al trabajador ('.$trabajador[0]['codigo'].' - '.$trabajador[0]['nombres'].' '.$trabajador[0]['apellidos'].').';
            $this->lib_logs->registra_log(time(), $this->session->userdata('usuario'), $_SERVER['REMOTE_ADDR'], $texto, true);
        }
        

        $compro = $this->Model_tickets->buscar_ticket($_POST['trabajador_idtrabajador'],$_POST['fecha'],$_POST['plato_idplato'], $_POST['sede_idsede'],$_POST['turno']);
        if ($compro == 0){
            $this->Model_tickets->insertar_ticket();
            $agregado=true;
        }else{
            $agregado=false;
        } 
        echo $agregado;
    }
    
    function actualiza_platos_lote(){
        if (!$this->seguridad($_POST['trabajador_idtrabajador'])){
            die();
        }
        $this->load->model('Model_tickets');
        $this->load->model('Model_platos');
        $this->load->model('Model_trabajadores');
        $this->load->library('lib_logs');
        
        $turno_="";
        if($_POST['turno']==1){
            $turno_="desayuno";
        }else if($_POST['turno']==2){
            $turno_="almuerzo";
        }else{
            $turno_="comida";
        }
        $salida=true;
        $arrPlatos = explode('|', $_POST['listaPlatos']);
        $selecionados = explode('|', $_POST['seleccionados']);
        for($i=0; $i < count($arrPlatos); $i++){
                        //Logs
            $_POST['plato_idplato'] = $arrPlatos[$i];
            
            if ($selecionados[$i] == 1){

                $plato = $this->Model_platos->buscar_plato($_POST['plato_idplato']);

                if($this->session->userdata('autenticado_front')==true){
                    $texto = 'Reservó "'.$plato[0]['nombreplato'].' $ '.$plato[0]['precio'].' para el '.$turno_.' del día '.$plato[0]["fecha"].'.';
                    $this->lib_logs->registra_log(time(), $this->session->userdata('usuario_f'), $_SERVER['REMOTE_ADDR'], $texto, false);
                }

                if($this->session->userdata('autenticado')==true){
                    $trabajador = $this->Model_trabajadores->buscar_trabajador($_POST['trabajador_idtrabajador']);
                    $texto = 'Le reservó "'.$plato[0]['nombreplato'].' $ '.$plato[0]['precio'].' para el '.$turno_.' del día '.$plato[0]["fecha"].' al trabajador ('.$trabajador[0]['codigo'].' - '.$trabajador[0]['nombres'].' '.$trabajador[0]['apellidos'].').';
                    $this->lib_logs->registra_log(time(), $this->session->userdata('usuario'), $_SERVER['REMOTE_ADDR'], $texto, true);
                }
                $_POST['fecha']=$plato[0]["fecha"];

                            //Si no existe crearlo
                $compro = $this->Model_tickets->buscar_ticket($_POST['trabajador_idtrabajador'], $_POST['fecha'],$_POST['plato_idplato'],$_POST['sede_idsede'], $_POST['turno']);
                
                if ($compro == 0){
                    $this->Model_tickets->insertar_ticket();
                    
                }
            } else {
                            //Logs
                $plato = $this->Model_platos->buscar_plato($_POST['plato_idplato']);

                if($this->session->userdata('autenticado_front')==true){
                    $texto = 'Canceló "'.$plato[0]['nombreplato'].' $ '.$plato[0]['precio'].' para el '.$turno_.' del día '.$plato[0]["fecha"].'.';
                    $this->lib_logs->registra_log(time(), $this->session->userdata('usuario_f'), $_SERVER['REMOTE_ADDR'], $texto, false);
                }

                if($this->session->userdata('autenticado')==true){
                    $trabajador = $this->Model_trabajadores->buscar_trabajador($_POST['trabajador_idtrabajador']);
                    $texto = 'Le canceló "'.$plato[0]['nombreplato'].' $ '.$plato[0]['precio'].' para el '.$turno_.' del día '.$plato[0]["fecha"].' al trabajador ('.$trabajador[0]['codigo'].' - '.$trabajador[0]['nombres'].' '.$trabajador[0]['apellidos'].').';
                    $this->lib_logs->registra_log(time(), $this->session->userdata('usuario'), $_SERVER['REMOTE_ADDR'], $texto, true);
                }
                $this->Model_tickets->eliminar_ticket();
                
            }
        }

    }
    
    function quitar_plato(){
        if (!$this->seguridad($_POST['trabajador_idtrabajador'])){
            die();
        }
        $this->load->model('Model_tickets');
        $this->load->model('Model_platos');
        $this->load->model('Model_trabajadores');
        $turno_="";
        if($_POST['turno']==1){
            $turno_="desayuno";
        }else if($_POST['turno']==2){
            $turno_="almuerzo";
        }else{
            $turno_="comida";
        }
                    //Logs
        $plato = $this->Model_platos->buscar_plato($_POST['plato_idplato']);
        $this->load->library('lib_logs');
        
        if($this->session->userdata('autenticado_front')==true){
            $texto = 'Canceló "'.$plato[0]['nombreplato'].' $ '.$plato[0]['precio'].' para el '.$turno_.' del día '.$plato[0]["fecha"].'.';
            $this->lib_logs->registra_log(time(), $this->session->userdata('usuario_f'), $_SERVER['REMOTE_ADDR'], $texto, false);
        }
        
        if($this->session->userdata('autenticado')==true){
            $trabajador = $this->Model_trabajadores->buscar_trabajador($_POST['trabajador_idtrabajador']);
            $texto = 'Le canceló "'.$plato[0]['nombreplato'].' $ '.$plato[0]['precio'].' para el '.$turno_.' del día '.$plato[0]["fecha"].' al trabajador ('.$trabajador[0]['codigo'].' - '.$trabajador[0]['nombres'].' '.$trabajador[0]['apellidos'].').';
            $this->lib_logs->registra_log(time(), $this->session->userdata('usuario'), $_SERVER['REMOTE_ADDR'], $texto, true);
        }
        $this->Model_tickets->eliminar_ticket();
    }
    
    /* funciones de reportes*/
    
    /*function listado_almuerzo(){
        $data['ruta_url'] = '../../../';
        $this->load->view('estadisticas/listado_main', $data);
    }*/

    function trabajador_reserva_turno_dia($idtrabajador,$dia,$mes,$anno,$sede,$turno){

        $fecha = $dia.'/'.$mes.'/'.$anno;
        
        $this->load->library('Herramientas');
        $this->load->model('Model_trabajadores');
        $this->load->model('Model_platos');
        $item_trabajador = array();
        $int_fecha = $this->herramientas->Cambia_Formato_Fecha_mysql($fecha);

        $item_trabajador['platos_dia'] = $this->Model_platos->lista_platos_fecha($int_fecha,$sede,$turno);   
        $item_trabajador['platos_selecc']=$this->Model_trabajadores->platos_trabajador_fecha($int_fecha,$idtrabajador,$sede,$turno);

        $importe = $this->Model_trabajadores->importe_trabajador_fecha($int_fecha,$idtrabajador,$sede,$turno);
        $item_trabajador['importe'] = '$ '.number_format($importe,2);  
        
        echo(json_encode($item_trabajador));
    }
    
    function listado_almuerzo_dia($dia,$mes,$anno){
        $fecha = $dia.'/'.$mes.'/'.$anno;

        $this->load->library('Herramientas');
        $this->load->model('Model_platos');
        $this->load->model('Model_tickets');
        $this->load->model('Model_trabajadores');

        $int_fecha = $this->herramientas->Cambia_Formato_Fecha_mysql($fecha);
        
        $platos_dia = $this->Model_platos->lista_platos_fecha($int_fecha);
        
        $idplatos = array();
        foreach ($platos_dia as $plato) {
            $idplatos[] = $plato['idplato'];
        }
        $data['idplatos'] = $idplatos;
        $trabajadores_dia = $this->Model_trabajadores->lista_trabajadores_fecha($int_fecha);
        
        $arreglo_salida = array();
        
        foreach ($trabajadores_dia as $trabajador){
            $item_trabajador = array();
            $item_trabajador['codigo'] = $trabajador['codigo'];
            $item_trabajador['nombres'] = $trabajador['nombres'];
            $item_trabajador['apellidos'] = $trabajador['apellidos'];
            $item_trabajador['platos'] = $this->Model_trabajadores->platos_trabajador_fecha($int_fecha, $trabajador['idtrabajador']);
            
            $importe = $this->Model_trabajadores->importe_trabajador_fecha($int_fecha, $trabajador['idtrabajador']);
            $i=0;
            $item_trabajador['importe'] = '$ '.number_format($importe,2);
            $arreglo_salida[] = $item_trabajador;
        }
        
        $data['platos_dias'] = $platos_dia;
        $data['arreglo_salida'] = $arreglo_salida;
        $this->load->view('estadisticas/listado_almuerzos', $data);
    }

    
    function listado_almuerzo_dia_pdf($dia,$mes,$anno,$sede,$turno){
        $this->load->library('html2pdf');
        
        $fecha = $dia.'/'.$mes.'/'.$anno;

        $this->load->library('Herramientas');
        $this->load->model('Model_platos');
        $this->load->model('Model_tickets');
        $this->load->model('Model_trabajadores');

        $int_fecha = $this->herramientas->Cambia_Formato_Fecha_mysql($fecha);
        
        $platos_dia = $this->Model_platos->lista_platos_fecha($int_fecha,$sede,$turno);
        
        $idplatos = array();
        foreach ($platos_dia as $plato) {
            $idplatos[] = $plato['idplato'];
        }
        $data['idplatos'] = $idplatos;
        $trabajadores_dia = $this->Model_trabajadores->lista_trabajadores_fecha($int_fecha,$sede,$turno);
        
        $arreglo_salida = array();
        
        foreach ($trabajadores_dia as $trabajador){
            $item_trabajador = array();
            $item_trabajador['codigo'] = $trabajador['codigo'];
            $item_trabajador['nombres'] = $trabajador['nombres'];
            $item_trabajador['apellidos'] = $trabajador['apellidos'];
            $item_trabajador['platos'] = $this->Model_trabajadores->platos_trabajador_fecha($int_fecha, $trabajador['idtrabajador'],$sede,$turno);
            
            $importe = $this->Model_trabajadores->importe_trabajador_fecha($int_fecha, $trabajador['idtrabajador'],$sede,$turno);
            $i=0;
            $item_trabajador['importe'] = '$ '.number_format($importe,2);
            $arreglo_salida[] = $item_trabajador;
        }
        $data['fecha'] = $dia.'/'.$mes.'/'.$anno;
        $data['platos_dias'] = $platos_dia;
        $data['arreglo_salida'] = $arreglo_salida;
        
        $this->html2pdf->folder("./reportes/Listado_Almuerzos/");            
        $this->createFolder("./reportes/");
        $this->createFolder("./reportes/Listado_Almuerzos/");
        $this->html2pdf->filename('listado_almuerzos'.'_'.$anno.$mes.$dia.'.pdf');
        $this->html2pdf->paper('letter', 'portrait');
        $this->html2pdf->html($this->load->view('estadisticas/listado_almuerzos_pdf', $data, true));

        if($this->html2pdf->create('save'))
        {
                          //  $this->show();
        }

        echo('reportes/Listado_Almuerzos/listado_almuerzos'.'_'.$anno.$mes.$dia.'.'.'pdf');   
        
    }
    
    function agregar_prereservacion(){
        if (!$this->seguridad($_POST['idtrabajador'])){
            die();
        }
        $this->load->model('Model_prereservaciones');
        $this->load->model('Model_trabajadores');
        
                    //Logs
        $this->load->library('lib_logs');
        $turno_="";
        if($_POST['turno']==1){
            $turno_="desayuno";
        }else if($_POST['turno']==2){
            $turno_="almuerzo";
        }else{
            $turno_="comida";
        }
        
        if($this->session->userdata('autenticado_front')==true){
            $texto = 'Prereservó para el '.$turno_.' del día '.$_POST["fecha"].'.';
            $this->lib_logs->registra_log(time(), $this->session->userdata('usuario_f'), $_SERVER['REMOTE_ADDR'], $texto, false);
        }
        
        if($this->session->userdata('autenticado')==true){
            $trabajador = $this->Model_trabajadores->buscar_trabajador($_POST['idtrabajador']);
            $texto = 'Le prereservó para el '.$turno_.'  del día '.$_POST["fecha"].' al trabajador ('.$trabajador[0]['codigo'].' - '.$trabajador[0]['nombres'].' '.$trabajador[0]['apellidos'].').';
            $this->lib_logs->registra_log(time(), $this->session->userdata('usuario'), $_SERVER['REMOTE_ADDR'], $texto, true);
        }
        
        $agregada=true;
        
        if(count($this->Model_prereservaciones->buscar_prereservacion_existente($_POST['idtrabajador'],$_POST['fecha'],$_POST['turno']))==0){
            $this->Model_prereservaciones->insertar_prereservacion_codigo($_POST['idtrabajador'],$_POST['fecha'],$_POST['sede_idsede'],$_POST['turno']); 
            $agregada=true;   
        }else{
            $agregada=false;  
        }
        echo $agregada;
    }

    function quitar_prereservacion(){
        if (!$this->seguridad($_POST['idtrabajador'])){
            die();
        }
        $this->load->model('Model_prereservaciones');
        $this->load->model('Model_trabajadores');
                    //Logs
        $this->load->library('lib_logs');
        
        $turno_="";
        if($_POST['turno']==1){
            $turno_="desayuno";
        }else if($_POST['turno']==2){
            $turno_="almuerzo";
        }else{
            $turno_="comida";
        }
        if($this->session->userdata('autenticado_front')==true){
            $texto = 'Canceló la prereservación para el '.$turno_.'  del día '.$_POST["fecha"].'.';
            $this->lib_logs->registra_log(time(), $this->session->userdata('usuario_f'), $_SERVER['REMOTE_ADDR'], $texto, false);
        }
        
        if($this->session->userdata('autenticado')==true){
            $trabajador = $this->Model_trabajadores->buscar_trabajador($_POST['idtrabajador']);
            $texto = 'Le canceló la prereservación para el '.$turno_.'  del día '.$_POST["fecha"].' al trabajador ('.$trabajador[0]['codigo'].' - '.$trabajador[0]['nombres'].' '.$trabajador[0]['apellidos'].').';
            $this->lib_logs->registra_log(time(), $this->session->userdata('usuario'), $_SERVER['REMOTE_ADDR'], $texto, true);
        }

        
        echo $_POST["fecha"];
        
        $this->Model_prereservaciones->eliminar_prereservacion_codigo($_POST['idtrabajador'],$_POST['fecha']);

    }
    
    function filtrar_trab($codigo){                                       
        $this->load->model('Model_trabajadores');                       
        $data['filtrar_trabajador'] = $this->Model_trabajadores->filtrar_trabajador($codigo);        
        echo json_encode($data);
    }             
}

?>
