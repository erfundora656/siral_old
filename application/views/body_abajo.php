<div id="dialogo_mensaje"></div>
<div id="dialogo_mensaje_2"></div>
<table width="100%">
    <tr>
        <td>&nbsp;</td>
        <td class="ui-widget-header"  width="1020">
            <div class="botton_bar" id="excercise-head-title"><span style="font-size: 1.2em;" >UNICA - UCLV. Todos los Derechos Reservados® <?php echo date("Y-m-d H:i:s") ?></span></div>            
        </td>
        <td>&nbsp;</td>
    </tr>
</table>    
<script type="text/javascript">
   

    function muestra_mensaje(texto, ancho,accion){
        $( "#dialogo_mensaje" ).dialog({
        width: ancho,
        autoOpen: false,
        modal: true,
        beforeClose: function(){
            if (accion != null) accion.accion();
        },
        buttons: {
                "Aceptar": function() {

                    $( "#dialogo_mensaje" ).html('');
                    $( "#dialogo_mensaje" ).dialog( "close" );
                }
        }
	});

        $( "#dialogo_mensaje" ).html(texto);

        $( "#dialogo_mensaje" ).dialog( "open" );

    }
</script>
</body>
</html>
