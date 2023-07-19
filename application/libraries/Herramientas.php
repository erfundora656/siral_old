<?php if (!defined('BASEPATH')) exit('No permitir el acceso directo al script');

class Herramientas {

    function Cambia_Formato_Fecha($fecha){

                $fecha_ = new DateTime($fecha);
                return $fecha_->format('d/m/Y');

    }

    function Cambia_Formato_Fecha_mysql($fecha){
                $anno = substr($fecha, 6, 4);
                $mes = substr($fecha, 3, 2);
                $dia = substr($fecha, 0, 2);
                return $anno."-".$mes."-".$dia;
    }
    
    function Separa_fecha($fecha){
                $salida['anno'] = substr($fecha, 6, 4);
                $salida['mes'] = substr($fecha, 3, 2);
                $salida['dia'] = substr($fecha, 0, 2);
                return $salida;
    }
    
    function Fecha_entero($fecha, $inicio){
                $anno = substr($fecha, 6, 4);
                $mes = substr($fecha, 3, 2);
                $dia = substr($fecha, 0, 2);
                if ($inicio){
                    $fecha = time(0, 0, 0, $mes, $dia, $anno);                
                } else {
                    $fecha = time(23, 59, 59, $mes, $dia, $anno);                
                }    
                //$diferencia = HORA_SERV - local_to_gmt(HORA_SERV);
                
                return $fecha;
    }

    function Fecha_hora_entero($fecha, $hora, $minutos){
                $anno = substr($fecha, 6, 4);
                $mes = substr($fecha, 3, 2);
                $dia = substr($fecha, 0, 2);
                
                $fecha = time($hora, $minutos, 0, $mes, $dia, $anno);                

                return $fecha;
    }
    
    function lista_fechas_semanal($fecha, $hasta){
                
                $fecha_aux = $fecha;
                $fechas = array();                
                
                while($fecha_aux <= $hasta){
                    $fechas[] = $fecha_aux;
                    $fecha_aux = $fecha_aux + (3600*24*7);
                }
                
                return $fechas;
    }
    
    
    function lista_fechas_d($dia_i,$mes_i,$anno_i,$dia_f,$mes_f,$anno_f){

    $fecha_ini = new DateTime($anno_i.'-'.$mes_i.'-'.$dia_i);
    $fecha_fin = new DateTime($anno_f.'-'.$mes_f.'-'.$dia_f);

        
    $fechas = array();

    while ($fecha_ini <= $fecha_fin) {
        $fechas[] = $fecha_ini->format('d/m/Y');
        $fecha_ini->add(new DateInterval('P1D'));
    }

                return $fechas;
    }
    
    function lista_fechas_mensual($fecha, $hasta){
                
                $fecha_aux = $fecha;
                $fechas = array();                
                
                while($fecha_aux <= $hasta){

                    $fechas[] = $fecha_aux;
                    
                    $anno = date('Y', $fecha_aux);
                    $mes = date('n', $fecha_aux);
                    $dia = date('j', $fecha_aux);

                    $hora = date('h', $fecha_aux);
                    $minuto = date('i', $fecha_aux);
                    $segundo = date('s', $fecha_aux);

                    $mes++;

                    if($mes > 12){
                            $mes = 1;
                            $anno = $anno + 1;
                    }
                    
                    $fecha_aux = time($hora, $minuto, $segundo, $mes, $dia, $anno);
                    
                    
                }
                
                return $fechas;
    }
    
    function lista_fechas_semana_mes($fecha, $hasta){
            // Determinar que dia y semana es
            $dia = date('j', $fecha);

            $dia_semana = date('N', $fecha);
            $semana = floor(($dia-1) / 7) + 1;
            
            
            // Crear la lista

                $fecha_aux = $fecha;
                $fechas = array();                
                
                while($fecha_aux <= $hasta){

                    $fechas[] = $fecha_aux;
                    
                    $anno = date('Y', $fecha_aux);
                    $mes = date('n', $fecha_aux);
                    $dia = date('j', $fecha_aux);

                    $hora = date('h', $fecha_aux);
                    $minuto = date('i', $fecha_aux);
                    $segundo = date('s', $fecha_aux);

		// Buscar el proximo dia

                    $mes = $mes + 1;

                    if($mes > 12){
                            $mes = 1;
                            $anno = $anno + 1;
                    }

                    $inicio_mes = date('N', time($hora, $minuto, $segundo, $mes, 1, $anno));

                    if ($inicio_mes <= $dia_semana){		
                            $dia = ($semana -1)*7 + $dia_semana - ($inicio_mes -1);
                    } else {
                            $dia = ($semana*7) - ($inicio_mes -1) + $dia_semana;
                    }
                    
                    $fecha_aux = time($hora, $minuto, $segundo, $mes, $dia, $anno);
                    
                    
                }
                
                return $fechas;
            
    }
    
    function calcula_mascara($red){
	$cadena_bits='';
	for($i=0;$i<32;$i++){
		if ($i < $red){
			$cadena_bits .= '1';	
		}  else { 
			$cadena_bits .= '0';
		}	
	}
	// Obtener los obtetos
/*	
	$o_1 = substr($cadena_bits, 0, 8);
	$o_2 = substr($cadena_bits, 8, 8);
	$o_3 = substr($cadena_bits, 16, 8);
	$o_4 = substr($cadena_bits, 24, 8);
*/	
	
	$o_1 = bindec(substr($cadena_bits, 0, 8));
	$o_2 = bindec(substr($cadena_bits, 8, 8));
	$o_3 = bindec(substr($cadena_bits, 16, 8));
	$o_4 = bindec(substr($cadena_bits, 24, 8));

	return $o_1.'.'.$o_2.'.'.$o_3.'.'.$o_4;
    }
    
    
	function build_heap(&$array, $i, $t){
	  $tmp_var = $array[$i];    
	  $j = $i * 2 + 1;

	  while ($j <= $t)  {
	   if($j < $t)
	    if($array[$j] < $array[$j + 1]) {
	     $j = $j + 1; 
	    }
	   if($tmp_var < $array[$j]) {
	    $array[$i] = $array[$j];
	    $i = $j;
	    $j = 2 * $i + 1;
	   } else {
	    $j = $t + 1;
	   }
	  }
	  $array[$i] = $tmp_var;
	 }

	 function heap_sort(&$array) {
	  //This will heapify the array
	  $init = (int)floor((count($array) - 1) / 2);
	  // Thanks jimHuang for bug report
	  for($i=$init; $i >= 0; $i--){
	   $count = count($array) - 1;
	   $this->build_heap($array, $i, $count);
	  }

	  //swaping of nodes
	  for ($i = (count($array) - 1); $i >= 1; $i--)  {
	   $tmp_var = $array[0];
	   $array [0] = $array [$i];
	   $array [$i] = $tmp_var;
	   $this->build_heap($array, 0, $i - 1);
	  }
	 }

}

?>
