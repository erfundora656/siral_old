<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<form id="feditar_dirigidos" method="post" acction="">
    <input type="hidden" id="iddirigido" value="<?php echo($dirigido['iddirigido']); ?>" />
    <table with="100%">
	<tr>
            <td>Cantidad:&nbsp;<input id="cantidad_dir" readonly value="1" type="text" size="5"/></td>
	</tr>
	<tr>
            <td>Detalles:</td>
        </tr>
        <tr>
            <td>
                <textarea id="detalles_dir" cols="80" rows="5"><?php echo($dirigido['detalles']); ?></textarea>
            </td>
	</tr>
    </table>
</form>
<div id="muestra_errores"></div>
<script type="text/javascript">
    $( "#cantidad_dir" ).spinner({min:1});
    $( "#cantidad_dir" ).val(<?php echo($dirigido['cantidad']); ?>);
</script>