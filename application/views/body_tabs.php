
<table width="100%">
    <tr>
        <td>&nbsp;</td>
        <td width="1020">
            <table width="100%">
                <tr>
                    <td width="200" valign="top" >
                        <div id="accordion">
                            <h3 ><a href="#" id="textLoguearse">Loguearse</a></h3>
                            <div>
                                <div class="botones_tabs">
                                    <?php if($ip_permitido){ ?>
                                     <!-- <p><button id="b_inicio" class="ui-button_2 ui-widget ui-state-default ui-corner-all ui-button-text-only">Inicio</button></p>-->
                                     <?php if($autenticado){ ?>
                                        <p><button id="b_salir" class="ui-button_2 ui-widget ui-state-default ui-corner-all ui-button-text-only">Cerrar sesión</button></p>
                                    <?php } else { ?>
                                        Usuario:<br />
                                        <input id="acceder_usuario" class="form_autenticar" type="text" size="19"/><br />
                                        Contraseña:<br />
                                        <input id="acceder_contrasenna" class="form_autenticar" type="password" size="19" /><br /><br />
                                        <button id="b_Acceder" class="ui-button_2 ui-widget ui-state-default ui-corner-all ui-button-text-only">Acceder</button><br />
                                        <script type="text/javascript">

                                            $(".form_autenticar").keypress(function(e){
                                                if (e.which == 13){
                                                    autenticar();

                                                }
                                            }); 

                                        </script>                            

                                    <?php }  ?>
                                <?php } else { ?>
                                    <p>                            
                                        <div style="font-size: 1.2em;font-weight: bold;" >Su IP no está autorizado para acceder a este panel.</div>
                                    </p>
                                <?php }  ?>
                            </div>
                        </div>
                        <?php if($autenticado){?>                                                              
                            <?php if($rol == 0 || $rol ==2 || $rol ==3){ ?>                    
                                <h3><a href="#">Gestionar reservaciones</a></h3>
                                <div>
                                    <div class="botones_tabs">
                                     <p><button id="b_Tickets_d" class="ui-button_2 ui-widget ui-state-default ui-corner-all ui-button-text-only">Desayunos</button></p> 
                                     <!--<p><span style="font-size: 1.2em;font-weight: bold;">Almuerzos</span></p> -->                      
                                     <p><button id="b_Tickets" class="ui-button_2 ui-widget ui-state-default ui-corner-all ui-button-text-only">Almuerzos</button></p>                        
                                     <!--<p><span style="font-size: 1.2em;font-weight: bold;">Comidas</span></p>-->
                                     <p><button id="b_Tickets_c" class="ui-button_2 ui-widget ui-state-default ui-corner-all ui-button-text-only">Comidas</button></p>
                                     <!--<p><span style="font-size: 1.2em;font-weight: bold;">Múltiples</span></p>-->
                                     <p><button id="b_Tickets_m_t" class="ui-button_2 ui-widget ui-state-default ui-corner-all ui-button-text-only">Múltiples</button></p>

                                 </div>
                             </div>
                         <?php }  ?>
                         <?php if($rol == 5 || $rol ==2 || $rol ==3 || $rol ==4){ ?>
                             <h3><a href="#">Dirigidos y Menús</a></h3>
                             <div>

                                <div class="botones_tabs">               
                                    <p><span style="font-size: 1.2em;font-weight: bold;">Dirigidos</span></p>
                                    <p><span style="font-size: 1.2em;">Trabajadores</span></p>
                                    <p><button id="b_D_A_Tickets" class="ui-button_2 ui-widget ui-state-default ui-corner-all ui-button-text-only">Desayunos</button></p>
                                    <p><button id="b_D_Tickets" class="ui-button_2 ui-widget ui-state-default ui-corner-all ui-button-text-only">Almuerzos</button></p>
                                    <p><button id="b_D_C_Tickets" class="ui-button_2 ui-widget ui-state-default ui-corner-all ui-button-text-only">Comidas</button></p>
                                    
                                        <p><span style="font-size: 1.2em;font-weight: bold;">Menús</span></p>
                                        <p><button id="b_Menus_d" class="ui-button_2 ui-widget ui-state-default ui-corner-all ui-button-text-only">Desayunos</button></p>
                                        <p><button id="b_Menus" class="ui-button_2 ui-widget ui-state-default ui-corner-all ui-button-text-only">Almuerzos</button></p>
                                        <p><button id="b_Menus_c" class="ui-button_2 ui-widget ui-state-default ui-corner-all ui-button-text-only">Comidas</button></p>
                                    </div>
                                </div>
                            <?php 
                        }  
                        ?>

                        <?php if($rol >= 0){ ?>
                            <h3><a href="#">Listados</a></h3>
                            <div>
                                <div class="botones_tabs">

                                    <p><span style="font-size: 1.2em;font-weight: bold;">Trabajadores</span></p>
                                    <p><button id="b_List_Desayuno" class="ui-button_2 ui-widget ui-state-default ui-corner-all ui-button-text-only">Desayunos diarios</button></p>
                                    <p><button id="b_List_Almuerzo" class="ui-button_2 ui-widget ui-state-default ui-corner-all ui-button-text-only">Almuerzos diarios</button></p>
                                    <p><button id="b_List_Comidas" class="ui-button_2 ui-widget ui-state-default ui-corner-all ui-button-text-only">Comidas diarias</button></p>
                                    <?php if($rol != 5 && $rol != 1 && $rol != 4){ ?>
                                        <p><button id="b_List_Chequear" class="ui-button_2 ui-widget ui-state-default ui-corner-all ui-button-text-only">Chequear</button></p>
                                    <?php } ?>

                                    <?php if($rol >= 1){ ?>
                                        <p><button id="b_List_M_Almuerzo" class="ui-button_2 ui-widget ui-state-default ui-corner-all ui-button-text-only">Mensual</button></p>
                                        <p><button id="b_List_R_F_Almuerzo" class="ui-button_2 ui-widget ui-state-default ui-corner-all ui-button-text-only">Rango de Fechas</button></p>

                                    </div>
                                </div>
                                <h3><a href="#">Reportes</a></h3>
                                <div>
                                    <div class="botones_tabs">

                                        <p><span style="font-size: 1.2em;font-weight: bold;">Reportes de Ventas</span></p>
                                        <p><button id="b_Rep_Ventas" class="ui-button_2 ui-widget ui-state-default ui-corner-all ui-button-text-only">Diario</button></p>
                                        <p><button id="b_Rep_S_Ventas" class="ui-button_2 ui-widget ui-state-default ui-corner-all ui-button-text-only">Semanal</button></p>
                                        <p><span style="font-size: 1.2em;font-weight: bold;">Otros reportes</span></p>
                                        <p><button id="b_Balance" class="ui-button_2 ui-widget ui-state-default ui-corner-all ui-button-text-only">Balance</button></p>
                                    </div>
                                </div>
                            <?php }
                        }  
                        ?>

                        <?php if($rol ==2 || $rol ==3){ ?>
                            <h3><a href="#">Administración</a></h3>
                            <div>
                                <?php// if($rol == 2){ ?>
                                    <div class="botones_tabs">
                                        <p><button id="b_Trabajadores" class="ui-button_2 ui-widget ui-state-default ui-corner-all ui-button-text-only">Trabajadores</button></p>
                                        <p><button id="b_Configuracion" class="ui-button_2 ui-widget ui-state-default ui-corner-all ui-button-text-only">Configuración</button></p>
                                        <?php// }  ?>

                                        <?php if($rol == 3){ ?>
                                            <p><button id="b_Logs" class="ui-button_2 ui-widget ui-state-default ui-corner-all ui-button-text-only">Logs</button></p>
                                            <p><button id="b_Usuarios" class="ui-button_2 ui-widget ui-state-default ui-corner-all ui-button-text-only">Usuarios</button></p>
                                            <p><button id="b_Sedes" class="ui-button_2 ui-widget ui-state-default ui-corner-all ui-button-text-only">Sedes</button></p>
                                            <p><button id="b_Mantenmiento" class="ui-button_2 ui-widget ui-state-default ui-corner-all ui-button-text-only">Mantenimiento</button></p>
                                        <?php }  ?>
                                    </div>
                                </div>

                            <?php }  ?>
                        <?php }  ?>
                    </div>
                </td>
                <td valign="top">
                    <div id="contenedor">
                    </div>
                </td>
            </tr>
        </table>
    </td>
    <td>&nbsp;</td>
</tr>
</table>    
<div id="dialogo_agregar"></div>
<div id="dialogo_editar"></div>
<div id="dialogo_contrasenna"></div>
<div id="dialogo_ficha"></div>
<div id="dialogo_imprimir"></div>
<div id="dialogo_lote"></div>

<?php 
if($autenticado){

    ?>
    <script type="text/javascript">
        var a=document.getElementById("textLoguearse");
        a.innerHTML="Autenticado";
    </script>
    <?php 
}
?>
