<?php



/*
 * Controladora para la gestion de los platos.
 */

class Estadisticas extends CI_Controller {

		/*
		Muestra el gestor de los platos
		*/
		function index()
		{

		}

    function delete_reportes(){
      $dirname ='./'.explode("/", $_POST['filename'])[0];
      $this->borrar_directorio($dirname);
    }

    private function borrar_directorio($dirname){     
     if (is_dir($dirname)){
       $dir_handle = opendir($dirname);
     
    while($file = readdir($dir_handle)) {
     if ($file != "." && $file != "..") {
      if (!is_dir($dirname."/".$file))
       unlink($dirname."/".$file);
     else 
      borrar_directorio($dirname.'/'.$file);
  }
}
closedir($dir_handle);
rmdir($dirname);
return true; 
}else{
  return false;
}
}

private function createFolder($carpeta)    {
  if(!is_dir($carpeta)){
   mkdir($carpeta, 0777);        
 }
}   

  private function actualiza_prereservacion_fecha($int_fecha,$sede,$turno){


    $this->load->model('Model_platos');
    $this->load->model('Model_tickets');
    $this->load->model('Model_prereservaciones');
    
    switch ($turno) {
      case '1':
      $lista_prereservaciones = $this->Model_prereservaciones->buscar_prereservacion_fecha($int_fecha,$sede,"1");
      if(count($lista_prereservaciones)>0){
        foreach($lista_prereservaciones as $n_prereservacion){

          $idtrabajador = $n_prereservacion['trabajador_idtrabajador'];

          $menus = $this->Model_platos->lista_platos_fecha($int_fecha,$sede,"1");
          foreach ($menus as $plato) {                        

            $compro = $this->Model_tickets->buscar_ticket($idtrabajador,$int_fecha,$plato['idplato'],$sede,"1");
            if ($compro== 0){
              $this->Model_tickets->insertar_ticket_codigo($idtrabajador,$plato['idplato'],$int_fecha,$sede,"1");
            } 
          }

          if(count($menus) > 0){
            $this->Model_prereservaciones->eliminar_prereservacion_codigo($idtrabajador, $int_fecha);
          } 
        }
      }   
      break;
      case '2':
      $lista_prereservaciones = $this->Model_prereservaciones->buscar_prereservacion_fecha($int_fecha,$sede,"2");
      if(count($lista_prereservaciones)>0){
        foreach($lista_prereservaciones as $n_prereservacion){

          $idtrabajador = $n_prereservacion['trabajador_idtrabajador'];

          $menus = $this->Model_platos->lista_platos_fecha($int_fecha,$sede,"2");
          foreach ($menus as $plato) {                        

            $compro = $this->Model_tickets->buscar_ticket($idtrabajador,$int_fecha ,$plato['idplato'],$sede,"2");
            if ($compro== 0){
              $this->Model_tickets->insertar_ticket_codigo($idtrabajador,$plato['idplato'],$int_fecha,$sede,"2");
            } 
          }

          if(count($menus) > 0){
            $this->Model_prereservaciones->eliminar_prereservacion_codigo($idtrabajador, $int_fecha);
          }  
        }   
      }
      break;
      case '3':
      $lista_prereservaciones = $this->Model_prereservaciones->buscar_prereservacion_fecha($int_fecha,$sede,"3");
      if(count($lista_prereservaciones)>0){
        foreach($lista_prereservaciones as $n_prereservacion){

          $idtrabajador = $n_prereservacion['trabajador_idtrabajador'];

          $menus = $this->Model_platos->lista_platos_fecha($int_fecha,$sede,"3");
          foreach ($menus as $plato) {                        

            $compro = $this->Model_tickets->buscar_ticket($idtrabajador,$int_fecha,$plato['idplato'],$sede,"3");
            if ($compro== 0){
              $this->Model_tickets->insertar_ticket_codigo($idtrabajador,$plato['idplato'],$int_fecha,$sede,"3");
            } 
          }

          if(count($menus) > 0){
            $this->Model_prereservaciones->eliminar_prereservacion_codigo($idtrabajador, $int_fecha);
          }    
        }
      }
      break;

      default:    
      break;
    }

  }

  private function actualiza_prereservacion_mes($idtrabajador, $mes, $anno){

    $this->load->model('Model_platos');
    $this->load->model('Model_tickets');
    $this->load->model('Model_prereservaciones');

    $this->load->helper('date');

    $dias_mes = days_in_month($mes,$anno);

    for ($i=1; $i <= $dias_mes; $i++){

      $int_fecha= $anno.'-'.$mes.'-'.$i;

      $n_prereservacion = $this->Model_prereservaciones->buscar_prereservacion_mes($idtrabajador, $int_fecha);

      if (count($n_prereservacion) > 0){

        $menus = $this->Model_platos->lista_platos_fecha_mes($int_fecha);
        foreach ($menus as $plato) {                        

          $compro = $this->Model_tickets->buscar_ticket($idtrabajador, $plato['idplato']);
          if ($compro == 0){
            $this->Model_tickets->insertar_ticket_codigo($idtrabajador,$plato['idplato']);
          } 
        }

        if(count($menus) > 0){
          $this->Model_prereservaciones->eliminar_prereservacion_codigo($idtrabajador, $int_fecha);
        }    

      }
    }

  }



  function reporte_ventas(){
    $this->config->load('reportescfg');
    $data['nombre_entidad'] = $this->config->item('nombre_entidad');
    $data['organismo'] = $this->config->item('organismo');
    $data['cargo_firma'] = $this->config->item('cargo_firma');
    $data['nombre_firma'] = $this->config->item('nombre_firma');
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
    $this->load->view('estadisticas/rep_ventas_main', $data);
  }

  private function calcula_ventas_dia($int_fecha,$sede){
    $this->load->library('Herramientas');
    $this->load->model('Model_platos');
    $this->load->model('Model_tickets');

    $this->load->model('Model_dirigidos');
    $this->load->model('Model_prereservaciones');
    $this->load->model('Model_trabajadores');
    


                    //Actualizar las prereservaciones Desayunos-Almuerzos-Comidas
    for ($i=1; $i < 4; $i++) { 
      $this->actualiza_prereservacion_fecha($int_fecha,$sede,""+$i);
      $data['cantidad_prereservaciones_'.$i] = count($this->Model_prereservaciones->buscar_prereservacion_fecha($int_fecha,$sede,$i));

      $platos_dia = $this->Model_platos->lista_platos_fecha($int_fecha,$sede,""+$i);
                    //Saber cuandos dirigidos hay reservados prara es día
      $cantidad_dirigidos = $this->Model_dirigidos->suma_dirigidos_fecha($int_fecha,$sede,$i);
      $data['cantidad_dirigidos'.$i] = $cantidad_dirigidos;

      $importe_total = 0;
      $datosplatos = array();
      $precio_ticket = 0;
      foreach ($platos_dia as $plato) {
        $elplato = array();
        $elplato['nombre'] = $plato['nombreplato'];
        $elplato['cantidad'] = $plato['cantidad'];
        $elplato['simbolo'] = $plato['simbolo'];
        $elplato['precio'] = $plato['precio'];
                        //Calcular cantidad 
        $elplato['cant_tickets'] = $this->Model_tickets->cantidad_ticket_plato($plato['idplato']);
        $elplato['importe'] = ($elplato['cant_tickets']) * $elplato['precio'];
        $importe_total += $elplato['importe'];
        $datosplatos[] = $elplato;
        $precio_ticket += $elplato['precio'];
      }

      $data['precio_ticket_'.$i] = $precio_ticket;
      $data['importe_total_'.$i] = $importe_total;
      $data['datos_platos_'.$i] = $datosplatos;

    }                   

    return $data;                    
  }

  function reporte_ventas_dia($dia,$mes,$anno,$sede){
    $fecha = $dia.'/'.$mes.'/'.$anno;
    $this->load->library('Herramientas');
    $this->config->load('ticketscfg');

    $int_fecha = $this->herramientas->Cambia_Formato_Fecha_mysql($fecha);

    $data = $this->calcula_ventas_dia($int_fecha,$sede);

    $this->load->view('estadisticas/rep_ventas_lista', $data);

  }

  function reporte_ventas_dia_pdf($dia,$mes,$anno,$sede){
    $this->load->library('html2pdf');
    $this->config->load('ticketscfg');

    $fecha = $dia.'/'.$mes.'/'.$anno;

    $this->load->library('Herramientas');

    $int_fecha = $this->herramientas->Cambia_Formato_Fecha_mysql($fecha);

    $data = $this->calcula_ventas_dia($int_fecha,$sede);
                    //$data['precio_alumnos'] = $this->config->item('precio_alumnos');

    $this->config->load('reportescfg');
    $data['nombre_entidad'] = $this->config->item('nombre_entidad');
    $data['organismo'] = $this->config->item('organismo');
    $data['cargo_firma'] = $this->config->item('cargo_firma');
    $data['nombre_firma'] = $this->config->item('nombre_firma');


    $data['fecha'] = $fecha;

    $this->html2pdf->folder("./reportes/Reportes_Ventas/");            
    $this->createFolder("./reportes/");
    $this->createFolder("./reportes/Reportes_Ventas/");
    $this->html2pdf->filename('reporte_ventas'.'_'.$anno.$mes.$dia.'.pdf');
    $this->html2pdf->paper('letter', 'portrait');
    $this->html2pdf->html($this->load->view('estadisticas/rep_ventas_lista_pdf', $data, true));

    if($this->html2pdf->create('save'))
    {
                          //  $this->show();
    }

    echo('reportes/Reportes_Ventas/reporte_ventas'.'_'.$anno.$mes.$dia.'.'.'pdf');   

  }

  function reporte_s_ventas($fecha,$sede_){
    $this->config->load('reportescfg');
    $this->config->load('ticketscfg');
    $data['nombre_entidad'] = $this->config->item('nombre_entidad');
    $data['organismo'] = $this->config->item('organismo');
    $data['cargo_firma'] = $this->config->item('cargo_firma');
    $data['nombre_firma'] = $this->config->item('nombre_firma');
    if($sede_==0){
      $sede=$this->config->item('sede_defecto');  
    }else{
      $sede=$sede_;
    } 


    if($fecha==0){
      $fecha = now();
    }
    $data['fecha'] = $fecha;

    $this->load->library('Herramientas');
    $this->load->model('Model_platos');
    $this->load->model('Model_tickets');

    $dias_semana = array('lunes','martes','miercoles','jueves','viernes','sabado','domingo');

    $dia_semana = mdate('%N', $fecha);

    $inicio_semana = human_to_unix(mdate('%Y-%m-%d',$fecha - (($dia_semana - 1) * 3600*24)).' 00:00 AM'); 

    $menus = array();
    $total_semana_d = 0;
    $total_semana_a = 0;
    $total_semana_c = 0;

    for($i=0; $i < count($dias_semana); $i++){
      $nombre_dia = $dias_semana[$i];    
      $int_fecha = mdate('%Y-%m-%d',$inicio_semana + 3600*24*($i));

      $menus[$nombre_dia] = $this->calcula_ventas_dia($int_fecha,$sede);
      if($menus[$nombre_dia]['precio_ticket_1'] > 0){
        $total_semana_d += $menus[$nombre_dia]['importe_total_1'] + ($menus[$nombre_dia]['cantidad_dirigidos1'] * $menus[$nombre_dia]['precio_ticket_1']);
      }
      if($menus[$nombre_dia]['precio_ticket_2'] > 0){
        $total_semana_a += $menus[$nombre_dia]['importe_total_2'] + ($menus[$nombre_dia]['cantidad_dirigidos2'] * $menus[$nombre_dia]['precio_ticket_2']);
      }
      if($menus[$nombre_dia]['precio_ticket_3'] > 0){
        $total_semana_c += $menus[$nombre_dia]['importe_total_3'] + ($menus[$nombre_dia]['cantidad_dirigidos3'] * $menus[$nombre_dia]['precio_ticket_3']);
      }
    }
    $data['menus'] = $menus;
    $data['inicio_semana'] = $inicio_semana;
    $data['total_semana_d'] = $total_semana_d;
    $data['total_semana_a'] = $total_semana_a;
    $data['total_semana_c'] = $total_semana_c;


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


    $data['ruta_url'] = '../../../';
    $this->load->view('estadisticas/rep_s_ventas_main', $data);
  }



  function reporte_s_ventas_pdf($fecha){

    $this->config->load('reportescfg');
    $this->config->load('ticketscfg');
    $data['nombre_entidad'] = $this->config->item('nombre_entidad');
    $data['organismo'] = $this->config->item('organismo');
    $data['cargo_firma'] = $this->config->item('cargo_firma');
    $data['nombre_firma'] = $this->config->item('nombre_firma');
    $data['precio_alumnos'] = $this->config->item('precio_alumnos');

    $this->load->library('html2pdf');
    if($fecha==0){
      $fecha = now();
    }
    $data['fecha'] = $fecha;

    $this->load->library('Herramientas');
    $this->load->model('Model_platos');
    $this->load->model('Model_tickets');

    $dias_semana = array('lunes','martes','miercoles','jueves','viernes','sabado','domingo');

    $dia_semana = mdate('%N', $fecha);

    $inicio_semana = human_to_unix(mdate('%Y-%m-%d',$fecha - (($dia_semana - 1) * 3600*24)).' 00:00 AM');

    $menus = array();
    $total_semana = 0;
    $total_semana_comida = 0;

    for($i=0; $i < count($dias_semana); $i++){

      $nombre_dia = $dias_semana[$i];    
      $int_fecha = mdate('%Y-%m-%d',$inicio_semana + 3600*24*($i));

      $menus[$nombre_dia] = $this->calcula_ventas_dia($int_fecha);

      if($menus[$nombre_dia]['precio_ticket'] > 0){
        $total_semana += $menus[$nombre_dia]['importe_total'] + ($menus[$nombre_dia]['cantidad_dirigidos'] * $menus[$nombre_dia]['precio_ticket']) + ($menus[$nombre_dia]['cantidad_dirigidos_e'] * $data['precio_alumnos']) + ($menus[$nombre_dia]['cantidad_est_a']['cant_ext'] * $data['precio_alumnos']);
      }
      if($menus[$nombre_dia]['precio_ticket_comida'] > 0){
        $total_semana_comida += $menus[$nombre_dia]['importe_total_comida'] + ($menus[$nombre_dia]['cantidad_dirigido_comidas'] * $menus[$nombre_dia]['precio_ticket_comida']) + ($menus[$nombre_dia]['cantidad_dirigido_comidas_e'] * $data['precio_alumnos'])  + $menus[$nombre_dia]['cantidad_est_c']['cant_ext'] * $data['precio_alumnos'];
      }

    }
    $data['menus'] = $menus;
    $data['inicio_semana'] = $inicio_semana;
    $data['total_semana'] = $total_semana;
    $data['total_semana_comida'] = $total_semana_comida;

    $this->html2pdf->folder("./reportes/Reportes_Semanal_Ventas/");            
    $this->createFolder("./reportes/");
    $this->createFolder("./reportes/Reportes_Semanal_Ventas/");
    $this->html2pdf->filename('reporte_s_ventas'.'_'.$inicio_semana.'.pdf');
    $this->html2pdf->paper('letter', 'portrait');
    $this->html2pdf->html($this->load->view('estadisticas/rep_s_ventas_main_pdf', $data, true));

    if($this->html2pdf->create('save'))
    {
                          //  $this->show();
    }

    echo('reportes/Reportes_Semanal_Ventas/reporte_s_ventas'.'_'.$inicio_semana.'.'.'pdf');   
  }

  /*function listado_comidas(){
    $this->config->load('reportescfg');
    $data['nombre_entidad'] = $this->config->item('nombre_entidad');
    $data['organismo'] = $this->config->item('organismo');
    $data['cargo_firma'] = $this->config->item('cargo_firma');
    $data['nombre_firma'] = $this->config->item('nombre_firma');
    $data['tipo_usuario'] = 'Trabajadores';

    $this->config->load('ticketscfg');
    $data['menu_platos'] = $this->config->item('menu_platos');
    $data['forma_pago'] = $this->config->item('forma_pago_trab');

    $data['ruta_url'] = '../../../';
    $data['tipo'] = 'comidas';
    $this->load->view('estadisticas/listados_main', $data);
  }*/

  function listado_turnos($turno){
    $this->config->load('reportescfg');
    $data['nombre_entidad'] = $this->config->item('nombre_entidad');
    $data['organismo'] = $this->config->item('organismo');
    $data['cargo_firma'] = $this->config->item('cargo_firma');
    $data['nombre_firma'] = $this->config->item('nombre_firma');

    $this->config->load('ticketscfg');
    $data['menu_platos'] = $this->config->item('menu_platos');
    $data['forma_pago'] = $this->config->item('forma_pago_trab');
    $data['tipo_usuario'] = 'Trabajadores';
    $data['ruta_url'] = '../../../';
    $data['tipo'] = $turno;
    $data['rol']=$this->session->userdata('rol');

    $this->load->model('Model_sedes');   
    if($this->session->userdata('rol') == 5 || $this->session->userdata('rol') == 0){ 
      $data['listasedes']="";                  
      $nombre_sede=$this->Model_sedes->buscar_sede($this->session->userdata('sede_idsede'))[0]['nombre'];
      $id_sede=$this->Model_sedes->buscar_sede($this->session->userdata('sede_idsede'))[0]['idsede'];
      $data['sede_pertenece'] =$nombre_sede;
      $data['id_sede']=$id_sede;
    }else{                               
      $data['listasedes'] = $this->Model_sedes->lista_sedes();
    }    


    $this->load->view('estadisticas/listados_main', $data);
  }

  function chequear_listado(){
    $this->config->load('templates');
    $data['templates'] = $this->config->item('t_templates');
    $this->config->load('reportescfg');
    $data['nombre_entidad'] = $this->config->item('nombre_entidad');
    $data['organismo'] = $this->config->item('organismo');
    $data['cargo_firma'] = $this->config->item('cargo_firma');
    $data['nombre_firma'] = $this->config->item('nombre_firma');

    $this->config->load('ticketscfg');
    $data['menu_platos'] = $this->config->item('menu_platos');
    $data['forma_pago'] = $this->config->item('forma_pago_trab');
    $data['tipo_usuario'] = 'Trabajadores';
    $data['ruta_url'] = '../../../';
    $data['tipo'] = 1;                 
    $data['rol'] =$this->session->userdata('rol');

    $this->load->model('Model_sedes');   
    /*Controlamos que los administradores solo generen menus en sus sedes*/
    if($this->session->userdata('rol') == 2 || $this->session->userdata('rol') == 0){ 
      $data['listasedes']="";               
      $nombre_sede=$this->Model_sedes->buscar_sede($this->session->userdata('sede_idsede'))[0]['nombre'];
      $id_sede=$this->Model_sedes->buscar_sede($this->session->userdata('sede_idsede'))[0]['idsede'];
      $data['sede_pertenece'] =$nombre_sede;
      $data['id_sede']=$id_sede;
    }else{                               
      $data['listasedes'] = $this->Model_sedes->lista_sedes();
    }
    $this->load->view('estadisticas/chequear_listado', $data);
  }

  function confirmar_chequeo(){                      
    $this->load->model('Model_tickets');
    $this->load->model('Model_trabajadores');                      
    $this->load->library('Herramientas');
    
    $fecha = $this->herramientas->Cambia_Formato_Fecha_mysql($_POST['fecha']);
    echo $fecha;
    $idtrabajador=$_POST['codigo_trabajador'];
    $sede=$_POST['sede_idsede'];
    $turno=$_POST['turno'];
    $conf=$_POST['conf'];                   

    $this->Model_tickets->actualizar_chequeo($idtrabajador,$fecha,$sede,$turno,$conf);

  }

  function confirmar_chequeo_todos(){                      
    $this->load->model('Model_tickets');
    $this->load->model('Model_trabajadores');                      
    $this->load->library('Herramientas');
    $_POST['fecha']= $this->herramientas->Cambia_Formato_Fecha_mysql($_POST['fecha']);

    $sede=$_POST['sede_idsede'];
    $turno=$_POST['turno'];
    $conf=$_POST['conf'];


    $this->Model_tickets->actualizar_chequeo_todo();

  }


  function menu_almuerzos_dia($dia,$mes,$anno,$sede,$turno){
    $fecha = $dia.'/'.$mes.'/'.$anno;

    $this->load->library('Herramientas');
    $this->load->model('Model_platos');

    $int_fecha = $this->herramientas->Cambia_Formato_Fecha_mysql($fecha);
    
    $plato_almuerzos_dia = $this->Model_platos->lista_platos_fecha($int_fecha,$sede,$turno);
    
    echo(json_encode($plato_almuerzos_dia));
    
  }

  function tiket_trabajador_dia($idtrabajador,$dia,$mes,$anno,$sede,$turno){
    $fecha = $dia.'/'.$mes.'/'.$anno;

    $this->load->library('Herramientas');
    $this->load->model('Model_platos');
    $this->load->model('Model_tickets');

    $int_fecha = $this->herramientas->Cambia_Formato_Fecha_mysql($fecha);
    
    $tiket = $this->Model_tickets->busca_tickets($idtrabajador,$int_fecha,$sede,$turno);
    
    echo(json_encode($tiket));
    
  }

  function trabajadores_almuerzos_dia($dia,$mes,$anno,$sede,$turno){
    $fecha = $dia.'/'.$mes.'/'.$anno;

    $this->load->library('Herramientas');
    $this->load->model('Model_trabajadores');

    $int_fecha = $this->herramientas->Cambia_Formato_Fecha_mysql($fecha);

    $this->actualiza_prereservacion_fecha($int_fecha,$sede,$turno);
    
    $trabajadores_dia = $this->Model_trabajadores->lista_trabajadores_fecha($int_fecha,$sede,$turno);
    $arrSalida = $trabajadores_dia;                    
    echo(json_encode($arrSalida));                    
  }

  function buscar_trabajador_turno($dia,$mes,$anno,$sede,$turno,$codigo){
    $fecha = $dia.'/'.$mes.'/'.$anno;

    $this->load->library('Herramientas');
    $this->load->model('Model_trabajadores');

    $int_fecha = $this->herramientas->Cambia_Formato_Fecha_mysql($fecha);

    $this->actualiza_prereservacion_fecha($int_fecha,$sede,$turno);
    
    $trabajador = $this->Model_trabajadores->buscar_trabajador_fecha($int_fecha,$sede,$turno,$codigo);
    $arrSalida = $trabajador;                    
    echo(json_encode($arrSalida));                    
  }




  function trabajadores_plato_almuerzos($idtrabajador,$dia,$mes,$anno,$ids,$sede,$turno){
    $this->load->model('Model_tickets');
    $this->load->library('Herramientas');
    $fecha = $dia.'/'.$mes.'/'.$anno;
    $int_fecha = $this->herramientas->Cambia_Formato_Fecha_mysql($fecha);
    $arrPlatos = explode('-', $ids);   
    $arrSalida = array();

    for($i=0; $i <count($arrPlatos); $i++){

      $compro = $this->Model_tickets->buscar_ticket_selecc($idtrabajador,$int_fecha,$arrPlatos[$i],$sede,$turno);
      
      if (count($compro) > 0){
        $arrSalida[$i]['compro'] = true;
        if($compro[0]['chequeo']==1){
          $arrSalida[$i]['chequeo'] = true;
        }else{
          $arrSalida[$i]['chequeo'] = false;
        }
      } else {
        $arrSalida[$i]['compro'] = false;
      }    
    }    
    
    echo(json_encode($arrSalida));  
  }

  function listado_mensual($mes,$anno){
    $this->config->load('reportescfg');
    $data['nombre_entidad'] = $this->config->item('nombre_entidad');
    $data['organismo'] = $this->config->item('organismo');
    $data['cargo_firma'] = $this->config->item('cargo_firma');
    $data['nombre_firma'] = $this->config->item('nombre_firma');
    $data['tipo_usuario'] = 'Trabajadores';
    
    $arrMes = array('Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'); 
    if($mes == 0 || $anno == 0){
                        //Calcular el mes actual
      $fecha = now();
      $mes = mdate('%n', $fecha);
      $anno = mdate('%Y', $fecha);
    }
    $data['ruta_url'] = '../../../';
    $data['anno'] = $anno;
    $data['nombre_mes'] = $arrMes[$mes - 1];
    $data['mes'] = $mes;
    $this->load->view('estadisticas/listado_m_main', $data);
  }


  function listado_rango_fechas($dia_i,$mes_i,$anno_i,$dia_f,$mes_f,$anno_f,$panel=0){
    $this->config->load('reportescfg');
    $data['nombre_entidad'] = $this->config->item('nombre_entidad');
    $data['organismo'] = $this->config->item('organismo');
    $data['cargo_firma'] = $this->config->item('cargo_firma');
    $data['nombre_firma'] = $this->config->item('nombre_firma');
    $data['tipo_usuario'] = 'Trabajadores';
    
    $data['panel'] = $panel;
    
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
                        //$dia_f = date('j', $fecha);
      $dia_f = 25;
      $mes_f = mdate('%n', $fecha);
      $anno_f = mdate('%Y', $fecha);
    }
    $data['ruta_url'] = '../../../';
    
    $data['dia_i'] = $dia_i;
    $data['mes_i'] = $mes_i;
    $data['anno_i'] = $anno_i;

    $data['dia_f'] = $dia_f;
    $data['mes_f'] = $mes_f;
    $data['anno_f'] = $anno_f;

    $this->load->view('estadisticas/listado_r_f_main', $data);
  }

  function balance($mes,$anno,$sede_){
    $this->load->library('Herramientas');
    $this->config->load('reportescfg');
    $this->load->model('Model_trabajadores');                    
    $this->load->model('Model_prereservaciones');
    $this->load->model('Model_dirigidos');
    $this->load->model('Model_platos');
    
    $this->config->load('ticketscfg');
    $this->load->model('Model_sedes');     


    
    if($sede_==0 || $sede_== -1 ){

      $this->load->model('Model_sedes');   
      /*Controlamos que los administradores solo generen menus en sus sedes*/
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
    $this->config->load('ticketscfg');                   
    
    $data['nombre_entidad'] = $this->config->item('nombre_entidad');
    $data['organismo'] = $this->config->item('organismo');
    $data['cargo_firma'] = $this->config->item('cargo_firma');
    $data['nombre_firma'] = $this->config->item('nombre_firma');
    
    $arrMes = array('Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'); 
    if($mes == 0 || $anno == 0){
                        //Calcular el mes actual
      $fecha = now();
      $mes = mdate('%n', $fecha);
      $anno = mdate('%Y', $fecha);
    }

    $data['ruta_url'] = '../../../';
    $data['anno'] = $anno;
    $data['nombre_mes'] = $arrMes[$mes - 1];
    $data['mes'] = $mes;
    
                    //Información a enviar

    if ($mes < 10){
      $str_mes = '0'.$mes;
    } else {
      $str_mes = $mes;
    }
    
    $inicio_mes = human_to_unix($anno.'-'.$mes.'-01 00:00 AM');
    
    $this->load->helper('date');

    $dias_mes = days_in_month($mes,$anno);
    $dia_del_mes = mdate('%j',$inicio_mes);
    /**************************************************************************************************/
    for ($a=1; $a < 4; $a++) {

      for ($i=0; $i < $dias_mes; $i++){
        $ent_fecha = $inicio_mes + (($i)*3600*24);
        $this->actualiza_prereservacion_fecha(mdate('%Y-%m-%d',$ent_fecha),$sede,$a);                      
      }

      $gastos_dias = array();
      $comenzales_dias = array();
      $total_mes = 0;
      $total_comenzales = 0;

      for ($i=0; $i < $dias_mes; $i++){

        $ent_fecha = $inicio_mes + (($i)*3600*24);


        $importe = 0;

        $importe += $this->Model_trabajadores->importe_fecha(mdate('%Y-%m-%d',$ent_fecha),$a);
        if ($importe == '') $importe = 0;
        $dirigidos = $this->Model_dirigidos->suma_dirigidos_fecha(mdate('%Y-%m-%d',$ent_fecha),$sede,$a);
        if($dirigidos == '') $dirigidos = 0;
        $precio_fecha = $this->Model_platos->precio_platos_fecha(mdate('%Y-%m-%d',$ent_fecha),$sede,$a);

        $importe += $precio_fecha * $dirigidos;

        $gastos_dias[$i] = "['".mdate('%Y-%n-%j',$ent_fecha)." 12:00AM',".$importe."]";
        $total_mes += $importe;
        $comenzales = $this->Model_trabajadores->cantidad_trabajadores_fecha(mdate('%Y-%m-%d',$ent_fecha),$sede,$a);
        $total_comenzales += $comenzales + $dirigidos;
        $comenzales_dias[$i] = "['".mdate('%Y-%n-%j',$ent_fecha)." 12:00AM',".$comenzales."]";
        $dirigidos_dias[$i] = "['".mdate('%Y-%n-%j',$ent_fecha)." 12:00AM',".$dirigidos."]";

      }

      $data['total_mes'.$a] = $total_mes;
      $data['total_comenzales'.$a] = $total_comenzales;
      $data['gastos_dias'.$a] = $gastos_dias;
      $data['comenzales_dias'.$a] =$comenzales_dias;
      $data['dirigidos_dias'.$a] =$dirigidos_dias;


      $listas_platos = $this->Model_platos->lista_d_platos_fechas(mdate('%Y-%m-%d',$inicio_mes + (3600*24)),mdate('%Y-%m-%d',$inicio_mes + ($dias_mes*3600*24)),$sede,$a);
      for ($i=0; $i < count($listas_platos); $i++){
        $listas_platos[$i]['cantidad'] = $this->Model_platos->cantidad_plato_fechas($listas_platos[$i]['nombre'],mdate('%Y-%m-%d',$inicio_mes + (3600*24)),mdate('%Y-%m-%d',$inicio_mes + ($dias_mes*3600*24)),$sede,$a);
      } 

      for ($i=0; $i < count($listas_platos); $i++){
        for ($j = $i + 1; $j < count($listas_platos) - 1; $j++){
          if ($listas_platos[$i]['cantidad'] < $listas_platos[$j]['cantidad']){
            $platoaux = $listas_platos[$i];
            $listas_platos[$i] = $listas_platos[$j];
            $listas_platos[$j] = $platoaux;
          }
        }
      }    

      $data['listas_platos_'.$a] = $listas_platos;
    }

    /**************************************************************************************/
    
    $this->load->view('estadisticas/balance_main', $data);
  }


  function lista_trabajadores(){
    $this->load->model('Model_trabajadores');
    $trabajadores_dia = $this->Model_trabajadores->lista_trabajadores_o_c();
    $arrSalida = $trabajadores_dia;
    echo(json_encode($arrSalida));
  }


  function importe_trabajador($mes,$anno,$idtrabajador){
    $this->load->model('Model_trabajadores');
    $this->load->library('Herramientas');
    
    if ($mes < 10){
      $str_mes = '0'.$mes;
    } else {
      $str_mes = $mes;
    }
    
    $inicio_mes = human_to_unix($anno.'-'.$mes.'-01 00:00 AM');
                    //$this->herramientas->Fecha_entero('01/'.$str_mes.'/'.$anno,true);

    $dias_mes = mdate('%t', $inicio_mes);
    
    $fin_mes = human_to_unix($anno.'-'.$mes.'-'.$dias_mes.' 00:00 AM'); 

    //$this->actualiza_prereservacion_mes($idtrabajador, $mes, $anno);


    $importe_d = $this->Model_trabajadores->importe_trabajador_fechas_mes(mdate('%Y-%m-%d',$inicio_mes),mdate('%Y-%m-%d',$fin_mes), $idtrabajador,"1");
    if($importe_d == '') $importe_d = 0;
    $importe_a = $this->Model_trabajadores->importe_trabajador_fechas_mes(mdate('%Y-%m-%d',$inicio_mes),mdate('%Y-%m-%d',$fin_mes), $idtrabajador,"2");
    if($importe_a == '') $importe_a = 0;
    $importe_c = $this->Model_trabajadores->importe_trabajador_fechas_mes(mdate('%Y-%m-%d',$inicio_mes),mdate('%Y-%m-%d',$fin_mes), $idtrabajador,"3");
    if($importe_c == '') $importe_c = 0;

    $arrSalida['desayunos'] = $importe_d;
    $arrSalida['almuerzos'] = $importe_a;
    $arrSalida['comidas'] = $importe_c;
    $arrSalida['importe'] = $importe_d + $importe_a + $importe_c;
    echo(json_encode($arrSalida));
  }


  function importe_trabajador_fechas($dia_i,$mes_i,$anno_i,$dia_f,$mes_f,$anno_f,$idtrabajador){
    $this->load->model('Model_trabajadores');
    $this->load->library('Herramientas');

    $arrSalida['fecha_ini'] = $anno_i.'-'.$mes_i.'-'.$dia_i;
    $arrSalida['fecha_fin'] = $anno_f.'-'.$mes_f.'-'.$dia_f;

    $sede=
    $importe_d = $this->Model_trabajadores->importe_trabajador_fechas_mes($anno_i.'-'.$mes_i.'-'.$dia_i,$anno_f.'-'.$mes_f.'-'.$dia_f, $idtrabajador,"1");
    if($importe_d == '') $importe_d = 0;
    $importe_a = $this->Model_trabajadores->importe_trabajador_fechas_mes($anno_i.'-'.$mes_i.'-'.$dia_i,$anno_f.'-'.$mes_f.'-'.$dia_f, $idtrabajador,"2");
    if($importe_a == '') $importe_a = 0;
    $importe_c = $this->Model_trabajadores->importe_trabajador_fechas_mes($anno_i.'-'.$mes_i.'-'.$dia_i,$anno_f.'-'.$mes_f.'-'.$dia_f, $idtrabajador,"3");
    if($importe_c == '') $importe_c = 0;

    $arrSalida['desayunos'] = $importe_d;
    $arrSalida['almuerzos'] = $importe_a;
    $arrSalida['comidas'] = $importe_c;
    $arrSalida['importe'] =  $importe_d + $importe_a + $importe_c;
    echo(json_encode($arrSalida));
  }


  function listado_mensual_pdf($mes, $anno){
    $this->load->library('html2pdf');
    $this->config->load('reportescfg');
    $data['nombre_entidad'] = $this->config->item('nombre_entidad');
    $data['organismo'] = $this->config->item('organismo');
    $data['cargo_firma'] = $this->config->item('cargo_firma');
    $data['nombre_firma'] = $this->config->item('nombre_firma');

    $arrMes = array('Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'); 
    $data['anno'] = $anno;
    $data['nombre_mes'] = $arrMes[$mes - 1];
    $data['mes'] = $mes;


    $data['cantidad'] = $_POST['cantidad'];
    $data['arrDatos'] = $_POST;

    $this->html2pdf->folder("./reportes/");            
    $this->createFolder("./reportes/");
    //$this->createFolder("./reportes/Reportes_Mensuales/");
    $this->html2pdf->filename('reporte_mensual'.'_'.$anno.'_'.$mes.'.pdf');
    $this->html2pdf->paper('letter', 'portrait');
    $this->html2pdf->html($this->load->view('estadisticas/listado_m_main_pdf', $data, true));

    if($this->html2pdf->create('save'))
    {
                            //$this->show();
    }

    echo('reportes/reporte_mensual'.'_'.$anno.'_'.$mes.'.'.'pdf');   

  }

  function listado_mensual_csv($mes, $anno){
    $this->config->load('reportescfg');
    $data['nombre_entidad'] = $this->config->item('nombre_entidad');
    $data['organismo'] = $this->config->item('organismo');
    $data['cargo_firma'] = $this->config->item('cargo_firma');
    $data['nombre_firma'] = $this->config->item('nombre_firma');

    $arrMes = array('Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'); 
    $data['anno'] = $anno;
    $data['nombre_mes'] = $arrMes[$mes - 1];
    $data['mes'] = $mes;

                // Escribir archivo


    $this->load->helper('file');

    $cantidad = $_POST['cantidad'];
    $data = "codigo,nombre_apellidos,desayunos,almuerzos,comidas,importe
    ";       

    for ($i=0;$i < $cantidad; $i++){
      $arrValores = explode('|', $_POST['linea'.$i]);
      $data .= $arrValores[0].",".$arrValores[1].",".$arrValores[2].",".$arrValores[3].",".$arrValores[4].",".$arrValores[5]."
      ";       

    }        

    $this->createFolder("./reportes/");
    //$this->createFolder("./reportes/Reportes_Mensuales/");
    if ( ! write_file('reportes/reporte_mensual'.'_'.$anno.'_'.$mes.'.'.'csv', $data))
    {
      echo 'No se puede escribir el archivo'; 
    }
    else
    {
      echo('reportes/reporte_mensual'.'_'.$anno.'_'.$mes.'.'.'csv');   

    }
  }

  function listado_mensual_asset($mes, $anno) {
    $arrMes = array('Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre');
    $data['anno'] = $anno;
    $data['nombre_mes'] = $arrMes[$mes - 1];
    $data['mes'] = $mes;

                    // Escribir archivo   

    $this->load->helper('file');

    $cantidad = $_POST['cantidad'];
    
    $dataSheet=array();    
    array_push($dataSheet, array('Id_Expediente','Importe'));

    for ($i=0;$i < $cantidad; $i++){
      $arrValores = explode('|', $_POST['linea'.$i]);
      array_push($dataSheet, array($arrValores[0],$arrValores[5]));     
    }

    $xlsx = Shuchkin\SimpleXLSXGen::fromArray($dataSheet);
    $xlsx_content = (string) $xlsx;
    $this->createFolder("./reportes/");
    if ( ! write_file('reportes/reporte_comedor'.'_'.$anno.'_'.$mes.'.'.'xlsx', $xlsx_content))
    {      
      echo 'No se puede escribir el archivo'; 
    }
    else
    {
      echo('reportes/reporte_comedor'.'_'.$anno.'_'.$mes.'.'.'xlsx'); 
    }  

  }

  function listado_mensual_json($mes, $anno){
    $this->config->load('reportescfg');
    $data['nombre_entidad'] = $this->config->item('nombre_entidad');
    $data['organismo'] = $this->config->item('organismo');
    $data['cargo_firma'] = $this->config->item('cargo_firma');
    $data['nombre_firma'] = $this->config->item('nombre_firma');

    $arrMes = array('Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'); 
    $data['anno'] = $anno;
    $data['nombre_mes'] = $arrMes[$mes - 1];
    $data['mes'] = $mes;

                // Escribir archivo


    $this->load->helper('file');

    $cantidad = $_POST['cantidad'];
    $data['trabajadores'] = array();
    for ($i=0;$i < $cantidad; $i++){
      $arrValores = explode('|', $_POST['linea'.$i]);
      $trabajador = array();
      $trabajador['codigo'] = $arrValores[0];
      $trabajador['nombre_apellidos'] = $arrValores[1];
      $trabajador['desayunos'] = $arrValores[2];
      $trabajador['almuerzos'] = $arrValores[3];
      $trabajador['comidas'] = $arrValores[4];
      $trabajador['importe'] = $arrValores[5];
      $data['trabajadores'][] = $trabajador;
    }  

    $datos = json_encode($data);


    if ( ! write_file('reportes/reporte_mensual'.'_'.$anno.'_'.$mes.'.'.'json', $datos))
    {
      echo 'No se puede escribir el archivo'; 
    }
    else
    {
      echo('reportes/reporte_mensual'.'_'.$anno.'_'.$mes.'.'.'json');   

    }
  }

  function listado_rango_fechas_pdf($dia_i,$mes_i,$anno_i,$dia_f,$mes_f,$anno_f){
    $this->load->library('html2pdf');
    $this->config->load('reportescfg');
    $data['nombre_entidad'] = $this->config->item('nombre_entidad');
    $data['organismo'] = $this->config->item('organismo');
    $data['cargo_firma'] = $this->config->item('cargo_firma');
    $data['nombre_firma'] = $this->config->item('nombre_firma');

    $data['dia_i'] = $dia_i;
    $data['mes_i'] = $mes_i;
    $data['anno_i'] = $anno_i;

    $data['dia_f'] = $dia_f;
    $data['mes_f'] = $mes_f;
    $data['anno_f'] = $anno_f;

    $data['cantidad'] = $_POST['cantidad'];
    $data['arrDatos'] = $_POST;

    $this->html2pdf->folder("./reportes/");            
    $this->createFolder("./reportes/");
   // $this->createFolder("./reportes/Reportes_Mensuales/");
    $this->html2pdf->filename('reporte_rango_fechas'.'_'.$anno_i.$mes_i.$dia_i.'_'.$anno_f.$mes_f.$dia_f.'.'.'pdf');
    $this->html2pdf->paper('letter', 'portrait');
    $this->html2pdf->html($this->load->view('estadisticas/listado_r_f_main_pdf', $data, true));

    if($this->html2pdf->create('save'))
    {
                          //  $this->show();
    }

    echo('reportes/reporte_rango_fechas'.'_'.$anno_i.$mes_i.$dia_i.'_'.$anno_f.$mes_f.$dia_f.'.'.'pdf');   

  }

  function listado_rango_fechas_csv($dia_i,$mes_i,$anno_i,$dia_f,$mes_f,$anno_f){
    $this->config->load('reportescfg');
    $data['nombre_entidad'] = $this->config->item('nombre_entidad');
    $data['organismo'] = $this->config->item('organismo');
    $data['cargo_firma'] = $this->config->item('cargo_firma');
    $data['nombre_firma'] = $this->config->item('nombre_firma');

    $data['dia_i'] = $dia_i;
    $data['mes_i'] = $mes_i;
    $data['anno_i'] = $anno_i;

    $data['dia_f'] = $dia_f;
    $data['mes_f'] = $mes_f;
    $data['anno_f'] = $anno_f;

                // Escribir archivo


    $this->load->helper('file');

    $cantidad = $_POST['cantidad'];
    $data = "codigo,nombre_apellidos,desayunos,almuerzos,comidas,importe
    ";       

    for ($i=0;$i < $cantidad; $i++){
      $arrValores = explode('|', $_POST['linea'.$i]);
      $data .= $arrValores[0].",".$arrValores[1].",".$arrValores[2].",".$arrValores[3].",".$arrValores[4].",".$arrValores[5]."
      ";       

    }  
    $this->createFolder("./reportes/");
    if ( ! write_file('reportes/reporte_rango_fechas'.'_'.$anno_i.$mes_i.$dia_i.'_'.$anno_f.$mes_f.$dia_f.'.'.'csv', $data))
    {
      echo 'No se puede escribir el archivo'; 
    }
    else
    {
      echo('reportes/reporte_rango_fechas'.'_'.$anno_i.$mes_i.$dia_i.'_'.$anno_f.$mes_f.$dia_f.'.'.'csv');   

    }
  }

  function listado_rango_fechas_json($dia_i,$mes_i,$anno_i,$dia_f,$mes_f,$anno_f){
    $this->config->load('reportescfg');
    $data['nombre_entidad'] = $this->config->item('nombre_entidad');
    $data['organismo'] = $this->config->item('organismo');
    $data['cargo_firma'] = $this->config->item('cargo_firma');
    $data['nombre_firma'] = $this->config->item('nombre_firma');

    $data['dia_i'] = $dia_i;
    $data['mes_i'] = $mes_i;
    $data['anno_i'] = $anno_i;

    $data['dia_f'] = $dia_f;
    $data['mes_f'] = $mes_f;
    $data['anno_f'] = $anno_f;

                // Escribir archivo


    $this->load->helper('file');

    $cantidad = $_POST['cantidad'];
    $data['trabajadores'] = array();
    for ($i=0;$i < $cantidad; $i++){
      $arrValores = explode('|', $_POST['linea'.$i]);
      $trabajador = array();
      $trabajador['codigo'] = $arrValores[0];
      $trabajador['nombre_apellidos'] = $arrValores[1];
      $trabajador['desayunos'] = $arrValores[2];
      $trabajador['almuerzos'] = $arrValores[3];
      $trabajador['comidas'] = $arrValores[4];
      $trabajador['importe'] = $arrValores[5];
      $data['trabajadores'][] = $trabajador;
    }  

    $datos = json_encode($data);
    $this->createFolder("./reportes/");
    if ( ! write_file('reportes/reporte_rango_fechas'.'_'.$anno_i.$mes_i.$dia_i.'_'.$anno_f.$mes_f.$dia_f.'.'.'json', $datos))
    {
      echo 'No se puede escribir el archivo'; 
    }
    else
    {
      echo('reportes/reporte_rango_fechas'.'_'.$anno_i.$mes_i.$dia_i.'_'.$anno_f.$mes_f.$dia_f.'.'.'json');   

    }
  }

  function lista_tickets_dir($dia,$mes,$anno,$sede,$turno){
    $fecha = $anno.'-'.$mes.'-'.$dia;

    $this->load->library('Herramientas');
    $this->load->model('Model_dirigidos');
    $this->load->model('Model_platos');
    $this->config->load('ticketscfg');                    

    $dirigidos_dia = $this->Model_dirigidos->lista_dirigidos_fecha($fecha,$sede,$turno);
    $data['precio'] = $this->Model_platos->precio_platos_fecha($fecha,$sede,$turno);
    $data['dirigidos_dia'] = $dirigidos_dia;
    $data['fecha'] = $fecha;
    $data['turno']=$turno;
    $data['sede']=$sede;
    $this->load->view('estadisticas/dirigidos_dia', $data);

  }



}

?>
