<?php

// Modelo de la tabla subitems

class Model_Dirigidos extends CI_Model {

    var $fecha = "";
    var $cantidad = "";
    var $detalles = "";
    var $sede_idsede = "";
    var $turno = "";
    
    function __construct(){
    // Llamar al constructor de CI_Model
        parent::__construct();
    }
    
    /**
     * OK
     * */
    function insertar_dirigido(){
        $this->fecha = $_POST["fecha"];
        $this->cantidad = $_POST["cantidad"];
        $this->detalles = $_POST["detalles"];
        $this->sede_idsede = $_POST["sede_idsede"];
        $this->turno = $_POST["turno"];

        $this->db->insert('dirigido', $this);
    }

    /**
     * OK
     * */
    function actualizar_dirigido(){

        $data = array('cantidad' => $_POST["cantidad"], 'detalles' => $_POST["detalles"],'sede_idsede' => $_POST["sede_idsede"],'turno' => $_POST["turno"]);
        $condicion = "iddirigido = ".$_POST["iddirigido"]." ";       

        $str = $this->db->update_string('dirigido', $data, $condicion);
        $this->db->query($str);

    }
    
    /**
     * OK
     * */
    function eliminar_dirigido(){

        $this->db->delete('dirigido', 'iddirigido = '.$_POST['iddirigido']);
    }

    function eliminar_dirigido_fechas($fecha_ini,$fecha_fin){

        $this->db->delete('dirigido', 'fecha >= "'.$fecha_ini.'" and fecha <= "'.$fecha_fin. '" ');
    }

    function buscar_dirigido($iddirigido){

        $cadenaselect = '*';
        
        $this->db->select($cadenaselect);
        $this->db->from('dirigido');
        $this->db->where('iddirigido', $iddirigido);
        
        $query = $this->db->get();
        return $query->result_array();

    }

   function lista_dirigidos_fecha($fecha,$sede,$turno){

        $cadenaselect = '*';
        
        $this->db->select($cadenaselect);
        $this->db->from('dirigido');
        $this->db->where('fecha', $fecha);
        $this->db->where('sede_idsede', $sede);
        $this->db->where('turno', $turno);
        
        $query = $this->db->get();
        return $query->result_array();

    }

    function suma_dirigidos_fecha($fecha,$sede,$turno){
      $cadenaselect = 'cantidad';

      $this->db->select_sum($cadenaselect);
      $this->db->from('dirigido');
      $this->db->where('fecha', $fecha);
      $this->db->where('sede_idsede', $sede);
      $this->db->where('turno', $turno);


      $query = $this->db->get();
      $cantidad = $query->result_array();
      if ($cantidad[0]['cantidad'] == ''){
        $salida = 0;
    } else {
        $salida = $cantidad[0]['cantidad'];;
    }
    return $salida;

}


function lista_dirigidos_rango_filtro($rp, $posicion, $sortname, $sortorder,$query,$qtype, $fecha,$sede,$turno){

    $cadenaselect = '*';

    $this->db->select($cadenaselect);
    $this->db->from('dirigido');
    $this->db->where('fecha', $fecha);
    $this->db->where('sede_idsede', $sede);
    $this->db->where('turno', $turno);


    $this->db->like($qtype, $query);
    $query = $this->db->order_by($sortname, $sortorder);
    $query = $this->db->get();
    $salida['rows'] = $query->result_array();
    $salida['count'] = $query->num_rows();
    return $salida;
}

function lista_dirigidos_rango($rp, $posicion, $sortname, $sortorder, $fecha,$sede,$turno){

    $cadenaselect = '*';

    $this->db->select($cadenaselect);
    $this->db->from('dirigido');
    $this->db->where('fecha', $fecha);
    $this->db->where('sede_idsede', $sede);
    $this->db->where('turno', $turno);

    $query = $this->db->order_by($sortname, $sortorder);
    $query = $this->db->get();
    $salida['rows'] = $query->result_array();
    $salida['count'] = $query->num_rows();
    return $salida;

}

function lista_dirigidos(){

    $query = $this->db->get('dirigido');
    return $query->result_array();
}

function lista_dias(){
    $cadenaselect = 'fecha';
    $this->db->select($cadenaselect);
    $this->db->distinct();
    $this->db->from('dirigido');
    $query = $this->db->get();
    return $query->result_array();
}

/*function insertar_dirigido_codigo($fecha,$cantidad,$detalles){

        $this->fecha = $fecha;
        $this->cantidad = $cantidad;
        $this->detalles = $detalles;

        $this->db->insert('dirigido', $this);
    }  
    function actualizar_fecha_dirigido($iddirigido,$fecha){

        $data = array('fecha' => $fecha);
        $condicion = "iddirigido = ".$iddirigido." ";
        $str = $this->db->update_string('dirigido', $data, $condicion);

        $this->db->query($str);
    }*/

}
?>
