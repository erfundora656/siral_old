    <table with="100%">               
        <tr>
            <td colspan="3">
                <?php if($use_ldap) {?>
                <input type="checkbox" checked id="use_ldap" />&nbsp;Usar un servidor LDAP para autenticar a los trabajadores.
            <script type='text/javascript'>
                var use_ldap = true;
            </script>
                <?php } else {?>
                <input type="checkbox" id="use_ldap" />&nbsp;Usar un servidor LDAP para autenticar a los trabajadores.
            <script type='text/javascript'>
                var use_ldap = false;
            </script>
                <?php }?>
            </td>
        </tr>
                <script type='text/javascript'>
                $('#use_ldap').iCheck({
                      checkboxClass: 'icheckbox_square-blue',
                      radioClass: 'iradio_flat-blue'
                });

                $('#use_ldap').on('ifChanged', function(event){
                  if(this.checked){
                        use_ldap = true;
                    } else {
                        use_ldap = false;
                  }
                    actualiza_cb_ldap();
                });
                </script>
        
        <tr title="Nombre o IP del servidor LDAP">
            <td>Servidor LDAP:</td>
                <td colspan="2"><input id="servidor" type="text" size="40" value="<?php echo($servidor) ?>" /></td>
        </tr>
        <tr title="Ejemplo: @domino.organizacion.org">
            <td>Sufijo:</td>
                <td colspan="2"><input id="sufijo" type="text" size="40" value="<?php echo($sufijo) ?>" /></td>
        </tr>
        <tr title="Ejemplo: DC=domino,DC=organizacion,DC=org">
            <td>DN Base:</td>
                <td colspan="2"><input id="base_dn" type="text" size="40" value="<?php echo($base_dn) ?>" /></td>
        </tr>
        <tr title="Uusuario con privilegios para acceder a las listas de usuarios en el LDAP">
            <td>Usuario:</td>
                <td colspan="2"><input id="admin_user" type="text" size="40" value="<?php echo($admin_user) ?>" /></td>
        </tr>
        <tr title="Contraseña del usuario">
            <td>Contraseña:</td>
                <td colspan="2"><input id="admin_pass" type="password" size="40" value="<?php echo($admin_pass) ?>" /></td>
        </tr>
        <tr title="Lista seperadas por comas de los grupos que son trabajadores ejemplo: gdocentes,gdirectivos,gtecnicos">
            <td>Grupos de trabajadores:</td>
            <td colspan="2"><input id="grupos" type="text" size="40" value="<?php echo(implode(',', $grupos)) ?>" /></td>
        </tr>
        <tr title="Lista seperadas por comas de los grupos que son estudiantes ejemplo: gestudiantes,gestint">
            <td>Grupos de trabajadores:</td>
            <td colspan="2"><input id="grupos_e" type="text" size="40" value="<?php echo(implode(',', $grupos_e)) ?>" /></td>
        </tr>
    </table>
<button id="b_actualizar_conf_ldap">Actualizar</button>
<script type="text/javascript">

// La función es la encargada de mandar los datos validados a la controladora.

    $("#b_actualizar_conf_ldap").button({
                            text: true,
                            icons: {
                                    primary: "ui-icon-check"
                            }
                        }).click(function(){
        
                            if (use_ldap){
                                var ldap = 'checked';
                            } else {
                                var ldap = 'unchecked';
                            }

                            var datos = "use_ldap=" + ldap + "&servidor=" + $("#servidor").val() + "&sufijo=" + $("#sufijo").val() + "&base_dn="+ $("#base_dn").val() + "&admin_user="+ $("#admin_user").val() + "&admin_pass="+ $("#admin_pass").val() + "&grupos=" + $("#grupos").val() + "&grupos_e=" + $("#grupos_e").val();

                            $.ajax({
                               type: "POST",
                               url: "<?php echo $ruta_url;?>index.php/configuracion/actualizar_ldap/",
                               data: datos,
                               async: false,
                               success: function(){
                                   alert("Datos actualizados");
                               }
                            });

                        });
    
    function actualiza_cb_ldap(){
        if (!use_ldap){
            $("#servidor").attr("disabled", true);
            $("#sufijo").attr("disabled", true);
            $("#base_dn").attr("disabled", true);
            $("#admin_user").attr("disabled", true);
            $("#admin_pass").attr("disabled", true);
            $("#grupos").attr("disabled", true);
            $("#grupos_e").attr("disabled", true);
        } else {
            $("#servidor").attr("disabled", false);
            $("#sufijo").attr("disabled", false);
            $("#base_dn").attr("disabled", false);
            $("#admin_user").attr("disabled", false);
            $("#admin_pass").attr("disabled", false);
            $("#grupos").attr("disabled", false);
            $("#grupos_e").attr("disabled", false);
        }
        
    }
    actualiza_cb_ldap();

</script>