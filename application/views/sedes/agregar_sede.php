<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

?>
    <table with="100%">               
	<tr>
            <td>Nombre:</td>
            <td><input id="nombre" type="text" size="40"/></td>
	</tr>
        <tr>
            <td colspan="3">
                <input type="checkbox" checked id="use_ldap" />&nbsp;Usar un servidor LDAP para autenticar a los trabajadores.
            <script type='text/javascript'>
                var use_ldap = 1;
            </script>
            </td>
        </tr>
                <script type='text/javascript'>
                $('#use_ldap').iCheck({
                      checkboxClass: 'icheckbox_square-blue',
                      radioClass: 'iradio_flat-blue'
                });

                $('#use_ldap').on('ifChanged', function(event){
                  if(this.checked){
                        use_ldap = 1;
                    } else {
                        use_ldap = 0;
                  }
                    actualiza_cb_ldap();
                });
                </script>
        
        <tr title="Nombre o IP del servidor LDAP">
            <td>Servidor LDAP:</td>
                <td colspan="2"><input id="servidor" type="text" size="40" /></td>
        </tr>
        <tr title="Ejemplo: @domino.organizacion.org">
            <td>Sufijo:</td>
                <td colspan="2"><input id="sufijo" type="text" size="40" /></td>
        </tr>
        <tr title="Ejemplo: DC=domino,DC=organizacion,DC=org">
            <td>DN Base:</td>
                <td colspan="2"><input id="base_dn" type="text" size="40" /></td>
        </tr>
        <tr title="Uusuario con privilegios para acceder a las listas de usuarios en el LDAP">
            <td>Usuario:</td>
                <td colspan="2"><input id="admin_user" type="text" size="40" /></td>
        </tr>
        <tr title="Contraseña del usuario">
            <td>Contraseña:</td>
                <td colspan="2"><input id="admin_pass" type="password" size="40" /></td>
        </tr>
        <tr title="Lista seperadas por comas de los grupos que son trabajadores ejemplo: gdocentes,gdirectivos,gtecnicos">
            <td>Grupos de trabajadores:</td>
            <td colspan="2"><input id="grupos" type="text" size="40" /></td>
        </tr>
        <tr title="Lista seperadas por comas de los grupos que son estudiantes ejemplo: gestudiantes,gestint">
            <td>Grupos de trabajadores:</td>
            <td colspan="2"><input id="grupos_e" type="text" size="40" /></td>
        </tr>
    </table>
<script type="text/javascript">

    function actualiza_cb_ldap(){
        if (use_ldap != 1){
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
<div id="muestra_errores"></div>
<script type="text/javascript">
//    activa_selectores_fecha();
    //alert('prueba');
</script>