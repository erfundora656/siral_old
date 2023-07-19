<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<table width="100%" class="ui-widget ui-widget-content" cellpadding="5" cellspacing="0">
    <thead class="ui-widget-header">
        <tr>
            
            <!--<td  style="font-size: 1.5em;">Listado de almuerzos</td>
                <td style="text-align: right;font-size: 1.2em;">
                <button id="b_list_alm_pdf">PDF</button>
                <button id="b_list_alm_print">Imprimir</button>
                Fecha:<input id="fecha_platos" size="10" type="text" readonly/>
            </td>-->
        </tr>
    </thead>
</table>
<br />
<div id="listado_dia"></div>

<script type="text/javascript">

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
                        });



$( "#fecha_platos" ).datepicker({changeMonth: true,changeYear: true});
$( "#fecha_platos" ).datepicker("option", "dateFormat", "dd/mm/yy");

// Detectar la fecha y ponerla en el datepicker

$("#fecha_platos").val('<?php echo date('d/m/Y') ?>');

function carga_listado(fecha_){
    myurl = '<?php echo $ruta_url; ?>index.php/admin_tickets/listado_almuerzo_dia/'+fecha_+'/';
    
    $( "#listado_dia" ).html('<div style="text-align: center;margin-top:150px;"><img width="100" src="../../../images/baile.gif"/><h3>Espere un momento...</h3></div>');
    var contenido = $.ajax({
        async: false,
        url: myurl
    }).responseText;

    $( "#listado_dia" ).html(contenido);
    
}

function carga_listado_pdf(fecha_){
    myurl = '<?php echo $ruta_url; ?>index.php/admin_tickets/listado_almuerzo_dia_pdf/'+fecha_+'/';

    var contenido = $.ajax({
        async: false,
        url: myurl
    }).responseText;
    return contenido;
    
}

$("#fecha_platos").change(function(){
    //alert($("#fecha_platos").val());
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


    fecha = $("#fecha_platos").val();
    
    
    
    carga_listado(fecha);

</script>