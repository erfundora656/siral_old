<?php

// Modelo de la tabla subitems

class Model_Tipocantidad extends CI_Model {

    var $idtipocantidad = "";
    var $nombre = "";
    var $simbolo = "";
        
    function __construct(){
    // Llamar al constructor de CI_Model
        parent::__construct();
    }

    function buscar_tipocantidad($idtipocantidad){

        $cadenaselect = 'idtipocantidad, nombre, simbolo ';
                
        $this->db->select($cadenaselect);
        $this->db->from('tipocantidad');
        $this->db->where('idtipocantidad', $idtipocantidad);
        
        $query = $this->db->get();
    return $query->result_array();

    }

    function lista_tipocantidad(){
        $query = $this->db->get('tipocantidad');
    return $query->result_array();
    }
    
    /*function insert_tipocantidad(){

        $this->nombre = $_POST["nombre"];
        $this->simbolo = $_POST["simbolo"];

        $this->db->insert('tipocantidad', $this);
    }  
    
    function actualizar_tipocantidad(){

        $this->nombre = $_POST["nombre"];
        $this->simbolo = $_POST["simbolo"];

        $this->db->update('tipocantidad', $this, 'idtipocantidad = '.$_POST['idtipocantidad']);
    }
    
    function eliminar_tipocantidad(){

        $this->db->delete('tipocantidad', 'idtipocantidad = '.$_POST['idtipocantidad']);
    }
    function lista_tipocantidad_rango_filtro($rp, $posicion, $sortname, $sortorder,$query,$qtype){

        $cadenaselect = 'idtipocantidad, nombre, simbolo ';
                
        $this->db->select($cadenaselect);
        $this->db->from('tipocantidad');
        $this->db->like($qtype, $query);
        $query = $this->db->order_by($sortname, $sortorder);
        $query = $this->db->get();
	$salida['rows'] = $query->result_array();
	$salida['count'] = $query->num_rows();
        return $salida;
    }
    
    function lista_tipocantidad_rango($rp, $posicion, $sortname, $sortorder){

        $cadenaselect = 'idtipocantidad, nombre, simbolo ';
                
        $this->db->select($cadenaselect);
        $this->db->from('tipocantidad');
        $this->db->where('fecha', $fecha);
        $query = $this->db->order_by($sortname, $sortorder);
        $query = $this->db->get();
	$salida['rows'] = $query->result_array();
	$salida['count'] = $query->num_rows();
        return $salida;

    }*/
 }
?>
