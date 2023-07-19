<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<form id="feditar_trabajadores" method="post" action="">
    <input id="idtrabajador" type="hidden"  value="<?php echo($trabajador['idtrabajador']); ?>" />
    <table width="100%">            
	<tr>
            <td>usuario:</td>
            <td><input id="usuario" type="text" size="30" readonly value="<?php echo($trabajador['usuario']); ?>" /></td>
	</tr>
	<tr>
            <td>Código:</td>
            <td><input id="codigo" type="text" size="30" value="<?php echo($trabajador['codigo']); ?>" /></td>
	</tr>
	<tr>
            <td>Nombre:</td>
            <td><input id="nombres" type="text" size="30" value="<?php echo($trabajador['nombres']); ?>" /></td>
	</tr>
	<tr>
		<td>Apellidos:</td>
		<td><input id="apellidos" type="text" size="30" value="<?php echo($trabajador['apellidos']); ?>" /></td>
	</tr>
    </table>
</form>
<div id="muestra_errores"></div>
<script type="text/javascript">
//    activa_selectores_fecha();
    //alert('prueba');
</script>