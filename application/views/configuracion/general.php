<table with="100%">
    <tr>
        <td colspan="3">
            <?php if($menu_platos) {?>
                <input type="checkbox" checked id="menu_platos" />
                <script type='text/javascript'>
                    var menu_platos = true;
                </script>
            <?php } else {?>
                <input type="checkbox" id="menu_platos" />
                <script type='text/javascript'>
                    var menu_platos = false;
                </script>
            <?php }?>
            &nbsp;Seleccionar Menú por platos.
        </td>
    </tr>
    <script type='text/javascript'>
        $('#menu_platos').iCheck({
            checkboxClass: '<?php echo ($iCheckStyleCB);?>',
            radioClass: '<?php echo ($iCheckStyleRB);?>'
        });

        $('#menu_platos').on('ifChanged', function(event){
            if(this.checked){
                menu_platos = true;
            } else {
                menu_platos = false;
            }
        });
    </script>

    <tr title="Hora en que se cierran las reservaciones para próximo día.">
        <td>Hora de cierre:</td>
        <td colspan="2">
            <input id="hora_cierre" readonly type="text" size="2" value="<?php echo($hora_cierre) ?>" />:<input id="minutos_cierre" readonly type="text" size="2" value="<?php echo($minutos_cierre) ?>" />
        </td>
    </tr>
    <tr>
        <td colspan="3">
            <?php if($cierra_viernes) {?>
                <input type="checkbox" checked id="cierra_viernes" />
                <script type='text/javascript'>
                    var cierra_viernes = true;
                </script>
            <?php } else {?>
                <input type="checkbox" id="cierra_viernes" />
                <script type='text/javascript'>
                    var cierra_viernes = false;
                </script>
            <?php }?>
            &nbsp;Cerrar las reservaciones del fin de semana el viernes.
        </td>
    </tr>
    <tr title="Primer día laboral de la semana, día hasta el que se cerrará los viernes .">
        <td>Primer día laboral de la semana:</td>
        <td colspan="2">
            <select id="incio_semana">
                <?php
                $dias_semana = array('Domingo','Lunes','Martes','Miercoles','Jueves','Viernes','Sabado');
                for($i=0; $i < count($dias_semana);$i++){
                    if($incio_semana == $i){
                        $seleccionado = 'selected';
                    } else {
                        $seleccionado = '';
                    }
                    echo('<option '.$seleccionado.' value="'.$i.'">'.$dias_semana[$i].'</option>');
                }
                ?>
            </select>
        </td>
    </tr>
    <script type='text/javascript'>
        $('#cierra_viernes').iCheck({
            checkboxClass: '<?php echo ($iCheckStyleCB);?>',
            radioClass: '<?php echo ($iCheckStyleRB);?>'
        });

        $('#cierra_viernes').on('ifChanged', function(event){
            if(this.checked){
                cierra_viernes = true;
            } else {
                cierra_viernes = false;
            }
        });
    </script>
    
    <tr title="Plantilla para los trabajadores.">
        <td>Plantilla para los trabajadores:</td>
        <td colspan="2">
            <select id="template_prof">
                <?php
                for($i=0; $i < count($templates); $i++) {
                    if($template_prof == $i){
                        $seleccionado = 'selected';
                    } else {
                        $seleccionado = '';
                    }
                    echo('<option '.$seleccionado.' value="'.$i.'">'.$templates[$i]['name'].'</option>');
                }
                ?>
            </select>
        </td>
    </tr>
    
    <tr title="Sede por defecto.">
        <td>Sede por defecto:</td>
        <td colspan="2">
            <select id="sede_defecto">
                <?php
                if($listasedes!=""){
                    foreach($listasedes as $sede) {
                        if($sede['idsede'] == $sede_defecto){
                            $seleccionado = 'selected';
                        } else {
                            $seleccionado = '';
                        }
                        echo('<option '.$seleccionado.' value="'.$sede['idsede'].'">'.$sede['nombre'].'</option>');
                    }
                }else{
                    echo("<option value='".$id_sede."'>".$sede_pertenece."</option>");  
                }
                
                ?>
            </select>
        </td>
    </tr>
    <tr>
        <td><strong>Formas de pago:</strong></td>
    </tr>
    <tr title="Forma de pago de Trabajadores.">
        <td>Trabajadores:</td>
        <td colspan="2">
            <select id="forma_pago_trab">
                <?php
                if($forma_pago_trab == 1){
                    ?>
                    <option value="0">Ticket</option>
                    <option selected value="1">Descuento</option>
                    <?php
                } else {
                    ?>
                    <option selected value="0">Ticket</option>
                    <option value="1">Descuento</option>
                    <?php
                }
                ?>
            </select>
        </td>
    </tr>
    
    <tr title="Redes o IPs desde los que se puede reservar:">
        <td>Redes o IPs desde los que se puede reservar:</td>
        <td colspan="2">
            <input id="acceso_front" type="text" size="40" value="<?php echo($acceso_front);?>" />
        </td>
    </tr>
    <tr title="Redes o IPs desde los que se puede administrar:">
        <td>Redes o IPs desde los que se puede administrar:</td>
        <td colspan="2">
            <input id="acceso_admin" type="text" size="40" value="<?php echo($acceso_admin);?>" />
        </td>
    </tr>
</table>
<button id="b_actualizar_conf">Actualizar</button>
<script type="text/javascript">

    $( "#hora_cierre" ).spinner({min:0,max:24});
    $( "#minutos_cierre" ).spinner({min:0,max:59});
    
    // La función es la encargada de mandar los datos validados a la controladora.

    $("#b_actualizar_conf").button({
        text: true,
        icons: {
            primary: "ui-icon-check"
        }
    }).click(function(){

        if (menu_platos){
            var platos = 'checked';
        } else {
            var platos = 'unchecked';
        }

        if (cierra_viernes){
            var viernes = 'checked';
        } else {
            var viernes = 'unchecked';
        }


        var datos = "menu_platos=" + platos + "&cierra_viernes=" + viernes + "&incio_semana=" + $("#incio_semana").val();
        datos += "&hora_cierre="+$("#hora_cierre").val() + "&minutos_cierre="+ $("#minutos_cierre").val();
        datos += "&template_prof="+ $("#template_prof").val();
        datos += "&sede_defecto="+ $("#sede_defecto").val();
        datos += "&forma_pago_trab="+ $("#forma_pago_trab").val();
        datos += "&acceso_front="+ $("#acceso_front").val();
        datos += "&acceso_admin="+ $("#acceso_admin").val();

        $.ajax({
            type: "POST",
            url: "<?php echo $ruta_url;?>index.php/configuracion/actualizar_general/",
            data: datos,
            async: false,
            success: function(){
                alert("Datos actualizados");
            }
        });

    });

</script>