<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

if($tipo_usuario == 'Trabajadores'){
   $listado_mensual = 'listado_mensual';
   $lista_usuarios = 'lista_trabajadores';
   $importe = 'importe_trabajador';
}

$proximo_mes = $mes + 1;
$anterior_mes = $mes - 1;
if ($proximo_mes > 12){
    $proximo_anno = $anno + 1;
    $proximo_mes = 1;
} else {
    $proximo_anno = $anno;
}    
if ($anterior_mes < 1){
    $anterior_anno = $anno - 1;
    $anterior_mes = 12;
} else {
    $anterior_anno = $anno;
}

?>

<table width="100%" class="ui-widget ui-widget-content" cellpadding="5" cellspacing="0">
    <thead class="ui-widget-header">
        <tr>
            <td style="font-size: 1.5em;">Listado mensual de <?php echo $tipo_usuario; ?></td>
            <td style="text-align: right;font-size: 1.0em;">    
                <iframe id="invisible" style="display:none;"></iframe>            
                <button id="b_lista_m_pdf">Exportar</button>
            </td><td style="text-align: right;font-size: 1.0em;">
                <button id="b_lista_m_print">Imprimir</button>
            </td>
            <td align="center" style="font-size: 1.0em;">
                <button id="prev" >Anterior</button>
                &nbsp;&nbsp;
                <div style="display:inline-block;" id="fechas">
                    <?php echo ($nombre_mes.' de '.$anno); ?>
                </div>
                &nbsp;&nbsp;
                <button id="next" >Próximo</button>
            </td>
        </tr>
    </thead>
</table>
<br />
<div id="listado_dia"></div>

<script type="text/javascript">

    var cant_trabajadores = 0;
    var arrLineas = [];

    $( "#prev" ).button({
        text: false,
        icons: {
            primary: "ui-icon-seek-prev"
        }
    }).click(function(){
        myURL = 'index.php/estadisticas/<?php echo $listado_mensual ?>/<?php echo $anterior_mes ?>/<?php echo $anterior_anno ?>/';

        carga_contenidos(myURL);
    });
    $( "#next" ).button({
        text: false,
        icons: {
            primary: "ui-icon-seek-next"
        }
    }).click(function(){
        myURL = 'index.php/estadisticas/<?php echo $listado_mensual ?>/<?php echo $proximo_mes ?>/<?php echo $proximo_anno ?>/';
        carga_contenidos(myURL);
    });


    $( "#b_lista_m_pdf" ).button({
        text: true,
        icons: {
            primary: "ui-icon-disk"
        }
    }).click(function(){

        fecha = $("#fecha_platos").val();
        var texto = '<h2>Exportar el Consumo mensual de alimentación de  <?php echo ($nombre_mes.' de '.$anno); ?>.</h2>';
        texto +='Formato: <select id="format_exp">' +
        '<option value="asset">Planilla Asset</option>' +                       
        '</select>';
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
        var contenido = '<div id="texto_imprimir">'
        contenido += '<p><span style="font-size: 1.5em;"><?php echo $nombre_entidad;?></span><br />';
        contenido += '<span style="font-size: 1.2em;"><?php echo $organismo;?></span></p>';
        contenido += '<p><span style="font-size: 1.3em;">Consumo mensual de alimentación (<?php echo ($nombre_mes.' de '.$anno); ?>)</span></p>';
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
                myurl = '<?php echo $ruta_url; ?>index.php/estadisticas/<?php echo $importe; ?>/<?php echo $mes ?>/<?php echo $anno ?>/' + idusuario + '/';

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
                    //Obtener importe

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
                
                $("#estado_proceso").html('Procesando '+ (i+1) +' de ' + usuarios.length + ' <?php echo $tipo_usuario; ?>.');

            }    
            
            var imp_total = imp_total_d + imp_total_a + imp_total_c;
            contenido +='<thead class="ui-widget-header"><tr><td colspan="2"><label ><strong>Totales</strong></label>&nbsp;<label> Trabajadores - '+arrLineas.length+'</label>';
            contenido +='</td><td align="right" >$&nbsp;'+imp_total_d.toFixed(2)+'</td>';
            contenido +='</td><td align="right" >$&nbsp;'+imp_total_a.toFixed(2)+'</td>';
            contenido +='</td><td align="right" >$&nbsp;'+imp_total_c.toFixed(2)+'</td>';
            contenido +='</td><td align="right" >$&nbsp;'+imp_total.toFixed(2)+'</td>';
            contenido +='</tr></thead>';

            contenido +='</table>'; 
            $( "#listado_dia" ).html(contenido);
        }

        function carga_listado_exp(fecha_,formato){
            myurl = '<?php echo $ruta_url; ?>index.php/estadisticas/listado_mensual_'+formato+'/<?php echo $mes ?>/<?php echo $anno ?>/';

            var datos = "cantidad=" + arrLineas.length;

            for(i=0;i < arrLineas.length; i++){
                datos += '&linea'+i+'='+arrLineas[i];
            }

            var contenido = $.ajax({
                type: "POST",
                async: false,
                data: datos,
                url: myurl
            }).responseText;
            return contenido;            
        }

        function eliminarListadoServer(filename_){              
            if(filename_!=undefined){
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
                $( "#notif_exp" ).html('<div style="text-align: center;"><h3>Exportado Correctamente</h3><h3><a target="_blank"  href="<?php echo $ruta_url;?>'+ mensaje +'">Click</a> para descargar.</h3></div>');

            },
            "Cerrar": function() {                
                eliminarListadoServer(mensaje);                
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
        carga_listado();

    </script>