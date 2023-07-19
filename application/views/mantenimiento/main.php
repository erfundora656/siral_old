<?php

?>
<div id="configuracion_tabs">
	<ul>
            <li><a href="<?php echo $ruta_url;?>index.php/mantenimiento/revisar_trabajadores/">Comprobar trabajadores</a></li>
            
            <li><a href="<?php echo $ruta_url;?>index.php/mantenimiento/acciones_lote/">Acciones en lote</a></li>
      	</ul>
</div>
<script type="text/javascript">
		$( "#configuracion_tabs" ).tabs({
			ajaxOptions: {
				error: function( xhr, status, index, anchor ) {
					$( anchor.hash ).html(
						"No disponible en la versión demo." );
				}
			}
		});
</script>