<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<table id="principal" width="100%" class="ui-widget ui-widget-content" cellpadding="5" cellspacing="0">
    <thead class="ui-widget-header">
        <tr>
            <td style="font-size: 2em;">Administración - Inicio</td>&nbsp;            
            <td style="text-align: right;font-size: 1.2em;">Seleccionar:&nbsp;<select id="sede_idsede">
                <?php
                if($listasedes!=""){
                    foreach($listasedes as $sede){
                        echo("<option value='".$sede['idsede']."'>".$sede['nombre']."</option>");
                    }
                }else{
                    echo("<option value='".$id_sede."'>".$sede_pertenece."</option>");  
                }
                ?>
            </select></td>        
        </tr>
    </thead>
</table>
<br/>
<table id="principal" width="100%" class="ui-widget ui-widget-content" cellpadding="5" cellspacing="0">
    <thead class="ui-widget-header">
        <tr>          
            <td style="text-align: center;font-size: 1.2em;">Informe y estadística de la Sede:&nbsp;<?php echo $sede_pertenece;?></td>        
        </tr>
    </thead>
</table>
<br/>
<table width="100%" class="menus ui-widget ui-widget-content" cellpadding="5">
    <thead>
    <tr>
        <td colspan="3" style="padding: 5px;font-size: 1.5em;" ><strong>Desayunos</strong></td>
    </tr>
    </thead>
    <thead class="ui-widget-header">
    <tr>
        <td style="width: 50%;"><div class="titulo_dia"><strong>Menú de hoy&nbsp;</strong>(<?php echo(date('d/m/Y',  time()));?>)</div></td>
        <td style="width: 50%;"><div class="titulo_dia"><strong>Con reservación hoy:</strong></div></td>
    </tr>
    </thead>
    <tr>
        <td valign="top">
            <table>
        <?php 
            $precio_hoy = 0;
            if(count($menu_hoy_1) < 1){
                echo '<h3>No hay platos definidos para hoy.</h3>';
            } else {
                for ($i=0; $i < count($menu_hoy_1); $i++) {
                    $plato = $menu_hoy_1[$i];
                    $precio_hoy += $plato['precio'];
                        echo '<tr><td style="border-bottom: solid 1px #0078AE;"><label>'.$plato['nombreplato'].' '.$plato['cantidad'].' '.$plato['simbolo'].'</label><label style="float: inline-end;">$'.number_format($plato['precio'], 2) .'</label></td></tr>';
                }
            }
        ?>
        </table>
        </td>
        <td valign="top">
            <div class="grafica">
            <div id="grafica_hoy_d" style="width:350px; height:200px;"></div>
            </div>
        </td>
    </tr>
    <thead class="ui-widget-header">
    <tr>
        <td><strong><div id="precio_hoy">Precio: <div style="padding:0 3px; float: right;">$ <?php echo number_format($precio_hoy, 2); ?></div></div></strong></td>
        <td><strong><div id="total_estadisticas"></div><?php echo $trabajadores_reservacion_1 + $dirigidos_1; ?> de <?php echo $total_trabajadores + $dirigidos_1; ?></strong></td>
    </tr>
    </thead>
</table>
<br />

<table width="100%" class="menus ui-widget ui-widget-content" cellpadding="5">
    <thead>
    <tr>
        <td colspan="3" style="padding: 5px;font-size: 1.5em;" ><strong>Almuerzos</strong></td>
    </tr>
    </thead>
    <thead class="ui-widget-header">
    <tr>
        <td style="width: 50%;"><div class="titulo_dia"><strong>Menú de hoy&nbsp;</strong>(<?php echo(date('d/m/Y',  time()));?>)</div></td>
        <td style="width: 50%;"><div class="titulo_dia"><strong>Con reservación hoy:</strong></div></td>
    </tr>
    </thead>
    <tr>
        <td valign="top">
            <table>
        <?php 
            $precio_hoy = 0;
            if(count($menu_hoy_2) < 1){
                echo '<h3>No hay platos definidos para hoy.</h3>';
            } else {
                for ($i=0; $i < count($menu_hoy_2); $i++) {
                    $plato = $menu_hoy_2[$i];
                    $precio_hoy += $plato['precio'];
                        echo '<tr><td style="border-bottom: solid 1px #0078AE;"><label>'.$plato['nombreplato'].' '.$plato['cantidad'].' '.$plato['simbolo'].'</label><label style="float: inline-end;">$'.number_format($plato['precio'], 2) .'</label></td></tr>';
                }
            }
        ?>
        </table>
        </td>
        <td valign="top">
            <div class="grafica">
            <div id="grafica_hoy_a" style="width:350px; height:200px;"></div>
            </div>
        </td>
    </tr>
    <thead class="ui-widget-header">
    <tr>
        <td><strong><div id="precio_hoy">Precio: <div style="padding:0 3px; float: right;">$ <?php echo number_format($precio_hoy, 2); ?></div></div></strong></td>
        <td><strong><div id="total_estadisticas"></div><?php echo $trabajadores_reservacion_2 + $dirigidos_2; ?> de <?php echo $total_trabajadores + $dirigidos_2; ?></strong></td>
    </tr>
    </thead>
</table>
<br />
<table width="100%" class="menus ui-widget ui-widget-content" cellpadding="5">
    <thead>
    <tr>
        <td colspan="3" style="padding: 5px;font-size: 1.5em;" ><strong>Comidas</strong></td>
    </tr>
    </thead>
    <thead class="ui-widget-header">
    <tr>
        <td style="width: 50%;"><div class="titulo_dia"><strong>Menú de hoy&nbsp;</strong>(<?php echo(date('d/m/Y',  time()));?>)</div></td>
        <td style="width: 50%;"><div class="titulo_dia"><strong>Con reservación hoy:</strong></div></td>
    </tr>
    </thead>
    <tr>
        <td valign="top">
            <table>
        <?php 
            $precio_hoy = 0;
            if(count($menu_hoy_3) < 1){
                echo '<h3>No hay platos definidos para hoy.</h3>';
            } else {
                for ($i=0; $i < count($menu_hoy_3); $i++) {
                    $plato = $menu_hoy_3[$i];
                    $precio_hoy += $plato['precio'];
                        echo '<tr><td style="border-bottom: solid 1px #0078AE;"><label>'.$plato['nombreplato'].' '.$plato['cantidad'].' '.$plato['simbolo'].'</label><label style="float: inline-end;">$'.number_format($plato['precio'], 2) .'</label></td></tr>';
                }
            }
        ?>
        </table>
        </td>
        <td valign="top">
            <div class="grafica">
            <div id="grafica_hoy_c" style="width:350px; height:200px;"></div>
            </div>
        </td>
    </tr>
    <thead class="ui-widget-header">
    <tr>
        <td><strong><div id="precio_hoy">Precio: <div style="padding:0 3px; float: right;">$ <?php echo number_format($precio_hoy, 2); ?></div></div></strong></td>
        <td><strong><div id="total_estadisticas"></div><?php echo $trabajadores_reservacion_3 + $dirigidos_3; ?> de <?php echo $total_trabajadores + $dirigidos_3; ?></strong></td>
    </tr>
    </thead>
</table>

<script type="text/javascript">
$('#sede_idsede').change(function(){
    myURL = 'index.php/principal/admin_inicio/'+$('#sede_idsede').val()+'/';

  carga_contenidos(myURL)
});



resto = <?php echo ($total_trabajadores )?> - <?php echo ($trabajadores_reservacion_1 )?>;
   
datos = [['Trabajadores', <?php echo ($trabajadores_reservacion_1)?>],['Dirigidos', <?php echo ($dirigidos_1)?>],['Resto', resto]];


   plot1 = jQuery.jqplot('grafica_hoy_d',
    [datos],
    {
      title: ' ',
      seriesDefaults: {
        shadow: false,
        renderer: jQuery.jqplot.PieRenderer,
        rendererOptions: {
          startAngle: 180,
          sliceMargin: 4,
          showDataLabels: true }
      },
      legend: { show:true, location: 'e' }
    }
  );


resto = <?php echo ($total_trabajadores )?> - <?php echo ($trabajadores_reservacion_2 )?>;
   
datos = [['Trabajadores', <?php echo ($trabajadores_reservacion_2)?>],['Dirigidos', <?php echo ($dirigidos_2)?>],['Resto', resto]];


   plot2 = jQuery.jqplot('grafica_hoy_a',
    [datos],
    {
      title: ' ',
      seriesDefaults: {
        shadow: false,
        renderer: jQuery.jqplot.PieRenderer,
        rendererOptions: {
          startAngle: 180,
          sliceMargin: 4,
          showDataLabels: true }
      },
      legend: { show:true, location: 'e' }
    }
  );

resto = <?php echo ($total_trabajadores )?> - <?php echo ($trabajadores_reservacion_3 )?>;
   
datos = [['Trabajadores', <?php echo ($trabajadores_reservacion_3)?>],['Dirigidos', <?php echo ($dirigidos_3)?>],['Resto', resto]];


   plot3 = jQuery.jqplot('grafica_hoy_c',
    [datos],
    {
      title: ' ',
      seriesDefaults: {
        shadow: false,
        renderer: jQuery.jqplot.PieRenderer,
        rendererOptions: {
          startAngle: 180,
          sliceMargin: 4,
          showDataLabels: true }
      },
      legend: { show:true, location: 'e' }
    }
  );   
  
</script>

