<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

if($tipo_usuario == 'Trabajador'){
   $tickets_alm = 'admin_tickets';
   $tickets_com = 'admin_ticket_comidas';
   $estadisticas = 'estadisticas';
   $controladora = 'trabajadores';
   $idusuario = $idtrabajador;
   $calendario = 'calendario';
   $acciones = 'acciones_trabajador';
}

?>
<table width="100%" class="ui-widget ui-widget-content" cellpadding="5" cellspacing="0">
    <thead class="ui-widget-header">
        <tr>
            <td></td>
            <td style="text-align: right;">Reservar del <input id="fecha_ini" size="10" type="text" readonly/></td>
            <td style="text-align: left;"> al <input id="fecha_fin" class="fechas_rep" size="10" type="text" readonly/></td>
            <td></td>
        </tr>
        <tr>
            <td>Incluir fin de semana:
                <select id="mult_finde">
                    <option value="0" >No</option>
                    <option value="1" >Si</option>
                </select>
            </td>
            <td>Acción:
                <select id="mult_accion">
                    <option value="0" >Reservar</option>
                    <option value="1" >Quitar reservación</option>
                </select>
            </td>
            <td>Actividad:
                <select id="mult_actividad">
                    <option value="0" >Desayuno</option>
                    <option value="1" >Almuerzo</option>
                    <option value="2" >Comida</option>
                    <option value="3" >Desayuno-Almuerzo-Comida</option>
                </select>
            </td>
            <td colspan="2"><button id="b_acciones_realizar">Realizar</button></td>
        </tr>    
    </thead>
</table>
<table width="100%" class="menus ui-widget ui-widget-content" cellpadding="5">
    <thead class="ui-widget-header">
        <tr>
            <td style="width: 75%;"><div class="titulo_dia" style="font-size: 1.2em;font-weight: bold;"><strong>Calendario</strong></div></td>
            <td style="width: 25%;"><div class="titulo_dia" style="font-size: 1.2em;font-weight: bold;"><strong>Menú de hoy&nbsp;</strong>(<?php echo(date('d/m/Y',  time()));?>)</div></td>
        </tr>
    </thead>
    <tr>
        <td valign="top">
            <div id="calendario_g"></div>
            <div><strong>Leyenda:</strong> NR - No Reservado, PR - Prereservado</div>
        </td>
        <td valign="top">
            <div style="border: solid 1px;padding: 3px;">
                <table>
                    <thead class="ui-widget-header">
                        <tr><td>
                            <h3 style="text-align:center;">Desayuno</h3>
                        </td></tr>

                    </thead>
                    <?php
                    $total = 0;
                    $precio_hoy_d = 0;
                    if(count($menu_hoy_d) < 1){
                        echo '<tr><td><h3>No hay platos definidos para hoy.</h3></td></tr>';
                    } else {
                        for ($i=0; $i < count($menu_hoy_d); $i++) {
                            $plato = $menu_hoy_d[$i];
                            $precio_hoy_d += $plato['precio'];
                            echo '<tr><td style="border-bottom: solid 1px #0078AE;"><label>'.$plato['nombreplato'].' '.$plato['cantidad'].' '.$plato['simbolo'].'</label><label style="float: inline-end;">$'.number_format($plato['precio'], 2) .'</label></td></tr>';
                        }
                    }
                    $total+=$precio_hoy_d;
                    ?>
                </table>
                <div id="precio_hoy" style="font-weight: bold;font-size: 1.2em;">Precio: <div style="padding:0 3px; float: right;">$ <?php echo number_format($precio_hoy_d, 2); ?></div></div>
            </div>
            <br/>
            <div style="border: solid 1px;padding: 3px;">                
                <table>
                    <thead class="ui-widget-header">
                        <tr><td>
                            <h3 style="text-align:center;">Almuerzo</h3>
                        </td></tr>
                    </thead>
                    <?php
                    
                    $precio_hoy_a = 0;
                    if(count($menu_hoy_a) < 1){
                        echo '<tr><td><h3>No hay platos definidos para hoy.</h3></td></tr>';
                    } else {
                        for ($i=0; $i < count($menu_hoy_a); $i++) {
                            $plato = $menu_hoy_a[$i];
                            $precio_hoy_a += $plato['precio'];
                            echo '<tr><td style="border-bottom: solid 1px #0078AE;"><label>'.$plato['nombreplato'].' '.$plato['cantidad'].' '.$plato['simbolo'].'</label><label style="float: inline-end;">$'.number_format($plato['precio'], 2) .'</label></td></tr>';
                        }
                    }
                    $total+=$precio_hoy_a;
                    ?>
                </table>
                <div id="precio_hoy" style="font-weight: bold;font-size: 1.2em;">Precio: <div style="padding:0 3px; float: right;">$ <?php echo number_format($precio_hoy_a, 2); ?></div></div>
            </div>
            <br/>
            <div style="border: solid 1px;padding: 3px;">
                
                <table>
                    <thead class="ui-widget-header">
                        <tr><td>
                            <h3 style="text-align:center;">Comida</h3>
                        </td></tr>
                    </thead>
                    <?php
                    
                    $precio_hoy_c = 0;
                    if(count($menu_hoy_c) < 1){
                        echo '<tr><td><h3>No hay platos definidos para hoy.</h3></td></tr>';
                    } else {

                        for ($i=0; $i < count($menu_hoy_c); $i++) {
                            $plato = $menu_hoy_c[$i];
                            $precio_hoy_c += $plato['precio'];
                            echo '<tr><td style="border-bottom: solid 1px #0078AE;"><label>'.$plato['nombreplato'].' '.$plato['cantidad'].' '.$plato['simbolo'].'</label><label style="float: inline-end;">$'.number_format($plato['precio'], 2) .'</label></td></tr>';
                        }
                    }
                    $total += $precio_hoy_c;
                    ?>
                </table>
                <div id="precio_hoy" style="font-weight: bold;font-size: 1.2em;">Precio: <div style="padding:0 3px; float: right;">$ <?php echo number_format($precio_hoy_c, 2); ?></div></div>
            </div>
        </td>
    </tr>
    <thead class="ui-widget-header">
        <tr>
            <td>
                <strong>
                    <div id="precio_mes">Total en el mes:<div style="float: right;" id="saldo_mes">&nbsp;</div></div>
                </strong>
            </td>
            <td><strong><div style="font-weight: bold;font-size: 1.2em;">Total: <div style="padding:0 3px; float: right;">$ <?php echo number_format($total, 2); ?></div></div></strong></td>
        </tr>
    </thead>
</table>


<div id="dialogo_lote"></div>
<script type="text/javascript">

    $( "#fecha_ini" ).datepicker({changeMonth: true,changeYear: true});
    $( "#fecha_ini" ).datepicker("option", "dateFormat", "dd/mm/yy");
    $( "#fecha_ini" ).val('<?php echo date('d/m/Y',$fecha_admisible) ?>');
    $( "#fecha_ini" ).datepicker("option", "minDate", '<?php echo date('d/m/Y',$fecha_admisible) ?>');

    $( "#fecha_fin" ).datepicker({changeMonth: true,changeYear: true});
    $( "#fecha_fin" ).datepicker("option", "dateFormat", "dd/mm/yy");
    $( "#fecha_fin" ).val('<?php echo date('d/m/Y',$fecha_admisible) ?>');
    $( "#fecha_fin" ).datepicker("option", "minDate", '<?php echo date('d/m/Y',$fecha_admisible) ?>');

    $( "#fecha_ini" ).change(function(){
        $( "#fecha_fin" ).datepicker("option", "minDate", $( "#fecha_ini" ).val());
    });

    $( "#fecha_fin" ).change(function(){
        $( "#fecha_ini" ).datepicker("option", "maxDate", $( "#fecha_fin" ).val());
    });


    $( "#b_acciones_realizar" ).button({
     text: true,
     icons: {
         primary: "ui-icon-check"
     }
 }).click(function(){
     realiza_acciones_multiples();
 });


 function actualiza_calendario(iniciosemana,idtrabajador,sede){

    myURL = '<?php echo $ruta_url; ?>index.php/principal/<?php echo $calendario; ?>/'+iniciosemana+'/'+idtrabajador+'/'+sede+'/';
    
    var contenido_semana = $.ajax({
        async: false,
        url: myURL
    }).responseText;

    $( "#calendario_g" ).html(contenido_semana);

    $( "#b_ant_mes" ).button({
        text: true,
        icons: {
            primary: "ui-icon-seek-prev"
        }        
    });
    
    $( "#b_prox_mes" ).button({
        text: true,
        icons: {
            secondary: "ui-icon-seek-next"
        }        
    });
    
    $('.ck_a_m').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_flat-blue'
  });

    myURL = '<?php echo $ruta_url; ?>index.php/<?php echo $tickets_alm; ?>/gastos_mes/'+iniciosemana+'/'+idtrabajador+'/'+sede+'/';
    
    var saldo_mes_d;
    var saldo_mes_a;
    var saldo_mes_c;

    $.ajax({
      url: myURL,
      dataType: 'json',
      async: false,
      success: function(data){
        saldo_mes_d = data['saldo_r_1'];
        saldo_mes_a = data['saldo_r_2'];
        saldo_mes_c = data['saldo_r_3'];
    }
});


    $( "#saldo_mes" ).html('| Desayunos = $ '+ saldo_mes_d + '| Almuerzos = $ '+ saldo_mes_a + ' | Comidas = $ '+ saldo_mes_c +' |');


}

function realiza_acciones_multiples(){
    //obtener lista de usuarios
    
    var texto = '<h1>Seguro desea realizar las siguientes acciones:</h1><h2>';
    if($("#mult_accion").val() == 0){
        texto += 'Realizar reservación';
    } else {
        texto += 'Quitar reservación';

    }

    switch ($("#mult_actividad").val()) {
        case '0':
        texto += ' de desayuno';
        break;

        case '1':
        texto += ' de almuerzo';
        break;

        case '2':
        texto += ' de comida';
        break;

        case '3':
        texto += ' de desayuno-almuerzo-comida';
        break;

        default:
        break;
    }    

    texto += ' desde el ' + $("#fecha_ini").val() + ' al ' + $("#fecha_fin").val();
    if($("#mult_finde").val() == 1){
        texto += '</h2><h2>Se incluirán los fines de semanas.</h2>';
    } else {
        texto += '</h2><h2>No se incluirán los fines de semanas.</h2>';
    }

    texto += '<div id="realizando_acciones" style="text-align: center;margin-top:50px;"></div>';

    $( "#dialogo_lote" ).html(texto);

    $( "#dialogo_lote" ).dialog( "open" );

}

$( "#dialogo_lote" ).attr('title','Acciones múltiples.');
$( "#dialogo_lote" ).dialog({
  width: 500,
  autoOpen: false,
  modal: true,
  buttons: {
     "Realizar": function() {

        $("#realizando_acciones").html('<img width="100" src="images/baile.gif"/><h3><div>Espere un momento...</div></h3>');

                                // Realizar acciones
                                var datos = 'id='+'<?php echo $idusuario; ?>'+'&accion='+$("#mult_accion").val()+'&actividad='+$("#mult_actividad").val();
                                datos += '&fecha_ini='+$("#fecha_ini").val()+'&fecha_fin='+$("#fecha_fin").val() +'&finde='+$("#mult_finde").val()+'&sede_idsede='+<?php echo $sede_idsede;?>;

                                $.ajax({
                                 type: "POST",
                                 url: "<?php echo $ruta_url; ?>index.php/admin_tickets_mult/<?php echo $acciones;?>/",
                                 data: datos,
                                 async: false,
                                 success: function(){
                                        //location.reload();
                                    }
                                });
                                
                                actualiza_calendario('<?php echo $inicio_mes; ?>','<?php echo $idusuario; ?>','<?php echo $sede_idsede;?>');

                                $( this ).dialog( "close" );

                                $( "#dialogo_lote" ).html('');
                                
                            },
                            "Cerrar": function() {
                                $( this ).dialog( "close" );
                                $( "#dialogo_lote" ).html('');
                            }
                        }
                    });


actualiza_calendario('<?php echo $inicio_mes; ?>','<?php echo $idusuario; ?>','<?php echo $sede_idsede;?>');

</script>
