<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<table width="100%" class="ui-widget ui-widget-content" cellpadding="3" cellspacing="0">
    <thead class="ui-widget-header">
        <tr>
            <td align="center" style="font-size: 1.5em;">Almuerzos</td>
        </tr>
    </thead>
</table>    
<table width="100%" class="menus ui-widget ui-widget-content" cellpadding="5">
    <thead class="ui-widget-header">
    <tr>
        <td><div class="titulo_dia"><strong>Menú de hoy&nbsp;</strong>(<?php echo(date('d/m/Y',  time()));?>)</div></td>
        <td><div class="titulo_dia"><strong>Almuerzos en el Mes:</strong></div></td>
        <td><div class="titulo_dia"><strong>Reservación Mañana&nbsp;</strong>(<?php echo(date('d/m/Y',time() + 3600*24));?>)</div></td>
    </tr>
    </thead>
    <tr>
        <td valign="top">
        <?php 
            $precio_hoy = 0;
            if(count($menu_hoy) < 1){
                echo '<h3>No hay platos definidos para hoy.</h3>';
            } else {
                for ($i=0; $i < count($menu_hoy); $i++) {
                    $plato = $menu_hoy[$i];
                    $precio_hoy += $plato['precio'];
                        echo '<p><div class="texto_plato">'.$plato['nombreplato'].' '.$plato['cantidad'].' '.$plato['simbolo'].' <div style="padding:0 3px; float: right;">$'.number_format($plato['precio'], 2) .'</div></div></p>';
                }
            }
        ?>
        </td>
        <td valign="top">
            <div id="calendario_g"></div>
        </td>
        <td valign="top">
            <div id="menu_manana"></div>
        </td>
    </tr>
    <thead class="ui-widget-header">
    <tr>
        <td><strong><div id="precio_hoy">Precio: <div style="padding:0 3px; float: right;">$ <?php echo number_format($precio_hoy, 2); ?></div></div></strong></td>
        <td><strong><div id="precio_mes">Total en el mes:<div style="float: right;" id="saldo_mes">&nbsp;</div></div></strong></td>
        <td><strong><div id="precio_manana"></div></strong></td>
    </tr>
    </thead>
</table>
<script type="text/javascript">
    function actualiza_calendario(){
        myURL = '<?php echo $ruta_url; ?>index.php/admin_tickets/calendario_gastos/<?php echo $inicio_semana; ?>/<?php echo $idtrabajador; ?>/';
    
        var contenido_semana = $.ajax({
            async: false,
            url: myURL
        }).responseText;

        $( "#calendario_g" ).html(contenido_semana);
        
        myURL = '<?php echo $ruta_url; ?>index.php/admin_tickets/gastos_mes/<?php echo $inicio_semana; ?>/<?php echo $idtrabajador; ?>/';
    
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
actualiza_calendario();

var platos_manana;
var precio_ticket = 0;
var platos_select = [];
var precios_platos = [];
var idplatos = [];

function carga_menu_manana(){
    // Obtener menu y selecionados por el tabajador
        myURL = '<?= $ruta_url; ?>index.php/principal/lista_platos_ma_trab_json/<?php echo $idtrabajador; ?>/';
        var listas;
      $.ajax({
          url: myURL,
          dataType: 'json',
          async: false,
          success: function(data){
            listas = data;
          }
        });
        // Crear la lista
        var texto = '<p>';

        var habilitado_ck;
        
        <?php 
            if($menu_platos == true){
                ?>
                habilitado_ck = ' ';
                <?php
            } else {
                ?>
                habilitado_ck = 'disabled';
                <?php
            }
        ?>


        if(listas['habilitado'] == true){
            habilitado = ' ';
        } else {
            habilitado = 'disabled ';
            habilitado_ck = 'disabled ';
            
        }
        
        if(listas['menus'].length >0){
        
            for(i=0; i < listas['menus'].length; i++){
                plato = listas['menus'][i];
                precios_platos[i] = parseFloat(plato['precio']); 
                idplatos[i] = plato['idplato']; 
                if (listas['seleccionado'][i]){
                    select = 'checked ';
                    precio_ticket += precios_platos[i];
                    platos_select[i] = true;
                } else {
                    select = ' ';
                    platos_select[i] = false;
                }
//                texto = texto + '<div class="texto_plato"><input class="ck_plato" '+ select +' onclick="actualiza_precio();" type="checkbox" id="cb'+ i +'" '+ habilitado +' />'+ plato['nombreplato']+' '+ plato['cantidad']+' '+ plato['simbolo']+' <div style="padding:3px; float: right;">$ '+ listas['precio_r'][i] + '</div></div>';
                texto = texto + '<div class="texto_plato"><input class="ck_plato" '+ select +' type="checkbox" id="cb'+ i +'" '+ habilitado_ck +' /><label>&nbsp;'+ plato['nombreplato']+' '+ plato['cantidad']+' '+ plato['simbolo']+' <div style="padding:3px; float: right;">$ '+ listas['precio_r'][i] + '</div></label></div>';
            }

            texto += '</p>';
            if (listas['habilitado'] == true){
                texto = texto + '<input type="button" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" onclick="marca_todos();" value="Todos" />';
                texto = texto + '<input type="button" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" onclick="desmarca_todos();" value="Ninguno" />';
                texto = texto + '<input type="button" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" onclick="actualiza_ticket();" value="Actualizar" />';
            } else {
                texto += '<h2>Se agotó el tiempo para reservar almuerzos para este día.</h2>';
            }
        } else {
            texto += '<h2>No hay menu establecido para este día.</h2></p>';
        }
        
        //alert(texto);
        $("#menu_manana").html(texto);
        
    $('.ck_plato').iCheck({
          checkboxClass: 'icheckbox_square-blue',
          radioClass: 'iradio_flat-blue'
    });
    
    $('.ck_plato').on('ifChanged', function(event){
      idcb = this.id;
      var nplato = parseInt(idcb.substring(2));
    
      if(this.checked){
          precio_ticket +=precios_platos[nplato];
          platos_select[nplato] = true;
      } else {
          precio_ticket -=precios_platos[nplato];
          platos_select[nplato] = false;
      }
      actualiza_precio();
    });
  
}

    function actualiza_precio(){
        $("#precio_manana").html('Precio: <div style="padding:0 3px; float: right;">$'+ precio_ticket.toFixed(2)+'</div>');
    }    

    function marca_todos(){
        $('.ck_plato').iCheck('check');
    }    

    function desmarca_todos(){
        $('.ck_plato').iCheck('uncheck');
    }    

    function actualiza_ticket(){
        
        for(var i = 0; i < precios_platos.length; i++){
            if (platos_select[i]){
                myURL = '<?php echo $ruta_url;?>index.php/admin_tickets/agregar_plato/';
            } else {
                myURL = '<?php echo $ruta_url;?>index.php/admin_tickets/quitar_plato/';
            }
            datos = "plato_idplato="+idplatos[i]+"&trabajador_idtrabajador=<?php echo $idtrabajador; ?>";

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


carga_menu_manana();
actualiza_precio();

</script>
<br />
<table width="100%" class="ui-widget ui-widget-content" cellpadding="3" cellspacing="0">
    <thead class="ui-widget-header">
        <tr>
            <td align="center" style="font-size: 1.5em;">Comidas</td>
        </tr>
    </thead>
</table>    
<table width="100%" class="menus ui-widget ui-widget-content" cellpadding="5">
    <thead class="ui-widget-header">
    <tr>
        <td><div class="titulo_dia"><strong>Menú de hoy&nbsp;</strong>(<?php echo(date('d/m/Y',  time()));?>)</div></td>
        <td><div class="titulo_dia"><strong>Comidas en el Mes:</strong></div></td>
        <td><div class="titulo_dia"><strong>Reservación Mañana&nbsp;</strong>(<?php echo(date('d/m/Y',time() + 3600*24));?>)</div></td>
    </tr>
    </thead>
    <tr>
        <td valign="top">
        <?php 
            $precio_hoy = 0;
            if(count($menu_hoy_comida) < 1){
                echo '<h3>No hay platos definidos para hoy.</h3>';
            } else {
                for ($i=0; $i < count($menu_hoy_comida); $i++) {
                    $plato = $menu_hoy_comida[$i];
                    $precio_hoy += $plato['precio'];
                        echo '<p><div class="texto_plato">'.$plato['nombreplato_comida'].' '.$plato['cantidad'].' '.$plato['simbolo'].' <div style="padding:0 3px; float: right;">$'.number_format($plato['precio'], 2) .'</div></div></p>';
                }
            }
        ?>
        </td>
        <td valign="top">
            <div id="calendario_g_comida"></div>
        </td>
        <td valign="top">
            <div id="menu_manana_comida"></div>
        </td>
    </tr>
    <thead class="ui-widget-header">
    <tr>
        <td><strong><div id="precio_hoy_comida">Precio: <div style="padding:0 3px; float: right;">$ <?php echo number_format($precio_hoy, 2); ?></div></div></strong></td>
        <td><strong><div id="precio_mes_comida">Total en el mes:<div style="float: right;" id="saldo_mes_comida">&nbsp;</div></div></strong></td>
        <td><strong><div id="precio_manana_comida"></div></strong></td>
    </tr>
    </thead>
</table>
<script type="text/javascript">
    function actualiza_calendario_comida(){
        myURL = '<?php echo $ruta_url; ?>index.php/admin_ticket_comidas/calendario_gastos/<?php echo $inicio_semana; ?>/<?php echo $idtrabajador; ?>/';
    
        var contenido_semana = $.ajax({
            async: false,
            url: myURL
        }).responseText;

        $( "#calendario_g_comida" ).html(contenido_semana);
        
        myURL = '<?php echo $ruta_url; ?>index.php/admin_ticket_comidas/gastos_mes/<?php echo $inicio_semana; ?>/<?php echo $idtrabajador; ?>/';
    
        var saldo_mes;
        
          $.ajax({
              url: myURL,
              dataType: 'json',
              async: false,
              success: function(data){
                saldo_mes = data;
              }
            });
        $( "#saldo_mes_comida" ).html('$ '+saldo_mes['saldo_r']);
            
    }
actualiza_calendario_comida();

var platos_manana_comida;
var precio_ticket_comida = 0;
var platos_select_comida = [];
var precios_platos_comida = [];
var idplatos_comida = [];

function carga_menu_manana_comida(){
    // Obtener menu y selecionados por el tabajador
        myURL = '<?= $ruta_url; ?>index.php/principal/lista_platos_comida_ma_trab_json/<?php echo $idtrabajador; ?>/';
        var listas;
      $.ajax({
          url: myURL,
          dataType: 'json',
          async: false,
          success: function(data){
            listas = data;
          }
        });
        // Crear la lista
        var texto = '<p>';

        var habilitado_ck;
        
        <?php 
            if($menu_platos == true){
                ?>
                habilitado_ck = ' ';
                <?php
            } else {
                ?>
                habilitado_ck = 'disabled ';
                <?php
            }
        ?>

        if(listas['habilitado'] == true){
            habilitado = ' ';
        } else {
            habilitado = 'disabled ';
            habilitado_ck = 'disabled ';
            
        }
        
        if(listas['menus'].length >0){
        
            for(i=0; i < listas['menus'].length; i++){
                plato = listas['menus'][i];
                precios_platos_comida[i] = parseFloat(plato['precio']); 
                idplatos_comida[i] = plato['idplato_comida']; 
                if (listas['seleccionado'][i]){
                    select = 'checked ';
                    precio_ticket_comida += precios_platos[i];
                    platos_select_comida[i] = true;
                } else {
                    select = ' ';
                    platos_select_comida[i] = false;
                }
//                texto = texto + '<div class="texto_plato"><input class="ck_plato" '+ select +' onclick="actualiza_precio();" type="checkbox" id="cb'+ i +'" '+ habilitado +' />'+ plato['nombreplato']+' '+ plato['cantidad']+' '+ plato['simbolo']+' <div style="padding:3px; float: right;">$ '+ listas['precio_r'][i] + '</div></div>';
                texto = texto + '<div class="texto_plato"><input class="ck_plato_comida" '+ select +' type="checkbox" id="cb_comida'+ i +'" '+ habilitado_ck +' /><label>&nbsp;'+ plato['nombreplato_comida']+' '+ plato['cantidad']+' '+ plato['simbolo']+' <div style="padding:3px; float: right;">$ '+ listas['precio_r'][i] + '</div></label></div>';
            }

            texto += '</p>';
            if (listas['habilitado'] == true){
                texto = texto + '<input type="button" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" onclick="marca_todos_comida();" value="Todos" />';
                texto = texto + '<input type="button" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" onclick="desmarca_todos_comida();" value="Ninguno" />';
                texto = texto + '<input type="button" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" onclick="actualiza_ticket_comida();" value="Actualizar" />';
            } else {
                texto += '<h2>Se agotó el tiempo para reservar comidas para este día.</h2>';
            }
        } else {
            texto += '<h2>No hay menu establecido para este día.</h2></p>';
        }
        
        //alert(texto);
        $("#menu_manana_comida").html(texto);
        
    $('.ck_plato_comida').iCheck({
          checkboxClass: 'icheckbox_square-blue',
          radioClass: 'iradio_flat-blue'
    });
    
    $('.ck_plato_comida').on('ifChanged', function(event){
      idcb = this.id;
      var nplato = parseInt(idcb.substring(9));
    
      if(this.checked){
          precio_ticket_comida +=precios_platos_comida[nplato];
          platos_select_comida[nplato] = true;
      } else {
          precio_ticket_comida -=precios_platos_comida[nplato];
          platos_select_comida[nplato] = false;
      }
      actualiza_precio_comida();
    });
  
}

    function actualiza_precio_comida(){
        $("#precio_manana_comida").html('Precio: <div style="padding:0 3px; float: right;">$'+ precio_ticket_comida.toFixed(2)+'</div>');
    }    

    function marca_todos_comida(){
        $('.ck_plato_comida').iCheck('check');
    }    

    function desmarca_todos_comida(){
        $('.ck_plato_comida').iCheck('uncheck');
    }    

    function actualiza_ticket_comida(){
        
        for(var i = 0; i < precios_platos_comida.length; i++){
            if (platos_select_comida[i]){
                myURL = '<?php echo $ruta_url;?>index.php/admin_ticket_comidas/agregar_plato_comida/';
            } else {
                myURL = '<?php echo $ruta_url;?>index.php/admin_ticket_comidas/quitar_plato_comida/';
            }
            datos = "plato_comida_idplato_comida="+idplatos[i]+"&trabajador_idtrabajador=<?php echo $idtrabajador; ?>";

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


carga_menu_manana_comida();
actualiza_precio_comida();


</script>