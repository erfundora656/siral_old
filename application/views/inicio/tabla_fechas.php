<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<script type="text/javascript">
    var precio_ds = [0,0,0,0,0,0,0];
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
 
 function carga_menu_d($seleccion, $lista_dia, $numerodia, $fecha_admisible, $fecha_dia){
            if ($fecha_admisible > $fecha_dia){
                $habiliado = "disabled";
            } else {
                $habiliado = "";
            }
            if(count($lista_dia) < 1){
                echo '<h3>No hay platos definidos para ese día.</h3>';
            } else {
                for ($i=0; $i < count($lista_dia); $i++) {
                    $plato = $lista_dia[$i];
                    if($seleccion[$i] == true){ 
                        echo '<div class="texto_plato"><input class="ck_plato_'.$numerodia.'" checked onclick="actualiza_precio('.$numerodia.',true);" type="checkbox" id="cb_'.$numerodia.'_'.$i.'" '.$habiliado.' />&nbsp;<label>'.$plato['nombreplato'].' '.$plato['cantidad'].' '.$plato['simbolo'].' <div style="padding:3px; float: right;">$'.number_format($plato['precio'], 2) .'</label></div></div>';
                        echo ("<script type='text/javascript'> platos_select[".$numerodia."][".$i."] = true;</script>");
                    } else {
                        echo '<div class="texto_plato"><input class="ck_plato_'.$numerodia.'" onclick="actualiza_precio('.$numerodia.',true);" type="checkbox" id="cb_'.$numerodia.'_'.$i.'" '.$habiliado.' /><label>&nbsp;'.$plato['nombreplato'].' '.$plato['cantidad'].' '.$plato['simbolo'].' <div  style="padding:3px; float: right;">$'.number_format($plato['precio'], 2) .'</label></div></div>';
                        echo ("<script type='text/javascript'> platos_select[".$numerodia."][".$i."] = false;</script>");
                    }
                }
                if ($fecha_admisible <= $fecha_dia){
                    echo'<input '.$habiliado.' type="button" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" onclick="marca_todos('.$numerodia.');" value="Todos" />';
                    echo'<input '.$habiliado.' type="button" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" onclick="desmarca_todos('.$numerodia.');" value="Ninguno" />';
                    echo'<input '.$habiliado.' type="button" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" onclick="actualiza_ticket('.$numerodia.');" value="Actualizar" />';
                }
            }
            
            $numero_dia_a = $numerodia -1;
            
            echo ("
                <script type='text/javascript'>
                $('.ck_plato_".$numerodia."').iCheck({
                      checkboxClass: 'icheckbox_square-blue',
                      radioClass: 'iradio_flat-blue'
                });

                $('.ck_plato_".$numerodia."').on('ifChanged', function(event){
                  idcb = this.id;
                  var nplato = parseInt(idcb.substring(5));
                  if(this.checked){
                      precio_ds[".$numero_dia_a."] +=precio_dia[".$numerodia."][nplato];
                      platos_select[".$numerodia."][nplato] = true;
                  } else {
                      precio_ds[".$numero_dia_a."] -=precio_dia[".$numerodia."][nplato];
                      platos_select[".$numerodia."][nplato] = false;
                  }
                  actualiza_precio(".$numerodia.",true);
                  
                });
                </script>
                ");
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
<table width="100%" class="ui-widget ui-widget-content" cellpadding="3" cellspacing="0">
    <thead class="ui-widget-header">
        <tr>
            <td align="center" style="font-size: 1.2em;">
                <button id="prev" >Anterior</button>
                &nbsp;&nbsp;
                <div style="display:inline-block;" id="fechas">
                    Del <?php echo(date('d/m/Y',$inicio_semana)); ?> al <?php echo(date('d/m/Y',$fin_semana)); ?>
                </div>
                &nbsp;&nbsp;
                <button id="next" >Próximo</button>
            </td>
        </tr>
    </thead>
</table>    

<table width="100%" class="menus ui-widget ui-widget-content" cellpadding="5">
    <thead class="ui-widget-header">
    <tr>
        <td><div class="titulo_dia"><strong>Lunes&nbsp;</strong>(<?php echo(date('d/m/Y',$inicio_semana));?>)</div></td>
        <td><div class="titulo_dia"><strong>Martes&nbsp;</strong>(<?php echo(date('d/m/Y',$inicio_semana + 3600*24));?>)</div></td>
        <td><div class="titulo_dia"><strong>Miercoles&nbsp;</strong>(<?php echo(date('d/m/Y',$inicio_semana + 3600*24*2));?>)</div></td>
    </tr>
    </thead>
    <tr>
        <td valign="top"><?php carga_menu_d($seleccionado['lunes'],$menus['lunes'],1,$fecha_admisible, $inicio_semana); ?></td>
        <td valign="top"><?php carga_menu_d($seleccionado['martes'],$menus['martes'],2,$fecha_admisible, $inicio_semana + 3600*24); ?></td>
        <td valign="top"><?php carga_menu_d($seleccionado['miercoles'],$menus['miercoles'],3,$fecha_admisible, $inicio_semana + 3600*24*2); ?></td>
    </tr>
    <tr>
        <td><div class="precio_ticket"><strong>Precio:<div style="float: right;" id="precio_1"></div></strong></div></td>
        <td><div class="precio_ticket"><strong>Precio:<div style="float: right;" id="precio_2"></div></strong></div></td>
        <td><div class="precio_ticket"><strong>Precio:<div style="float: right;" id="precio_3"></div></strong></div></td>
    </tr>
</table>
<table width="100%" class="menus ui-widget ui-widget-content" cellpadding="5">
    <thead class="ui-widget-header">
    <tr>
        <td><div class="titulo_dia"><strong>Jueves&nbsp;</strong>(<?php echo(date('d/m/Y',$inicio_semana + 3600*24*3));?>)</div></td>
        <td><div class="titulo_dia"><strong>Viernes&nbsp;</strong>(<?php echo(date('d/m/Y',$inicio_semana + 3600*24*4));?>)</div></td>
        <td><div class="titulo_dia"><strong>Sábado&nbsp;</strong>(<?php echo(date('d/m/Y',$inicio_semana + 3600*24*5));?>)</div></td>
    </tr>
    </thead>
    <tr>
        <td valign="top"><?php carga_menu_d($seleccionado['jueves'],$menus['jueves'],4,$fecha_admisible, $inicio_semana + 3600*24*3); ?></td>
        <td valign="top"><?php carga_menu_d($seleccionado['viernes'],$menus['viernes'],5,$fecha_admisible, $inicio_semana + 3600*24*4); ?></td>
        <td valign="top"><?php carga_menu_d($seleccionado['sabado'],$menus['sabado'],6,$fecha_admisible, $inicio_semana + 3600*24*5); ?></td>
    </tr>
    <tr>
        <td><div class="precio_ticket"><strong>Precio:<div style="float: right;" id="precio_4"></div></strong></div></td>
        <td><div class="precio_ticket"><strong>Precio:<div style="float: right;" id="precio_5"></div></strong></div></td>
        <td><div class="precio_ticket"><strong>Precio:<div style="float: right;" id="precio_6"></div></strong></div></td>
    </tr>
</table> 
<table width="100%" class="menus ui-widget ui-widget-content" cellpadding="5">
    <thead class="ui-widget-header">
    <tr>
        <td><div class="titulo_dia"><strong>Domingo&nbsp;</strong>(<?php echo(date('d/m/Y',$inicio_semana + 3600*24*6));?>)</div></td>
        <td><div class="resumen_semana"><strong>Resumen de la Semana&nbsp;</strong></div></td>
        <td><div class="almuerzos_mes"><strong>Almuerzos en el mes&nbsp;</strong></div></td>
    </tr>
    </thead>
    <tr>
        <td valign="top"><?php carga_menu_d($seleccionado['domingo'],$menus['domingo'],7,$fecha_admisible, $inicio_semana + 3600*24*6); ?></td>
        <td valign="top">
            <div class="texto_plato" style="padding: 3px;">Lunes:<div style="float: right;" id="precios_1"></div></div>            
            <div class="texto_plato" style="padding: 3px;">Martes:<div style="float: right;" id="precios_2"></div></div>            
            <div class="texto_plato" style="padding: 3px;">Miercoles:<div style="float: right;" id="precios_3"></div></div>            
            <div class="texto_plato" style="padding: 3px;">Jueves:<div style="float: right;" id="precios_4"></div></div>            
            <div class="texto_plato" style="padding: 3px;">Viernes:<div style="float: right;" id="precios_5"></div></div>            
            <div class="texto_plato" style="padding: 3px;">Sábado:<div style="float: right;" id="precios_6"></div></div>            
            <div class="texto_plato" style="padding: 3px;">Domingo:<div style="float: right;" id="precios_7"></div></div>            
        </td>
        <td valign="top"><div id="calendario_g"></div></td>
    </tr>
    <tr>
        <td><div  class="precio_ticket"><strong>Precio:<div style="float: right;" id="precio_7"></div></strong></div></td>
        <td><div style="padding: 0 5px;"><strong>Gasto en la semana:<div style="float: right;" id="precio_semana"></div></strong></div></td>
        <td><strong><div id="precio_mes">Total en el mes:<div style="float: right;" id="saldo_mes">&nbsp;</div></div></strong></td>
    </tr>
</table> 
<br />
<script type="text/javascript">
    
                    $( "#prev" ).button({
                            text: false,
                            icons: {
                                    primary: "ui-icon-seek-prev"
                            }
                    }).click(function(){
                        myURL = 'index.php/principal/tabla_semana/<?php echo $fecha - 3600*24*7; ?>/';

                        carga_contenidos(myURL);
                        });
                    $( "#next" ).button({
                            text: false,
                            icons: {
                                    primary: "ui-icon-seek-next"
                            }
                    }).click(function(){
                        myURL = 'index.php/principal/tabla_semana/<?php echo $fecha + 3600*24*7; ?>/';

                        carga_contenidos(myURL);
                        });

    function marca_todos(numero_dia){
        $('.ck_plato_'+numero_dia).iCheck('check');
    }    

    function desmarca_todos(numero_dia){
        $('.ck_plato_'+numero_dia).iCheck('uncheck');
    }    

    function actualiza_precio(numero_dia, semana){
        var estedia = dia[numero_dia];
        var estediap = precio_dia[numero_dia];
        importe = 0;
        for(var i = 0; i < estediap.length; i++){
            
            if (platos_select[numero_dia][i]){
                importe += estediap[i];
            }    
        }
        $("#precio_"+numero_dia).html('$'+ importe.toFixed(2));
        $("#precios_"+numero_dia).html('$'+ importe.toFixed(2));
        precio_ds[numero_dia-1]=importe;
        if (semana){
            actualiza_precio_s();
        }    
    }    

    function actualiza_precio_s(){
        importe = 0;
        for(var i = 0; i < 7; i++){
           importe += precio_ds[i];
        }
        $("#precio_semana").html('$'+ importe.toFixed(2));
        actualiza_calendario();
    }    

    function actualiza_calendario(){
        idtrabajador = $("#s_trabajador").val();
        myURL = 'index.php/admin_tickets/calendario_gastos/'+<?php echo $fecha; ?>+'/<?php echo $idtrabajador; ?>/';
    
        var contenido_semana = $.ajax({
            async: false,
            url: myURL
        }).responseText;

        $( "#calendario_g" ).html(contenido_semana);
        
        myURL = 'index.php/admin_tickets/gastos_mes/'+<?php echo $fecha; ?>+'/<?php echo $idtrabajador; ?>/';
    
        var saldo_mes;
        
          $.ajax({
              url: myURL,
              dataType: 'json',
              async: false,
              success: function(data){
                saldo_mes = data;
              }
            });
        $( "#saldo_mes" ).html('$ '+saldo_mes['saldo_r']);
            
    }

    function actualiza_ticket(numero_dia){
        
        var estedia = dia[numero_dia];
        for(var i = 0; i < estedia.length; i++){
            if (platos_select[numero_dia][i]){
                myURL = 'index.php/admin_tickets/agregar_plato/';
            } else {
                myURL = 'index.php/admin_tickets/quitar_plato/';
            }
            datos = "plato_idplato="+estedia[i]+"&trabajador_idtrabajador=<?php echo $idtrabajador; ?>";

            $.ajax({
               type: "POST",
               url: myURL,
               data: datos,
               success: function(){
               }
            });
        }
        alert('Actualizado correctamente.');
        
    }    


    actualiza_precio(1,false);
    actualiza_precio(2,false);
    actualiza_precio(3,false);
    actualiza_precio(4,false);
    actualiza_precio(5,false);
    actualiza_precio(6,false);
    actualiza_precio(7,false);
    actualiza_precio_s();
</script>    