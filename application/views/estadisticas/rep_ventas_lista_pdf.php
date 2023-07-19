<html lang="en">
<head>
    <meta content="text/html; charset=UTF-8" http-equiv="Content-Type"></meta>
<head>
<body>    
<div class="WordSection1" style="font-family: Arial,sans-serif;font-size: 0.8em;">
<table width="100%" cellpadding="5" cellspacing="0">
        <tr>
            <td style="font-size: 1.5em;">Reporte diario de ventas</td>
            <td style="text-align: right;font-size: 1.5em;">
                Fecha:<?php echo $fecha; ?>
            </td>
        </tr>
</table>
<div style="padding: 5px;font-size: 1.5em;"><strong>Desayunos</strong></div>
<table width="100%" cellpadding="5" cellspacing="0">
        <tr>
            <td rowspan="2" style="border-bottom: 1px solid;border-top: 1px solid;vertical-align: top;" width="50%"><strong>Menú</strong></td>
            <td rowspan="2" style="border-bottom: 1px solid;border-top: 1px solid;vertical-align: top;" align="right"><strong>Medida</strong></td>
            <td rowspan="2" style="border-bottom: 1px solid;border-top: 1px solid;vertical-align: top;" align="right"><strong>Precio</strong></td>
            <td colspan="2" style="border-top: 1px solid;vertical-align: top;" align="center"><strong>Trabajadores</strong></td>
            <td rowspan="2" style="border-bottom: 1px solid;border-top: 1px solid;vertical-align: top;" align="right"><strong>Total</strong></td>
        </tr>
        <tr>
            <td align="right" style="border-bottom: 1px solid;"><strong>Dirigidos</strong></td>
            <td align="right" style="border-bottom: 1px solid;"><strong>Tickets</strong></td>
            
        </tr>
        <?php
        $importe_total_d = 0;
        $importe_ticket_d = 0;
        $precio_ticket_d = 0;
        foreach ($datos_platos_1 as $plato) {
        ?>
        <tr>
            <td style="border-bottom: 1px solid;"><?php echo($plato['nombre']);?></td>
            <td style="border-bottom: 1px solid;" align="right" ><?php echo($plato['cantidad']);?>&nbsp;<?php echo($plato['simbolo']);?></td>
            <td style="border-bottom: 1px solid;" align="right" >$&nbsp;<?php echo(number_format($plato['precio'],2));?></td>
            <td style="border-bottom: 1px solid;" align="right" ><?php echo($cantidad_dirigidos1);?></td>
            <td style="border-bottom: 1px solid;" align="right" ><?php echo($plato['cant_tickets']);?></td>
            <td align="right" ><?php echo($plato['cant_tickets'] + $cantidad_dirigidos1);?></td>
            
        </tr>
        <?php
            $importe_total_d += $plato['importe'] + ($cantidad_dirigidos1 * $plato['precio']);
            $precio_ticket_d += $plato['precio'];
            $importe_ticket_d += $plato['importe'];
        }
        ?>
    <thead>
        <tr>
            <td colspan="2" style="border-bottom: 1px solid;"><strong>Total</strong></td>
            <td align="right" style="border-bottom: 1px solid;" >$<?php echo(number_format($precio_ticket_d,2));?></td>
            <td align="right" style="border-bottom: 1px solid;" >$<?php echo(number_format($cantidad_dirigidos1 * $precio_ticket_d,2));?></td>
            <td align="right" style="border-bottom: 1px solid;" >$<?php echo(number_format($importe_ticket_d,2));?></td>
            <td align="right" style="border-bottom: 1px solid;"><strong>$<?php echo(number_format($importe_total_d,2));?></strong></td>
        </tr>
    </thead>    
</table>
<br /><br />
<div style="padding: 5px;font-size: 1.5em;"><strong>Almuerzos</strong></div>
<table width="100%" cellpadding="5" cellspacing="0">
        <tr>
            <td rowspan="2" style="border-bottom: 1px solid;border-top: 1px solid;vertical-align: top;" width="50%"><strong>Menú</strong></td>
            <td rowspan="2" style="border-bottom: 1px solid;border-top: 1px solid;vertical-align: top;" align="right"><strong>Medida</strong></td>
            <td rowspan="2" style="border-bottom: 1px solid;border-top: 1px solid;vertical-align: top;" align="right"><strong>Precio</strong></td>
            <td colspan="2" style="border-top: 1px solid;vertical-align: top;" align="center"><strong>Trabajadores</strong></td>
            <td rowspan="2" style="border-bottom: 1px solid;border-top: 1px solid;vertical-align: top;" align="right"><strong>Total</strong></td>
        </tr>
        <tr>
            <td align="right" style="border-bottom: 1px solid;"><strong>Dirigidos</strong></td>
            <td align="right" style="border-bottom: 1px solid;"><strong>Tickets</strong></td>
            
        </tr>
        <?php
        $importe_total_a = 0;
        $importe_ticket_a = 0;
        $precio_ticket_a = 0;
        foreach ($datos_platos_2 as $plato) {
        ?>
        <tr>
            <td style="border-bottom: 1px solid;"><?php echo($plato['nombre']);?></td>
            <td style="border-bottom: 1px solid;" align="right" ><?php echo($plato['cantidad']);?>&nbsp;<?php echo($plato['simbolo']);?></td>
            <td style="border-bottom: 1px solid;" align="right" >$&nbsp;<?php echo(number_format($plato['precio'],2));?></td>
            <td style="border-bottom: 1px solid;" align="right" ><?php echo($cantidad_dirigidos2);?></td>
            <td style="border-bottom: 1px solid;" align="right" ><?php echo($plato['cant_tickets']);?></td>
            <td align="right" ><?php echo($plato['cant_tickets'] + $cantidad_dirigidos2);?></td>
            
        </tr>
        <?php
            $importe_total_a += $plato['importe'] + ($cantidad_dirigidos2 * $plato['precio']);
            $precio_ticket_a += $plato['precio'];
            $importe_ticket_a += $plato['importe'];
        }
        ?>
    <thead>
        <tr>
            <td colspan="2" style="border-bottom: 1px solid;"><strong>Total</strong></td>
            <td align="right" style="border-bottom: 1px solid;" >$<?php echo(number_format($precio_ticket_a,2));?></td>
            <td align="right" style="border-bottom: 1px solid;" >$<?php echo(number_format($cantidad_dirigidos2 * $precio_ticket_a,2));?></td>
            <td align="right" style="border-bottom: 1px solid;" >$<?php echo(number_format($importe_ticket_a,2));?></td>
            <td align="right" style="border-bottom: 1px solid;"><strong>$<?php echo(number_format($importe_total_a,2));?></strong></td>
        </tr>
    </thead>    
</table>
<br /><br />
<div style="padding: 5px;font-size: 1.5em;"><strong>Comidas</strong></div>
<table width="100%" cellpadding="5" cellspacing="0">
        <tr>
            <td rowspan="2" style="border-bottom: 1px solid;border-top: 1px solid;vertical-align: top;" width="50%"><strong>Menú</strong></td>
            <td rowspan="2" style="border-bottom: 1px solid;border-top: 1px solid;vertical-align: top;" align="right"><strong>Medida</strong></td>
            <td rowspan="2" style="border-bottom: 1px solid;border-top: 1px solid;vertical-align: top;" align="right"><strong>Precio</strong></td>
            <td colspan="2" style="border-top: 1px solid;vertical-align: top;" align="center"><strong>Trabajadores</strong></td>
            
            <td rowspan="2" style="border-bottom: 1px solid;border-top: 1px solid;vertical-align: top;" align="right"><strong>Total</strong></td>
        </tr>
        <tr>
            <td align="right" style="border-bottom: 1px solid;"><strong>Dirigidos</strong></td>
            <td align="right" style="border-bottom: 1px solid;"><strong>Tickets</strong></td>
           
        </tr>
        <?php
        $importe_total_c = 0;
        $importe_ticket_c = 0;
        $precio_ticket_c = 0;

        foreach ($datos_platos_3 as $plato) {
        ?>
        <tr>
            <td style="border-bottom: 1px solid;"><?php echo($plato['nombre']);?></td>
            <td style="border-bottom: 1px solid;" align="right" ><?php echo($plato['cantidad']);?>&nbsp;<?php echo($plato['simbolo']);?></td>
            <td style="border-bottom: 1px solid;" align="right" >$&nbsp;<?php echo(number_format($plato['precio'],2));?></td>
            <td style="border-bottom: 1px solid;" align="right" ><?php echo($cantidad_dirigidos3);?></td>
            <td style="border-bottom: 1px solid;" align="right" ><?php echo($plato['cant_tickets']);?></td>
            <td align="right" ><?php echo($plato['cant_tickets'] + $cantidad_dirigidos3);?></td>
           
        </tr>
        <?php  
            $importe_total_c += $plato['importe'] + ($cantidad_dirigidos3 * $plato['precio']);
            $precio_ticket_c += $plato['precio'];
            $importe_ticket_c += $plato['importe'];
        }
        ?>
    <thead>
        <tr>
            <td colspan="2" style="border-bottom: 1px solid;"><strong>Total</strong></td>
            <td align="right" style="border-bottom: 1px solid;" >$<?php echo(number_format($precio_ticket_c,2));?></td>
            <td align="right" style="border-bottom: 1px solid;" >$<?php echo(number_format($cantidad_dirigidos3 * $precio_ticket_c,2));?></td>
            <td align="right" style="border-bottom: 1px solid;" >$<?php echo(number_format($importe_ticket_c,2));?></td>
          
            <td align="right" style="border-bottom: 1px solid;"><strong>$<?php echo(number_format($importe_total_c,2));?></strong></td>
        </tr>
    </thead>    
</table>
<p><br /><br /><br /><br /></p>
<p style="text-align:center;">
__________________________________<br />
<?php echo $nombre_firma;?><br />
<?php echo $cargo_firma;?>
</p>

</div>
 
</body>
</html>