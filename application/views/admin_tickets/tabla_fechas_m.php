<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<script type="text/javascript">
    var precio_ds = [0,0,0,0,0,0,0];
    var prereservacion = [];
    var platos_select = [[],[],[],[],[],[],[],[]];
    var dia = [];
    <?php script_array_menu($menus['lunes'],1); ?>
    <?php script_array_menu($menus['martes'],2); ?>
    <?php script_array_menu($menus['miercoles'],3); ?>
    <?php script_array_menu($menus['jueves'],4); ?>
    <?php script_array_menu($menus['viernes'],5); ?>
    <?php script_array_menu($menus['sabado'],6); ?>
    <?php script_array_menu($menus['domingo'],7); ?>

    var precio_dia = [];
    <?php script_precios_menu($menus['lunes'],1); ?>
    <?php script_precios_menu($menus['martes'],2); ?>
    <?php script_precios_menu($menus['miercoles'],3); ?>
    <?php script_precios_menu($menus['jueves'],4); ?>
    <?php script_precios_menu($menus['viernes'],5); ?>
    <?php script_precios_menu($menus['sabado'],6); ?>
    <?php script_precios_menu($menus['domingo'],7); ?>
</script>
<?php
 $dia_semana = date('N', $fecha);
 //$inicio_semana = $fecha - (($dia_semana - 1) * 3600*24);
 $fin_semana = $inicio_semana + 6 * 3600*24;
 
 function carga_menu_d($seleccion, $lista_dia, $numerodia, $fecha_admisible, $fecha_dia, $prereservacion, $menu_platos, 
                        $ruta_url, $idtrabajador, $cierra_viernes, $iCheckStyleCB, $iCheckStyleRB, $incio_semana){
            
            if($menu_platos == true){
                $habiliado_ck = "";
            } else {
                $habiliado_ck = "disabled";
            }
            
            $habiliado = "";
            $texto_reservar ="";
            
            if ($fecha_admisible > $fecha_dia){
                $habiliado = "disabled";
                $habiliado_ck = "disabled";
                $texto_reservar ="(Tiempo agotado)";
            }

            if($cierra_viernes == true){
            
                //Para los lunes

                if ($numerodia == 1 && $fecha_admisible > $fecha_dia - (2 * 3600*24)){
                    $habiliado = "disabled";
                    $habiliado_ck = "disabled";
                    $texto_reservar ="(Tiempo agotado)";
                }

                //Para los domingos
                if ($numerodia == 7 && $fecha_admisible > $fecha_dia - (1 * 3600*24)){
                    $habiliado = "disabled";
                    $habiliado_ck = "disabled";
                    $texto_reservar ="(Tiempo agotado)";
                }

            }
            
            $numero_dia_a = $numerodia -1;

            
            if(count($lista_dia) < 1){
                echo '<label>No hay platos definidos para ese día.</label>';
                
                echo '<div class="ui-checkbox">';
                echo '<label for="checkbox-enhanced" class="ui-btn ui-corner-all ui-btn-inherit ui-btn-icon-left ui-checkbox-off">Prereservar.'.$texto_reservar.'</label>';
                
                if(count($prereservacion[$numero_dia_a]) > 0){
                
                echo '<input data-cacheval="true" name="checkbox-enhanced" id="checkbox-enhanced" data-enhanced="true" type="checkbox" checked onclick="actualiza_prereservacion('.$numerodia.',true);"></input>';
               
                    echo ("<script type='text/javascript'> prereservacion[".$numero_dia_a."] = true;</script>");
                
                } else {
                echo '<input id="cb_pre'.$numerodia.'" type="checkbox" data-mini="true" name="checkbox-mini-0" data-cacheval="false" onclick="actualiza_prereservacion('.$numerodia.',true);"></input>';
                    
                    echo ("<script type='text/javascript'> prereservacion[".$numero_dia_a."] = false;</script>");
                }

                echo '</div>';                    
                
                
            } else {
                for ($i=0; $i < count($lista_dia); $i++) {
                    $plato = $lista_dia[$i];
                    if($seleccion[$i] == true){ 
                        echo '<div class="texto_plato"><input class="ck_plato_'.$numerodia.'" checked onclick="actualiza_precio('.$numerodia.',true);" type="checkbox" id="cb_'.$numerodia.'_'.$i.'" '.$habiliado_ck.' />&nbsp;<label>'.$plato['nombreplato'].' '.$plato['cantidad'].' '.$plato['simbolo'].' <div style="padding:3px; float: right;">$'.number_format($plato['precio'], 2) .'</label></div></div>';
                        echo ("<script type='text/javascript'> platos_select[".$numerodia."][".$i."] = true;</script>");
                    } else {
                        echo '<div class="texto_plato"><input class="ck_plato_'.$numerodia.'" onclick="actualiza_precio('.$numerodia.',true);" type="checkbox" id="cb_'.$numerodia.'_'.$i.'" '.$habiliado_ck.' /><label>&nbsp;'.$plato['nombreplato'].' '.$plato['cantidad'].' '.$plato['simbolo'].' <div  style="padding:3px; float: right;">$'.number_format($plato['precio'], 2) .'</label></div></div>';
                        echo ("<script type='text/javascript'> platos_select[".$numerodia."][".$i."] = false;</script>");
                    }
                }
                if ($habiliado != "disabled"){
                    echo'<input '.$habiliado.' type="button" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" onclick="marca_todos('.$numerodia.');" value="Todos" />';
                    echo'<input '.$habiliado.' type="button" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" onclick="desmarca_todos('.$numerodia.');" value="Ninguno" />';
                    
                }

                  
            }
            
 }
 
 function script_array_menu($lista_dia, $numerodia){

    $lista_platos_id = array();
    foreach ($lista_dia as $plato) {
        $lista_platos_id[] = $plato['idplato'];
    }
     
    echo 'dia['.$numerodia.']=["'.  implode('","', $lista_platos_id).'"];'; 
 }
 
 function script_precios_menu($lista_dia, $numerodia){

    $lista_platos_precio = array();
    foreach ($lista_dia as $plato) {
        $lista_platos_precio[] = $plato['precio'];
    }
     
    echo 'precio_dia['.$numerodia.']=['.  implode(',', $lista_platos_precio).'];'; 
 }
 
?>
<table data-role="table" data-mode="reflow" class="iu-resposive">
    <tr>
        <td>
            <div class="ui-btn ui-input-btn ui-corner-all ui-shadow ui-icon-carat-l ui-btn-icon-notext">
            Icon only
                <input data-icon="carat-l" id="prev" data-iconpos="notext" value="Anterior" type="button">
            </div>
        </td>
        <td>
        Del <?php echo(date('d/m/Y',$inicio_semana)); ?> al <?php echo(date('d/m/Y',$fin_semana)); ?>
        </td>
        <td>
            <div class="ui-btn ui-input-btn ui-corner-all ui-shadow ui-icon-carat-r ui-btn-icon-notext">
            Icon only
                <input data-icon="carat-r" id="next" data-iconpos="notext" value="Anterior" type="button">
            </div>
        </td>
    </tr>
</table>
<table data-role="table" data-mode="reflow" class="iu-resposive">
    <thead>
    <tr>
        <td><div class="titulo_dia"><strong>Lunes&nbsp;</strong>(<?php echo(date('d/m/Y',$inicio_semana));?>)&nbsp;<strong><span id="estado_1">&nbsp;</span></strong></div></td>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td valign="top"><?php carga_menu_d($seleccionado['lunes'],$menus['lunes'],1,$fecha_admisible, $inicio_semana, $prereservacion, $menu_platos, $ruta_url, $idtrabajador, $cierra_viernes, $iCheckStyleCB, $iCheckStyleRB, $incio_semana); ?></td>
    </tr>
    <tr>
        <td><div class="precio_ticket"><strong>Precio:<div style="float: right;" id="precio_1"></div></strong></div></td>
    </tr>
    </tbody>
</table>
<table data-role="table" data-mode="reflow" class="iu-resposive">
    <thead>
    <tr>
        <td><div class="titulo_dia"><strong>Martes&nbsp;</strong>(<?php echo(date('d/m/Y',$inicio_semana + 3600*24));?>)&nbsp;<strong><span id="estado_2">&nbsp;</span></strong></div></td>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td valign="top"><?php carga_menu_d($seleccionado['martes'],$menus['martes'],2,$fecha_admisible, $inicio_semana + 3600*24, $prereservacion, $menu_platos, $ruta_url, $idtrabajador, $cierra_viernes, $iCheckStyleCB, $iCheckStyleRB, $incio_semana); ?></td>
    </tr>
    <tr>
        <td><div class="precio_ticket"><strong>Precio:<div style="float: right;" id="precio_2"></div></strong></div></td>
    </tr>
    </tbody>
</table>
<table data-role="table" data-mode="reflow" class="iu-resposive">
    <thead>
    <tr>
        <td><div class="titulo_dia"><strong>Miércoles&nbsp;</strong>(<?php echo(date('d/m/Y',$inicio_semana + 3600*24*2));?>)&nbsp;<strong><span id="estado_3">&nbsp;</span></strong></div></td>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td valign="top"><?php carga_menu_d($seleccionado['miercoles'],$menus['miercoles'],3,$fecha_admisible, $inicio_semana + 3600*24*2, $prereservacion, $menu_platos, $ruta_url, $idtrabajador, $cierra_viernes, $iCheckStyleCB, $iCheckStyleRB, $incio_semana); ?></td>
    </tr>
    <tr>
        <td><div class="precio_ticket"><strong>Precio:<div style="float: right;" id="precio_3"></div></strong></div></td>
    </tr>
    </tbody>
</table>
<table data-role="table" data-mode="reflow" class="iu-resposive">
    <thead>
    <tr>
        <td><div class="titulo_dia"><strong>Jueves&nbsp;</strong>(<?php echo(date('d/m/Y',$inicio_semana + 3600*24*3));?>)&nbsp;<strong><span id="estado_4">&nbsp;</span></strong></div></td>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td valign="top"><?php carga_menu_d($seleccionado['jueves'],$menus['jueves'],4,$fecha_admisible, $inicio_semana + 3600*24*3, $prereservacion, $menu_platos, $ruta_url, $idtrabajador, $cierra_viernes, $iCheckStyleCB, $iCheckStyleRB, $incio_semana); ?></td>
    </tr>
    <tr>
        <td><div class="precio_ticket"><strong>Precio:<div style="float: right;" id="precio_4"></div></strong></div></td>
    </tr>
    </tbody>
</table> 
<table data-role="table" data-mode="reflow" class="iu-resposive">
    <thead>
    <tr>
        <td><div class="titulo_dia"><strong>Viernes&nbsp;</strong>(<?php echo(date('d/m/Y',$inicio_semana + 3600*24*4));?>)&nbsp;<strong><span id="estado_5">&nbsp;</span></strong></div></td>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td valign="top"><?php carga_menu_d($seleccionado['viernes'],$menus['viernes'],5,$fecha_admisible, $inicio_semana + 3600*24*4, $prereservacion, $menu_platos, $ruta_url, $idtrabajador, $cierra_viernes, $iCheckStyleCB, $iCheckStyleRB, $incio_semana); ?></td>
    </tr>
    <tr>
        <td><div class="precio_ticket"><strong>Precio:<div style="float: right;" id="precio_5"></div></strong></div></td>
    </tr>
    </tbody>
</table> 
<table data-role="table" data-mode="reflow" class="iu-resposive">
    <thead>
    <tr>
        <td><div class="titulo_dia"><strong>Sábado&nbsp;</strong>(<?php echo(date('d/m/Y',$inicio_semana + 3600*24*5));?>)&nbsp;<strong><span id="estado_6">&nbsp;</span></strong></div></td>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td valign="top"><?php carga_menu_d($seleccionado['sabado'],$menus['sabado'],6,$fecha_admisible, $inicio_semana + 3600*24*5, $prereservacion, $menu_platos, $ruta_url, $idtrabajador, $cierra_viernes, $iCheckStyleCB, $iCheckStyleRB, $incio_semana); ?></td>
    </tr>
    <tr>
        <td><div class="precio_ticket"><strong>Precio:<div style="float: right;" id="precio_6"></div></strong></div></td>
    </tr>
    </tbody>
</table> 
<table data-role="table" data-mode="reflow" class="iu-resposive">
    <thead>
    <tr>
        <td><div class="titulo_dia"><strong>Domingo&nbsp;</strong>(<?php echo(date('d/m/Y',$inicio_semana + 3600*24*6));?>)&nbsp;<strong><span id="estado_7">&nbsp;</span></strong></div></td>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td valign="top"><?php carga_menu_d($seleccionado['domingo'],$menus['domingo'],7,$fecha_admisible, $inicio_semana + 3600*24*6, $prereservacion, $menu_platos, $ruta_url, $idtrabajador, $cierra_viernes, $iCheckStyleCB, $iCheckStyleRB, $incio_semana); ?></td>
    </tr>
    <tr>
        <td><div  class="precio_ticket"><strong>Precio:<div style="float: right;" id="precio_7"></div></strong></div></td>
    </tr>
    </tbody>
</table> 
<table data-role="table" data-mode="reflow" class="iu-resposive">
    <thead>
    <tr>
        <td><div class="resumen_semana"><strong>Resumen de la Semana&nbsp;</strong></div></td>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td valign="top">
            <div class="texto_plato" style="padding: 3px;">Lunes:<div style="float: right;" id="precios_1"></div></div>            
            <div class="texto_plato" style="padding: 3px;">Martes:<div style="float: right;" id="precios_2"></div></div>            
            <div class="texto_plato" style="padding: 3px;">Miércoles:<div style="float: right;" id="precios_3"></div></div>            
            <div class="texto_plato" style="padding: 3px;">Jueves:<div style="float: right;" id="precios_4"></div></div>            
            <div class="texto_plato" style="padding: 3px;">Viernes:<div style="float: right;" id="precios_5"></div></div>            
            <div class="texto_plato" style="padding: 3px;">Sábado:<div style="float: right;" id="precios_6"></div></div>            
            <div class="texto_plato" style="padding: 3px;">Domingo:<div style="float: right;" id="precios_7"></div></div>            
        </td>
    </tr>
    <tr>
        <td><div style="padding: 0 5px;"><strong>Gasto en la semana:<div style="float: right;" id="precio_semana"></div></strong></div></td>
    </tr>
    </tbody>
</table> 
<table data-role="table" data-mode="reflow" class="iu-resposive">
    <thead>
    <tr>
        <td><div class="almuerzos_mes"><strong>Almuerzos en el mes&nbsp;</strong></div></td>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td valign="top"><div id="calendario_g"></div></td>
    </tr>
    <tr>
        <td><strong><div id="precio_mes">Total en el mes:<div style="float: right;" id="saldo_mes">&nbsp;</div></div></strong></td>
    </tr>
    </tbody>
</table> 
<br />
  
