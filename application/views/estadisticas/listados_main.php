<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

if($tipo_usuario == 'Trabajadores'){
 $almuerzos_dia = 'trabajadores_almuerzos_dia';
} 

?>
<table width="100%" class="ui-widget ui-widget-content" cellpadding="5" cellspacing="0">
    <thead class="ui-widget-header">
        <tr>
            <td style="font-size: 1.5em;">
                <?php
                if ($tipo == '1'){
                    echo 'Listado de desayunos';
                }else if ($tipo == '2'){
                    echo 'Listado de almuerzos';
                } else if ($tipo == '3'){
                    echo 'Listado de comidas';
                    
                }

                ?>
            </td>
            <td style="font-size: 1.0em;">
                <?php
                if($forma_pago == '0'){
                    ?>
                    <button id="b_tickets_pago">Tickets</button>
                    <?php
                }
                ?>                
            </td>
            <td style="font-size: 1.0em;"><button id="b_list_alm_print">Imprimir</button></td>
            <?php
            if($rol >= 2){?>
                <td style="font-size: 1.0em;"><button id="b_chequear_listado">Chequear</button></td>
            <?php }
            ?>        
            <td style="text-align: right;font-size: 1.0em;">Fecha:<input id="fecha_platos" size="8" type="text" readonly/></td>                    
            <td style="text-align: right;font-size: 1.0em;">Sede:<select id="sede_idsede">
                <?php
                if($listasedes!=""){
                    foreach($listasedes as $sede){
                        echo("<option value='".$sede['idsede']."'>".$sede['nombre']."</option>");
                    }
                }else{
                    echo("<option value='".$id_sede."'>".$sede_pertenece."</option>");  
                }
                ?>
            </select>
        </td>
        
    </tr>
</thead>
</table>
<br />
<div id="listado_dia"></div>
<?php
if($forma_pago == '0'){
    ?>
    <div id="tickets_dia" style="visibility: hidden;"></div>
    <?php
}
?>

<script type="text/javascript">
    $( "#b_chequear_listado" ).button({
        text: true,
        icons: {
            primary: "ui-icon-check"
        }
    }).click(function(){
        fecha_ = $("#fecha_platos").val();
        sede_=$("#sede_idsede").val();
        turno_=<?php echo $tipo?>;
        confirmar_todo(fecha_,sede_,turno_);        
        carga_listado(fecha);

    });

    $( "#b_list_alm_pdf" ).button({
        text: true,
        icons: {
            primary: "ui-icon-disk"
        }
    }).click(function(){
        fecha = $("#fecha_platos").val();
        $( "#dialogo_mensaje" ).html('<h2>Exportar a PDF el listado de reservaciones de almuerzo del día: '+fecha+ '</h2><div id="notif_exp"></div>');
        $( "#dialogo_mensaje" ).dialog( "open" );

    });

    $( "#b_list_alm_print" ).button({
        text: true,
        icons: {
            primary: "ui-icon-print"
        }
    }).click(function(){
        var contenido = '<div id="texto_imprimir">'
        contenido += '<p><span style="font-size: 1.5em;"><?php echo $nombre_entidad;?></span><br />';
        contenido += '<span style="font-size: 1.2em;"><?php echo $organismo;?></span></p>';
        <?php
        if ($tipo == '1'){?>
            contenido += '<p><span style="font-size: 1.3em;">Listados de desayunos de <?php echo $tipo_usuario; ?> ('+$("#fecha_platos").val()+')</span></p>';
    contenido += '<p><span style="font-size: 1.3em;">Exportado en: <?php echo date("d/m/y - H:i:s") ?> </span></p>';

        <?php } else if ($tipo == '2'){?>
            contenido += '<p><span style="font-size: 1.3em;">Listados de almuerzos de <?php echo $tipo_usuario; ?> ('+$("#fecha_platos").val()+')</span></p>';
    contenido += '<p><span style="font-size: 1.3em;">Exportado en: <?php echo date("d/m/y - H:i:s") ?> </span></p>';
            
        <?php } else if ($tipo == '3'){ ?>
            contenido += '<p><span style="font-size: 1.3em;">Listados de comidas de <?php echo $tipo_usuario; ?> ('+$("#fecha_platos").val()+')</span></p>';
    contenido += '<p><span style="font-size: 1.3em;">Exportado en: <?php echo date("d/m/y - H:i:s") ?> </span></p>';

        <?php }

        ?>

        contenido += $( "#listado_dia" ).html();
        contenido += '<p style="text-align:center;margin-top:50px;">__________________________________<br /><?php echo $nombre_firma;?><br />';
        contenido += '<?php echo $cargo_firma;?></p>';
        contenido += '</div>';
        $( "#dialogo_imprimir" ).html(contenido);
        $( "#dialogo_imprimir" ).dialog( "open" );
    });

    $( "#b_tickets_pago" ).button({
        text: true,
        icons: {
            primary: "ui-icon-print"
        }
    }).click(function(){
        var contenido = '<div id="texto_imprimir">'
        contenido += '<p><span style="font-size: 1.5em;"><?php echo $nombre_entidad;?></span><br />';
        contenido += '<span style="font-size: 1.2em;"><?php echo $organismo;?></span></p>';

        <?php
        if ($tipo == '1'){?>
            contenido += '<p><span style="font-size: 1.3em;">Tickets de desayunos de <?php echo $tipo_usuario; ?> ('+$("#fecha_platos").val()+')</span></p>';
        <?php } else if ($tipo == '2'){?>
            contenido += '<p><span style="font-size: 1.3em;">Tickets de almuerzos de <?php echo $tipo_usuario; ?> ('+$("#fecha_platos").val()+')</span></p>';
        <?php } else if ($tipo == '3'){ ?>
            contenido += '<p><span style="font-size: 1.3em;">Tickets de comidas de <?php echo $tipo_usuario; ?> ('+$("#fecha_platos").val()+')</span></p>';
        <?php }

        ?>


        contenido += $( "#tickets_dia" ).html();
        contenido += '<p style="text-align:center;margin-top:50px;">__________________________________<br /><?php echo $nombre_firma;?><br />';
        contenido += '<?php echo $cargo_firma;?></p>';
        contenido += '</div>';
        $( "#dialogo_imprimir" ).html(contenido);
        $( "#dialogo_imprimir" ).dialog( "open" );
    });

    <?php
    if($rol >= 2 ){
        ?>
        $( "#fecha_platos" ).datepicker({changeMonth: true,changeYear: true});
        $( "#fecha_platos" ).datepicker("option", "dateFormat", "dd/mm/yy");
    <?php }?>
// Detectar la fecha y ponerla en el datepicker

$("#fecha_platos").val('<?php echo date('d/m/Y') ?>');





var trabajadores_listados;
function carga_listado(fecha_){

    $( "#listado_dia" ).html('<div style="text-align: center;margin-top:150px;"><img width="100" src="../../../images/baile.gif"/><h3><div id="estado_proceso">Espere un momento...</div></h3><div id="progressbar"></div></div>');

   /* $( "#progressbar" ).progressbar({
       value: 0
   });*/

   var contenido = '<table width="100%" class="ui-widget ui-widget-content" cellpadding="5" cellspacing="0"><thead class="ui-widget-header">';
   contenido +='<tr><td><strong>Chequeo</strong></td><td><strong>Código</strong></td><td><strong>Nombre y apellidos</strong></td>';



    //Obtener el menu del día
    sede=$("#sede_idsede").val();
    <?php
    if ($tipo == '1'){?>
     myurl = '<?php echo $ruta_url; ?>index.php/estadisticas/menu_almuerzos_dia/'+fecha_+'/'+sede+'/1/';
 <?php } else if ($tipo == '2'){?>
    myurl = '<?php echo $ruta_url; ?>index.php/estadisticas/menu_almuerzos_dia/'+fecha_+'/'+sede+'/2/';
<?php } else if ($tipo == '3'){ ?>
    myurl = '<?php echo $ruta_url; ?>index.php/estadisticas/menu_almuerzos_dia/'+fecha_+'/'+sede+'/3/';
<?php }?>

var menu_dia;
var lista_id_platos ='';

$.ajax({
  url: myurl,
  dataType: 'json',
  async: false,
  success: function(data){
    menu_dia = data;
}
});



    for (i = 0; i < menu_dia.length;i++){ 

        <?php if ($tipo == '1'){?>
            contenido +='<td align="right"><strong>' + menu_dia[i].nombreplato + '</strong></td>';
            lista_id_platos += menu_dia[i].idplato;
        <?php } else if ($tipo == '2'){?>
            contenido +='<td align="right"><strong>' + menu_dia[i].nombreplato + '</strong></td>';
            lista_id_platos += menu_dia[i].idplato;
        <?php } else if ($tipo == '3'){ ?>
            contenido +='<td align="right"><strong>' + menu_dia[i].nombreplato + '</strong></td>';
            lista_id_platos += menu_dia[i].idplato;
        <?php } ?>
        if (i < (menu_dia.length - 1)) lista_id_platos +='-';
    }
                                

contenido += '<td align="right"><strong>Importe</strong></td></tr></thead>';   

/*  TRABAJADORES */
<?php
if ($tipo == '1'){?>
    myurl = '<?php echo $ruta_url; ?>index.php/estadisticas/<?php echo $almuerzos_dia; ?>/'+fecha_+'/'+sede+'/1/';
<?php } else if ($tipo == '2'){?>
 myurl = '<?php echo $ruta_url; ?>index.php/estadisticas/<?php echo $almuerzos_dia; ?>/'+fecha_+'/'+sede+'/2/';
<?php } else if ($tipo == '3'){ ?>
    myurl = '<?php echo $ruta_url; ?>index.php/estadisticas/<?php echo $almuerzos_dia; ?>/'+fecha_+'/'+sede+'/3/';
<?php } ?>

var usuarios;

$.ajax({
  url: myurl,
  dataType: 'json',
  async: false,
  success: function(data){
    usuarios = data;
}
});
trabajadores_listados=usuarios;


<?php
if($forma_pago == '0'){
    ?>
    var num_tick = 0;
    var tabla_tickets = '<table width="100%" cellpadding="5" cellspacing="3" border="0"><tr>';
    <?php
}
?>

var par  = false;
var imp_total = 0;
for (i = 0; i < usuarios.length;i++){
    if (par == true){
        contenido +='<tr class ="fila_par">';
    } else {
        contenido +='<tr class ="fila_impar">';
    }
    par = !par;
                //Obtener los platos que reservó
                
                <?php
                if ($tipo_usuario == 'Trabajadores'){
                    if ($tipo == '1'){?>
                        myurl = '<?php echo $ruta_url; ?>index.php/estadisticas/trabajadores_plato_almuerzos/'+usuarios[i].idtrabajador+'/'+fecha_+'/'+lista_id_platos+'/'+sede+'/1/';
                    <?php } else if ($tipo == '2'){?>
                        myurl = '<?php echo $ruta_url; ?>index.php/estadisticas/trabajadores_plato_almuerzos/'+usuarios[i].idtrabajador+'/'+fecha_+'/'+lista_id_platos+'/'+sede+'/2/';
                    <?php } else if ($tipo == '3'){ ?>
                        myurl = '<?php echo $ruta_url; ?>index.php/estadisticas/trabajadores_plato_almuerzos/'+usuarios[i].idtrabajador+'/'+fecha_+'/'+lista_id_platos+'/'+sede+'/3/';
                    <?php }
                    ?>
                    
                    var reservo;
                    $.ajax({
                      url: myurl,
                      dataType: 'json',
                      async: false,
                      success: function(datos){
                        reservo = datos;                        
                    }
                });
                    
                   
                    /*Incoorporar el chequeo al Listado*/
                    var cPlatos_res=0;
                    for (j = 0; j < menu_dia.length;j++){
                     if(reservo[j]['chequeo']==true){                            
                        cPlatos_res++;
                    } 
                }
                
                

                if(cPlatos_res == 0){ 

                    contenido +='<td><input class="chequeo_'+i+'" onclick="isChequed('+usuarios[i].idtrabajador+','+i+')" type="checkbox"/></td>';
                }else{
                    contenido +='<td><input class="chequeo_'+i+'" onclick="isChequed('+usuarios[i].idtrabajador+','+i+')" type="checkbox" checked/></td>';
                }


                contenido +='<td>' + usuarios[i].codigo + '</td>';
                elnombre = usuarios[i].nombres + ' ' + usuarios[i].apellidos; 
                contenido +='<td>' + elnombre.toUpperCase() + '</td>';

                var importe_t = 0;
                
                    for (j = 0; j < menu_dia.length;j++){
                        if(reservo[j]['compro'] == true){
                            contenido +='<td align="right">' + menu_dia[j].precio + '</td>';
                            importe_t += parseFloat(menu_dia[j].precio);
                        } else {
                            contenido +='<td>&nbsp;</td>';
                        }

                    }

                
                contenido +='<td align="right">$&nbsp;' + importe_t.toFixed(2) + '</td>';
                contenido +='</tr>';

                <?php
                if($forma_pago == '0'){
                    ?>
                    num_tick++;    
                    tabla_tickets += '<td align="center" style="border: 1px solid;">Ticket #'+num_tick+'<br/>'+elnombre.toUpperCase()+'<br />'+fecha+'<br />$ '+importe_t.toFixed(2)+'</td>';
                    if(num_tick % 5 ==0) {
                        tabla_tickets += '</tr><tr>';
                    }
                    <?php
                }

                ?>
                imp_total += importe_t;
                <?php
            }?>
                //Calcular promedio
                /*var promedio = (i+1)*100/usuarios.length;
                //promedio = promedio.toFixed(0);
                $( "#progressbar" ).progressbar({
                    value: promedio
                });*/
                $("#estado_proceso").html('Procesando '+ (i+1) +' de ' + usuarios.length + ' Trabajadores.');

            }

            <?php
            if($forma_pago == '0'){
                ?>
                tabla_tickets += '</tr></table>';
                $("#tickets_dia").html(tabla_tickets);
                <?php
            }
            ?>

           
                var columnas = menu_dia.length;
                columnas++;
                columnas++;
                if(usuarios.length == 0){
                    contenido += '<td align="center" colspan="'+ (parseInt(columnas)+2) + '"><h1>No hay trabajadores con reservación para este día.</h1>'
                }
                contenido +='<thead class="ui-widget-header"><tr><td colspan="'+ (columnas+1) + '"><strong>Total</strong>';
           

            contenido +='</td><td align="right" >$&nbsp;'+imp_total.toFixed(2)+'</td>';
            contenido +='</tr></thead>';

            contenido +='</table>';    
            $( "#listado_dia" ).html(contenido);
        }


        function confirmar_todo(fecha_,sede_,turno_){
            conf=1;        
            fecha = fecha_;
            sede=sede_;
            turno=turno_;


            /*var datos = "codigos_trabajadores=" + codigos;                */
            var datos = "&fecha=" + fecha;
            datos += "&sede_idsede=" + sede;
            datos += "&turno=" + turno;
            datos += "&conf=" + conf;

            $.ajax({
             type: "POST",
             async: false,
             url: "<?= $ruta_url; ?>index.php/estadisticas/confirmar_chequeo_todos/",
             data: datos,
             success: function(){
                        //location.reload();
                    }
                });



        }

        function confirmar(idtrabajador_,fecha_,sede_,turno_,conf_){
            /*conf=conf_;
            codigo = idtrabajador_;
            fecha = fecha_;
            sede=sede_;
            turno=turno_;*/


            var datos = "&codigo_trabajador=" + idtrabajador_;                
            datos += "&fecha=" + fecha_;
            datos += "&sede_idsede=" + sede_;
            datos += "&turno=" + turno_;
            datos += "&conf=" + conf_;

            $.ajax({
               type: "POST",
               async: false,
               url: "<?= $ruta_url; ?>index.php/estadisticas/confirmar_chequeo/",
               data: datos,
               success: function(){                
                        //location.reload();
                    }
                });

        }



        function isChequed(idtrabajador_,i){
            fecha_ = $("#fecha_platos").val();
            sede_=$("#sede_idsede").val();
            turno_=<?php echo $tipo?>;
            if($(".chequeo_"+i).is(":checked")){       
                confirmar(idtrabajador_,fecha_,sede_,turno_,1)
            }else{
                confirmar(idtrabajador_,fecha_,sede_,turno_,0)
            }    
        }

        $("#fecha_platos").change(function(){
            fecha = $("#fecha_platos").val();    
            carga_listado(fecha);
        });
        $("#sede_idsede").change(function(){
            fecha = $("#fecha_platos").val();
            carga_listado(fecha);
        });

        $( "#dialogo_mensaje" ).attr('title','Exportar a PDF');
        $( "#dialogo_mensaje" ).dialog({
          width: 400,
          height: 320,
          autoOpen: false,
          modal: true,
          buttons: {
           "Exportar": function() {

            fecha = $("#fecha_platos").val();
            $( "#notif_exp" ).html('<div style="text-align: center;"><img width="100" src="../../../images/baile.gif"/><h3>Espere un momento...</h3></div>');
            mensaje = carga_listado_pdf(fecha);
            $( "#notif_exp" ).html('<div style="text-align: center;"><h3>Exportado Correctamente</h3><h3><a target="_blank" href="<?php echo $ruta_url; ?>'+ mensaje +'" >Click</a> para descargar.</h3></div>');

        },
        "Cerrar": function() {
            $( this ).dialog( "close" );
            $( "#dialogo_mensaje" ).html('');
        }
    }
});

        $( "#dialogo_imprimir" ).attr('title','Imprimir');
        $( "#dialogo_imprimir" ).dialog({
          width: 1000,
          height: 600,
          autoOpen: false,
          modal: true,
          buttons: {
           "Imprimir": function() {
            $("#texto_imprimir").printArea();
        },
        "Cerrar": function() {
            $( this ).dialog( "close" );
            $( "#dialogo_imprimir" ).html('');
        }
    }
});



        fecha = $("#fecha_platos").val();
        carga_listado(fecha);

    </script>
