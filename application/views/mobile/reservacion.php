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

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$dia_semana = date('N', $fecha);
$fin_semana = $inicio_semana + 6 * 3600*24;

    $turno_="";    
    if($turno==1){
        $turno_="reservar_desayunos";
    }else if($turno==2){
        $turno_="reservar_almuerzos";

    }else if($turno==3){
        $turno_="reservar_comidas";

    }

function carga_menu_d($seleccion, $lista_dia, $numerodia, $fecha_admisible, $fecha_dia, $prereservacion, $menu_platos,$ruta_url, $idtrabajador, $cierra_viernes, $iCheckStyleCB, $iCheckStyleRB, $incio_semana,$sede_idsede,$turno,$tiene_tickets_fecha){

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

                //Para los dias de la semana

        if ($numerodia <= $incio_semana && $fecha_admisible > $fecha_dia - ((1 + $incio_semana) * 3600*24)){
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

        echo '<form><fieldset data-role="controlgroup">';

        if(count($prereservacion[$numero_dia_a]) > 0){
            if($prereservacion[$numero_dia_a][0]["sede_idsede"]==$sede_idsede && $prereservacion[$numero_dia_a][0]["turno"]==$turno){

            echo '<input type="checkbox" checked id="cb_pre'.$fecha_dia.'"  name="cb_pre'.$fecha_dia.'" '.$habiliado.' >';
             echo '<label for="cb_pre'.$fecha_dia.'"  class="ui-btn ui-corner-all ui-btn-inherit ui-btn-icon-left ui-checkbox-off">Reservado.</label>';
            echo ("<script type='text/javascript'> prereservacion[".$numero_dia_a."] = true;</script>");
        }else if($prereservacion[$numero_dia_a][0]["sede_idsede"]!=$sede_idsede && ($prereservacion[$numero_dia_a][0]["turno"]==$turno)){
            $texto_reservar="Prereservó en otra sede.";
            $habiliado="disabled";
            echo '<input type="checkbox"  id="cb_pre'.$fecha_dia.'"  name="cb_pre'.$fecha_dia.'" '.$habiliado.' >';
             echo '<label for="cb_pre'.$fecha_dia.'"  class="ui-btn ui-corner-all ui-btn-inherit ui-btn-icon-left ui-checkbox-off">'.$texto_reservar.'</label>';

            echo ("<script type='text/javascript'> prereservacion[".$numero_dia_a."] = true;</script>");
        }
        } else {
            echo '<input type="checkbox" id="cb_pre'.$fecha_dia.'"  name="cb_pre'.$fecha_dia.'" '.$habiliado.' >';
             echo '<label for="cb_pre'.$fecha_dia.'"  class="ui-btn ui-corner-all ui-btn-inherit ui-btn-icon-left ui-checkbox-off">Prereservar. '.$texto_reservar.'</label>';
            echo ("<script type='text/javascript'> prereservacion[".$numero_dia_a."] = false;</script>");
        }
       

        echo '</fieldset></form>';                   
        echo ("
            <script type='text/javascript'>

            $('#cb_pre".$fecha_dia."').change(function(){

                var seguridad = controla_seguridad('".$ruta_url."');
                if (seguridad){

                              //$('#estado_'".$numerodia."').html('Sin actualizar'); 

                  if($('#cb_pre".$fecha_dia."').attr('data-cacheval')=='true'){
                      prereservacion[".$numero_dia_a."] = false;
                      } else {
                          prereservacion[".$numero_dia_a."] = true;
                      }
                      actualiza_prereservacion(".$numero_dia_a.",'".date('Y-m-d',$fecha_dia)."');

                  }         
                  });
                  </script>
                  ");

    } else {

        if($menu_platos == true){
            echo '<form><fieldset data-role="controlgroup">';
        } else {
             echo '<dl data-role="controlgroup" data-count-theme="b" data-inset="true">';
        }

        for ($i=0; $i < count($lista_dia); $i++) {

            $plato = $lista_dia[$i];
            if($menu_platos == true){

                if($seleccion[$i] == true){ 
                    echo '<input type="checkbox" class=".ck_plato_'.$numerodia.'" checked  id="cb_'.$numerodia.'_'.$i.'" name="cb_'.$numerodia.'_'.$i.'" '.$habiliado_ck.' >';
                    echo ("<script type='text/javascript'> platos_select[".$numerodia."][".$i."] = true;</script>");
                } else {
                    echo '<input type="checkbox" id="cb_'.$numerodia.'_'.$i.'" name="cb_'.$numerodia.'_'.$i.'" '.$habiliado_ck.' >';
                    echo ("<script type='text/javascript'> platos_select[".$numerodia."][".$i."] = false;</script>");
                }
                echo '<label for="cb_'.$numerodia.'_'.$i.'" class="ui-btn ui-corner-all ui-btn-inherit ui-btn-icon-left ui-checkbox-off">'.$plato['nombreplato'].' '.$plato['cantidad'].' '.$plato['simbolo'].' <div style="padding:3px; float: right;">$'.number_format($plato['precio'], 2) .'</div></label>';

                echo ("
                    <script type='text/javascript'>

                    $('#cb_".$numerodia."_".$i."').change(function(){

                        var seguridad = controla_seguridad('".$ruta_url."');
                        if (seguridad){

                                      //$('#estado_'".$numerodia."').html('Sin actualizar'); 

                          if($('#cb_".$numerodia."_".$i."').attr('data-cacheval')!='true'){
                            precio_ds[".$numerodia."] +=precio_dia[".$numerodia."][".$i."];
                            platos_select[".$numerodia."][".$i."] = true;
                            myURL = '".$ruta_url."index.php/admin_tickets/agregar_plato/';    
                            } else {
                                precio_ds[".$numerodia."] -=precio_dia[".$numerodia."][".$i."];
                                platos_select[".$numerodia."][".$i."] = false;
                                myURL = '".$ruta_url."index.php/admin_tickets/quitar_plato/';    
                            }
                            actualiza_precio(".$numerodia.", true); 
                            actualiza_ticket(".$numerodia.");    
                        }         
                        });
                        </script>
                        ");
            } else {
                if($seleccion[$i] == true){ 
                    echo '<li><img id="cb_'.$numerodia.'_'.$i.'" src="'.$ruta_url.'css/mobile/images/icons-png/check-black.png" class="ui-li-icon ui-corner-none">&nbsp;'.$plato['nombreplato'].' '.$plato['cantidad'].' '.$plato['simbolo'].'<div style=" float: right;">$'.number_format($plato['precio'], 2) .'</div></li>';
                    echo ("<script type='text/javascript'> platos_select[".$numerodia."][".$i."] = true;</script>");
                } else {
                    echo '<li><img id="cb_'.$numerodia.'_'.$i.'" src="'.$ruta_url.'css/mobile/images/icons-png/delete-black.png" class="ui-li-icon ui-corner-none">&nbsp;'.$plato['nombreplato'].' '.$plato['cantidad'].' '.$plato['simbolo'].'<div style="float: right;">$'.number_format($plato['precio'], 2) .'</div></li>';
                    echo ("<script type='text/javascript'> platos_select[".$numerodia."][".$i."] = false;</script>");
                }

            }

        }
        if($menu_platos == true){
            echo '</fieldset></form>';
        } else {
            echo '</dl>';
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
<div role="main" id="contenido" class="ui-content">
    <div id="custom-border-radius" style="float: left;">
        <a href="#<?php echo $ruta_url;?>index.php/mobile/<?php echo $turno_;?>/<?php echo $fecha - 3600*24*7; ?>/" class="ui-btn ui-icon-carat-l ui-btn-icon-notext ui-corner-all">No text</a>
    </div>
    <div id="custom-border-radius" style="float: right;">
        <a href="#<?php echo $ruta_url;?>index.php/mobile/<?php echo $turno_;?>/<?php echo $fecha + 3600*24*7; ?>/" class="ui-btn ui-icon-carat-r ui-btn-icon-notext ui-corner-all">No text</a>
    </div>
    <div style="width: 100%;height: 50px;text-align: center;">
        Del <?php echo(date('d/m/Y',$inicio_semana)); ?> al <?php echo(date('d/m/Y',$fin_semana)); ?>
    </div>
    <div data-role="collapsible">
        <h4>Lunes&nbsp;(<?php echo(date('d/m/Y',$inicio_semana));?>)&nbsp;<span id="precio_1">$ 0.00</span></h4>
        <p>
            <?php carga_menu_d($seleccionado['lunes'],$menus['lunes'],1,$fecha_admisible, $inicio_semana, $prereservacion, $menu_platos, $ruta_url, $idtrabajador, $cierra_viernes, $iCheckStyleCB, $iCheckStyleRB, $incio_semana,$sede_idsede,$turno,$tiene_tickets_fecha); ?>
        </p>
    </div>
    <div data-role="collapsible">
        <h4>Martes&nbsp;(<?php echo(date('d/m/Y',$inicio_semana + 3600*24));?>)&nbsp;<span id="precio_2">$ 0.00</span></h4>
        <p>
            <?php carga_menu_d($seleccionado['martes'],$menus['martes'],2,$fecha_admisible, $inicio_semana + 3600*24, $prereservacion, $menu_platos, $ruta_url, $idtrabajador, $cierra_viernes, $iCheckStyleCB, $iCheckStyleRB, $incio_semana,$sede_idsede,$turno,$tiene_tickets_fecha); ?>
        </p>
    </div>
    <div data-role="collapsible">
        <h4>Miércoles&nbsp;(<?php echo(date('d/m/Y',$inicio_semana + 3600*24*2));?>)&nbsp;<span id="precio_3">$ 0.00</span></h4>
        <p>
            <?php carga_menu_d($seleccionado['miercoles'],$menus['miercoles'],3,$fecha_admisible, $inicio_semana + 3600*24*2, $prereservacion, $menu_platos, $ruta_url, $idtrabajador, $cierra_viernes, $iCheckStyleCB, $iCheckStyleRB, $incio_semana,$sede_idsede,$turno,$tiene_tickets_fecha); ?>
        </p>
    </div>
    <div data-role="collapsible">
        <h4>Jueves&nbsp;(<?php echo(date('d/m/Y',$inicio_semana + 3600*24*3));?>)&nbsp;<span id="precio_4">$ 0.00</span></h4>
        <p>
            <?php carga_menu_d($seleccionado['jueves'],$menus['jueves'],4,$fecha_admisible, $inicio_semana + 3600*24*3, $prereservacion, $menu_platos, $ruta_url, $idtrabajador, $cierra_viernes, $iCheckStyleCB, $iCheckStyleRB, $incio_semana,$sede_idsede,$turno,$tiene_tickets_fecha); ?>
        </p>
    </div>
    <div data-role="collapsible">
        <h4>Viernes&nbsp;(<?php echo(date('d/m/Y',$inicio_semana + 3600*24*4));?>)&nbsp;<span id="precio_5">$ 0.00</span></h4>
        <p>
            <?php carga_menu_d($seleccionado['viernes'],$menus['viernes'],5,$fecha_admisible, $inicio_semana + 3600*24*4, $prereservacion, $menu_platos, $ruta_url, $idtrabajador, $cierra_viernes, $iCheckStyleCB, $iCheckStyleRB, $incio_semana,$sede_idsede,$turno,$tiene_tickets_fecha); ?>
        </p>
    </div>
    <div data-role="collapsible">
        <h4>Sábado&nbsp;(<?php echo(date('d/m/Y',$inicio_semana + 3600*24*5));?>)&nbsp;<span id="precio_6">$ 0.00</span></h4>
        <p>
            <?php carga_menu_d($seleccionado['sabado'],$menus['sabado'],6,$fecha_admisible, $inicio_semana + 3600*24*5, $prereservacion, $menu_platos, $ruta_url, $idtrabajador, $cierra_viernes, $iCheckStyleCB, $iCheckStyleRB, $incio_semana,$sede_idsede,$turno,$tiene_tickets_fecha); ?>
        </p>
    </div>
    <div data-role="collapsible">
        <h4>Domingo&nbsp;(<?php echo(date('d/m/Y',$inicio_semana + 3600*24*6));?>)&nbsp;<span id="precio_7">$ 0.00</span></h4>
        <p>
            <?php carga_menu_d($seleccionado['domingo'],$menus['domingo'],7,$fecha_admisible, $inicio_semana + 3600*24*6, $prereservacion, $menu_platos, $ruta_url, $idtrabajador, $cierra_viernes, $iCheckStyleCB, $iCheckStyleRB, $incio_semana,$sede_idsede,$turno,$tiene_tickets_fecha); ?>
        </p>
    </div>
</div>
<script type="text/javascript">
/*    
                    $( "#prev" ).click(function(){
                        fecha_ver = <?php echo $fecha - 3600*24*7; ?>;
                        actualiza_trabajador();
                        });
                    $( "#next" ).click(function(){
                        fecha_ver = <?php echo $fecha + 3600*24*7; ?>;
                        actualiza_trabajador();
                        });
                        */
                        function marca_todos(numero_dia){
                            var seguridad = controla_seguridad('<?php echo $ruta_url ?>');
                            if (seguridad){
                             for(var i = 0; i < platos_select[numero_dia].length; i++){
                //alert('#cb_'+numero_dia+'_'+i+' '+platos_select[numero_dia][i]);
                if (!platos_select[numero_dia][i]){
                    <?php 
                    if($menu_platos == true){ 
                        ?>    
                        $('#cb_'+numero_dia+'_'+i).click();
                        <?php 
                    } else {
                        ?>    
                        $('#cb_'+numero_dia+'_'+i).attr("src","<?php echo $ruta_url ?>css/mobile/images/icons-png/check-black.png");
                        <?php 
                    } 
                    ?>    
                    platos_select[numero_dia][i]=true;
                }    
            }

            
            actualiza_ticket(numero_dia);
            //muestra_mensaje('<h2>Actualizado correctamente.</h2>', 300,null);
//            alert('Actualizado correctamente.');
}    
}    

function desmarca_todos(numero_dia){
    var seguridad = controla_seguridad('<?php echo $ruta_url ?>');
    if (seguridad){
     for(var i = 0; i < platos_select[numero_dia].length; i++){
                //alert('#cb_'+numero_dia+'_'+i+' '+platos_select[numero_dia][i]);
                if (platos_select[numero_dia][i]){
                    <?php 
                    if($menu_platos == true){ 
                        ?>    
                        $('#cb_'+numero_dia+'_'+i).click();
                        <?php 
                    } else {
                        ?>    
                        $('#cb_'+numero_dia+'_'+i).attr("src","<?php echo $ruta_url ?>css/mobile/images/icons-png/delete-black.png");
                        <?php 
                    } 
                    ?>    
                    platos_select[numero_dia][i]=false;
                }    
            }
            actualiza_ticket(numero_dia);
            //muestra_mensaje('<h2>Actualizado correctamente.</h2>', 300,null);
//            alert('Actualizado correctamente.');
}    
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
            //actualiza_precio_s();
        }
        //actualiza_calendario();
    }    
/*
    function actualiza_precio_s(){
        importe = 0;
        for(var i = 0; i < 7; i++){
           importe += precio_ds[i];
        }
        $("#precio_semana").html('$'+ importe.toFixed(2));
        //actualiza_calendario();
    }    

    function actualiza_calendario(){
        idtrabajador = <?php echo $idtrabajador; ?>;
        myURL = '<?php echo $ruta_url; ?>index.php/admin_tickets/calendario_gastos/'+<?php echo $inicio_semana; ?>+'/'+idtrabajador+'/';
    
        var contenido_semana = $.ajax({
            async: false,
            url: myURL
        }).responseText;

        $( "#calendario_g" ).html(contenido_semana);
        
        myURL = '<?php echo $ruta_url; ?>index.php/admin_tickets/gastos_mes/'+<?php echo $inicio_semana; ?>+'/'+idtrabajador+'/';
    
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
    */
    function actualiza_ticket(numero_dia){
        var estedia = dia[numero_dia];
        var listaPlatos = '';
        var marcado = '';
        for(var i = 0; i < estedia.length; i++){
            listaPlatos += estedia[i];
            if (i < estedia.length - 1){
                listaPlatos += '|';
            }
            if (platos_select[numero_dia][i]){
                marcado += '1';
            } else {
                marcado += '0';
            }
            if (i < estedia.length - 1){
                marcado += '|';
            }

        }

        myURL = '<?php echo $ruta_url;?>index.php/admin_tickets/actualiza_platos_lote/';
        
        datos = "listaPlatos="+listaPlatos+"&seleccionados="+marcado+"&trabajador_idtrabajador=<?php echo $idtrabajador; ?>"+"&sede_idsede=<?php echo $sede_idsede; ?>"+"&turno=<?php echo $turno; ?>";

        $.ajax({
         type: "POST",
         url: myURL,
         async: false,
         data: datos,
         success: function(){
         }
     });
        
        
        $('#estado_'+numero_dia).html('&nbsp;');
        actualiza_precio(numero_dia,true);
//        muestra_mensaje('<h2>Actualizado correctamente.</h2>', 300,null);
//        alert('Actualizado correctamente.');

}    

function actualiza_prereservacion(numero_dia,fecha_dia){

    if (prereservacion[numero_dia]){
        myURL = '<?php echo $ruta_url;?>index.php/admin_tickets/agregar_prereservacion/';
    } else {
        myURL = '<?php echo $ruta_url;?>index.php/admin_tickets/quitar_prereservacion/';
    }
    datos = "fecha="+fecha_dia+"&idtrabajador=<?php echo $idtrabajador; ?>"+"&sede_idsede=<?php echo $sede_idsede; ?>"+"&turno=<?php echo $turno; ?>";

    $.ajax({
     type: "POST",
     url: myURL,
     data: datos,
     success: function(){
     }
 });
    numero_dia++;
    $('#estado_'+numero_dia).html('&nbsp;');
}  
actualiza_precio(1,false);
actualiza_precio(2,false);
actualiza_precio(3,false);
actualiza_precio(4,false);
actualiza_precio(5,false);
actualiza_precio(6,false);
actualiza_precio(7,false);
/*    
    actualiza_precio_s();
    */    
</script>    
