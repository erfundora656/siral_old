<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Logs extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
            $data['ruta_url'] = '../../../';
            $this->load->view('logs/main',$data);
	}
        
        function lista_trazas(){
          $data['ruta_url'] = '../../../';
          //Obtener la lista de archivos en la carpeta CSeguridad
          $this->load->helper('file');

          //$data['archivos'] = directory_map('./logs/');
          
          $data['archivos'] = get_dir_file_info('logs/');

          //print_r($data['archivos'] );
          $this->load->view('configuracion/lista_trazas', $data);
        }

        function fecha_trazas_json(){
          $this->load->helper('file');
	  $this->load->library('Herramientas');

          //$data['archivos'] = directory_map('./logs/');
          
          $listaarchivos = get_dir_file_info('logs/');

          //print_r($data['archivos'] );
          
          //Procesar
          $arrSalida = array();
	  
	  $listanombres = array();

	  foreach ($listaarchivos as $archivo){
		$listanombres[] = $archivo['name'];
          }
          
	  $this->herramientas->heap_sort($listanombres);
          
          if(count($listanombres) > 0){
          
              $log = explode('.', $listanombres[0]);
              $arrNombre = explode('_', $log[0]); 
              $fecha_ini = $arrNombre[2].'/'.$arrNombre[1].'/'.$arrNombre[0];          

              $log = explode('.', $listanombres[count($listanombres)-1]);
              $arrNombre = explode('_', $log[0]); 
              $fecha_fin = $arrNombre[2].'/'.$arrNombre[1].'/'.$arrNombre[0];

              $arrSalida = array();
              $arrSalida['fecha_ini'] = $fecha_ini;
              $arrSalida['fecha_fin'] = $fecha_fin;

          } else {
              $arrSalida = array();
              $arrSalida['fecha_ini'] = NULL;
              $arrSalida['fecha_fin'] = NULL;
          }    
          echo(json_encode($arrSalida));
          
        }
        
        function lista_trazas_json(){
          $data['ruta_url'] = '../../../';
          //Obtener la lista de archivos en la carpeta CSeguridad
          $this->load->helper('file');
	  $this->load->library('Herramientas');

          //$data['archivos'] = directory_map('./logs/');
          
          $listaarchivos = get_dir_file_info('logs/');

          //print_r($data['archivos'] );
          
          //Procesar
          $arrSalida = array();
	  
	  $listanombres = array();

	  foreach ($listaarchivos as $archivo){
		$listanombres[] = $archivo['name'];
          }
          
		$this->herramientas->heap_sort($listanombres);

            foreach ($listanombres as $nombre) {
                $nombrearchivo = explode('.', $nombre);

                if ($nombrearchivo[1] == 'log'){
                    $arrDatos = array();
                    $arrNombre = explode('_', $nombrearchivo[0]); 

                    $arrDatos['fecha'] = $arrNombre[2].'/'.$arrNombre[1].'/'.$arrNombre[0];

                    if($arrNombre[3]=='admin'){
                        $arrDatos['admin'] = 1;
                    } else {
                        $arrDatos['admin'] = 0;
                    }
                }
                $arrSalida[] = $arrDatos;
            }
          
          echo(json_encode($arrSalida));
        }
        
        
        function analiza_traza($dia,$mes,$anno,$admin = 0){
            $this->load->helper('file');
            if($admin == 1){
                $nombrearchivo='./logs/'.$anno.'_'.$mes.'_'.$dia.'_admin.log';
            } else {
                $nombrearchivo='./logs/'.$anno.'_'.$mes.'_'.$dia.'_front.log';
            }
            $string = read_file($nombrearchivo);
//            $arrstring = explode('\n', $string);
            $arrstring = preg_split('/\n/', $string);
            if($arrstring[count($arrstring)-1] == '') array_pop($arrstring);
            //print_r($arrstring);
/*            
            foreach ($arrstring as $log) {
                echo($log.'<br />');
            }
 * 
 */
            $data['logs'] = $arrstring;
            $this->load->view('configuracion/analiza_traza', $data);
        }
        
        function analiza_traza_json($dia,$mes,$anno,$admin = 0,$usuario = '_todos',$ip = '_todos'){
            $this->load->helper('file');
            if($admin == 1){
                $nombrearchivo='./logs/'.$anno.'_'.$mes.'_'.$dia.'_admin.log';
            } else {
                $nombrearchivo='./logs/'.$anno.'_'.$mes.'_'.$dia.'_front.log';
            }
            $string = read_file($nombrearchivo);
//            $arrstring = explode('\n', $string);
            $arrstring = preg_split('/\n/', $string);
            if($arrstring[count($arrstring)-1] == '') array_pop($arrstring);
            //print_r($arrstring);
/*            
            foreach ($arrstring as $log) {
                echo($log.'<br />');
            }
 * 
 */
            $logs = $arrstring;
            $arrSalida = array();
            
            foreach ($logs as $log) {
                $arrLog = explode('|', $log);
                if((strpos($arrLog[2], $usuario) !== FALSE  || $usuario == '_todos') && (strpos($arrLog[1], $ip) !== false || $ip == '_todos')){
                    $item = array();
                    $fecha_hora = explode('_', $arrLog[0]); 
                    $item['fecha'] = $fecha_hora[2].'/'.$fecha_hora[1].'/'.$fecha_hora[0];
                    $item['hora'] = $fecha_hora[3].':'.$fecha_hora[4].':'.$fecha_hora[5];
                    $item['IP'] = $arrLog[1];
                    $item['usuario'] = $arrLog[2];
                    $item['accion'] = $arrLog[3];
                    $arrSalida[] = $item;
                }    
            }
            
            echo(json_encode($arrSalida));
        }

        function analiza_traza_f_json($dia_ini,$mes_ini,$anno_ini,$dia_fin,$mes_fin,$anno_fin,$admin = 0,$usuario = '_todos',$ip = '_todos'){
            $this->load->helper('file');
            
            $this->load->library('Herramientas');
            
            
            $fecha_entero_ini = $this->herramientas->Fecha_entero($dia_ini.'-'.$mes_ini.'-'.$anno_ini, true);
            $fecha_entero_fin = $this->herramientas->Fecha_entero($dia_fin.'-'.$mes_fin.'-'.$anno_fin, true);

            $arrSalida = array();
            
            while ($fecha_entero_ini <= $fecha_entero_fin) {
                
                $arrFecha = $this->herramientas->Separa_fecha(date('d-m-Y',$fecha_entero_ini));
                
                $anno = $arrFecha['anno'];
                $mes = $arrFecha['mes'];
                $dia = $arrFecha['dia'];

                if($admin == 1){
                    $nombrearchivo='./logs/'.$anno.'_'.$mes.'_'.$dia.'_admin.log';
                } else {
                    $nombrearchivo='./logs/'.$anno.'_'.$mes.'_'.$dia.'_front.log';
                }
                
                if(file_exists($nombrearchivo)){

                    $string = read_file($nombrearchivo);
        //            $arrstring = explode('\n', $string);
                    $arrstring = preg_split('/\n/', $string);
                    if($arrstring[count($arrstring)-1] == '') array_pop($arrstring);
                    //print_r($arrstring);
        /*            
                    foreach ($arrstring as $log) {
                        echo($log.'<br />');
                    }
         * 
         */
                    $logs = $arrstring;

                    foreach ($logs as $log) {
                        $arrLog = explode('|', $log);
                        if((strpos($arrLog[2], $usuario) !== FALSE  || $usuario == '_todos') && (strpos($arrLog[1], $ip) !== false || $ip == '_todos')){
                            $item = array();
                            $fecha_hora = explode('_', $arrLog[0]); 
                            $item['fecha'] = $fecha_hora[2].'/'.$fecha_hora[1].'/'.$fecha_hora[0];
                            $item['hora'] = $fecha_hora[3].':'.$fecha_hora[4].':'.$fecha_hora[5];
                            $item['IP'] = $arrLog[1];
                            $item['usuario'] = $arrLog[2];
                            $item['accion'] = $arrLog[3];
                            $arrSalida[] = $item;
                        }    
                    }
                }
                $fecha_entero_ini += 3600*24;
                
            }
            
            echo(json_encode($arrSalida));
        }
        
        
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
