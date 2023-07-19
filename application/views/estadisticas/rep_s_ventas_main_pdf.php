<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

 $dia_semana = date('N', $fecha);
 //$inicio_semana = $fecha - (($dia_semana - 1) * 3600*24);
 $fin_semana = $inicio_semana + 6 * 3600*24;

function carga_menu_d($lista_dia,$numero_dia, $precio_alumnos){
        if(count($lista_dia['datos_platos']) < 1){
            echo '<table width="100%" cellpadding="5" cellspacing="0">
                        <tr>
                            <td style="text-align:center;"><h2>No hay platos definidos para ese día.</h2></td>
                        </tr>
                  </table> ';
        } else {
            $par=false;
            echo '<table width="100%" cellpadding="5" cellspacing="0">
                    <thead>
                        <tr>
                            <td rowspan="2" style="border-bottom: 1px solid;border-top: 1px solid;vertical-align: top;" width="50%"><strong>Menu</strong></td>
                            <td rowspan="2" style="border-bottom: 1px solid;border-top: 1px solid;vertical-align: top;" align="right"><strong>Medida</strong></td>
                            <td rowspan="2" style="border-bottom: 1px solid;border-top: 1px solid;vertical-align: top;" align="right"><strong>Precio</strong></td>
                            <td colspan="2" style="border-top: 1px solid;vertical-align: top;" align="center"><strong>Trabajadores</strong></td>
                          
                            <td rowspan="2" style="border-bottom: 1px solid;border-top: 1px solid;vertical-align: top;" align="right"><strong>Total</strong></td>
                        </tr>
                        <tr>
                            <td style="border-bottom: 1px solid;" align="right"><strong>Dirigidos</strong></td>
                            <td style="border-bottom: 1px solid;" align="right"><strong>Tickets</strong></td>
                           
                        </tr>
                    </thead>';
            $importe_total = 0;
            $importe_ticket = 0;
            $precio_ticket = 0;
            for ($i=0; $i < count($lista_dia['datos_platos']); $i++) {
                $plato = $lista_dia['datos_platos'][$i];
                  if ($par){
                    echo'<tr class ="fila_par">';
                    $par = false;    
                  } else {
                    echo'<tr class ="fila_impar">';
                  $par=true;
                  }
                  
               $total_a_c = $plato['cant_tickets']+$lista_dia['cantidad_dirigidos'];
                  
                echo '
                            <td style="border-bottom: 1px solid;">'.$plato['nombre'].'</td>
                            <td style="border-bottom: 1px solid;" align="right" >'.$plato['cantidad'].'&nbsp;'.$plato['simbolo'].'</td>
                            <td style="border-bottom: 1px solid;" align="right" >$'.number_format($plato['precio'],2).'</td>
                            <td style="border-bottom: 1px solid;" align="right" >'.$lista_dia['cantidad_dirigidos'].'</td>
                            <td style="border-bottom: 1px solid;" align="right" >'.$plato['cant_tickets'].'</td>
                            
                            <td style="border-bottom: 1px solid;" align="right" >'.$total_a_c.'</td>
                        </tr>';
                $importe_total += $plato['importe'] + ($lista_dia['cantidad_dirigidos'] * $plato['precio']);
                $precio_ticket += $plato['precio'];
                $importe_ticket += $plato['importe'];
            }
            echo'    <thead>
                        <tr>
                            <td style="border-bottom: 1px solid;" colspan="2"><strong>Total</strong></td>
                            <td style="border-bottom: 1px solid;" align="right" >$'.number_format($precio_ticket,2).'</td>
                            <td style="border-bottom: 1px solid;" align="right" >$'.number_format($lista_dia['cantidad_dirigidos'] * $precio_ticket,2).'</td>
                            <td style="border-bottom: 1px solid;" align="right" >$'.number_format($importe_ticket,2).'</td>
                            
                            <td style="border-bottom: 1px solid;" align="right" >$'.number_format($importe_total,2).'</td>
                        </tr>
                    </thead>
                </table>';
        }
    
} 

function carga_menu_d_comida($lista_dia,$numero_dia, $precio_alumnos){
        if(count($lista_dia['datos_plato_comidas']) < 1){
            echo '<table width="100%" class="ui-widget ui-widget-content" cellpadding="5" cellspacing="0">
                        <tr>
                            <td style="text-align:center;"><h2>No hay platos definidos para ese día.</h2></td>
                        </tr>
                  </table> ';
        } else {
            $par=false;
            echo '<table width="100%" class="ui-widget ui-widget-content" cellpadding="5" cellspacing="0">
                    <thead class="ui-widget-header">
                        <tr>
                            <td rowspan="2" style="border-bottom: 1px solid;border-top: 1px solid;vertical-align: top;" width="50%"><strong>Menu</strong></td>
                            <td rowspan="2" style="border-bottom: 1px solid;border-top: 1px solid;vertical-align: top;" align="right"><strong>Medida</strong></td>
                            <td rowspan="2" style="border-bottom: 1px solid;border-top: 1px solid;vertical-align: top;" align="right"><strong>Precio</strong></td>
                            <td colspan="2" style="border-top: 1px solid;vertical-align: top;" align="center"><strong>Trabajadores</strong></td>
                           
                            <td rowspan="2" style="border-bottom: 1px solid;border-top: 1px solid;vertical-align: top;" align="right"><strong>Total</strong></td>
                        </tr>
                        <tr>
                            <td style="border-bottom: 1px solid;" align="right"><strong>Dirigidos</strong></td>
                            <td style="border-bottom: 1px solid;" align="right"><strong>Tickets</strong></td>
                            
                        </tr>
                    </thead>';
            $importe_total_comida = 0;
            $importe_ticket = 0;
            $precio_ticket = 0;
            for ($i=0; $i < count($lista_dia['datos_plato_comidas']); $i++) {
                $plato = $lista_dia['datos_plato_comidas'][$i];
                  if ($par){
                    echo'<tr class ="fila_par">';
                    $par = false;    
                  } else {
                    echo'<tr class ="fila_impar">';
                  $par=true;
                  }
                  
                $total_a_c =  $plato['cant_tickets_comida']+$lista_dia['cantidad_dirigido_comidas'];
                
                echo '
                            <td style="border-bottom: 1px solid;">'.$plato['nombre'].'</td>
                            <td style="border-bottom: 1px solid;" align="right" >'.$plato['cantidad'].'&nbsp;'.$plato['simbolo'].'</td>
                            <td style="border-bottom: 1px solid;" align="right" >$&nbsp;'.number_format($plato['precio'],2).'</td>
                            <td style="border-bottom: 1px solid;" align="right" >'.$lista_dia['cantidad_dirigido_comidas'].'</td>
                            <td style="border-bottom: 1px solid;" align="right" >'.$plato['cant_tickets_comida'].'</td>
                        
                            <td style="border-bottom: 1px solid;" align="right" >'.$total_a_c.'</td>
                        </tr>';
                $importe_total_comida += $plato['importe_comida'] + ($lista_dia['cantidad_dirigido_comidas'] * $plato['precio']);
                $precio_ticket += $plato['precio'];
                $importe_ticket += $plato['importe_comida'];
            }
            echo'    <thead class="ui-widget-header">
                        <tr>
                            <td style="border-bottom: 1px solid;" colspan="2"><strong>Total</strong></td>
                            <td style="border-bottom: 1px solid;" align="right" >$'.number_format($precio_ticket,2).'</td>
                            <td style="border-bottom: 1px solid;" align="right" >$'.number_format($lista_dia['cantidad_dirigido_comidas'] * $precio_ticket,2).'</td>
                            <td style="border-bottom: 1px solid;" align="right" >$'.number_format($importe_ticket,2).'</td>
                            
                            <td style="border-bottom: 1px solid;" align="right" >$'.number_format($importe_total_comida,2).'</td>
                        </tr>
                    </thead>    
                     <tr>
                        <td colspan="5">&nbsp;</td>
                    </tr>
               </table>';
        }
    
} 


?>
<html lang="en">
<head>
    <meta content="text/html; charset=UTF-8" http-equiv="Content-Type"></meta>
<head>
<body>    
<div class="WordSection1" style="font-family: Arial,sans-serif;font-size: 0.8em;">
<table width="100%" cellpadding="5" cellspacing="0">
    <thead>
        <tr>
            <td style="font-size: 1.5em;">Reporte semanal de ventas</td>
            <td style="text-align: right;font-size: 1.2em;">
            </td>
            <td align="right" style="font-size: 1.2em;">
                <div style="display:inline-block;" id="fechas">
                    Del <?php echo(date('d/m/Y',$inicio_semana)); ?> al <?php echo(date('d/m/Y',$fin_semana)); ?>
                </div>
            </td>
        </tr>
    </thead>
</table>
<br />

<table width="100%" cellpadding="0">
    <tr>
        <td colspan="2" style="padding: 5px;font-size: 1.5em;"><div class="titulo_dia"><strong>Lunes&nbsp;</strong>(<?php echo(date('d/m/Y',$inicio_semana));?>)</div></td>
    </tr>
    <tr>
        <td colspan="2" style="padding: 5px;font-size: 1.2em;" ><strong>Almuerzos</strong></td>
    </tr>
    <tr>
        <td colspan="2" valign="top"><?php carga_menu_d($menus['lunes'], 1, $precio_alumnos); ?></td>
    </tr>
    <tr>
        <td colspan="2" style="padding: 5px;font-size: 1.2em;" ><strong>Comidas</strong></td>
    </tr>
    <tr>
        <td colspan="2" valign="top"><?php carga_menu_d_comida($menus['lunes'], 1, $precio_alumnos); ?></td>
    </tr>
    <tr>
        <td colspan="2" style="padding: 5px;font-size: 1.5em;" ><div class="titulo_dia"><strong>Martes&nbsp;</strong>(<?php echo(date('d/m/Y',$inicio_semana + 3600*24));?>)</div></td>
    </tr>
    <tr>
        <td colspan="2" style="padding: 5px;font-size: 1.2em;" ><strong>Almuerzos</strong></td>
    </tr>
    <tr>
        <td colspan="2" valign="top"><?php carga_menu_d($menus['martes'], 2, $precio_alumnos); ?></td>
    </tr>
    <tr>
        <td colspan="2" style="padding: 5px;font-size: 1.2em;" ><strong>Comidas</strong></td>
    </tr>
    <tr>
        <td colspan="2" valign="top"><?php carga_menu_d_comida($menus['martes'], 2, $precio_alumnos); ?></td>
    </tr>
    <tr>
        <td colspan="2" style="padding: 5px;font-size: 1.5em;" ><div class="titulo_dia"><strong>Miercoles&nbsp;</strong>(<?php echo(date('d/m/Y',$inicio_semana + 3600*24*2));?>)</div></td>
    </tr>
    <tr>
        <td colspan="2" style="padding: 5px;font-size: 1.2em;" ><strong>Almuerzos</strong></td>
    </tr>
    <tr>
        <td colspan="2" valign="top"><?php carga_menu_d($menus['miercoles'], 3, $precio_alumnos); ?></td>
    </tr>
    <tr>
        <td colspan="2" style="padding: 5px;font-size: 1.2em;" ><strong>Comidas</strong></td>
    </tr>
    <tr>
        <td colspan="2" valign="top"><?php carga_menu_d_comida($menus['miercoles'], 3, $precio_alumnos); ?></td>
    </tr>
    <tr>
        <td colspan="2" style="padding: 5px;font-size: 1.5em;" ><div class="titulo_dia"><strong>Jueves&nbsp;</strong>(<?php echo(date('d/m/Y',$inicio_semana + 3600*24*3));?>)</div></td>
    </tr>
    <tr>
        <td colspan="2" style="padding: 5px;font-size: 1.2em;" ><strong>Almuerzos</strong></td>
    </tr>
    <tr>
        <td colspan="2" valign="top"><?php carga_menu_d($menus['jueves'], 4, $precio_alumnos); ?></td>
    </tr>
    <tr>
        <td colspan="2" style="padding: 5px;font-size: 1.2em;" ><strong>Comidas</strong></td>
    </tr>
    <tr>
        <td colspan="2" valign="top"><?php carga_menu_d_comida($menus['jueves'], 4, $precio_alumnos); ?></td>
    </tr>
    <tr>
        <td colspan="2" style="padding: 5px;font-size: 1.5em;" ><div class="titulo_dia"><strong>Viernes&nbsp;</strong>(<?php echo(date('d/m/Y',$inicio_semana + 3600*24*4));?>)</div></td>
    </tr>
    <tr>
        <td colspan="2" style="padding: 5px;font-size: 1.2em;" ><strong>Almuerzos</strong></td>
    </tr>
    <tr>
        <td colspan="2" valign="top"><?php carga_menu_d($menus['viernes'], 5, $precio_alumnos); ?></td>
    </tr>
    <tr>
        <td colspan="2" style="padding: 5px;font-size: 1.2em;" ><strong>Comidas</strong></td>
    </tr>
    <tr>
        <td colspan="2" valign="top"><?php carga_menu_d_comida($menus['viernes'], 5, $precio_alumnos); ?></td>
    </tr>
    <tr>
        <td colspan="2" style="padding: 5px;font-size: 1.5em;" ><div class="titulo_dia"><strong>Sábado&nbsp;</strong>(<?php echo(date('d/m/Y',$inicio_semana + 3600*24*5));?>)</div></td>
    </tr>
    <tr>
        <td colspan="2" style="padding: 5px;font-size: 1.2em;" ><strong>Almuerzos</strong></td>
    </tr>
    <tr>
        <td colspan="2" valign="top"><?php carga_menu_d($menus['sabado'], 6, $precio_alumnos); ?></td>
    </tr>
    <tr>
        <td colspan="2" style="padding: 5px;font-size: 1.2em;" ><strong>Comidas</strong></td>
    </tr>
    <tr>
        <td colspan="2" valign="top"><?php carga_menu_d_comida($menus['sabado'], 6, $precio_alumnos); ?></td>
    </tr>
    <tr>
        <td colspan="2" style="padding: 5px;font-size: 1.5em;" ><div class="titulo_dia"><strong>Domingo&nbsp;</strong>(<?php echo(date('d/m/Y',$inicio_semana + 3600*24*6));?>)</div></td>
    </tr>
    <tr>
        <td colspan="2" style="padding: 5px;font-size: 1.2em;" ><strong>Almuerzos</strong></td>
    </tr>
    <tr>
        <td colspan="2" valign="top"><?php carga_menu_d($menus['domingo'], 7, $precio_alumnos); ?></td>
    </tr>
    <tr>
        <td colspan="2" style="padding: 5px;font-size: 1.2em;" ><strong>Comidas</strong></td>
    </tr>
    <tr>
        <td colspan="2" valign="top"><?php carga_menu_d_comida($menus['domingo'], 7, $precio_alumnos); ?></td>
    </tr>
    <tr>
        <td style="padding: 5px;font-size: 1.5em;"><strong>Total de la Semana:</strong></td><td align="right" style="padding: 5px;font-size: 1.5em;"><strong>$ <?php echo number_format($total_semana + $total_semana_comida,2); ?></strong></td>
    </tr>
</table> 
</div>
 
</body>
</html>