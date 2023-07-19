<?php $par=false; ?>
<table width="100%" class="ui-widget ui-widget-content" cellpadding="5" cellspacing="0">
    <thead class="ui-widget-header">
        <td>Fecha</td>    
    </thead>
<?php
$i = 0;
foreach ($archivos as $archivo) {
    $nombrearchivo = explode('.', $archivo['name']);
    
    $arrNombre = explode('_', $nombrearchivo[0]); 
    
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
        <?php 
            echo ($arrNombre[2].'/'.$arrNombre[1].'/'.$arrNombre[0].' - ');
            if($arrNombre[3]=='admin'){
                echo('Administración');
            } else {
                echo('Panel principal');
            }
        ?>
    </td>
</tr>
    <?php    
    $i++;
}

?>

</table>
