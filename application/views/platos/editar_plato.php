<?php
$cantidad_base = 10;
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<input type="hidden" size="35" id="idplato" value="<?php echo $plato['idplato']; ?>" />
<input type="hidden" size="35" id="fechaplato" value="<?php echo $plato['fecha']; ?>" />
<input type="hidden" size="35" id="sede_idsede" value="<?php echo $plato['sede_idsede']; ?>" />
<input type="hidden" size="35" id="turno" value="<?php echo $plato['turno']; ?>" />

<table>
    <tr>
        <td>Nombre: </td>
        <td><input type="text" size="35" id="nombreplato" value="<?php echo $plato['nombreplato']; ?>" /></td>
    </tr>
    <tr>
        <td>Precio: </td>
        <td>$&nbsp;<input type="text" size="10" id="precioplato" value="<?php echo $plato['precio']; ?>" /></td>
    </tr>
    <tr>
        <td>Cantidad: </td>
        <td>
            <input type="text" size="10" id="cantidadplato" value="<?php echo $plato['cantidad']; ?>" />&nbsp;
            <select id="unidadplato" >
                    <?php
                        foreach ($tipocantidades as $cantidad) {
                            if ($plato['tipocantidad_idtipocantidad'] == $cantidad['idtipocantidad']){
                            ?>
                <option value="<?php echo $cantidad['idtipocantidad']; ?>" selected ><?php echo $cantidad['simbolo']; ?> (<?php echo $cantidad['nombre']; ?>)</option>
                            <?php
                            } else {
                            ?>
                        <option value="<?php echo $cantidad['idtipocantidad']; ?>"><?php echo $cantidad['simbolo']; ?> (<?php echo $cantidad['nombre']; ?>)</option>
                            <?php
                            }
                        }
                    ?>
            </select>    

        </td>
    </tr>
</table>

<script type="text/javascript">
    
    $("#precioplato").decimalMask('999.99');
    $("#cantidadplato").decimalMask('999.99');
    
    function editar_plato(){

                idplato = $("#idplato").val();             
                nombre = jQuery.trim($("#nombreplato").val());             
                precio = $("#precioplato").val();             
                cantidad = $("#cantidadplato").val();             
                tipocantidad_idtipocantidad = $("#unidadplato").val();
                fecha = $("#fechaplato").val();
                sede=$("#sede_idsede").val();
                turno=$("#turno").val();

                var datos = "idplato=" + idplato + "&nombre=" + nombre + "&precio="+ precio;
                datos += "&cantidad=" + cantidad + "&tipocantidad_idtipocantidad="+ tipocantidad_idtipocantidad;
                datos += "&fecha=" + fecha;
                datos += "&sede_idsede=" + sede;
                datos += "&turno=" + turno;

                $.ajax({
                   type: "POST",
                   async: false,
                   url: "<?= $ruta_url; ?>index.php/platos/editar_plato/",
                   data: datos,
                   success: function(){
                        //location.reload();
                   }
                });
        
    }

</script>