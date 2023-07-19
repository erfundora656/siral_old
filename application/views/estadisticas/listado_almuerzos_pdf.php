<html lang="en">
<head>
    <meta content="text/html; charset=UTF-8" http-equiv="Content-Type"></meta>
<head>
<body>    
<div class="WordSection1" style="font-family: Arial,sans-serif;font-size: 0.8em;">
<table width="100%" cellpadding="5" cellspacing="0">
        <tr>
           <td style="font-size: 1.5em;">Listado de almuerzos</td>
            
            <td style="text-align: right;font-size: 1.5em;">
                Fecha:<?php echo $fecha; ?>
            </td>
        </tr>
</table>
<table width="100%" cellpadding="3" cellspacing="0">
        <tr>
            <td style="border-bottom: 1px solid;border-top: 1px solid;vertical-align: top;"><strong>Folio</strong></td>
            <td style="border-bottom: 1px solid;border-top: 1px solid;vertical-align: top;"><strong>Nombre y apellidos</strong></td>
            <?php
            foreach ($platos_dias as $plato) {
            ?>
            <td style="border-bottom: 1px solid;border-top: 1px solid;vertical-align: top;" align="right"><strong><?php echo($plato['nombreplato']);?></strong></td>
            <?php    
            }
            ?>
            <td style="border-bottom: 1px solid;border-top: 1px solid;vertical-align: top;" align="right"><strong>Importe</strong></td>
        </tr>
        <?php
        foreach ($arreglo_salida as $trabajador) {
        ?>
    <tr>
            <td style="border-bottom: 1px solid;"><?php echo($trabajador['codigo']);?></td>
            <td style="border-bottom: 1px solid;"><?php echo($trabajador['nombres']);?> <?php echo($trabajador['apellidos']);?></td>
            <?php
            foreach ($platos_dias as $plato) {
            ?>
            <td style="border-bottom: 1px solid;" align="right">
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
            <td style="border-bottom: 1px solid;" align="right" ><strong><?php echo($trabajador['importe']);?></strong></td>
        </tr>
        <?php    
        }
        ?>
</table>
</div>
 
</body>
</html>
