<?php

// Modelo de la tabla subitems

class Model_Platos extends CI_Model {

    var $nombre = "";
    var $cantidad = "";
    var $tipocantidad_idtipocantidad = "";
    var $precio = "";
    var $fecha = "";
    var $sede_idsede="";  
    var $turno="";  
     

    function __construct(){
    // Llamar al constructor de CI_Model
        parent::__construct();
    }
    
    function insertar_plato(){

        $this->nombre = $_POST["nombre"];
        $this->cantidad = $_POST["cantidad"];
        $this->tipocantidad_idtipocantidad = $_POST["tipocantidad_idtipocantidad"];
        $this->precio = $_POST["precio"];
        $this->fecha = $_POST["fecha"];
        /*
        Agregamos el id de la sede a la que responde el plato para establecer la relación.
        */
        $this->sede_idsede= $_POST["sede_idsede"];
        $this->turno=$_POST["turno"];        
        $this->db->insert('plato', $this);        
        return $this->db->insert_id();
    }  
    
    function actualizar_plato(){

        $this->nombre = $_POST["nombre"];
        $this->cantidad = $_POST["cantidad"];
        $this->tipocantidad_idtipocantidad = $_POST["tipocantidad_idtipocantidad"];
        $this->precio = $_POST["precio"];
        $this->fecha = $_POST["fecha"];
        /*
        Manejamos el id y elturno de la sede para su actualización ya que este no es modificable desde la vista mostrada al usuario.
        */
        $this->sede_idsede= $_POST["sede_idsede"];
        $this->turno= $_POST["turno"];

        $this->db->update('plato', $this, 'idplato = '.$_POST['idplato']);
    }

    function actualizar_precios_platos($platos_mod){
    
    for ($i=0; $i < count($platos_mod); $i+=2) { 
              $data = array('idplato' => $platos_mod[$i],'precio' => $platos_mod[$i+1]);   
              $condicion = "idplato = ".$platos_mod[$i]." ";   
              $str = $this->db->update_string('plato', $data, $condicion);
        $this->db->query($str);           
           } 
}

function eliminar_plato(){

    $this->db->delete('plato', 'idplato = '.$_POST['idplato']);

}

function buscar_plato($idplato){
    /*Agragamos el sede_idsede presente en la tabla para obtener el resultado en la consulta*/
    $cadenaselect = 'idplato, plato.nombre as nombreplato, precio, cantidad, tipocantidad.nombre as nombrecatidad, simbolo, fecha, tipocantidad_idtipocantidad,sede_idsede,turno';

    $this->db->select($cadenaselect);
    $this->db->from('plato');
    $this->db->join('tipocantidad', 'tipocantidad.idtipocantidad = plato.tipocantidad_idtipocantidad');       
    $this->db->where('idplato', $idplato);

    $query = $this->db->get();
    return $query->result_array();

}

function lista_platos_fecha($fecha,$sede,$turno){      

    $cadenaselect = 'idplato, plato.nombre as nombreplato, precio, cantidad, tipocantidad.nombre as nombrecantidad, simbolo,fecha, idtipocantidad,sede_idsede,turno';

    $this->db->select($cadenaselect);
    $this->db->from('plato');
    $this->db->join('tipocantidad', 'tipocantidad.idtipocantidad = plato.tipocantidad_idtipocantidad');       
    $this->db->where('fecha', $fecha);
    $this->db->where('sede_idsede', $sede);
    $this->db->where('turno', $turno);

    $query = $this->db->get();
    return $query->result_array();

}
function lista_platos_fecha_mes($fecha){      

    $cadenaselect = 'idplato, plato.nombre as nombreplato, precio, cantidad, tipocantidad.nombre as nombrecantidad, simbolo, fecha, idtipocantidad,sede_idsede,turno';

    $this->db->select($cadenaselect);
    $this->db->from('plato');
    $this->db->join('tipocantidad', 'tipocantidad.idtipocantidad = plato.tipocantidad_idtipocantidad');       
    $this->db->where('fecha', $fecha);


    $query = $this->db->get();
    return $query->result_array();

}

function lista_platos_fecha_ini($fecha,$sede,$turno){
    $this->config->load('reportescfg');        

    $cadenaselect = 'idplato, plato.nombre as nombreplato, precio, cantidad, tipocantidad.nombre as nombrecantidad, simbolo, fecha, idtipocantidad,sede_idsede,turno';

    $this->db->select($cadenaselect);
    $this->db->from('plato');
    $this->db->join('tipocantidad', 'tipocantidad.idtipocantidad = plato.tipocantidad_idtipocantidad');       
    $this->db->where('fecha', $fecha);
    $this->db->where('sede_idsede', $sede);
    $this->db->where('turno', $turno);

    $query = $this->db->get();
    return $query->result_array();

}    

/*SOLUCION PARA CONSULTA SEMANAL HACIENDO UN MÉTODO PARTICULAR*/
function lista_platos_fecha_s($fecha){
    $this->config->load('reportescfg');
    $sede=$this->config->item('sede_defecto');


    $cadenaselect = 'idplato, plato.nombre as nombreplato, precio, cantidad, tipocantidad.nombre as nombrecantidad, simbolo, fecha, idtipocantidad,sede_idsede';

    $this->db->select($cadenaselect);
    $this->db->from('plato');
    $this->db->join('tipocantidad', 'tipocantidad.idtipocantidad = plato.tipocantidad_idtipocantidad');       
    $this->db->where('fecha', $fecha);
    $this->db->where('sede_idsede', $sede);


    $query = $this->db->get();
    return $query->result_array();

}



function lista_platos_fechas($fecha_ini,$fecha_fin){

    $cadenaselect = '*';

    $this->db->select($cadenaselect);
    $this->db->distinct();
    $this->db->from('plato');
    $this->db->where('fecha >=', $fecha_ini);
    $this->db->where('fecha <=', $fecha_fin);

    $query = $this->db->get();
    return $query->result_array();

}

function lista_d_platos_fechas($fecha_ini,$fecha_fin,$sede,$turno){

    $cadenaselect = 'nombre';

    $this->db->select($cadenaselect);
    $this->db->distinct();
    $this->db->from('plato');
    $this->db->where('fecha >=', $fecha_ini);
    $this->db->where('fecha <=', $fecha_fin);
    $this->db->where('sede_idsede', $sede);
    $this->db->where('turno', $turno);

    $query = $this->db->get();
    return $query->result_array();

}

function cantidad_platos_fecha($fecha){
    $cadenaselect = 'idplato';

    $this->db->select($cadenaselect);
    $this->db->distinct();
    $this->db->from('plato');
    $this->db->where('fecha =', $fecha);

    $query = $this->db->get();
    return $query->num_rows();

}

function cantidad_plato_fechas($nombre,$fecha_ini,$fecha_fin,$sede){
    $cadenaselect = 'idplato';

    $this->db->select($cadenaselect);
    $this->db->distinct();
    $this->db->from('plato');
    $this->db->where('fecha >=', $fecha_ini);
    $this->db->where('fecha <=', $fecha_fin);
    $this->db->where('nombre', $nombre);
    $this->db->where('sede_idsede', $sede);

    $query = $this->db->get();
    return $query->num_rows();

}

function precio_platos_fecha($fecha,$sede,$turno){

    $cadenaselect = 'precio';

    $this->db->select_sum($cadenaselect);
    $this->db->from('plato');
    $this->db->where('fecha', $fecha);
    $this->db->where('sede_idsede', $sede);
    $this->db->where('turno', $turno);
    $query = $this->db->get();
    $cantidad = $query->result_array();
    return $cantidad[0]['precio'];

}

    /*
    Aagregamos el parámetro $sede_idsede para los filtrados de los platos.
    */
    function lista_platos_rango_filtro($rp, $posicion, $sortname, $sortorder,$query,$qtype, $fecha,$sede_idsede,$turno){

        $cadenaselect = 'idplato, plato.nombre as nombreplato, precio, cantidad, tipocantidad.nombre as nombrecantidad, simbolo, fecha,sede_idsede,turno';

        $this->db->select($cadenaselect);
        $this->db->from('plato');
        $this->db->join('tipocantidad', 'tipocantidad.idtipocantidad = plato.tipocantidad_idtipocantidad');       
        $this->db->where('fecha', $fecha);
        $this->db->where('sede_idsede', $sede_idsede);
        $this->db->where('turno', $turno);

        $this->db->like($qtype, $query);
        $query = $this->db->order_by($sortname, $sortorder);
        $query = $this->db->get();
        $salida['rows'] = $query->result_array();
        $salida['count'] = $query->num_rows();
        return $salida;
    }
    
    function lista_platos_rango($rp, $posicion, $sortname, $sortorder, $fecha,$sede_idsede,$turno){

        $cadenaselect = 'idplato, plato.nombre as nombreplato, precio, cantidad, tipocantidad.nombre as nombrecatidad, simbolo, fecha,sede_idsede,turno';

        $this->db->select($cadenaselect);
        $this->db->from('plato');
        $this->db->join('tipocantidad', 'tipocantidad.idtipocantidad = plato.tipocantidad_idtipocantidad');       
        $this->db->where('fecha', $fecha);
        $this->db->where('sede_idsede', $sede_idsede);
        $this->db->where('turno', $turno);

        $query = $this->db->order_by($sortname, $sortorder);
        $query = $this->db->get();
        $salida['rows'] = $query->result_array();
        $salida['count'] = $query->num_rows();
        return $salida;

    }

    function lista_platos(){

        $query = $this->db->get('plato');
        return $query->result_array();
    }
    
    function lista_dias(){
        $cadenaselect = 'fecha';
        $this->db->select($cadenaselect);
        $this->db->distinct();
        $this->db->from('plato');
        $query = $this->db->get();
        return $query->result_array();
    }
/*function actualizar_fecha_plato($idplato,$fecha){

    $data = array('fecha' => $fecha);
    $condicion = "idplato = ".$idplato." ";
    $str = $this->db->update_string('plato', $data, $condicion);

    $this->db->query($str);
}*/
}
?>
