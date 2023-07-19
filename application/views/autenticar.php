<table width="100%">
    <tr>
        <td>&nbsp;</td>
        <td width="1020">
            <table width="100%" class="menus ui-widget ui-widget-content" cellpadding="5">
                <thead class="ui-widget-header">
                    <tr>
                        <td style="width: 75%;">
                            <div class="titulo_dia"><strong>Acceder:</strong></div>
                        </td>
                        <!--<td style="width: 25%;">
                            <div class="titulo_dia"><strong>Menú de hoy&nbsp;</strong><?php echo(date('d/m/Y', time())); ?></div>
                        </td>-->
                    </tr>
                </thead>

                 <tr>
                    <td valign="top">
                        <br>
                        <div align="center"><img width="150" id="imgUser" src="images/reservation.png" /></div>
                        <br>
                        <div align="center" style="font-size: 1.2em;">
                            <table width="40%">
                                <tr align="center">
                                    <td>
                                        <p>Usuario:</p>
                                    </td>
                                    <td><input id="usuario" class="form_autenticar" type="text" /></label></td>
                                </tr>
                                <tr align="center">
                                    <td>
                                        <p>Contraseña:</p>
                                    </td>
                                    <td><input id="contrasenna" class="form_autenticar" type="password" /></label></td>
                                </tr>
                            </table>
                        </div>
                        <div align="center" style="font-size: 1.2em;">
                            <?php if ($ip_permitido == true && count($listasedes) > 0) {?>
                            <p>Comedor a reservar:
                                <select id="idsede" required>
                                    <option selected value="" >Seleccionar...</option>
                                    <?php
                                    foreach ($listasedes as $sede) {
                                        echo('<option '.$seleccionado.' value="'.$sede['idsede'].'">'.$sede['nombre'].'</option>');
                                    }
                                    ?>
                                </select>
                            </p>

                        </div>
                        <div align="center">
                            <br>
                           
                           <label ><button class="form_autenticar" id="b_acceder" >Acceder</button>                           
                            <br>

                                <!--<strong><p style="padding-top: 25px; color:#008000 ; font-size: 15px;"> Restablecida la reserva en línea.</p></strong>
                                <strong><p style="padding-top: 10px; color:#9c0731 ;"> ¡ Nota importante ! Los trabajadores que se encuentren de Vacaciones no deben utilizar el sistema de reservación. Gracias Dirección de Alimentación</p></strong>-->
                             </label></td>


                    </div>
                    <?php } else {
                            if (count($listasedes) == 0) {
                                ?>
                    <div style="font-size: 1.2em;text-align: center;">
                        <p>
                        <div style="font-size: 1.5em;margin-top: 50px;">No se ha configurado ninguna sede para acceder a
                            la plataforma.</div>
                        </p>
                        </br>
                        <p><button id="p_admin">Panel de Administración</button></p>
                    </div>

                    <?php 

                        } else {
                            ?>
                    <p>
                    <div style="font-size: 1.5em;margin-top: 50px;">Su IP no está autorizado para realizar
                        reservaciones.</div>
                    </p>
                    <?php 

                        }
                    }
                    ?>
        </td>
        <!--<td valign="top">
            <div style="border: solid 1px;padding: 3px;">
                <table>
                    <thead class="ui-widget-header">
                        <tr>
                            <td>
                                <h3 style="text-align:center;">Desayuno</h3>
                            </td>
                        </tr>

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
                <div id="precio_hoy" style="font-weight: bold;font-size: 1.2em;">Precio: <div
                        style="padding:0 3px; float: right;">$ <?php echo number_format($precio_hoy_d, 2); ?></div>
                </div>
            </div>
            <br />
            <div style="border: solid 1px;padding: 3px;">

                <table>
                    <thead class="ui-widget-header">
                        <tr>
                            <td>
                                <h3 style="text-align:center;">Almuerzo</h3>
                            </td>
                        </tr>
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
                <div id="precio_hoy" style="font-weight: bold;font-size: 1.2em;">Precio: <div
                        style="padding:0 3px; float: right;">$ <?php echo number_format($precio_hoy_a, 2); ?></div>
                </div>
            </div>
            <br />
            <div style="border: solid 1px;padding: 3px;">
                <table>
                    <thead class="ui-widget-header">
                        <tr>
                            <td>
                                <h3 style="text-align:center;">Comida</h3>
                            </td>
                        </tr>
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
                <div id="precio_hoy" style="font-weight: bold;font-size: 1.2em;">Precio: <div
                        style="padding:0 3px; float: right;">$ <?php echo number_format($precio_hoy_c, 2); ?></div>
                </div>
            </div>
        </td>-->
    </tr>
    <thead class="ui-widget-header">
        <tr>
            <td><strong>
                    <div id="botones"><button id="p_admin">Panel de Administración</button></div>
                </strong></td>
            <!--<td><strong>
                    <div style="font-weight: bold;font-size: 1.2em;">Total: <div style="padding:0 3px; float: right;">$
                            <?php echo number_format($total, 2); ?></div>
                    </div>
                </strong></td>-->
        </tr>
    </thead>
</table>
</td>
<td>&nbsp;</td>
</tr>
</table>
<script type="text/javascript">
var tipo_usuario = 'T';

$('.tipo_usuario').iCheck({
    checkboxClass: '<?php echo ($templates[$template_prof]['iCheckStyleCB']); ?>',
    radioClass: '<?php echo ($templates[$template_prof]['iCheckStyleRB']); ?>'
});

$('#tipo_usuario_T').on('ifChanged', function(event) {

    if (this.checked) {
        tipo_usuario = 'T';
    }
});

$("#b_acceder").button({
    text: true,
    icons: {
        primary: "ui-icon-check"
    }
}).click(function() {
    autenticar();
});

$("#p_admin").button({
    text: true,
    icons: {
        primary: "ui-icon-gear"
    }
}).click(function() {   
    window.open("index.php/principal/admin/");
});


/* ENVIO DE CORREO 
$("#p_sendEmail").button({
    text: true,
    icons: {
        primary: "ui-icon-gear"
    }
}).click(function() {
    myURL = '<?php echo $ruta_url;?>index.php/principal/enviar/';
    $.ajax({
                 type: 'POST',
                 url: myURL,                 
                 success: function(){
                 }
                 });  
});*/

$(".form_autenticar").keypress(function(e) {
    if (e.which == 13) {
        autenticar();
    }
});



function autenticar() {
    if ($("#idsede").val() != "" && $("#usuario").val() != "" && $("#contrasenna").val() != "") {
        $(".form_autenticar").attr("disabled", true);

        if (tipo_usuario == 'T') {
            myURL = "index.php/seguridad/autenticar_trab/";

        }
        $.post(myURL, {
                usuario: $("#usuario").val(),
                contrasenna: $("#contrasenna").val(),
                idsede: $("#idsede").val()
            },
            function(data) {
                $("#d_autenticar").dialog("close");
                var accion = {
                    accion: function() {
                        location.reload();
                    }
                };

                muestra_mensaje("<h2>" + data + "</h2>", 400, accion);

            });
    } else {
        var accion = {
            accion: function() {
                location.reload();
            }
        };
        muestra_mensaje("<h2>Debe completar los datos del formulario.</h2>", 400, accion);
        //location.reload();


    }

}

$(".form_autenticar").attr("disabled", false);
</script>