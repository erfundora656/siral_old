<?php $par=false; ?>
<table width="100%" class="ui-widget ui-widget-content" cellpadding="5" cellspacing="0">
    <thead class="ui-widget-header">
        <td>Fecha</td>    
        <td>Hora</td>    
        <td>IP</td>    
        <td>Usuario</td>    
        <td>Acción</td>    
    </thead>
<?php
foreach ($logs as $log) {
    
    $arrLog = explode('|', $log);
    $fecha_hora = explode('_', $arrLog[0]); 
    
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
            echo ($fecha_hora[2].'/'.$fecha_hora[1].'/'.$fecha_hora[0]);
        ?>
    </td>
    <td>
        <?php 
            echo ($fecha_hora[3].':'.$fecha_hora[4].':'.$fecha_hora[5]);
        ?>
    </td>
    <td>
        <?php 
            echo ($arrLog[1]);
        ?>
    </td>
    <td>
        <?php 
            echo ($arrLog[2]);
        ?>
    </td>
    <td>
        <?php 
            echo ($arrLog[3]);
        ?>
    </td>
</tr>
    <?php    
}

?>

</table>
