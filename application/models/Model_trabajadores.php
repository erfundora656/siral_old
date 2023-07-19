<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Model_trabajadores extends CI_Model {
   
    var $ci = "";
    var $nombres = "";
    var $apellidos = "";
    var $codigo = "";
    var $usuario = "";
    var $email = "";    
    var $becado="";
    
    function __construct(){
    // Llamar al constructor de CI_Model
        parent::__construct();
    }

    function insertar_trabajador(){
        
        $this->ci = $_POST["ci"];
        $this->nombres = $_POST["nombres"];
        $this->apellidos = $_POST["apellidos"];
        $this->codigo = $_POST["codigo"];
        $this->usuario = $_POST["usuario"];
        $this->email = $_POST["email"];

        $this->db->insert('trabajador', $this);
    }

    function insertar_trabajador_codigo($ci,$nombres,$apellidos,$codigo,$usuario,$email,$becado){

        $this->ci = $ci;
        $this->nombres = $nombres;
        $this->apellidos = $apellidos;
        $this->codigo = $codigo;
        $this->usuario = $usuario;
        $this->email = $email;        
        $this->becado = $becado;
        if($this->db->insert('trabajador', $this))
            return true;
        else
            return false;

    }

    function actualizar_trabajador(){

        $data = array('nombres' => $_POST["nombres"], 'apellidos' => $_POST["apellidos"], 'codigo' => $_POST["codigo"]);
        $condicion = "idtrabajador = ".$_POST['idtrabajador']." ";
        $str = $this->db->update_string('trabajador', $data, $condicion);

        $this->db->query($str);

    }

    function actualizar_trabajador_autenticado($idtrabajador,$ci,$nombres,$apellidos,$codigo,$usuario,$email,$becado){

        $data = array('ci' => $ci,'nombres' => $nombres, 'apellidos' => $apellidos, 'codigo' => $codigo,'usuario'=> $usuario, 'email' => $email,'becado'=> $becado);
        $condicion = "idtrabajador = ".$idtrabajador." ";
        $str = $this->db->update_string('trabajador', $data, $condicion);

        $this->db->query($str);

    }

    function eliminar_trabajador(){

        $this->db->delete('trabajador', 'idtrabajador = '.$_POST['idtrabajador']);
    }

    function buscar_trabajador($idtrabajador){

        $query = $this->db->get_where('trabajador', array('idtrabajador' => $idtrabajador));
        return $query->result_array();

    }

    function filtrar_trabajador($codigo){
        $query = $this->db->get_where('trabajador', array('codigo' => $codigo));
        return $query->result_array();

    }

    function lista_trabajadores(){

        $this->db->order_by('nombres asc, apellidos asc');
        $query = $this->db->get('trabajador');
        return $query->result_array();
    }

    function lista_trabajadores_filtro($campo_filtrar,$texto_filtrar){

        $this->db->order_by('nombres asc, apellidos asc');
        $this->db->where($campo_filtrar,$texto_filtrar);
        $query = $this->db->get('trabajador');
        return $query->result_array();
    }

    function lista_trabajadores_o_c(){

        $query = $this->db->order_by('codigo asc, nombres asc, apellidos asc');
        $query = $this->db->get('trabajador');
        return $query->result_array();
    }

     function lista_trabajadores_rango($cantidad, $posicion, $campo_orden, $direccion_orden){


        $cadenaselect = '*';//'idtrabajador, nombres, apellidos, codigo, trabajador.usuario, baja';

        $this->db->select($cadenaselect);
        $this->db->from('trabajador');
        $this->db->limit($cantidad, $posicion);
        $this->db->order_by($campo_orden, $direccion_orden);
        $query = $this->db->get();
        return $query->result_array();
    }

    function lista_trabajadores_rango_filtro($cantidad, $posicion, $campo_orden, $direccion_orden, $texto_filtrar, $campo_filtrar){

        $cadenaselect = '*';//'idtrabajador, nombres, apellidos, codigo, trabajador.usuario, baja';

        $this->db->select($cadenaselect);
        $this->db->from('trabajador');
        $this->db->like($campo_filtrar, $texto_filtrar);
        $this->db->limit($cantidad, $posicion);
        $this->db->order_by($campo_orden, $direccion_orden);
        $query = $this->db->get();
        return $query->result_array();
        return $query->result_array();

    }

    function cantidad_trabajadores(){

       $query = $this->db->get('trabajador');
       return $query->num_rows();

   }

   function lista_trabajadores_fecha($fecha,$sede,$turno){

    $cadenaselect = 'idtrabajador, nombres, apellidos, codigo, usuario,ticket.chequeo';

    $this->db->select($cadenaselect);
    $this->db->distinct();
    $this->db->from('trabajador');
    $this->db->join('ticket', 'ticket.trabajador_idtrabajador = trabajador.idtrabajador');       
    $this->db->join('plato', 'plato.idplato = ticket.plato_idplato');       
    $this->db->where('plato.fecha', $fecha);
    $this->db->where('plato.sede_idsede', $sede);
    $this->db->where('plato.turno', $turno);
    $this->db->order_by('nombres','asc');
    $this->db->order_by('apellidos','asc');
    $query = $this->db->get();
    return $query->result_array();
}

function buscar_trabajador_fecha($fecha,$sede,$turno,$codigo){

    $cadenaselect = 'idtrabajador, nombres, apellidos, codigo, usuario, ticket.chequeo ';

    $this->db->select($cadenaselect);
    $this->db->distinct();
    $this->db->from('trabajador');
    $this->db->join('ticket', 'ticket.trabajador_idtrabajador = trabajador.idtrabajador');       
    $this->db->join('plato', 'plato.idplato = ticket.plato_idplato');

    if(strlen($codigo) == 11){       
        $this->db->where('ci', $codigo);
    }else {
        $this->db->where('codigo', $codigo);
    }
          
    $this->db->where('plato.fecha', $fecha);
    $this->db->where('plato.sede_idsede', $sede);
    $this->db->where('plato.turno', $turno);
    $this->db->order_by('nombres','asc');
    $this->db->order_by('apellidos','asc');
    $query = $this->db->get();
    return $query->result_array();
}

function cantidad_trabajadores_fecha($fecha,$sede,$turno){

    $cadenaselect = 'idtrabajador';

    $this->db->select($cadenaselect);
    $this->db->distinct();
    $this->db->from('trabajador');
    $this->db->join('ticket', 'ticket.trabajador_idtrabajador = trabajador.idtrabajador');       
    $this->db->join('plato', 'plato.idplato = ticket.plato_idplato');       
    $this->db->where('plato.fecha', $fecha);
    $this->db->where('plato.sede_idsede', $sede);
    $this->db->where('plato.turno', $turno);
    $query = $this->db->get();
    return $query->num_rows();
}

function importe_trabajador_fecha($fecha, $idtrabajador,$sede,$turno){

    $cadenaselect = 'precio';

    $this->db->select_sum($cadenaselect);
    $this->db->from('trabajador');
    $this->db->join('ticket', 'ticket.trabajador_idtrabajador = trabajador.idtrabajador');       
    $this->db->join('plato', 'plato.idplato = ticket.plato_idplato');       
    $this->db->where('plato.fecha', $fecha);
    $this->db->where('plato.sede_idsede', $sede);
    $this->db->where('plato.turno', $turno);
    $this->db->where('idtrabajador', $idtrabajador);
    $query = $this->db->get();
    $cantidad = $query->result_array();
    return $cantidad[0]['precio'];
}

function importe_fecha($fecha,$turno){

    $cadenaselect = 'precio';

    $this->db->select_sum($cadenaselect);
    $this->db->from('trabajador');
    $this->db->join('ticket', 'ticket.trabajador_idtrabajador = trabajador.idtrabajador');       
    $this->db->join('plato', 'plato.idplato = ticket.plato_idplato');       
    $this->db->where('plato.fecha', $fecha);
    $this->db->where('plato.turno', $turno);
    $query = $this->db->get();
    $cantidad = $query->result_array();
    return $cantidad[0]['precio'];
}

function platos_trabajador_fecha($fecha, $idtrabajador,$sede,$turno){

    $cadenaselect = 'idplato';

    $this->db->select($cadenaselect);
    $this->db->from('trabajador');
    $this->db->join('ticket', 'ticket.trabajador_idtrabajador = trabajador.idtrabajador');       
    $this->db->join('plato', 'plato.idplato = ticket.plato_idplato');       
    $this->db->where('plato.fecha', $fecha);
    $this->db->where('plato.sede_idsede', $sede);
    $this->db->where('plato.turno', $turno);
    $this->db->where('idtrabajador', $idtrabajador);
    $query = $this->db->get();
    return $query->result_array();
}

function fechas_trabajador($idtrabajador,$turno){

    $cadenaselect = 'fecha';

    $this->db->select($cadenaselect);
    $this->db->distinct();
    $this->db->from('trabajador');
    $this->db->join('ticket', 'ticket.trabajador_idtrabajador = trabajador.idtrabajador AND ticket.turno='.$turno);       
    //$this->db->join('plato', 'plato.idplato = ticket.plato_idplato');       
    $this->db->where('idtrabajador', $idtrabajador);
    $query = $this->db->get();
    return $query->result_array();
}

function importe_trabajador_fechas($fecha_ini,$fecha_fin, $idtrabajador,$sede,$turno){

    $cadenaselect = 'precio';

    $this->db->select_sum($cadenaselect);
    $this->db->from('trabajador');
    $this->db->join('ticket', 'ticket.trabajador_idtrabajador = trabajador.idtrabajador');       
    $this->db->join('plato', 'plato.idplato = ticket.plato_idplato');       
    $this->db->where('plato.fecha >=', $fecha_ini);
    $this->db->where('plato.fecha <=', $fecha_fin);
    $this->db->where('plato.sede_idsede', $sede);
    $this->db->where('plato.turno', $turno);
    $this->db->where('idtrabajador', $idtrabajador);
    $query = $this->db->get();
    $cantidad = $query->result_array();
    return $cantidad[0]['precio'];
}

function importe_trabajador_fechas_mes($fecha_ini,$fecha_fin, $idtrabajador,$turno){

    $cadenaselect = 'precio';

    $this->db->select_sum($cadenaselect);
    $this->db->from('trabajador');
    $this->db->join('ticket', 'ticket.trabajador_idtrabajador = trabajador.idtrabajador');       
    $this->db->join('plato', 'plato.idplato = ticket.plato_idplato');       
    $this->db->where('plato.fecha >=', $fecha_ini);
    $this->db->where('plato.fecha <=', $fecha_fin);
    $this->db->where('plato.turno', $turno);
    $this->db->where('idtrabajador', $idtrabajador);
    $query = $this->db->get();
    $cantidad = $query->result_array();
    return $cantidad[0]['precio'];
}

function cantidad_trabajadores_filtro($texto_filtrar, $campo_filtrar){

    $query = $this->db->like($campo_filtrar, $texto_filtrar);
    $query = $this->db->get('trabajador');
    return $query->num_rows();

}

function existe_trabajador($usuario){
    $this->db->select('*');
    $this->db->from('trabajador');
    $this->db->where('usuario',$usuario);
    $query = $this->db->get();

    return $query->result_array();

}

function autenticar($usuario){

    $this->db->select('*');
    $this->db->from('trabajador');
    $this->db->where('usuario',$usuario);
    
    $query = $this->db->get();

    return $query->result_array();

}
    /*******************REVISADO*********************/
    

    /*function lista_trabajadores_activos(){

        $this->db->order_by('nombres asc, apellidos asc');
        $this->db->where('baja',0);
        $query = $this->db->get('trabajador');
        return $query->result_array();
    }

function platos_trabajador_desde($fecha, $idtrabajador){

    $cadenaselect = 'idplato';

    $this->db->select($cadenaselect);
    $this->db->from('trabajador');
    $this->db->join('ticket', 'ticket.trabajador_idtrabajador = trabajador.idtrabajador');       
    $this->db->join('plato', 'plato.idplato = ticket.plato_idplato');       
    $this->db->where('plato.fecha >', $fecha);
    $this->db->where('idtrabajador', $idtrabajador);
    $query = $this->db->get();
    return $query->result_array();
}
    function actualizar_trabajador_codigo($idtrabajador,$nombres,$apellidos){

        $data = array('nombres' => $nombres, 'apellidos' => $apellidos);
        $condicion = "idtrabajador = ".$idtrabajador." ";
        $str = $this->db->update_string('trabajador', $data, $condicion);

        $this->db->query($str);

    }
    function actualizar_codigo_trabajador($usuario,$codigo){

        $data = array('codigo' => $codigo);
        $condicion = "usuario = '".$usuario;
        $str = $this->db->update_string('trabajador', $data, $condicion);

        $this->db->query($str);

    }
    function dar_baja($idtrabajador){

        $data = array('baja' => 1);
        $condicion = "idtrabajador = ".$idtrabajador." ";
        $str = $this->db->update_string('trabajador', $data, $condicion);

        $this->db->query($str);

    }
    function es_baja($usuario){
        $this->db->select('*');
        $this->db->from('trabajador');
        $this->db->where('usuario',$usuario);
        $this->db->where('baja',  1);
        $query = $this->db->get();

        $salida = $query->result_array();
        if(count($salida) > 0){
            return true;
        } else {
            return false;
        }

    }*/

}

?>
