<?php

?>
<div id="configuracion_tabs">
	<ul>
            <li><a href="<?php echo $ruta_url;?>index.php/configuracion/general/">General</a></li>
            <li><a href="<?php echo $ruta_url;?>index.php/configuracion/copia_serguridad/">Copias de Seguridad</a></li>
            <li><a href="<?php echo $ruta_url;?>index.php/configuracion/documentos/">Reportes</a></li>
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