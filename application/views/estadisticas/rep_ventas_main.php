<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<table width="100%" class="ui-widget ui-widget-content" cellpadding="5" cellspacing="0">
    <thead class="ui-widget-header">
        <tr>
            <td style="font-size: 1.5em;">Reporte diario de ventas</td>
            <td style="text-align: right;font-size: 1.0em;">

                <button id="b_rep_ventas_print">Imprimir</button></td >
              <td style="text-align: right;font-size: 1.0em;">  <button id="b_rep_dirigidos">Dirigidos</button>

            </td>
            <td style="text-align: right;font-size: 1.0em;">
             Fecha:<input id="fecha_platos" size="8" type="text" readonly/></td>
             <td style="text-align: right;font-size: 1.0em;">Sede:&nbsp;<select id="sede_idsede">
             <?php
             if($listasedes!=""){
                        foreach($listasedes as $sede){
                        echo("<option  value='".$sede['idsede']."'>".$sede['nombre']."</option>");
                    }
                    }else{
                        echo("<option  value='".$id_sede."'>".$sede_pertenece."</option>");  
                    }
            ?>
        </select>
    </td>
        </tr>        
</thead>
</table>
<br />
<div id="listado_dia"></div>

<script type="text/javascript">

/*$( "#b_rep_ventas_pdf" ).button({
    text: true,
    icons: {
        primary: "ui-icon-disk"
    }
}).click(function(){
    fecha = $("#fecha_platos").val();
    $( "#dialogo_mensaje" ).html('<h2>Exportar a PDF el reporte de ventas de los platos del menu del día: '+fecha+ '</h2><div id="notif_exp"></div>');
    $( "#dialogo_mensaje" ).dialog( "open" );
});*/

$( "#b_rep_ventas_print" ).button({
    text: true,
    icons: {
        primary: "ui-icon-print"
    }
}).click(function(){
    var contenido = '<div id="texto_imprimir">'
    contenido += '<p><span style="font-size: 1.5em;"><?php echo $nombre_entidad;?></span><br />';
    contenido += '<span style="font-size: 1.2em;"><?php echo $organismo;?></span></p>';
    contenido += '<p><span style="font-size: 1.3em;">Reporte diario de ventas ('+$("#fecha_platos").val()+')</span></p>';
contenido += '<p><span style="font-size: 1.3em;">Exportado en: <?php echo date("d/m/y - H:i:s") ?> </span></p>';
    contenido += $( "#listado_dia" ).html();
    contenido += '<p style="text-align:center;margin-top:50px;">__________________________________<br /><?php echo $nombre_firma;?><br />';
    contenido += '<?php echo $cargo_firma;?></p>';
    contenido += '</div>';
    $( "#dialogo_imprimir" ).html(contenido);
    $( "#dialogo_imprimir" ).dialog( "open" );
});

$( "#b_rep_dirigidos" ).button({
    text: true,
    icons: {
        primary: "ui-icon-document-b"
    }
}).click(function(){
    var contenido = '<div id="texto_imprimir">'
    contenido += '<p><span style="font-size: 1.5em;"><?php echo $nombre_entidad;?></span><br />';
    contenido += '<span style="font-size: 1.2em;"><?php echo $organismo;?></span></p>';
    contenido += '<p><span style="font-size: 1.3em;">Listados de tickets dirigidos ('+$("#fecha_platos").val()+')</span></p>';
    
    fecha = $("#fecha_platos").val();
    contenido += '<p>DESAYUNOS</p>';
    contenido += carga_tickets_dir(fecha,"1");
    contenido += '<p>ALMUERZOS</p>';
    contenido += carga_tickets_dir(fecha,"2");
    contenido += '<p>COMIDAS</p>';
    contenido += carga_tickets_dir(fecha,"3");

    contenido += '<p style="text-align:center;margin-top:50px;">__________________________________<br /><?php echo $nombre_firma;?><br />';
    contenido += '<?php echo $cargo_firma;?></p>';
    contenido += '</div>';
    $( "#dialogo_imprimir" ).html(contenido);
    $( "#dialogo_imprimir" ).dialog( "open" );
});

$( "#fecha_platos" ).datepicker({changeMonth: true,changeYear: true});
$( "#fecha_platos" ).datepicker("option", "dateFormat", "dd/mm/yy");

// Detectar la fecha y ponerla en el datepicker

$("#fecha_platos").val('<?php echo date('d/m/Y') ?>');

function carga_listado(fecha_){

    myurl = '<?= $ruta_url; ?>index.php/estadisticas/reporte_ventas_dia/'+fecha_+'/'+$("#sede_idsede").val()+'/';
    
    $( "#listado_dia" ).html('<div style="text-align: center;margin-top:150px;"><img width="100" src="../../../images/baile.gif"/><h3>Espere un momento...</h3></div>');
    var contenido = $.ajax({
        async: false,
        url: myurl
    }).responseText;


    $( "#listado_dia" ).html(contenido);
    
}

/*function carga_listado_pdf(fecha_){
    myurl = '<?php echo $ruta_url; ?>index.php/estadisticas/reporte_ventas_dia_pdf/'+fecha_+'/'+$("#sede_idsede").val()+'/';

    var contenido = $.ajax({
        async: false,
        url: myurl
    }).responseText;
    return contenido;
    
}*/

function carga_tickets_dir(fecha_,turno){
    myurl = '<?php echo $ruta_url; ?>index.php/estadisticas/lista_tickets_dir/'+fecha_+'/'+$("#sede_idsede").val()+'/'+turno+'/';

    var contenido = $.ajax({
        async: false,
        url: myurl
    }).responseText;
    return contenido;
    
}




/*function carga_tickets_dir_c(fecha_){
    myurl = '<?php echo $ruta_url; ?>index.php/estadisticas/lista_tickets_dir_c/'+fecha_+'/';

    var contenido = $.ajax({
        async: false,
        url: myurl
    }).responseText;
    return contenido;
    
}*/

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
