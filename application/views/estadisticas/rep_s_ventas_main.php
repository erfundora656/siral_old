<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$dia_semana = date('N', $fecha);
 //$inicio_semana = $fecha - (($dia_semana - 1) * 3600*24);
$fin_semana = $inicio_semana + 6 * 3600*24;

function carga_menu_d($lista_dia,$numero_dia,$turno){
    switch ($turno) {
        case '1':
        if(count($lista_dia['datos_platos_1']) < 1){
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
            </thead>';
            $importe_total_d = 0;
            $importe_ticket_d = 0;
            $precio_ticket_d = 0;
            for ($i=0; $i < count($lista_dia['datos_platos_1']); $i++) {
                $plato = $lista_dia['datos_platos_1'][$i];
                if ($par){
                    echo'<tr class ="fila_par">';
                    $par = false;    
                } else {
                    echo'<tr class ="fila_impar">';
                    $par=true;
                }

                $total_a_c =$plato['cant_tickets']+$lista_dia['cantidad_dirigidos1'];

                echo '
                <td>'.$plato['nombre'].'</td>
                <td align="right" >'.$plato['cantidad'].'&nbsp;'.$plato['simbolo'].'</td>
                <td align="right" >$&nbsp;'.number_format($plato['precio'],2).'</td>
                <td align="right" >'.$lista_dia['cantidad_dirigidos1'].'</td>
                <td align="right" >'.$plato['cant_tickets'].'</td>

                <td align="right" >'.$total_a_c.'</td>
                </tr>';
                $importe_total_d += $plato['importe'] + ($lista_dia['cantidad_dirigidos1'] * $plato['precio']);
                $precio_ticket_d += $plato['precio'];
                $importe_ticket_d += $plato['importe'];
            }
            
            echo'    <thead class="ui-widget-header">
            <tr>
            <td colspan="2"><strong>Total</strong></td>
            <td align="right" >$&nbsp;'.number_format($precio_ticket_d,2).'</td>
            <td align="right" >$&nbsp;'.number_format($lista_dia['cantidad_dirigidos1'] * $precio_ticket_d,2).'</td>
            <td align="right" >$&nbsp;'.number_format($importe_ticket_d,2).'</td>

            <td align="right" >$&nbsp;'.number_format($importe_total_d ,2).'</td>
            </tr>
            </thead>    
            </table>';
        }
        break;
        case '2':
        if(count($lista_dia['datos_platos_2']) < 1){
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
            </thead>';
            $importe_total_a = 0;
            $importe_ticket_a = 0;
            $precio_ticket_a = 0;
            for ($i=0; $i < count($lista_dia['datos_platos_2']); $i++) {
                $plato = $lista_dia['datos_platos_2'][$i];
                if ($par){
                    echo'<tr class ="fila_par">';
                    $par = false;    
                } else {
                    echo'<tr class ="fila_impar">';
                    $par=true;
                }

                $total_a_c =$plato['cant_tickets']+$lista_dia['cantidad_dirigidos2'];

                echo '
                <td>'.$plato['nombre'].'</td>
                <td align="right" >'.$plato['cantidad'].'&nbsp;'.$plato['simbolo'].'</td>
                <td align="right" >$&nbsp;'.number_format($plato['precio'],2).'</td>
                <td align="right" >'.$lista_dia['cantidad_dirigidos2'].'</td>
                <td align="right" >'.$plato['cant_tickets'].'</td>

                <td align="right" >'.$total_a_c.'</td>
                </tr>';
                $importe_total_a += $plato['importe'] + ($lista_dia['cantidad_dirigidos2'] * $plato['precio']);
                $precio_ticket_a += $plato['precio'];
                $importe_ticket_a += $plato['importe'];
            }
            
            echo'    <thead class="ui-widget-header">
            <tr>
            <td colspan="2"><strong>Total</strong></td>
            <td align="right" >$&nbsp;'.number_format($precio_ticket_a,2).'</td>
            <td align="right" >$&nbsp;'.number_format($lista_dia['cantidad_dirigidos2'] * $precio_ticket_a,2).'</td>
            <td align="right" >$&nbsp;'.number_format($importe_ticket_a,2).'</td>

            <td align="right" >$&nbsp;'.number_format($importe_total_a ,2).'</td>
            </tr>
            </thead>    
            </table>';
        }
        break;
        case '3':
        if(count($lista_dia['datos_platos_3']) < 1){
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
            </thead>';
            $importe_total_c = 0;
            $importe_ticket_c = 0;
            $precio_ticket_c = 0;
            for ($i=0; $i < count($lista_dia['datos_platos_3']); $i++) {
                $plato = $lista_dia['datos_platos_3'][$i];
                if ($par){
                    echo'<tr class ="fila_par">';
                    $par = false;    
                } else {
                    echo'<tr class ="fila_impar">';
                    $par=true;
                }

                $total_a_c =$plato['cant_tickets']+$lista_dia['cantidad_dirigidos3'];

                echo '
                <td>'.$plato['nombre'].'</td>
                <td align="right" >'.$plato['cantidad'].'&nbsp;'.$plato['simbolo'].'</td>
                <td align="right" >$&nbsp;'.number_format($plato['precio'],2).'</td>
                <td align="right" >'.$lista_dia['cantidad_dirigidos3'].'</td>
                <td align="right" >'.$plato['cant_tickets'].'</td>

                <td align="right" >'.$total_a_c.'</td>
                </tr>';
                $importe_total_c += $plato['importe'] + ($lista_dia['cantidad_dirigidos3'] * $plato['precio']);
                $precio_ticket_c += $plato['precio'];
                $importe_ticket_c += $plato['importe'];
            }
            
            echo'    <thead class="ui-widget-header">
            <tr>
            <td colspan="2"><strong>Total</strong></td>
            <td align="right" >$&nbsp;'.number_format($precio_ticket_c,2).'</td>
            <td align="right" >$&nbsp;'.number_format($lista_dia['cantidad_dirigidos3'] * $precio_ticket_c,2).'</td>
            <td align="right" >$&nbsp;'.number_format($importe_ticket_c,2).'</td>

            <td align="right" >$&nbsp;'.number_format($importe_total_c ,2).'</td>
            </tr>
            </thead>    
            </table>';
        }
        break;
        default:
        break;
    }

} 



?>
<table width="100%" class="ui-widget ui-widget-content" cellpadding="5" cellspacing="0">
    <thead class="ui-widget-header">
        <tr>
            <td style="font-size: 1.5em;">Reporte semanal de ventas</td>
            <td style="text-align: right;font-size: 1.0em;"><button id="b_rep_ventas_print">Imprimir</button></td>          
            <td style="text-align: right;font-size: 1.0em;">Sede:&nbsp;<select id="sede_idsede">
               <?php
               if($listasedes!=""){
                foreach($listasedes as $sede){
                    echo("<option value='".$sede['idsede']."'>".$sede['nombre']."</option>");
                }
            }else{
                echo("<option value='".$id_sede."'>".$sede_pertenece."</option>");  
            }
            ?>
        </select>        
    </td>    
</tr>
<tr>
    <td></td>        
    <td align="center" style="font-size: 1.0em;">
        <button id="prev" >Anterior</button>
        &nbsp;&nbsp;
        <div style="display:inline-block;" id="fechas">
            Del <?php echo(date('d/m/Y',$inicio_semana)); ?> al <?php echo(date('d/m/Y',$fin_semana)); ?>
        </div>
        &nbsp;&nbsp;
        <button id="next" >Próximo</button>
    </td>
    <td></td>
</tr>
</thead>
</table>
<br />
<div id="listado_dia">
    <table width="100%" class="ui-widget ui-widget-content" cellpadding="0">
        <thead>
            <tr>
                <td style="padding: 5px;font-size: 1.2em;" ><div class="titulo_dia"><strong>Lunes&nbsp;</strong>(<?php echo(date('d/m/Y',$inicio_semana));?>)</div></td>
            </tr>
        </thead>
        <tr>
            <td style="padding: 5px;font-size: 1.2em;" ><strong>Desayunos</strong></td>
        </tr>
        <tr>
            <td valign="top"><?php carga_menu_d($menus['lunes'], 1,1);?></td>
        </tr>
        <tr>
            <td style="padding: 5px;font-size: 1.2em;" ><strong>Almuerzos</strong></td>
        </tr>
        <tr>
            <td valign="top"><?php carga_menu_d($menus['lunes'], 1,2);?></td>
        </tr>
        <tr>
            <td style="padding: 5px;font-size: 1.2em;" ><strong>Comidas</strong></td>
        </tr>
        <tr>
            <td valign="top"><?php carga_menu_d($menus['lunes'], 1,3); ?></td>
        </tr>
        <thead>
            <tr>
                <td style="padding: 5px;font-size: 1.2em;" ><div class="titulo_dia"><strong>Martes&nbsp;</strong>(<?php echo(date('d/m/Y',$inicio_semana + 3600*24));?>)</div></td>
            </tr>
        </thead>
        <tr>
            <td style="padding: 5px;font-size: 1.2em;" ><strong>Desayunos</strong></td>
        </tr>
        <tr>
            <td valign="top"><?php carga_menu_d($menus['martes'], 2,1);?></td>
        </tr>
        <tr>
            <td style="padding: 5px;font-size: 1.2em;" ><strong>Almuerzos</strong></td>
        </tr>
        <tr>
            <td valign="top"><?php carga_menu_d($menus['martes'], 2,2);?></td>
        </tr>
        <tr>
            <td style="padding: 5px;font-size: 1.2em;" ><strong>Comidas</strong></td>
        </tr>
        <tr>
            <td valign="top"><?php carga_menu_d($menus['martes'], 2,3); ?></td>
        </tr>
        <thead>
            <tr>
                <td style="padding: 5px;font-size: 1.2em;" ><div class="titulo_dia"><strong>Miercoles&nbsp;</strong>(<?php echo(date('d/m/Y',$inicio_semana + 3600*24*2));?>)</div></td>
            </tr>
        </thead>
        <tr>
            <td style="padding: 5px;font-size: 1.2em;" ><strong>Desayunos</strong></td>
        </tr>
        <tr>
            <td valign="top"><?php carga_menu_d($menus['miercoles'], 3,1);?></td>
        </tr>
        <tr>
            <td style="padding: 5px;font-size: 1.2em;" ><strong>Almuerzos</strong></td>
        </tr>
        <tr>
            <td valign="top"><?php carga_menu_d($menus['miercoles'], 3,2);?></td>
        </tr>
        <tr>
            <td style="padding: 5px;font-size: 1.2em;" ><strong>Comidas</strong></td>
        </tr>
        <tr>
            <td valign="top"><?php carga_menu_d($menus['miercoles'], 3,3); ?></td>
        </tr>
        <thead>
            <tr>
                <td style="padding: 5px;font-size: 1.2em;" ><div class="titulo_dia"><strong>Jueves&nbsp;</strong>(<?php echo(date('d/m/Y',$inicio_semana + 3600*24*3));?>)</div></td>
            </tr>
        </thead>
        <tr>
            <td style="padding: 5px;font-size: 1.2em;" ><strong>Desayunos</strong></td>
        </tr>
        <tr>
            <td valign="top"><?php carga_menu_d($menus['jueves'], 4,1);?></td>
        </tr>
        <tr>
            <td style="padding: 5px;font-size: 1.2em;" ><strong>Almuerzos</strong></td>
        </tr>
        <tr>
            <td valign="top"><?php carga_menu_d($menus['jueves'], 4,2);?></td>
        </tr>
        <tr>
            <td style="padding: 5px;font-size: 1.2em;" ><strong>Comidas</strong></td>
        </tr>
        <tr>
            <td valign="top"><?php carga_menu_d($menus['jueves'], 4,3); ?></td>
        </tr>
        <thead>
            <tr>
                <td style="padding: 5px;font-size: 1.2em;" ><div class="titulo_dia"><strong>Viernes&nbsp;</strong>(<?php echo(date('d/m/Y',$inicio_semana + 3600*24*4));?>)</div></td>
            </tr>
        </thead>
        <tr>
            <td style="padding: 5px;font-size: 1.2em;" ><strong>Desayunos</strong></td>
        </tr>
        <tr>
            <td valign="top"><?php carga_menu_d($menus['viernes'], 5,1);?></td>
        </tr>
        <tr>
            <td style="padding: 5px;font-size: 1.2em;" ><strong>Almuerzos</strong></td>
        </tr>
        <tr>
            <td valign="top"><?php carga_menu_d($menus['viernes'], 5,2);?></td>
        </tr>
        <tr>
            <td style="padding: 5px;font-size: 1.2em;" ><strong>Comidas</strong></td>
        </tr>
        <tr>
            <td valign="top"><?php carga_menu_d($menus['viernes'], 5,3); ?></td>
        </tr>
        <thead>
            <tr>
                <td style="padding: 5px;font-size: 1.2em;" ><div class="titulo_dia"><strong>Sábado&nbsp;</strong>(<?php echo(date('d/m/Y',$inicio_semana + 3600*24*5));?>)</div></td>
            </tr>
        </thead>        
        <tr>
            <td style="padding: 5px;font-size: 1.2em;" ><strong>Desayunos</strong></td>
        </tr>
        <tr>
            <td valign="top"><?php carga_menu_d($menus['sabado'], 6,1);?></td>
        </tr>
        <tr>
            <td style="padding: 5px;font-size: 1.2em;" ><strong>Almuerzos</strong></td>
        </tr>
        <tr>
            <td valign="top"><?php carga_menu_d($menus['sabado'], 6,2);?></td>
        </tr>
        <tr>
            <td style="padding: 5px;font-size: 1.2em;" ><strong>Comidas</strong></td>
        </tr>
        <tr>
            <td valign="top"><?php carga_menu_d($menus['sabado'], 6,3); ?></td>
        </tr>
        <thead>
            <tr>
                <td style="padding: 5px;font-size: 1.2em;" ><div class="titulo_dia"><strong>Domingo&nbsp;</strong>(<?php echo(date('d/m/Y',$inicio_semana + 3600*24*6));?>)</div></td>
            </tr>
        </thead>
        <tr>
            <td style="padding: 5px;font-size: 1.2em;" ><strong>Desayunos</strong></td>
        </tr>
        <tr>
            <td valign="top"><?php carga_menu_d($menus['domingo'], 7,1);?></td>
        </tr>
        <tr>
            <td style="padding: 5px;font-size: 1.2em;" ><strong>Almuerzos</strong></td>
        </tr>
        <tr>
            <td valign="top"><?php carga_menu_d($menus['domingo'], 7,2);?></td>
        </tr>
        <tr>
            <td style="padding: 5px;font-size: 1.2em;" ><strong>Comidas</strong></td>
        </tr>
        <tr>
            <td valign="top"><?php carga_menu_d($menus['domingo'], 7,3); ?></td>
        </tr>
        <thead class="ui-widget-header">
            <tr>
                <td style="padding: 5px;font-size: 1.2em;" ><div class="titulo_dia"><strong>Total de la Semana<div style="float: right;" id="saldo_mes">$ <?php echo number_format($total_semana_d + $total_semana_a + $total_semana_c,2); ?></div></strong></div></td>
            </tr>
        </thead>
    </table>
</div>
<script type="text/javascript">
    $( "#sede_idsede" ).change(function(){
        fecha_ver = <?php echo $fecha - 3600*24*7; ?>;
        myURL = 'index.php/estadisticas/reporte_s_ventas/'+fecha_ver+'/'+$( "#sede_idsede" ).val()+'/';

        carga_contenidos(myURL);
    });
    $( "#prev" ).button({
        text: false,
        icons: {
            primary: "ui-icon-seek-prev"
        }
    }).click(function(){
        fecha_ver = <?php echo $fecha - 3600*24*7; ?>;
        myURL = 'index.php/estadisticas/reporte_s_ventas/'+fecha_ver+'/'+$( "#sede_idsede" ).val()+'/';

        carga_contenidos(myURL);
    });
    $( "#next" ).button({
        text: false,
        icons: {
            primary: "ui-icon-seek-next"
        }
    }).click(function(){
        fecha_ver = <?php echo $fecha + 3600*24*7; ?>;
        myURL = 'index.php/estadisticas/reporte_s_ventas/'+fecha_ver+'/'+$( "#sede_idsede" ).val()+'/';
        carga_contenidos(myURL);
    });

$( "#b_rep_ventas_print" ).button({
    text: true,
    icons: {
        primary: "ui-icon-print"
    }
}).click(function(){
    var contenido = '<div id="texto_imprimir">'
    contenido += '<p><span style="font-size: 1.5em;"><?php echo $nombre_entidad;?></span><br />';
    contenido += '<span style="font-size: 1.2em;"><?php echo $organismo;?></span></p>';
    contenido += '<p><span style="font-size: 1.3em;">Reporte semanal de ventas <?php echo(date('d/m/Y',$inicio_semana)); ?> al <?php echo(date('d/m/Y',$fin_semana)); ?></span></p>';

    contenido += $( "#listado_dia" ).html();
    contenido += '<p style="text-align:center;margin-top:50px;">__________________________________<br /><?php echo $nombre_firma;?><br />';
    contenido += '<?php echo $cargo_firma;?></p>';
    contenido += '</div>';
    $( "#dialogo_imprimir" ).html(contenido);
    $( "#dialogo_imprimir" ).dialog( "open" );
});


$( "#dialogo_imprimir" ).attr('title','Imprimir');
$( "#dialogo_imprimir" ).dialog({
  width: 1000,
  height: 600,
  autoOpen: false,
  modal: true,
  buttons: {
   "Imprimir": function() {
    $("#texto_imprimir").printArea();
},
"Cerrar": function() {
    $( this ).dialog( "close" );
    $( "#dialogo_imprimir" ).html('');
}
}
});

</script>