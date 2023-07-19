<?php

// Modelo de la tabla subitems

class Model_tickets extends CI_Model {

    var $trabajador_idtrabajador = "";
    var $plato_idplato = "";
    var $chequeo="";
    var $fecha="";
    var $sede_idsede="";
    var $turno="";

    function __construct(){
    // Llamar al constructor de CI_Model
        parent::__construct();
    }
    
    function insertar_ticket(){

        $this->trabajador_idtrabajador = $_POST["trabajador_idtrabajador"];
        $this->plato_idplato = $_POST["plato_idplato"];
        $this->fecha = $_POST["fecha"];        
        $this->sede_idsede = $_POST["sede_idsede"];
        $this->turno = $_POST["turno"];

        $this->db->insert('ticket', $this);
    }  
    
    function insertar_ticket_codigo($idtrabajador,$idplato,$fecha,$sede,$turno){

        $this->trabajador_idtrabajador = $idtrabajador;
        $this->plato_idplato = $idplato;
        $this->fecha = $fecha;
        $this->sede_idsede = $sede;
        $this->turno = $turno;
        $this->db->insert('ticket', $this);
    }  

    function eliminar_ticket(){

        $this->db->delete('ticket', 'trabajador_idtrabajador = '.$_POST['trabajador_idtrabajador']. ' and plato_idplato = '.$_POST['plato_idplato']. ' ');
    }

    function eliminar_ticket_codigo($idtrabajador,$idplato){

        $this->db->delete('ticket', 'trabajador_idtrabajador = '.$idtrabajador. ' and plato_idplato = '.$idplato. ' ');
    }

    function eliminar_tickets_plato($idplato){
        $this->db->delete('ticket', 'plato_idplato = '.$idplato.' ');
    }
    
    function buscar_ticket($trabajador_idtrabajador,$fecha, $plato_idplato,$sede_idsede,$turno){
        $cadenaselect = '*';
        $this->db->select($cadenaselect);
        $this->db->from('ticket');
        $this->db->where('trabajador_idtrabajador', $trabajador_idtrabajador);
        $this->db->where('turno', $turno);
        $this->db->where('fecha', $fecha);
        $query = $this->db->get();

        if($query->num_rows() == 0){
            return 0;
        }else{
            $insertar=true;
            foreach($query->result() as $row){
                if($row->sede_idsede != $sede_idsede || $row->plato_idplato == $plato_idplato){
                    $insertar=false;
                    break;
                }
            }              

            if($insertar){
                return 0;
            }else{
                return 1;
            }
        }
    }

    function buscar_ticket_selecc($trabajador_idtrabajador,$fecha, $plato_idplato,$sede_idsede,$turno){

        $cadenaselect = '*';
                
        $this->db->select($cadenaselect);
        $this->db->from('ticket');
        $this->db->where('trabajador_idtrabajador', $trabajador_idtrabajador);
        $this->db->where('plato_idplato', $plato_idplato);
        $this->db->where('turno', $turno);
        $this->db->where('fecha', $fecha);
        $this->db->where('sede_idsede', $sede_idsede);
        
        $query = $this->db->get();
    return $query->result_array();

    }

    function cantidad_ticket_plato($plato_idplato){

        $cadenaselect = '*';

        $this->db->select($cadenaselect);
        $this->db->from('ticket');
        $this->db->where('plato_idplato', $plato_idplato);
        
        $query = $this->db->get();
        return $query->num_rows();

    }
    
    function lista_tickets_plato($plato_idplato){

        $cadenaselect = '*';

        $this->db->select($cadenaselect);
        $this->db->from('ticket');
        $this->db->where('plato_idplato', $plato_idplato);
        
        $query = $this->db->get();
        return $query->result_array();

    }
    
    function mover_tickets($idtrabajador_origen,$idtrabajador_destino){

        $data = array('trabajador_idtrabajador' => $idtrabajador_destino);
        $condicion = "trabajador_idtrabajador = ".$idtrabajador_origen." ";
        $str = $this->db->update_string('ticket', $data, $condicion);

        $this->db->query($str);
        
    }

    function actualizar_chequeo($idtrabajador,$fecha,$sede_idsede,$turno,$conf){
        
       /*busco todos tickets del trabajador donde cumpla*/
       $cadenaselect = '*';

       $this->db->select($cadenaselect);
       $this->db->from('ticket');
       $this->db->join('plato', 'plato.idplato = ticket.plato_idplato');
       $this->db->where('trabajador_idtrabajador', $idtrabajador);
       $this->db->where('plato.fecha',$fecha);
       $this->db->where('plato.sede_idsede', $sede_idsede);
       $this->db->where('plato.turno', $turno);

       $query = $this->db->get();
       $data =$query->result_array(); 


       foreach($data as $d) {

        $this->trabajador_idtrabajador= $d['trabajador_idtrabajador'];     
        $this->plato_idplato=$d['plato_idplato'];
        $this->chequeo= $conf;
        $this->fecha= $d['fecha'];
        $this->sede_idsede= $d['sede_idsede'];
        $this->turno= $d['turno'];
        $this->db->where('trabajador_idtrabajador', $d['trabajador_idtrabajador']);
        $this->db->where('plato_idplato', $d['plato_idplato']);         
        $this->db->update('ticket', $this);    
        
    }
    
}


function actualizar_chequeo_todo(){

    $fecha=$_POST['fecha'];
    $sede=$_POST['sede_idsede'];
    $turno=$_POST['turno'];
    $conf=$_POST['conf'];

    /*busco todos tickets del trabajador donde cumpla*/
    $cadenaselect = 'idplato';

    $this->db->select($cadenaselect);
    $this->db->from('plato');      
    $this->db->where('fecha',$fecha);
    $this->db->where('sede_idsede', $sede);
    $this->db->where('turno', $turno);

    $query = $this->db->get();
    $data =$query->result_array(); 


    foreach ($data as $key) {
        $change = array('chequeo' => $conf);
        $condicion = "plato_idplato = ".$key['idplato']." ";
        $str = $this->db->update_string('ticket', $change, $condicion);

        $this->db->query($str);
    }
    


}

function tiene_tickets($idtrabajador,$fecha,$sede_idsede,$turno){
        $cadenaselect = '*';

        $this->db->select($cadenaselect);
        $this->db->from('ticket');
        $this->db->where('trabajador_idtrabajador', $idtrabajador);
        $this->db->where('fecha',$fecha);
        $this->db->where_not_in('sede_idsede',$sede_idsede);
        $this->db->where('turno', $turno);
        
        $query = $this->db->get();
        $tickets = $query->num_rows();
        if ($tickets > 0){
            return 1;
        } else {
            return 0;
        }
    }

    /*function busca_tickets($idtrabajador,$fecha,$sede_idsede,$turno){
        $cadenaselect = '*';

        $this->db->select($cadenaselect);
        $this->db->from('ticket');
        $this->db->where('trabajador_idtrabajador', $idtrabajador);
        $this->db->where('fecha',$fecha);
        $this->db->where_not_in('sede_idsede',$sede_idsede);
        $this->db->where('turno', $turno);
        
        $query = $this->db->get();
         return $query->result_array();        
    }*/


}
?>
