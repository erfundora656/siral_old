<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<h2>Trabajadores</h2>
<table width="100%" cellpadding="5" cellspacing="3" border="0">
    <?php $i=0; ?>
        <tr>
    <?php
        foreach ($dirigidos_dia as $dirigido){
            for($j=0; $j < $dirigido['cantidad']; $j++){
                $i++;
                if($turno=="1"){
                echo ('<td align="center" style="border: 1px solid;">DIRIGIDO #'.$i.' - DESAYUNO<br/>'.$dirigido['detalles'].'<br />'.$fecha.'<br />$ '.number_format($precio, 2).'</td>');    
                }else if($turno=="2"){
                echo ('<td align="center" style="border: 1px solid;">DIRIGIDO #'.$i.' - ALMUERZO<br/>'.$dirigido['detalles'].'<br />'.$fecha.'<br />$ '.number_format($precio, 2).'</td>');    
                }else if($turno=="3"){
                echo ('<td align="center" style="border: 1px solid;">DIRIGIDO #'.$i.' - COMIDA<br/>'.$dirigido['detalles'].'<br />'.$fecha.'<br />$ '.number_format($precio, 2).'</td>');    
                }
                
                if(($i % 5) == 0) echo ('</tr>');
            }
        }
        if($i % 5 != 0) echo ('</tr>');
    ?>
</table> 
