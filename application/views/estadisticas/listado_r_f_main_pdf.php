<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<html lang="en">
<head>
    <meta content="text/html; charset=UTF-8" http-equiv="Content-Type"></meta>
<head>
<body>    
<div class="WordSection1" style="font-family: Arial,sans-serif;font-size: 0.8em;">
<p>
    <span style="font-size: 1.5em;"><?php echo $nombre_entidad;?></span><br />
    <span style="font-size: 1.2em;"><?php echo $organismo;?></span>
</p>
<p><span style="font-size: 1.3em;">Consumo de alimentación de <?php echo $dia_i.'/'.$mes_i.'/'.$anno_i; ?> a <?php echo $dia_f.'/'.$mes_f.'/'.$anno_f; ?>.</span></p><br />

<table width="100%" class="ui-widget ui-widget-content" cellpadding="5" cellspacing="0">
    <thead class="ui-widget-header">
        <tr>
            <td style="border-bottom: 1px solid;border-top: 1px solid;vertical-align: top;"><strong>Código</strong></td>
            <td style="border-bottom: 1px solid;border-top: 1px solid;vertical-align: top;"><strong>Nombre y apellidos</strong></td>
            <td style="border-bottom: 1px solid;border-top: 1px solid;vertical-align: top;" align="right"><strong>Desayunos</strong></td>
            <td style="border-bottom: 1px solid;border-top: 1px solid;vertical-align: top;" align="right"><strong>Almuerzos</strong></td>
            <td style="border-bottom: 1px solid;border-top: 1px solid;vertical-align: top;" align="right"><strong>Comidas</strong></td>
            <td style="border-bottom: 1px solid;border-top: 1px solid;vertical-align: top;" align="right"><strong>Importe</strong></td>
        </tr>
    </thead>
    <?php 
        for ($i=0;$i < $cantidad; $i++){
            $arrValores = explode('|', $arrDatos['linea'.$i]);
            ?>
        <tr>
            <td style="border-bottom: 1px solid;"><strong><?php echo $arrValores[0]; ?></strong></td>
            <td style="border-bottom: 1px solid;"><strong><?php echo $arrValores[1]; ?></strong></td>
            <td style="border-bottom: 1px solid;" align="right"><strong>$&nbsp;<?php echo $arrValores[2]; ?></strong></td>
            <td style="border-bottom: 1px solid;" align="right"><strong>$&nbsp;<?php echo $arrValores[3]; ?></strong></td>
            <td style="border-bottom: 1px solid;" align="right"><strong>$&nbsp;<?php echo $arrValores[4]; ?></strong></td>
            <td style="border-bottom: 1px solid;" align="right"><strong>$&nbsp;<?php echo $arrValores[5]; ?></strong></td>
        </tr>
            <?php
        }
    ?>
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