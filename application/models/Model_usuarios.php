<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Model_usuarios extends CI_Model {

    var $nombres = "";
    var $apellidos = "";    
    var $rol = "";
    var $usuario = "";
    var $sede_idsede="";

    function __construct(){
    // Llamar al constructor de CI_Model
        parent::__construct();
    }

    function insertar_usuario(){

        $this->nombres = $_POST["nombres"];
        $this->apellidos = $_POST["apellidos"];
        $this->rol = $_POST["rol"];
        $this->usuario = $_POST["usuario"];        
        $this->sede_idsede = $_POST["sede_idsede"];

        $this->db->insert('usuario', $this);
    }

    function actualizar_usuario(){

        $data = array('nombres' => $_POST["nombres"], 'apellidos' => $_POST["apellidos"], 'rol' => $_POST["rol"], 'usuario' => $_POST["usuario"], 'sede_idsede' => $_POST["sede_idsede"]);
        $condicion = "idusuario = ".$_POST['idusuario']." ";
        $str = $this->db->update_string('usuario', $data, $condicion);

        $this->db->query($str);
        
    }

    function eliminar_usuario(){
      
        $this->db->delete('usuario', 'idusuario = '.$_POST['idusuario'].' and rol != 3');
    }

    function cantidad_usuarios(){

    $query = $this->db->get('usuario');
    return $query->num_rows();

    }

    function buscar_usuario($idusuario){

        $query = $this->db->get_where('usuario', array('idusuario' => $idusuario));
    return $query->result_array();

    }

    function lista_usuarios(){

        $query = $this->db->get('usuario');
    return $query->result_array();
    }


    function lista_usuarios_rango($cantidad, $posicion, $campo_orden, $direccion_orden){

        $query = $this->db->order_by($campo_orden, $direccion_orden);
        $query = $this->db->get('usuario',$cantidad, $posicion);
    return $query->result_array();
    }

    function lista_usuarios_rango_filtro($cantidad, $posicion, $campo_orden, $direccion_orden, $texto_filtrar, $campo_filtrar){

        $query = $this->db->like($campo_filtrar, $texto_filtrar);
        $query = $this->db->order_by($campo_orden, $direccion_orden);
        $query = $this->db->get('usuario',$cantidad, $posicion);
    return $query->result_array();

    }

    function cantidad_usuarios_filtro($texto_filtrar, $campo_filtrar){

        $query = $this->db->like($campo_filtrar, $texto_filtrar);
    $query = $this->db->get('usuario');
    return $query->num_rows();

    }

    function existe_usuario($usuario){
        $this->db->select('*');
        $this->db->from('usuario');
        $this->db->where('usuario',$usuario);
        $query = $this->db->get();
        
        return $query->result_array();
       
    }

    function autenticar($usuario){
        
        $this->db->select('*');
        $this->db->from('usuario');
        $this->db->where('usuario',$usuario);        
        $query = $this->db->get();
        
        return $query->result_array();
    }

/*************REVISADO************************/
}

?>
