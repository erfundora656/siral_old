<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<?php $par=false; 

?>
<table width="100%" class="ui-widget ui-widget-content" cellpadding="5" cellspacing="0">
    <thead class="ui-widget-header">
        <tr>
            <td><strong>Folio</strong></td>
            <td><strong>Nombre y apellidos</strong></td>
            <?php
            foreach ($platos_dias as $plato) {
            ?>
            <td align="right"><strong><?php echo($plato['nombreplato']);?></strong></td>
            <?php    
            }
            ?>
            <td align="right"><strong>Importe</strong></td>
        </tr>
    </thead>    
        <?php
        
        foreach ($arreglo_salida as $trabajador) {
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
            <td><?php echo($trabajador['codigo']);?></td>
            <td><?php echo($trabajador['nombres']);?>&nbsp;<?php echo($trabajador['apellidos']);?></td>
            <?php
            foreach ($platos_dias as $plato) {
            ?>
            <td align="right">
            <?php 
                $esta = false;
                foreach($trabajador['platos'] as $plato_s){
                    if($plato_s['idplato'] == $plato['idplato']){
                        $esta = true;
                    }
                }
                
             if ($esta){
                echo('$ '.number_format($plato['precio'],2));
             } else {
                echo('&nbsp;');
             }   
            ?>
            </td>
            <?php    
            }
            ?>
            <td align="right" ><strong><?php echo($trabajador['importe']);?></strong></td>
        </tr>
        <?php 
        $importe_total = 0;  
            $importe_total += $trabajador['importe'];
        }
        ?>
        <thead class="ui-widget-header">
            <tr>
                
                <td colspan="<?php echo(count($platos_dias) + 2) ?>"><strong>Total</strong></td>
                
                <td align="right" >$&nbsp;<?php echo(number_format($importe_total,2); ?></td>
            </tr>
        </thead>          
</table>
