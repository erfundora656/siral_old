<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
if($tipo_usuario == 'Trabajadores'){
 $listado_rango_fechas = 'listado_rango_fechas';
 $lista_usuarios = 'lista_trabajadores';
 $importe = 'importe_trabajador_fechas';
}

?>
<table width="100%" class="ui-widget ui-widget-content" cellpadding="5" cellspacing="0">
    <thead class="ui-widget-header">
        <tr>
            <td style="font-size: 1.5em;">Listado de <?php echo $tipo_usuario; ?></td>
            <td style="text-align: right;font-size: 1.0em;"><button id="b_lista_m_pdf">Exportar</button></td>
            <td style="text-align: right;font-size: 1.0em;"><button id="b_lista_m_print">Imprimir</button></td>
            <td align="center" style="font-size: 1.0em;">Del <input id="fecha_ini" class="fechas_rep" size="7" type="text" readonly/> al <input id="fecha_fin" class="fechas_rep" size="7" type="text" readonly/></td>
            <td align="center" style="font-size: 1.0em;"><button id="b_lista_m_mostrar">Mostrar</button></td>
        </tr>
    </thead>
</table>
<br />
<div id="listado_dia"></div>

<script type="text/javascript">

    $( "#fecha_ini" ).datepicker({changeMonth: true,changeYear: true});
    $( "#fecha_ini" ).datepicker("option", "dateFormat", "dd/mm/yy");
    $( "#fecha_ini" ).val('<?php echo $dia_i.'/'.$mes_i.'/'.$anno_i; ?>');

    $( "#fecha_fin" ).datepicker({changeMonth: true,changeYear: true});
    $( "#fecha_fin" ).datepicker("option", "dateFormat", "dd/mm/yy");
    $( "#fecha_fin" ).val('<?php echo $dia_f.'/'.$mes_f.'/'.$anno_f; ?>');

    $("#b_lista_m_mostrar").button({
        text: true,
        icons: {
            primary: "ui-icon-check"
        }
    }).click(function(){
        fecha_i = $("#fecha_ini").val();
        fecha_f = $("#fecha_fin").val();

        myURL = 'index.php/estadisticas/<?php echo $listado_rango_fechas; ?>/'+fecha_i+'/'+fecha_f+'/1/';
                            //alert(myURL);
                            carga_contenidos(myURL);


                        });

    var cant_trabajadores = 0;
    var arrLineas = [];

    $( "#b_lista_m_pdf" ).button({
        text: true,
        icons: {
            primary: "ui-icon-disk"
        }
    }).click(function(){
        fecha = $("#fecha_platos").val();
        var texto = '<h2>Exportar el Consumo mensual de alimentación de <?php echo $tipo_usuario; ?> del <?php echo $dia_i.'/'.$mes_i.'/'.$anno_i; ?> al <?php echo $dia_f.'/'.$mes_f.'/'.$anno_f; ?>.</h2>';
        texto +='Formato: <select id="format_exp"><option value="pdf">PDF</option><option value="csv">CSV</option><option value="json">JSON</option></select>';       
        texto +='<div id="notif_exp"></div>';       

        $( "#dialogo_mensaje" ).html(texto);
        $( "#dialogo_mensaje" ).dialog( "open" );

    });

    $( "#b_lista_m_print" ).button({
        text: true,
        icons: {
            primary: "ui-icon-print"
        }
    }).click(function(){
                            //$("#listado_dia").printArea();
                            var contenido = '<div id="texto_imprimir">'
                            contenido += '<p><span style="font-size: 1.5em;"><?php echo $nombre_entidad;?></span><br />';
                            contenido += '<span style="font-size: 1.2em;"><?php echo $organismo;?></span></p>';
                            contenido += '<p><span style="font-size: 1.3em;">Consumo mensual de alimentación de <?php echo $dia_i.'/'.$mes_i.'/'.$anno_i; ?> a <?php echo $dia_f.'/'.$mes_f.'/'.$anno_f; ?>.</span></p>';
                            contenido += $( "#listado_dia" ).html();
                            contenido += '<p style="text-align:center;margin-top:50px;">__________________________________<br /><?php echo $nombre_firma;?><br />';
                            contenido += '<?php echo $cargo_firma;?></p>';
                            contenido += '</div>';
                            $( "#dialogo_imprimir" ).html(contenido);
                            $( "#dialogo_imprimir" ).dialog( "open" );
                            
                        });

    function carga_listado(){

        $( "#listado_dia" ).html('<div style="text-align: center;margin-top:150px;"><img width="100" src="../../../images/baile.gif"/><h3><div id="estado_proceso">Espere un momento...</div></h3><div id="progressbar"></div></div>');

    /*$( "#progressbar" ).progressbar({
	value: 0
});*/

var contenido = '<table width="100%" class="ui-widget ui-widget-content" cellpadding="5" cellspacing="0"><thead class="ui-widget-header">';
contenido +='<tr><td><strong>Código</strong></td><td><strong>Nombre y apellidos</strong></td>';
contenido += '<td align="right"><strong>Desayunos</strong></td>';  
contenido += '<td align="right"><strong>Almuerzos</strong></td>';   
contenido += '<td align="right"><strong>Comidas</strong></td>';   
contenido += '<td align="right"><strong>Importe</strong></td></tr></thead>';   

/*  USUARIOS */

myurl = '<?php echo $ruta_url; ?>index.php/estadisticas/<?php echo $lista_usuarios; ?>/';

var usuarios;

$.ajax({
  url: myurl,
  dataType: 'json',
  async: false,
  success: function(data){
    usuarios = data;
}
});
var par  = false;
cant_usuarios = usuarios.length;
var imp_total_d = 0;
var imp_total_a = 0;
var imp_total_c = 0;
var pos = 0;

for (i = 0; i < usuarios.length;i++){
   <?php 
   if($tipo_usuario == 'Trabajadores'){
    ?>
    var idusuario = usuarios[i].idtrabajador;
    <?php
                } /*else {
                    ?>
                       var idusuario = usuarios[i].idestudiante;
                    <?php
                }*/
                ?> 
                    //Obtener importe
                    myurl = '<?php echo $ruta_url; ?>index.php/estadisticas/<?php echo $importe; ?>/<?php echo $dia_i.'/'.$mes_i.'/'.$anno_i ?>/<?php echo $dia_f.'/'.$mes_f.'/'.$anno_f ?>/' + idusuario + '/';

                    var importe;

                    $.ajax({
                      url: myurl,
                      dataType: 'json',
                      async: false,
                      success: function(data){
                        importe = data;
                    }
                });
                    var imp_d = parseFloat(importe['desayunos']);
                    var imp_a = parseFloat(importe['almuerzos']);
                    var imp_c = parseFloat(importe['comidas']);

                    if(imp_d != 0 || imp_a != 0 || imp_c != 0){
                        if (par == true){
                            contenido +='<tr class ="fila_par">';
                        } else {
                            contenido +='<tr class ="fila_impar">';
                        }
                        par = !par;
                        contenido +='<td>' + usuarios[i].codigo + '</td>';
                        elnombre = usuarios[i].nombres + ' ' + usuarios[i].apellidos; 
                        contenido +='<td>' + elnombre.toUpperCase() + '</td>';

                        contenido +='<td align="right">$&nbsp;' + parseFloat(importe['desayunos']).toFixed(2) + '</td>';
                        contenido +='<td align="right">$&nbsp;' + parseFloat(importe['almuerzos']).toFixed(2) + '</td>';
                        contenido +='<td align="right">$&nbsp;' + parseFloat(importe['comidas']).toFixed(2) + '</td>';
                        contenido +='<td align="right"><strong>$&nbsp;' + parseFloat(importe['importe']).toFixed(2) + '</strong></td>';
                        imp_total_d += parseFloat(importe['desayunos']);
                        imp_total_a += parseFloat(importe['almuerzos']);
                        imp_total_c += parseFloat(importe['comidas']);

                        contenido +='</tr>';
                        arrLineas[pos] = usuarios[i].codigo+'|'+usuarios[i].nombres + ' ' + usuarios[i].apellidos+'|';
                        arrLineas[pos] += parseFloat(importe['desayunos']).toFixed(2) + '|' + parseFloat(importe['almuerzos']).toFixed(2) + '|' + parseFloat(importe['comidas']).toFixed(2) + '|' + parseFloat(importe['importe']).toFixed(2);
                        pos++;
                    }
                //Calcular promedio
                /*var promedio = (i+1)*100/usuarios.length;
                //promedio = promedio.toFixed(0);
                $( "#progressbar" ).progressbar({
                    value: promedio
                });*/
                //$("#estado_proceso").html('Procesando '+ (i+1) +' de ' + usuarios.length + ' Trabajadores.');

            }    

            var imp_total = imp_total_d + imp_total_a + imp_total_c;
            contenido +='<thead class="ui-widget-header"><tr><td colspan="2"><strong>Total</strong>';
            contenido +='</td><td align="right" >$&nbsp;'+imp_total_d.toFixed(2)+'</td>';
            contenido +='</td><td align="right" >$&nbsp;'+imp_total_a.toFixed(2)+'</td>';
            contenido +='</td><td align="right" >$&nbsp;'+imp_total_c.toFixed(2)+'</td>';
            contenido +='</td><td align="right" >$&nbsp;'+imp_total.toFixed(2)+'</td>';
            contenido +='</tr></thead>';

            contenido +='</table>'; 
            $( "#listado_dia" ).html(contenido);
        }

        function carga_listado_exp(fecha_,formato){
            myurl = '<?php echo $ruta_url; ?>index.php/estadisticas/listado_rango_fechas_'+formato+'/<?php echo $dia_i.'/'.$mes_i.'/'.$anno_i; ?>/<?php echo $dia_f.'/'.$mes_f.'/'.$anno_f; ?>/';

            var datos = "cantidad=" + arrLineas.length;

            for(i=0;i < arrLineas.length; i++){
                datos += '&linea'+i+'='+arrLineas[i];
            }
    //alert(datos);
    var contenido = $.ajax({
        type: "POST",
        async: false,
        data: datos,
        url: myurl
    }).responseText;
    return contenido;
    
}
function eliminarListadoServer(filename_){
  
    myurl = '<?php echo $ruta_url; ?>index.php/estadisticas/delete_reportes/';
    var datos = "filename="+filename_;
    $.ajax({
        type: "POST",
        async: false,
        data: datos,
        url: myurl,
        success:function(){            
        }
    });   
}

var mensaje;
$( "#dialogo_mensaje" ).attr('title','Exportar fichero.');
$( "#dialogo_mensaje" ).dialog({
  width: 400,
  height: 320,
  autoOpen: false,
  modal: true,
  buttons: {
   "Exportar": function() {

    fecha = $("#fecha_platos").val();
    formato = $("#format_exp").val();
    $( "#notif_exp" ).html('<div style="text-align: center;"><img width="100" src="../../../images/baile.gif"/><h3>Espere un momento...</h3></div>');
    mensaje = carga_listado_exp(fecha,formato);
    $( "#notif_exp" ).html('<div style="text-align: center;"><h3>Exportado Correctamente</h3><h3><a target="_blank" href="<?php echo $ruta_url; ?>'+ mensaje +'" >Click</a> para descargar.</h3></div>');

},
"Cerrar": function() {
    if(mensaje!=undefined){
        eliminarListadoServer(mensaje);
    }
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

<?php if($panel==0){ ?>    
    $( "#listado_dia" ).html('<div style="text-align: center;margin-top:150px;"><h1>Establesca un rango de fechas y haga click en el botón mostrar</h1>');
<?php } else { ?>
    carga_listado();
<?php } ?>

</script>