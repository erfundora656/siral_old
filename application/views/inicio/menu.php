<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Plataforma de gestión para la reservación de alimentos - UCLV</title>
</head>
<body>
<div style="font-family: Arial,Helvetica,Arial,sans-serif;font-size: 0.8em;">

<table width="100%" cellpadding="3" cellspacing="0">
    <thead>
        <tr>
            <td align="center" style="font-size: 1.2em;">Almuerzos</td>
        </tr>
    </thead>
</table>    
<table width="100%" cellpadding="5">
    <thead>
    <tr>
        <td colspan="2"><div class="titulo_dia"><strong>Menú de hoy&nbsp;</strong>(<?php echo(date('d/m/Y',  time()));?>)</div></td>
    </tr>
    </thead>
    
        
        <?php 
            $precio_hoy = 0;
            if(count($menu_hoy) < 1){
                echo '<h3>No hay platos definidos para hoy.</h3>';
            } else {
                for ($i=0; $i < count($menu_hoy); $i++) {
                    $plato = $menu_hoy[$i];
                    $precio_hoy += $plato['precio'];
                        echo '<tr><td valign="top"><div class="texto_plato" style="display: flex;">'.$plato['nombreplato'].' '.$plato['cantidad'].' '.$plato['simbolo'].'</td><td valign="top"> <div style="padding:0 3px; float: right;">$'.number_format($plato['precio'], 2) .'</div></div></td></tr>';
                }
            }
        ?>
        
    
    <thead>
    <tr>
        <td><strong><div id="precio_hoy">Precio: </strong></td><td valign="top"><strong><div style="padding:0 3px; float: right;">$<?php echo number_format($precio_hoy, 2); ?></div></div></strong></td>
    </tr>
    </thead>
</table>
</div>
</body>
