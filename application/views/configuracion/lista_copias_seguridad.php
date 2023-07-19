<?php $par=false; ?>
<table width="100%" class="ui-widget ui-widget-content" cellpadding="5" cellspacing="0">
    <thead class="ui-widget-header">
        <td>Archivo</td>
        <td>Fecha</td>    
        <td>&nbsp;</td>
    </thead>
<?php
$i = 0;
if($archivos){
foreach ($archivos as $archivo) {
    ?>
    <?php
          if ($par){
    ?>
    <tr class ="fila_par">
    <?php
          $par = false;    
          } else {
    ?>
    <tr class ="fila_impar">
    <?php
          $par=true;
          }
    ?>
    <td>
        <?php echo ($archivo['name'])?>
    </td>
    <td>
        <?php echo(date('Y-m-d h:i:s',$archivo['date'])); ?>
    </td>    
    <td style="text-align: right;">
        <button id="b_Descargar_CS_<?php echo $i; ?>">Descargar</button>
        &nbsp;
        <button id="b_Eliminar_CS_<?php echo $i; ?>">Eliminar</button>
    </td>
<script type="text/javascript">
             $( "#b_Descargar_CS_<?php echo $i; ?>" ).button({
                            text: true,
                            icons: {
                                    primary: "ui-icon-disk"
                            }
                        }).click(function(){
                            window.open('<?php echo $ruta_url;?>index.php/configuracion/descargar_archivo/?archivo=CSeguridad/<?php echo ($archivo['name'])?>&nombre=<?php echo ($archivo['name'])?>');
                        });

             $( "#b_Eliminar_CS_<?php echo $i; ?>" ).button({
                            text: true,
                            icons: {
                                    primary: "ui-icon-trash"
                            }
                        }).click(function(){
                            eliminar_cs('<?php echo ($archivo['name'])?>');
                        });

</script>
</tr>
    <?php    
    $i++;
}
}
?>

</table>
