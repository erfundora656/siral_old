<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Model_sedes extends CI_Model {

    var $nombre = "";
    var $activa = "";
    
    function __construct(){
    // Llamar al constructor de CI_Model
        parent::__construct();
    }

    function insertar_sede(){

        $this->nombre = $_POST["nombre"];
        $this->activa = $_POST["activa"];        

        $this->db->insert('sede', $this);
    }

    function actualizar_sede(){

        $this->nombre = $_POST["nombre"];
        $this->activa = $_POST["activa"];        

        $this->db->update('sede', $this, 'idsede = '.$_POST['idsede']);        
        
    }

    function eliminar_sede(){

        $this->db->delete('sede', 'idsede = '.$_POST['idsede']);
    }

    function buscar_sede($idsede){

        $query = $this->db->get_where('sede', array('idsede' => $idsede));
	return $query->result_array();

    }

    function lista_sedes(){

        $query = $this->db->get('sede');
	return $query->result_array();
    }

    function lista_sedes_a(){

        $this->db->where('activa',1);
        $query = $this->db->get('sede');
	return $query->result_array();
    }
    
    function lista_sedes_filtro($texto_filtrar, $campo_filtrar){

        $query = $this->db->like($campo_filtrar, $texto_filtrar);
        $query = $this->db->get('sede');
	return $query->result_array();

    }
    
    function lista_sedes_rango($cantidad, $posicion, $campo_orden, $direccion_orden){

        $query = $this->db->order_by($campo_orden, $direccion_orden);
        $query = $this->db->get('sede',$cantidad, $posicion);
	return $query->result_array();
    }

    function lista_sedes_rango_filtro($cantidad, $posicion, $campo_orden, $direccion_orden, $texto_filtrar, $campo_filtrar){

        $query = $this->db->like($campo_filtrar, $texto_filtrar);
        $query = $this->db->order_by($campo_orden, $direccion_orden);
        $query = $this->db->get('sede',$cantidad, $posicion);
	return $query->result_array();

    }

    function cantidad_sedes(){

	$query = $this->db->get('sede');
	return $query->num_rows();

    }

    function cantidad_sedes_filtro($texto_filtrar, $campo_filtrar){

        $query = $this->db->like($campo_filtrar, $texto_filtrar);
	$query = $this->db->get('sede');
	return $query->num_rows();

    }
    
    function activar_sede(){
        $data = array('activa' => '1');
        $condicion = "idsede = ".$_POST['idsede']." ";
        $str = $this->db->update_string('sede', $data, $condicion);

        $this->db->query($str);
        
    }

    function desactivar_sede(){
        $data = array('activa' => '0');
        $condicion = "idsede = ".$_POST['idsede']." ";
        $str = $this->db->update_string('sede', $data, $condicion);

        $this->db->query($str);
        
    }

}

?>
