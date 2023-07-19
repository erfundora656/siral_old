<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<table width="100%" class="ui-widget ui-widget-content" cellpadding="5" cellspacing="0">
    <thead class="ui-widget-header">
        <tr>
            <td style="font-size: 1.5em;">Administrar tickets de <?php if($turno=="1") echo "Desayunos."; else if($turno=="2") echo "Almuerzos."; else if($turno=="3") echo "Comidas.";?></td>

            <td style="text-align: right;font-size: 1.0em;"> Trabajador:&nbsp;<input id="n_trabajador"  size="25" style="font-size: 1.0em;" readonly/>
             <input id="s_trabajador" hidden/>                   
         </td>              
         <td style="text-align: left;font-size: 1.0em;">Código:&nbsp;
            <input class="buscar_trab" size="5" type="text" id="c_trabajador"/>         
            <button   id="b_filtro_T">Buscar</button>
        </td>
        <td style="text-align: right;font-size: 1.0em;">Sede:&nbsp;<select id="sede_idsede">
            <?php
            foreach($listasedes as $sede){
                echo("<option value='".$sede['idsede']."'>".$sede['nombre']."</option>");
            }
            ?>
        </select>
    </td>  
</tr>
</thead>
</table>
<br />
<div id="tabla_semana" style="overflow: auto; width:100%; height: 385px;"></div>
<?php
$fecha = time();
?>

<script type="text/javascript">
    var fecha_hoy = <?php echo $fecha; ?>;
    var fecha_ver = <?php echo $fecha; ?>;

    function buscar_trabajador(){
        /*
        Variante 1: Cargar al hacer la llamada al controlador Principal de la vista para la gestión de las reservaciones
        el listado completo de trabajadores y comparar contra el código entrado. 

        var trabajadores=<?php echo json_encode($trabajadores);?>;
        for (var i = 0; i < trabajadores.length; i++) {
            if(codigo==trabajadores[i]["codigo"]){
                $("#s_trabajador").val(codigo);
                $("#n_trabajador").val(trabajadores[i]["nombres"]+" "+trabajadores[i]["apellidos"]);
                actualiza_trabajador();
        }else
        alert("No hay registrado ningún trabajador con ese código.");            
        };

        */

        /*
        Variante 2: Hacer petición al servidor cada vez que se necesite buscar un trabajador por su código.
        */
        var codigo=$("#c_trabajador").val();
        if(codigo!=""){

            $( "#tabla_semana" ).html('<div style="text-align: center;margin-top:200px;"><img width="100" src="../../../images/baile.gif"/><h3>Espere un momento...</h3></div>');
            
            myURL = '<?php echo $ruta_url;?>index.php/admin_tickets/filtrar_trab/'+codigo+'/';

            var trabajador="";

            $.ajax({
              url: myURL,
              dataType: 'json',
              async: false,
              success: function(data){            
                trabajador = data;
            }
        });

            if(trabajador['filtrar_trabajador'][0]== undefined){
                turno=<?php echo $turno;?>;

                if(turno==1){
                    url = '<?php echo $ruta_url;?>index.php/principal/gest_reserva_d/';
                }else if(turno==2){
                    url = '<?php echo $ruta_url;?>index.php/principal/gest_reserva/';
                }else{
                    url = '<?php echo $ruta_url;?>index.php/principal/gest_reserva_c/';
                }                        
                $( "#tabla_semana" ).html("");
                muestra_mensaje("<h2>El trabajador no se encuentra registrado en el sistema.</h2>", 400,null);

            } else if(codigo==trabajador['filtrar_trabajador'][0]["codigo"]){
                $("#s_trabajador").val(trabajador['filtrar_trabajador'][0]["idtrabajador"]);
                $("#n_trabajador").val(trabajador['filtrar_trabajador'][0]["nombres"]+" "+trabajador['filtrar_trabajador'][0]["apellidos"]);
                actualiza_trabajador();
            }
        }else{
            muestra_mensaje("<h2>Debe introcucir el código del trabajador.</h2>", 400,null);
        } 
    }

    function actualiza_trabajador(){
        $( "#tabla_semana" ).html('<div style="text-align: center;margin-top:200px;"><img width="100" src="../../../images/baile.gif"/><h3>Espere un momento...</h3></div>');

        idtrabajador = $("#s_trabajador").val();    
        sede=$("#sede_idsede").val();
        turno=<?php echo $turno;?>;
        myURL = '<?php echo $ruta_url; ?>index.php/admin_tickets/tabla_semana/'+fecha_ver+'/'+idtrabajador+'/1/'+sede+'/'+turno+'/';

        var contenido_semana = $.ajax({
            async: false,
            url: myURL
        }).responseText;

        $( "#tabla_semana" ).html(contenido_semana);
    }   

    $("#b_filtro_T").button({
        text: false,    
        icons: {
            primary: "ui-icon-search"
        }
    }).click(function(){
        
            buscar_trabajador();       
    });


    $(".buscar_trab").keypress(function(e){
        if (e.which == 13){
            buscar_trabajador(); 
        }
    });   

    $("#sede_idsede").change(function(){

        if($("#s_trabajador").val()!=""){
            actualiza_trabajador();
        }

    });

    $( "#tabla_semana" ).html('<div style="text-align: center;margin-top:200px;"><h1>Introduzca el código del Trabajador...</h1></div>');
</script>
