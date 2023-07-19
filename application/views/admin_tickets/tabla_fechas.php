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
$fin_semana = $inicio_semana + 6 * 3600*24;

$datos_mes=script_datos_mes($seleccionado,$menus,$fecha_admisible, $inicio_semana, $prereservacion, $menu_platos, $ruta_url, $idtrabajador, $cierra_viernes, $iCheckStyleCB, $iCheckStyleRB, $incio_semana,$sede_idsede,$turno,$tiene_tickets_fecha);

function carga_menu_d($seleccion, $lista_dia, $numerodia, $fecha_admisible, $fecha_dia, $prereservacion, $menu_platos,$ruta_url, $idtrabajador, $cierra_viernes, $iCheckStyleCB, $iCheckStyleRB, $incio_semana,$sede_idsede,$turno,$tiene_tickets_fecha){
    $contenido_="";
    $mensaje_estado="";
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

        $contenido_.= '<h3>Esperando la publicación del menú.</h3>';
        
        if(count($prereservacion[$numero_dia_a]) > 0 ){           
            if($prereservacion[$numero_dia_a][0]["sede_idsede"]==$sede_idsede && $prereservacion[$numero_dia_a][0]["turno"]==$turno){
                $contenido_.= '<p><div class="texto_plato"><input class="ck_prereservacion_'.$numerodia.'" checked onclick="actualiza_prereservacion('.$numerodia.',true);" type="checkbox" id="cb_pre'.$numerodia.'" '.$habiliado.' />&nbsp;<label>Prereservar.'.$texto_reservar.'</label></div></div></p>';
                $contenido_.= ("<script type='text/javascript'> prereservacion[".$numero_dia_a."] = true;</script>");
            }else if($prereservacion[$numero_dia_a][0]["sede_idsede"]!=$sede_idsede && ($prereservacion[$numero_dia_a][0]["turno"]==$turno)){
                $habiliado="disabled";
                $mensaje_estado="Prereservó en otra sede.";
                $contenido_.= '<p><div class="texto_plato"><input class="ck_prereservacion_'.$numerodia.'" onclick="actualiza_prereservacion('.$numerodia.',true);" type="checkbox" id="cb_pre'.$numerodia.'" '.$habiliado.' />&nbsp;<label>Prereservar.</label></div></div></p>';             
                $contenido_.= ("<script type='text/javascript'> prereservacion[".$numero_dia_a."] = true;</script>");
            }
        } else {
            $contenido_.= '<p><div class="texto_plato"><input class="ck_prereservacion_'.$numerodia.'" onclick="actualiza_prereservacion('.$numerodia.',true);" type="checkbox" id="cb_pre'.$numerodia.'" '.$habiliado.' />&nbsp;<label>Prereservar.'.$texto_reservar.'</label></div></div></p>';
            $contenido_.= ("<script type='text/javascript'> prereservacion[".$numero_dia_a."] = false;</script>");
        }
        $contenido_.= '<br><h4 align="center" style="color: #804000;">'.$mensaje_estado.'</h4>';

        $contenido_.= ("
            <script type='text/javascript'>
            $('.ck_prereservacion_".$numerodia."').iCheck({
              checkboxClass: '".$iCheckStyleCB."',
              radioClass: '".$iCheckStyleRB."'
              });
              ");
        
        $contenido_.= ("
          $('.ck_prereservacion_".$numerodia."').on('ifChanged', function(event){

            var seguridad = controla_seguridad();
            if (seguridad){

              $('#estado_".$numerodia."').html('Sin actualizar'); 
              idcb = this.id;
              var nplato = parseInt(idcb.substring(6));
              if(this.checked ){
                  prereservacion[".$numero_dia_a."] = true;
                  } else {
                      prereservacion[".$numero_dia_a."] = false;
                  }
                  actualiza_prereservacion(".$numero_dia_a.",'".date('Y-m-d',$fecha_dia)."');
              }         
              });
              </script>
              ");

    } else {
      
        for ($i=0; $i < count($lista_dia); $i++) {
            $plato = $lista_dia[$i];            
           
            if($seleccion[$i] == true){ 
                $contenido_.= '<div class="texto_plato"><input class="ck_plato_'.$numerodia.'" checked onclick="actualiza_precio('.$numerodia.',true);" type="checkbox" id="cb_'.$numerodia.'_'.$i.'" '.$habiliado_ck.' />&nbsp;<label>'.$plato['nombreplato'].' '.$plato['cantidad'].' '.$plato['simbolo'].' <div style="padding:3px; float: right;">$'.number_format($plato['precio'], 2) .'</label></div></div>';
                $mensaje_estado='Reservado.';
                $contenido_.= ("<script type='text/javascript'> platos_select[".$numerodia."][".$i."] = true;</script>");                
            }else if($tiene_tickets_fecha ){                   
                $habiliado_ck="disabled";
                $habiliado="disabled";
                $contenido_.= '<div class="texto_plato"><input class="ck_plato_'.$numerodia.'" onclick="actualiza_precio('.$numerodia.',true);" type="checkbox" id="cb_'.$numerodia.'_'.$i.'" '.$habiliado_ck.' /><label>&nbsp;'.$plato['nombreplato'].' '.$plato['cantidad'].' '.$plato['simbolo'].' <div  style="padding:3px; float: right;">$'.number_format($plato['precio'], 2) .'</label></div></div>';
                $mensaje_estado='Reservado en otra sede.';
                $contenido_.= ("<script type='text/javascript'> platos_select[".$numerodia."][".$i."] = false;</script>");
             }else{
                //if(substr_count($plato['nombreplato'],'(')==0){
                $contenido_.= '<div class="texto_plato"><input class="ck_plato_'.$numerodia.'" onclick="actualiza_precio('.$numerodia.',true);" type="checkbox" id="cb_'.$numerodia.'_'.$i.'" '.$habiliado_ck.' /><label>&nbsp;'.$plato['nombreplato'].' '.$plato['cantidad'].' '.$plato['simbolo'].' <div  style="padding:3px; float: right;">$'.number_format($plato['precio'], 2) .'</label></div></div>';
                $contenido_.= ("<script type='text/javascript'> platos_select[".$numerodia."][".$i."] = false;</script>");
                /*}else{                    
                    $contenido_.='<h3 align="center" style="color: #804000;">Platos Opcionales</h3>';
                    $contenido_.= '<div class="texto_plato"><input class="ck_plato_'.$numerodia.'" onclick="actualiza_precio('.$numerodia.',true);" type="checkbox" id="cb_'.$numerodia.'_'.$i.'" '.$habiliado_ck.' /><label>&nbsp;'.$plato['nombreplato'].' '.$plato['cantidad'].' '.$plato['simbolo'].' <div  style="padding:3px; float: right;">$'.number_format($plato['precio'], 2) .'</label></div></div>';
                $contenido_.= ("<script type='text/javascript'> platos_select[".$numerodia."][".$i."] = false;</script>");
                }*/
            }
        }
        if ($habiliado != "disabled"){
            $contenido_.= '<input '.$habiliado.' type="button" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" onclick="marca_todos('.$numerodia.');" value="Todos" />';
            $contenido_.= '<input '.$habiliado.' type="button" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" onclick="desmarca_todos('.$numerodia.');" value="Ninguno" />';
        }

        $contenido_.= '<br><h4 align="center" style="color: #804000;">'.$mensaje_estado.'</h4>';

        $contenido_.= ("
            <script type='text/javascript'>

            $('.ck_plato_".$numerodia."').iCheck({
              checkboxClass: '".$iCheckStyleCB."',
              radioClass: '".$iCheckStyleRB."'
              });

              $('.ck_plato_".$numerodia."').on('ifChanged', function(event){
                      //$('#estado_".$numerodia."').html('Sin actualizar'); 

                  ");
        if($menu_platos){
            $contenido_.= ("

                var seguridad = controla_seguridad();
                if (seguridad){
                  ");
        }
        $contenido_.= ("

          idcb = this.id;
          var nplato = parseInt(idcb.substring(5));
          if(this.checked){
              precio_ds[".$numero_dia_a."] +=precio_dia[".$numerodia."][nplato];
              platos_select[".$numerodia."][nplato] = true;
              myURL = '".$ruta_url."index.php/admin_tickets/agregar_plato/';    
              } else {
                  precio_ds[".$numero_dia_a."] -=precio_dia[".$numerodia."][nplato];
                  platos_select[".$numerodia."][nplato] = false;
                  myURL = '".$ruta_url."index.php/admin_tickets/quitar_plato/';    
              }

              ");
        if($menu_platos){


            $fecha=date('Y-m-d',$fecha_dia);
            $contenido_.= ("
                datos = 'plato_idplato='+dia[".$numerodia."][nplato]+'&trabajador_idtrabajador=".$idtrabajador."'+'&sede_idsede=".$sede_idsede."'+'&turno=".$turno."'+'&fecha=".$fecha."';

                $.ajax({
                 type: 'POST',
                 url: myURL,
                 data: datos,
                 success: function(){
                 }
                 });
                 actualiza_precio(".$numerodia.",true);
             }

             ");
        } 

        $contenido_.= ("
            });

            </script>
            ");

    }
    return $contenido_;
}


function script_datos_mes($seleccionado,$menus,$fecha_admisible, $inicio_semana, $prereservacion, $menu_platos,$ruta_url, $idtrabajador, $cierra_viernes, $iCheckStyleCB, $iCheckStyleRB, $incio_semana,$sede_idsede,$turno,$tiene_tickets_fecha){
    $dia=['lunes','martes','miercoles','jueves','viernes','sabado','domingo'];
    $datos_mess=array();

    for ($i=0; $i < 7; $i++) {          
        if($i>1){

            $datos_mes['carga_menu_d_'.$i]=carga_menu_d($seleccionado[$dia[$i]],$menus[$dia[$i]],$i+1,$fecha_admisible, ($inicio_semana+3600*24*$i), $prereservacion, $menu_platos, $ruta_url, $idtrabajador, $cierra_viernes, $iCheckStyleCB, $iCheckStyleRB, $incio_semana,$sede_idsede,$turno,$tiene_tickets_fecha[$i]);
        }else{
           
            $datos_mes['carga_menu_d_'.$i]=carga_menu_d($seleccionado[$dia[$i]],$menus[$dia[$i]],$i+1,$fecha_admisible, ($inicio_semana+3600*24*$i), $prereservacion, $menu_platos, $ruta_url, $idtrabajador, $cierra_viernes, $iCheckStyleCB, $iCheckStyleRB, $incio_semana,$sede_idsede,$turno,$tiene_tickets_fecha[$i]);            
        }    
    } 
    return $datos_mes;
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
            <td><div class="titulo_dia"><strong>Lunes&nbsp;</strong>(<?php echo(date('d/m/Y',$inicio_semana));?>)&nbsp;<strong><span id="estado_1">&nbsp;</span></strong></div></td>
            <td><div class="titulo_dia"><strong>Martes&nbsp;</strong>(<?php echo(date('d/m/Y',$inicio_semana + 3600*24));?>)&nbsp;<strong><span id="estado_2">&nbsp;</span></strong></div></td>
            <td><div class="titulo_dia"><strong>Miércoles&nbsp;</strong>(<?php echo(date('d/m/Y',$inicio_semana + 3600*24*2));?>)&nbsp;<strong><span id="estado_3">&nbsp;</span></strong></div></td>
        </tr>
    </thead>
    <tr>
        <td id="l" valign="top"><?php echo $datos_mes['carga_menu_d_0'];?></td>
        <td valign="top"><?php echo $datos_mes['carga_menu_d_1'];?></td>
        <td valign="top"><?php echo $datos_mes['carga_menu_d_2'];?></td>
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
            <td><div class="titulo_dia"><strong>Jueves&nbsp;</strong>(<?php echo(date('d/m/Y',$inicio_semana + 3600*24*3));?>)&nbsp;<strong><span id="estado_4">&nbsp;</span></strong></div></td>
            <td><div class="titulo_dia"><strong>Viernes&nbsp;</strong>(<?php echo(date('d/m/Y',$inicio_semana + 3600*24*4));?>)&nbsp;<strong><span id="estado_5">&nbsp;</span></strong></div></td>
            <td><div class="titulo_dia"><strong>Sábado&nbsp;</strong>(<?php echo(date('d/m/Y',$inicio_semana + 3600*24*5));?>)&nbsp;<strong><span id="estado_6">&nbsp;</span></strong></div></td>
        </tr>
    </thead>
    <tr>
        <td valign="top"><?php echo $datos_mes['carga_menu_d_3'];?></td>
        <td valign="top"><?php echo $datos_mes['carga_menu_d_4'];?></td>
        <td valign="top"><?php echo $datos_mes['carga_menu_d_5'];?></td>
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
            <td><div class="titulo_dia"><strong>Domingo&nbsp;</strong>(<?php echo(date('d/m/Y',$inicio_semana + 3600*24*6));?>)&nbsp;<strong><span id="estado_7">&nbsp;</span></strong></div></td>
            <td><div class="resumen_semana"><strong>Resumen de la Semana&nbsp;</strong></div></td>
            <td><div class="almuerzos_mes"><strong><?php if($turno==1) echo "Desayunos"; else if($turno==2) echo "Almuerzos"; else if($turno==3) echo "Comidas";?> en el mes&nbsp;</strong></div></td>
        </tr>
    </thead>
    <tr>
        <td valign="top"><?php echo $datos_mes['carga_menu_d_6'];?></td>
        <td valign="top">
            <div class="texto_plato" style="padding: 3px;">Lunes:<div style="float: right;" id="precios_1"></div></div>            
            <div class="texto_plato" style="padding: 3px;">Martes:<div style="float: right;" id="precios_2"></div></div>            
            <div class="texto_plato" style="padding: 3px;">Miércoles:<div style="float: right;" id="precios_3"></div></div>            
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
        fecha_ver = <?php echo $fecha - 3600*24*7; ?>;
        actualiza_trabajador();
    });
    $( "#next" ).button({
        text: false,
        icons: {
            primary: "ui-icon-seek-next"
        }
    }).click(function(){
        fecha_ver = <?php echo $fecha + 3600*24*7; ?>;
        actualiza_trabajador();
    });

    function marca_todos(numero_dia){
        var seguridad = controla_seguridad();
        if (seguridad){
            $('.ck_plato_'+numero_dia).iCheck('check');
            actualiza_ticket(numero_dia);
            muestra_mensaje('<h2>Reservado correctamente.</h2>', 300,null);
        }    
    }    

    function desmarca_todos(numero_dia){
        var seguridad = controla_seguridad();
        if (seguridad){
            $('.ck_plato_'+numero_dia).iCheck('uncheck');
            actualiza_ticket(numero_dia);
            muestra_mensaje('<h2>Cancelada la reserva correctamente.</h2>', 300,null);
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
        actualiza_precio_s();
    }
    actualiza_calendario();
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
    idtrabajador = <?php echo $idtrabajador; ?>;
    sede =<?php echo $sede_idsede; ?>;
    turno =<?php echo $turno; ?>;
    myURL = '<?php echo $ruta_url; ?>index.php/admin_tickets/calendario_gastos/'+<?php echo $inicio_semana; ?>+'/'+idtrabajador+'/'+sede+'/'+turno+'/';

    var contenido_semana = $.ajax({
        async: false,
        url: myURL
    }).responseText;

    $( "#calendario_g" ).html(contenido_semana);

    myURL = '<?php echo $ruta_url; ?>index.php/admin_tickets/gastos_mes/'+<?php echo $inicio_semana; ?>+'/'+idtrabajador+'/'+sede+'/'+turno+'/';

    var saldo_mes;
    

    $.ajax({
        url: myURL,
        dataType: 'json',
        async: false,
        success: function(data){                    
            if(turno==1)
                saldo_mes = data['saldo_r_1'];
            else if(turno==2)
                saldo_mes= data['saldo_r_2'];
            else if(turno==3){
                saldo_mes= data['saldo_r_3'];
            }
        }
    });
    
    $( "#saldo_mes" ).html('$ '+ saldo_mes);

}

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
    
    actualiza_calendario();
    muestra_mensaje('<h2>Actualizado correctamente.</h2>', 300,null);
//alert('Actualizado correctamente.');

}  

for (var i = 1; i < 8; i++) {
    actualiza_precio(i,false);    
};    
actualiza_precio_s();
</script>    
