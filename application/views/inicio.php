<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>

<table width="100%">
    <tr>
        <td>&nbsp;</td>
        <td width="1020">
            <div style="width: 100%;">
                <button id="b_front_inicio_pdf">Inicio</button>
                <button id="b_front_s_tickets_d">Desayunos</button>
                <button id="b_front_s_tickets">Almuerzos</button>
                <button id="b_front_s_tickets_c">Comidas</button>
                <button id="b_front_estadisticas">Estadísticas</button>
                &nbsp;<span id="ruta" style="font-size: 1.2em;font-weight: bold;">Inicio</span>                 
                <div style="float: right;" >
                    &nbsp;<span  style="font-size: 1.2em;font-weight: bold;">Sede: &nbsp;<?php echo $nombre_idsede;?></span>
                    &nbsp;<button id="b_front_salir">Salir</button></div>
            </div>
            
<?php
 $fecha = time();
 
 if($tipo_usuario == 'Trabajador'){
     $tickets_alm = 'admin_tickets';
     $tickets_com = 'admin_ticket_comidas';
     $estadisticas = 'estadisticas';
     $controladora = 'seguridad';
     $idusuario = $idtrabajador;
 } 
 
?>

<script type="text/javascript">
    var fecha_hoy = <?php echo $fecha; ?>;
    var fecha_ver = <?php echo $fecha; ?>;
    var tipo_menu = 0;
    
    function actualiza_trabajador(){
        
        idtrabajador = <?php echo $idusuario; ?>;

        if(tipo_menu == 1){
            myURL = '<?php echo $ruta_url; ?>index.php/<?php echo $tickets_alm; ?>/tabla_semana/'+fecha_ver+'/'+idtrabajador+'/0/<?php echo $sede_idsede;?>/1/';
        } else if(tipo_menu == 2){
            myURL = '<?php echo $ruta_url; ?>index.php/<?php echo $tickets_alm; ?>/tabla_semana/'+fecha_ver+'/'+idtrabajador+'/0/<?php echo $sede_idsede;?>/2/';
        }else if(tipo_menu == 3){
            myURL = '<?php echo $ruta_url; ?>index.php/<?php echo $tickets_alm; ?>/tabla_semana/'+fecha_ver+'/'+idtrabajador+'/0/<?php echo $sede_idsede;?>/3/';
        }
        carga_contenidos(myURL);
        
    }   

  

 $( "#b_front_inicio_pdf" ).button({
                            text: true,
                            icons: {
                                    primary: "ui-icon-home"
                            }
                        }).click(function(){
                                myURL = 'index.php/principal/inicio/';
                                $("#ruta").html('Inicio');
                                carga_contenidos(myURL);

                        });

 $( "#b_front_s_tickets_d" ).button({
                            text: true,
                            icons: {
                                    primary: "ui-icon-calendar"
                            }
                        }).click(function(){
                                myURL = 'index.php/<?php echo $tickets_alm; ?>/tabla_semana/<?php echo $fecha_hoy; ?>/<?php echo $idusuario; ?>/0/<?php echo $sede_idsede;?>/1/';
                                tipo_menu = 1;
                                $("#ruta").html('Reservaciones de desayuno.');
                                carga_contenidos(myURL);
                        });

 $( "#b_front_s_tickets" ).button({
                            text: true,
                            icons: {
                                    primary: "ui-icon-calendar"
                            }
                        }).click(function(){
                                myURL = 'index.php/<?php echo $tickets_alm; ?>/tabla_semana/<?php echo $fecha_hoy; ?>/<?php echo $idusuario; ?>/0/<?php echo $sede_idsede;?>/2/';
                                tipo_menu = 2;
                                $("#ruta").html('Reservaciones de almuerzo.');
                                carga_contenidos(myURL);
                        });

 $( "#b_front_s_tickets_c" ).button({
                            text: true,
                            icons: {
                                    primary: "ui-icon-calendar"
                            }
                        }).click(function(){
                                myURL = 'index.php/<?php echo $tickets_alm; ?>/tabla_semana/<?php echo $fecha_hoy; ?>/<?php echo $idusuario; ?>/0/<?php echo $sede_idsede;?>/3/';
                                tipo_menu = 3;
                                $("#ruta").html('Reservaciones de comida.');
                                carga_contenidos(myURL);
                        });

 $( "#b_front_estadisticas" ).button({
                            text: true,
                            icons: {
                                    primary: "ui-icon-note"
                            }
                        }).click(function(){
                                myURL = 'index.php/principal/<?php echo $estadisticas; ?>/0/0/0/0/0/0/';
                                $("#ruta").html('Estadísticas');
                                carga_contenidos(myURL);
                            
                        });

 $( "#b_front_salir" ).button({
                            text: true,
                            icons: {
                                    primary: "ui-icon-power"
                            }
                        }).click(function(){
                            $.post("index.php/<?php echo $controladora; ?>/cerrar_sesion_trab/", {}, 
                                function (data) {
                                        //alert(data);
                                        location.reload();
                                });
                        });
                        
                        
</script>
<br />
<div id="contenedor_h"></div>
<br />
        </td>
        <td>&nbsp;</td>
    </tr>
</table>    
<script type="text/javascript">
    myURL = 'index.php/principal/inicio/';

    carga_contenidos(myURL);
    
    
    
</script>