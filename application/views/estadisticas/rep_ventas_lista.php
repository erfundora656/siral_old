<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
//print_r($cantidad_est_a);
?>
<div style="padding: 5px;font-size: 1.5em;"><strong>Desayunos&nbsp;</strong><?php if($cantidad_prereservaciones_1 > 0){ echo " - preresrvas: ".$cantidad_prereservaciones_1;}?>
</div>
<?php $par=false; ?>
<table width="100%" class="ui-widget ui-widget-content" cellpadding="5" cellspacing="0">
    <thead class="ui-widget-header">
        <tr>
            <td rowspan="2" width="50%"><strong>Menú</strong></td>
            <td rowspan="2" align="right"><strong>Medida</strong></td>
            <td rowspan="2" align="right"><strong>Precio</strong></td>
            <td colspan="2" align="center"><strong>Trabajadores</strong></td>        
            <td rowspan="2" align="right"><strong>Total</strong></td>
        </tr>
        <tr>
            <td align="right"><strong>Dirigidos</strong></td>
            <td align="right"><strong>Tickets</strong></td>
            
            
        </tr>
    </thead>    
    <?php
    $importe_total_d = 0;
    $importe_ticket_d = 0;
    $precio_ticket_d = 0;

    foreach ($datos_platos_1 as $plato) {
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
                <td><?php echo($plato['nombre']);?></td>
                <td align="right" ><?php echo($plato['cantidad']);?>&nbsp;<?php echo($plato['simbolo']);?></td>
                <td align="right" >$&nbsp;<?php echo(number_format($plato['precio'],2));?></td>
                <td align="right" ><?php echo($cantidad_dirigidos1);?></td>
                <td align="right" ><?php echo($plato['cant_tickets']);?></td>

                <td align="right" ><?php echo($plato['cant_tickets'] + $cantidad_dirigidos1);?></td>

            </tr>
            <?php 
            $importe_total_d += $plato['importe'] + ($cantidad_dirigidos1 * $plato['precio']);
            $precio_ticket_d += $plato['precio'];
            $importe_ticket_d += $plato['importe'];
        }
        ?>
        <thead class="ui-widget-header">
            <tr>
                <td colspan="2"><strong>Total</strong></td>
                <td align="right" >$&nbsp;<?php echo(number_format($precio_ticket_d,2));?></td>
                <td align="right" >$&nbsp;<?php echo(number_format($cantidad_dirigidos1 * $precio_ticket_d,2));?></td>
                <td align="right" >$&nbsp;<?php echo(number_format($importe_ticket_d,2));?></td>

                <td align="right" >$&nbsp;<?php echo(number_format($importe_total_d ,2));?></td>
            </tr>
        </thead>    
    </table>

    <div style="padding: 5px;font-size: 1.5em;"><strong>Almuerzos&nbsp;</strong><?php if($cantidad_prereservaciones_2 > 0){ echo " - preresrvas: ".$cantidad_prereservaciones_2;}?></div>
    <?php $par=false; ?>
    <table width="100%" class="ui-widget ui-widget-content" cellpadding="5" cellspacing="0">
        <thead class="ui-widget-header">
            <tr>
                <td rowspan="2" width="50%"><strong>Menú</strong></td>
                <td rowspan="2" align="right"><strong>Medida</strong></td>
                <td rowspan="2" align="right"><strong>Precio</strong></td>
                <td colspan="2" align="center"><strong>Trabajadores</strong></td>           
                <td rowspan="2" align="right"><strong>Total</strong></td>
            </tr>
            <tr>
                <td align="right"><strong>Dirigidos</strong></td>
                <td align="right"><strong>Tickets</strong></td>

            </tr>
        </thead>    
        <?php
        $importe_total_a = 0;
        $importe_ticket_a = 0;
        $precio_ticket_a = 0;
        foreach ($datos_platos_2 as $plato) {
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
                    <td><?php echo($plato['nombre']);?></td>
                    <td align="right" ><?php echo($plato['cantidad']);?>&nbsp;<?php echo($plato['simbolo']);?></td>
                    <td align="right" >$&nbsp;<?php echo(number_format($plato['precio'],2));?></td>
                    <td align="right" ><?php echo($cantidad_dirigidos2);?></td>
                    <td align="right" ><?php echo($plato['cant_tickets']);?></td>     
                    <td align="right" ><?php echo($plato['cant_tickets'] + $cantidad_dirigidos2);?></td>  
                </tr>
                <?php
                $importe_total_a += $plato['importe'] + ($cantidad_dirigidos2 * $plato['precio']);
                $precio_ticket_a += $plato['precio'];
                $importe_ticket_a += $plato['importe'];
            }
            ?>
            <thead class="ui-widget-header">
                <tr>
                    <td colspan="2"><strong>Total</strong></td>
                    <td align="right" >$&nbsp;<?php echo(number_format($precio_ticket_a,2));?></td>
                    <td align="right" >$&nbsp;<?php echo(number_format($cantidad_dirigidos2 * $precio_ticket_a,2));?></td>
                    <td align="right" >$&nbsp;<?php echo(number_format($importe_ticket_a,2));?></td>

                    <td align="right" >$&nbsp;<?php echo(number_format($importe_total_a,2));?></td>

                </tr>
            </thead>    
        </table>

        <div style="padding: 5px;font-size: 1.5em;"><strong>Comidas&nbsp;</strong><?php if($cantidad_prereservaciones_3 > 0){ echo " - preresrvas: ".$cantidad_prereservaciones_3;}?></div>
        <?php $par=false; ?>
        <table width="100%" class="ui-widget ui-widget-content" cellpadding="5" cellspacing="0">
            <thead class="ui-widget-header">
                <tr>
                    <td rowspan="2" width="50%"><strong>Menú</strong></td>
                    <td rowspan="2" align="right"><strong>Medida</strong></td>
                    <td rowspan="2" align="right"><strong>Precio</strong></td>
                    <td colspan="2" align="center"><strong>Trabajadores</strong></td>        
                    <td rowspan="2" align="right"><strong>Total</strong></td>
                </tr>
                <tr>
                    <td align="right"><strong>Dirigidos</strong></td>
                    <td align="right"><strong>Tickets</strong></td>

                </tr>
            </thead>    
            <?php
            $importe_total_c = 0;
            $importe_ticket_c = 0;
            $precio_ticket_c = 0;
            foreach ($datos_platos_3 as $plato) {
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
                        <td><?php echo($plato['nombre']);?></td>
                        <td align="right" ><?php echo($plato['cantidad']);?>&nbsp;<?php echo($plato['simbolo']);?></td>
                        <td align="right" >$&nbsp;<?php echo(number_format($plato['precio'],2));?></td>
                        <td align="right" ><?php echo($cantidad_dirigidos3);?></td>
                        <td align="right" ><?php echo($plato['cant_tickets']);?></td>

                        <td align="right" ><?php echo($plato['cant_tickets'] + $cantidad_dirigidos3);?></td>
                    </tr>
                    <?php 
                    $importe_total_c += $plato['importe'] + ($cantidad_dirigidos3 * $plato['precio']);
                    $precio_ticket_c += $plato['precio'];
                    $importe_ticket_c += $plato['importe'];
                }
                ?>
                <thead class="ui-widget-header">
                    <tr>
                        <td colspan="2"><strong>Total</strong></td>
                        <td align="right" >$&nbsp;<?php echo(number_format($precio_ticket_c,2));?></td>
                        <td align="right" >$&nbsp;<?php echo(number_format($cantidad_dirigidos3 * $precio_ticket_c,2));?></td>
                        <td align="right" >$&nbsp;<?php echo(number_format($importe_ticket_c,2));?></td>

                        <td align="right" >$&nbsp;<?php echo(number_format($importe_total_c ,2));?></td>
                    </tr>
                </thead> 

                <tr>
                    <td>
                        <div style="padding: 5px;font-size: 1.5em;">
                        <?php if($cantidad_prereservaciones_1 > 0 || $cantidad_prereservaciones_2 > 0 || $cantidad_prereservaciones_3 > 0){
                        echo ("<strong>Total de prereservaciones:&nbsp;</strong>".($cantidad_prereservaciones_1+$cantidad_prereservaciones_2+$cantidad_prereservaciones_3));
                        }?>
                        </div>
                    </td>
                </tr> 

            </table>
