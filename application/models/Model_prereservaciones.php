<?php

// Modelo de la tabla subitems

class Model_prereservaciones extends CI_Model {

    var $trabajador_idtrabajador = "";
    var $fecha = "";
    var $sede_idsede = "";
    var $turno = "";
        
    function __construct(){
    // Llamar al constructor de CI_Model
        parent::__construct();
    }
    
    function insertar_prereservacion(){

        $this->trabajador_idtrabajador = $_POST["trabajador_idtrabajador"];
        $this->fecha = $_POST["fecha"];

        $this->db->insert('prereservacion', $this);
    }  
    
    function insertar_prereservacion_codigo($idtrabajador,$fecha,$sede,$turno){

        $this->trabajador_idtrabajador = $idtrabajador;
        $this->fecha = $fecha;
        $this->sede_idsede = $sede;
        $this->turno = $turno;

        $this->db->insert('prereservacion', $this);
    }  

    function eliminar_prereservacion(){

        $this->db->delete('prereservacion', 'idprereservacion = "'.$_POST['idprereservacion']. '" ');
    }
    
    function eliminar_prereservacion_codigo($idtrabajador,$fecha){

        $this->db->delete('prereservacion', 'trabajador_idtrabajador = '.$idtrabajador.' and fecha = "'.$fecha. '" ');
    }

    function eliminar_prereservacion_fechas($fecha_ini,$fecha_fin){

        $this->db->delete('prereservacion', 'fecha >= "'.$fecha_ini.'" and fecha <= "'.$fecha_fin. '" ');
    }
    
    function buscar_prereservacion_fecha($fecha,$sede,$turno){

        $cadenaselect = '*';
                
        $this->db->select($cadenaselect);
        $this->db->from('prereservacion');
        $this->db->where('fecha', $fecha);
        $this->db->where('sede_idsede', $sede);
        $this->db->where('turno', $turno);
        
        $query = $this->db->get();
	return $query->result_array();

    }
     function existe_prereservacion($trabajador_idtrabajador, $fecha,$sede,$turno){
        $lista_prereservaciones=buscar_prereservacion($trabajador_idtrabajador, $fecha,$sede,$turno);
        if(count($lista_prereservaciones) > 0)
            return true; 
        else
            return false;
    }
    
    function buscar_prereservacion($trabajador_idtrabajador, $fecha,$sede,$turno){

        $cadenaselect = '*';
                
        $this->db->select($cadenaselect);
        $this->db->from('prereservacion');
        $this->db->where('trabajador_idtrabajador', $trabajador_idtrabajador);
        $this->db->where('fecha', $fecha);
        $this->db->where('sede_idsede', $sede);
        $this->db->where('turno', $turno);
        
        $query = $this->db->get();          
	return $query->result_array();

    }
    function buscar_prereservacion_existente($trabajador_idtrabajador, $fecha,$turno){

         $cadenaselect = '*';
                
        $this->db->select($cadenaselect);
        $this->db->from('prereservacion');
        $this->db->where('trabajador_idtrabajador', $trabajador_idtrabajador);
        $this->db->where('fecha', $fecha);
        //$this->db->where('sede_idsede', $sede);
        $this->db->where('turno', $turno);
        
        $query = $this->db->get();
    return $query->result_array();

    }
    function buscar_prereservacion_mes($trabajador_idtrabajador, $fecha){

        $cadenaselect = '*';
                
        $this->db->select($cadenaselect);
        $this->db->from('prereservacion');
        $this->db->where('trabajador_idtrabajador', $trabajador_idtrabajador);
        $this->db->where('fecha', $fecha);        
        
        $query = $this->db->get();
    return $query->result_array();

    }

    function fechas_prereservacion($trabajador_idtrabajador,$turno){

        $cadenaselect = 'fecha';
                
        $this->db->select($cadenaselect);
        $this->db->distinct();
        $this->db->from('prereservacion');
        $this->db->where('trabajador_idtrabajador', $trabajador_idtrabajador);
        $this->db->where('turno', $turno);
        
        $query = $this->db->get();
	return $query->result_array();

    }

    function mover_prereservaciones($idtrabajador_origen,$idtrabajador_destino){
        
        $data = array('trabajador_idtrabajador' => $idtrabajador_destino);
        $condicion = "trabajador_idtrabajador = ".$idtrabajador_origen." ";
        $str = $this->db->update_string('prereservacion', $data, $condicion);

        $this->db->query($str);
        
    }
    
     /*function eliminar_prereservacion_desde($idtrabajador,$fecha){

        $this->db->delete('prereservacion', 'trabajador_idtrabajador = '.$idtrabajador. ' and fecha > "'.$fecha. '" ');
    }*/
 }
?>
