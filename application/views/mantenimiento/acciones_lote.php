<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<table width="100%" class="ui-widget ui-widget-content" cellpadding="5" cellspacing="0">
    <thead class="ui-widget-header">
        <tr>
            <td style="font-size: 1.2em;">Acciones sobre una sede.</td>
            <td style="font-size: 1.2em;">Sede:
                <select id="idsede_lote">
                    <?php foreach ($lista_sedes as $sede) {
                        ?>
                    <option value="<?php echo($sede['idsede']); ?>" ><?php echo($sede['nombre']); ?></option>
                        <?php
                    } ?>
                </select>
            </td>
        </tr>
    </thead>
</table>

<table width="100%" class="ui-widget ui-widget-content" cellpadding="5" cellspacing="0">
        <tr class="fila_impar">
            <td style="font-size: 1.2em;">Borrar trabajadores y estudiantes que no tienen reservaciones y además no aparecen en servidor LDAP.</td>
            <td style="font-size: 1.2em;text-align: right;"><button id="b_accion_borrar_usuarios">Realizar</button></td>
        </tr>
        <tr class="fila_par">
            <td style="font-size: 1.2em;">Buscar usuarios posiblemente repetidos.</td>
            <td style="font-size: 1.2em;text-align: right;"><button id="b_accion_buscar_repetidos">Realizar</button></td>
        </tr>
</table>
<table width="100%" class="ui-widget ui-widget-content" cellpadding="5" cellspacing="0">
    <thead class="ui-widget-header">
        <tr>
            <td style="font-size: 1.2em;">Acciones sobre todo el sistema.</td>
        </tr>
    </thead>
</table>

<table width="100%" class="ui-widget ui-widget-content" cellpadding="5" cellspacing="0">
        <tr class="fila_impar">
            <td style="font-size: 1.2em;">Borrar reservaciones de un rango de fechas. Del <input id="fecha_ini" class="fechas_rep" size="10" type="text" readonly/> al <input id="fecha_fin" class="fechas_rep" size="10" type="text" readonly/></td>
            <td style="font-size: 1.2em;text-align: right;"><button id="b_accion_borrar_reservaciones">Realizar</button></td>
        </tr>
</table>

<div id="acciones_lote"></div>
<script type="text/javascript">

$( "#fecha_ini" ).datepicker({changeMonth: true,changeYear: true});
$( "#fecha_ini" ).datepicker("option", "dateFormat", "dd/mm/yy");

$( "#fecha_fin" ).datepicker({changeMonth: true,changeYear: true});
$( "#fecha_fin" ).datepicker("option", "dateFormat", "dd/mm/yy");


     $( "#b_accion_borrar_usuarios" ).button({
                                text: true,
                                icons: {
                                        primary: "ui-icon-check"
                                }
                            }).click(function(){
                                borra_usuarios();
                            });

     $( "#b_accion_borrar_reservaciones" ).button({
                                text: true,
                                icons: {
                                        primary: "ui-icon-check"
                                }
                            }).click(function(){
                                borrar_reservaciones_fechas($("#fecha_ini").val(),$("#fecha_fin").val());
                                //alert('Fucionalidad en desarrollo');
                            });

     $( "#b_accion_buscar_repetidos" ).button({
                                text: true,
                                icons: {
                                        primary: "ui-icon-check"
                                }
                            }).click(function(){
                                buscar_repetidos();
                               // alert('Fucionalidad en desarrollo');
                            });

function borra_usuarios(){
        $( "#acciones_lote" ).html('<div style="text-align: center;margin-top:50px;"><img width="100" src="../../../images/baile.gif"/><h3><div id="estado_proceso_lote">Espere un momento...</div></h3><div id="progressbar_lote"></div></h3><h3><div id="accion_lote">Realizando...</div></h3></div>');

        $( "#progressbar_lote" ).progressbar({
            value: 0
        });
        
        var Trabajadores_eliminados = 0;
        var Estudiantes_eliminados = 0;
        //Revisar Trabajadores
        
        $("#accion_lote").html('Borrando trabajadores...');

        myurl = '<?php echo $ruta_url; ?>index.php/mantenimiento/lista_trabajadores_sede/' + $("#idsede_lote").val() + '/';

        var usuarios;
        
          $.ajax({
              url: myurl,
              dataType: 'json',
              async: false,
              success: function(data){
                usuarios = data;
              }
            });
            
            cant_usuarios = usuarios.length;

            for (i = 0; i < usuarios.length;i++){
                
                    //Comprobar si existe el usuario en el ldap
                    
                    var existe_usuario;
                    myurl = '<?php echo $ruta_url; ?>index.php/mantenimiento/existe_trabajador_ldap/' + $("#idsede_lote").val() + '/' + usuarios[i].usuario + '/';

                      $.ajax({
                          url: myurl,
                          dataType: 'json',
                          async: false,
                          success: function(data){
                            existe_usuario = data;
                          }
                        });
                    
                    
                    if (existe_usuario == false){
                        
                        //Saber cuantos días con reservaciones tiene
                        var dias;
                        myurl = '<?php echo $ruta_url; ?>index.php/mantenimiento/dias_trabajador/' + usuarios[i].idtrabajador + '/';

                          $.ajax({
                              url: myurl,
                              dataType: 'json',
                              async: false,
                              success: function(data){
                                dias = data;
                              }
                            });


                            if(dias.almuerzos == 0 && dias.comidas == 0){
                                //Eliminar trabajador
                                var datos = "idtrabajador=" + usuarios[i].idtrabajador;
                                $.ajax({
                                   type: "POST",
                                   url: "<?= $ruta_url; ?>index.php/trabajadores/eliminar_trabajador/",
                                   async: false,
                                   data: datos,
                                   success: function(){
                                        Trabajadores_eliminados++;
                                   }
                                });
                            }
                        
                    }
                    
                    //Calcular promedio
                    var promedio = (i+1)*100/usuarios.length;
                    //promedio = promedio.toFixed(0);
                    $( "#progressbar_lote" ).progressbar({
                        value: promedio
                    });
                    $("#estado_proceso_lote").html('Procesando '+ (i+1) +' de ' + usuarios.length + ' Trabajadores');
                    
            }


        //Revisar Estudiantes

        $("#accion_lote").html('Borrando estudiantes...');

        myurl = '<?php echo $ruta_url; ?>index.php/mantenimiento/lista_estudiantes_sede/' + $("#idsede_lote").val() + '/';

        var usuarios;
        
          $.ajax({
              url: myurl,
              dataType: 'json',
              async: false,
              success: function(data){
                usuarios = data;
              }
            });
            
            cant_usuarios = usuarios.length;

            for (i = 0; i < usuarios.length;i++){
                
                    //Comprobar si existe el usuario en el ldap
                    
                    var existe_usuario;
                    myurl = '<?php echo $ruta_url; ?>index.php/mantenimiento/existe_estudiante_ldap/' + $("#idsede_lote").val() + '/' + usuarios[i].usuario + '/';

                      $.ajax({
                          url: myurl,
                          dataType: 'json',
                          async: false,
                          success: function(data){
                            existe_usuario = data;
                          }
                        });
                    
                    
                    if (existe_usuario == false){
                        
                        //Saber cuantos días con reservaciones tiene
                        var dias;
                        myurl = '<?php echo $ruta_url; ?>index.php/mantenimiento/dias_estudiante/' + usuarios[i].idestudiante + '/';

                          $.ajax({
                              url: myurl,
                              dataType: 'json',
                              async: false,
                              success: function(data){
                                dias = data;
                              }
                            });


                            if(dias.almuerzos == 0 && dias.comidas == 0){
                                //Eliminar trabajador
                                var datos = "idestudiante=" + usuarios[i].idestudiante;
                                $.ajax({
                                   type: "POST",
                                   url: "<?= $ruta_url; ?>index.php/estudiantes/eliminar_estudiante/",
                                   async: false,
                                   data: datos,
                                   success: function(){
                                        Estudiantes_eliminados++;
                                   }
                                });
                            }
                        
                    }
                    
                    //Calcular promedio
                    var promedio = (i+1)*100/usuarios.length;
                    //promedio = promedio.toFixed(0);
                    $( "#progressbar_lote" ).progressbar({
                        value: promedio
                    });
                    $("#estado_proceso_lote").html('Procesando '+ (i+1) +' de ' + usuarios.length + ' Estudiantes');
                    
            }


        //Mostrar estadísticas
        
        $( "#acciones_lote" ).html('<div style="text-align: center;margin-top:50px;"><h2>Resultados:</h3><div id="progressbar_lote"><h3>Trabajadores borrados:'+ Trabajadores_eliminados +'<br /><br />Estudiantes borrados:'+ Estudiantes_eliminados +'</h3>');
        
}

function borrar_reservaciones_fechas(fecha_ini,fecha_fin){
    //Validar datos
    var avance = 12.5;
    var pos_progress = 0;
    var correcto = true;
    var mensaje = '';
    if(fecha_ini==''){
        mensaje +='Debe estalecer una fecha inicial.';
        correcto = false;
    }

    if(fecha_fin==''){
        mensaje +='\nDebe estalecer una fecha final.';
        correcto = false;
    }

    if(correcto == false){
        alert('Existen errores:\n'+mensaje);
    }

    if (correcto == true){
        if(confirm('Seguro desea borrar las reservaciones y dirigidos \ndesde el ' + fecha_ini + ' al ' + fecha_fin)){
            
        } else {
            coarrecto == false;
        }       
    }
    if(correcto == true){
        
        $( "#acciones_lote" ).html('<div style="text-align: center;margin-top:50px;"><img width="100" src="../../../images/baile.gif"/><div id="progressbar_lote"></div></h3><h3><div id="accion_lote">Realizando...</div></h3></div>');

        $( "#progressbar_lote" ).progressbar({
            value: 0
        });
       
        //Trabajadores
        //Borrar Almuerzos
        $("#accion_lote").html('Borrando almuerzos de los Trabajadores...');
        
        $.ajax({
           url: "<?= $ruta_url; ?>index.php/mantenimiento/borrar_almuerzos_trabajadores/"+fecha_ini+"/"+fecha_fin+"/",
           async: false,
           success: function(){
           }
        });
        
        pos_progress += avance;
        $( "#progressbar_lote" ).progressbar({
            value: pos_progress
        });

        //Borrar Comidas
        $("#accion_lote").html('Borrando comidas de los Trabajadores...');
        
        $.ajax({
           url: "<?= $ruta_url; ?>index.php/mantenimiento/borrar_comidas_trabajadores/"+fecha_ini+"/"+fecha_fin+"/",
           async: false,
           success: function(){
           }
        });

        pos_progress += avance;
        $( "#progressbar_lote" ).progressbar({
            value: pos_progress
        });

        //Borrar Prereservaciones Almuerzos
        $("#accion_lote").html('Borrando prereservaciones de almuerzo de los Trabajadores...');
        
        $.ajax({
           url: "<?= $ruta_url; ?>index.php/mantenimiento/borrar_pre_almuerzos_trabajadores/"+fecha_ini+"/"+fecha_fin+"/",
           async: false,
           success: function(){
           }
        });

        pos_progress += avance;
        $( "#progressbar_lote" ).progressbar({
            value: pos_progress
        });

        //Borrar Prereservaciones Comidas
        $("#accion_lote").html('Borrando prereservaciones de comidas de los Trabajadores...');
        
        $.ajax({
           url: "<?= $ruta_url; ?>index.php/mantenimiento/borrar_pre_comidas_trabajadores/"+fecha_ini+"/"+fecha_fin+"/",
           async: false,
           success: function(){
           }
        });

        pos_progress += avance;
        $( "#progressbar_lote" ).progressbar({
            value: pos_progress
        });

        //Borrar dirigidos para almuerzo
        $("#accion_lote").html('Borrando dirigidos de almuerzo...');
        
        $.ajax({
           url: "<?= $ruta_url; ?>index.php/mantenimiento/borrar_dirigidos_almuerzos/"+fecha_ini+"/"+fecha_fin+"/",
           async: false,
           success: function(){
           }
        });

        pos_progress += avance;
        $( "#progressbar_lote" ).progressbar({
            value: pos_progress
        });

        //Borrar dirigidos para comida
        $("#accion_lote").html('Borrando dirigidos de comida...');
        
        $.ajax({
           url: "<?= $ruta_url; ?>index.php/mantenimiento/borrar_dirigidos_comidas/"+fecha_ini+"/"+fecha_fin+"/",
           async: false,
           success: function(){
           }
        });

        pos_progress += avance;
        $( "#progressbar_lote" ).progressbar({
            value: pos_progress
        });

        //Estudiantes
        //Borrar Almuerzos
        $("#accion_lote").html('Borrando almuerzos de los Estudiantes...');
        
        $.ajax({
           url: "<?= $ruta_url; ?>index.php/mantenimiento/borrar_almuerzos_estudiantes/"+fecha_ini+"/"+fecha_fin+"/",
           async: false,
           success: function(){
           }
        });

        pos_progress += avance;
        $( "#progressbar_lote" ).progressbar({
            value: pos_progress
        });

        //Borrar Comidas
        $("#accion_lote").html('Borrando comidas de los Estudiantes...');
        
        $.ajax({
           url: "<?= $ruta_url; ?>index.php/mantenimiento/borrar_comidas_estudiantes/"+fecha_ini+"/"+fecha_fin+"/",
           async: false,
           success: function(){
           }
        });
        
        pos_progress += avance;
        $( "#progressbar_lote" ).progressbar({
            value: pos_progress
        });

        $( "#progressbar_lote" ).progressbar({
            value: 100
        });
         $( "#acciones_lote" ).html('<div style="text-align: center;margin-top:50px;"><h2>Realizado</h2></div>');
    }

}

function buscar_repetidos(){

        var i,j,h,k;

        $( "#acciones_lote" ).html('<div style="text-align: center;margin-top:50px;"><img width="100" src="../../../images/baile.gif"/><h3><div id="estado_proceso_lote">Espere un momento...</div></h3><div id="progressbar_lote"></div></h3><h3><div id="accion_lote">Realizando...</div></h3></div>');

        $( "#progressbar_lote" ).progressbar({
            value: 0
        });
        var texto_salida = '';
        
        //Revisar Trabajadores
        
        $("#accion_lote").html('Buscando trabajadores...');

        myurl = '<?php echo $ruta_url; ?>index.php/mantenimiento/lista_trabajadores_sede/' + $("#idsede_lote").val() + '/';

        var usuarios;
        
          $.ajax({
              url: myurl,
              dataType: 'json',
              async: false,
              success: function(data){
                usuarios = data;
              }
            });
            
            cant_usuarios = usuarios.length;
            
            var nombres = '';
            var apellidos = '';
            var arrnombres = [];
            var arrapellidos = [];
            
            for (i = 0; i < usuarios.length;i++){
                nombres = $.trim(usuarios[i].nombres);
                apellidos = $.trim(usuarios[i].apellidos);
                
                arrnombres[i] = nombres.split(' '); 
                arrapellidos[i] = apellidos.split(' ');

                //Calcular promedio
                var promedio = (i+1)*100/usuarios.length;
                //promedio = promedio.toFixed(0);
                $( "#progressbar_lote" ).progressbar({
                    value: promedio
                });
                $("#estado_proceso_lote").html('Leyendo '+ (i+1) +' de ' + usuarios.length + ' Trabajadores');


            }

        $( "#progressbar_lote" ).progressbar({
            value: 0
        });
            
            texto_salida += '<h2>Posibles trabajadores repetidos.</h2>';

            for (i = 0; i < usuarios.length - 1;i++){
                nombres_repetidos = [];
                var rep_nom;
                var rep_app;
                for(j = i+1; j < usuarios.length;j++){
                    //Ver si concide alguno de los nombres
                    rep_nom = false;
                    rep_app = false;
                    
                    for(h = 0; h < arrnombres[i].length; h++){
                        for(k=0; k < arrnombres[j].length; k++){
                            if(arrnombres[i][h] == arrnombres[j][k]){
                                rep_nom = true;
                            }
                        }
                    }
                    
                    for(h = 0; h < arrapellidos[i].length; h++){
                        for(k=0; k < arrapellidos[j].length; k++){
                            if(arrapellidos[i][h] == arrapellidos[j][k]){
                                rep_app=true;
                            }
                        }
                    }
                    
                    if(rep_nom == true && rep_app == true){
                        nombres_repetidos.push(usuarios[j].usuario + ' - ' + usuarios[j].nombres + ' ' +usuarios[j].apellidos);
                    }
                }
                
                if (nombres_repetidos.length > 0){
                    texto_salida += "<p><strong>"+ usuarios[i].usuario + ' - ' + usuarios[i].nombres + ' ' +usuarios[i].apellidos + "</strong><br />";
                    
                    for(j = 0; j < nombres_repetidos.length; j++){
                        texto_salida += nombres_repetidos[j]+"<br />";
                        
                    }
                    
                    texto_salida +=  "</p>";
                }

                //Calcular promedio
                var promedio = (i+1)*100/usuarios.length;
                //promedio = promedio.toFixed(0);
                $( "#progressbar_lote" ).progressbar({
                    value: promedio
                });
                $("#estado_proceso_lote").html('Comparando '+ (i+1) +' de ' + usuarios.length + ' Trabajadores');


            }

        //Revisar Estudiantes
        
        $("#accion_lote").html('Buscando estudiantes...');

        myurl = '<?php echo $ruta_url; ?>index.php/mantenimiento/lista_estudiantes_sede/' + $("#idsede_lote").val() + '/';

        var usuarios;
        
          $.ajax({
              url: myurl,
              dataType: 'json',
              async: false,
              success: function(data){
                usuarios = data;
              }
            });
            
            cant_usuarios = usuarios.length;
            
            nombres = '';
            apellidos = '';
            arrnombres = [];
            arrapellidos = [];
            
            for (i = 0; i < usuarios.length;i++){
                nombres = $.trim(usuarios[i].nombres);
                apellidos = $.trim(usuarios[i].apellidos);
                
                arrnombres[i] = nombres.split(' '); 
                arrapellidos[i] = apellidos.split(' ');

                //Calcular promedio
                var promedio = (i+1)*100/usuarios.length;
                //promedio = promedio.toFixed(0);
                $( "#progressbar_lote" ).progressbar({
                    value: promedio
                });
                $("#estado_proceso_lote").html('Leyendo '+ (i+1) +' de ' + usuarios.length + ' Estudiantes');


            }

        $( "#progressbar_lote" ).progressbar({
            value: 0
        });
            
            texto_salida += '<h2>Posibles estudiantes repetidos.</h2>';

            for (i = 0; i < usuarios.length - 1;i++){
                nombres_repetidos = [];
                var rep_nom;
                var rep_app;
                for(j = i+1; j < usuarios.length;j++){
                    //Ver si concide alguno de los nombres
                    rep_nom = false;
                    rep_app = false;
                    
                    for(h = 0; h < arrnombres[i].length; h++){
                        for(k=0; k < arrnombres[j].length; k++){
                            if(arrnombres[i][h] == arrnombres[j][k]){
                                rep_nom = true;
                            }
                        }
                    }
                    
                    for(h = 0; h < arrapellidos[i].length; h++){
                        for(k=0; k < arrapellidos[j].length; k++){
                            if(arrapellidos[i][h] == arrapellidos[j][k]){
                                rep_app = true;
                            }
                        }
                    }
                    
                    if(rep_nom == true && rep_app == true){
                        nombres_repetidos.push(usuarios[j].usuario + ' - ' + usuarios[j].nombres + ' ' +usuarios[j].apellidos);
                    }
                }
                
                if (nombres_repetidos.length > 0){
                    texto_salida += "<p><strong>"+ usuarios[i].usuario + ' - ' + usuarios[i].nombres + ' ' +usuarios[i].apellidos + "</strong><br />";
                    
                    for(j = 0; j < nombres_repetidos.length; j++){
                        texto_salida += nombres_repetidos[j]+"<br />";
                        
                    }
                    
                    texto_salida +=  "</p>";
                }

                //Calcular promedio
                var promedio = (i+1)*100/usuarios.length;
                //promedio = promedio.toFixed(0);
                $( "#progressbar_lote" ).progressbar({
                    value: promedio
                });
                $("#estado_proceso_lote").html('Comparando '+ (i+1) +' de ' + usuarios.length + ' Estudiantes');


            }


            $("#acciones_lote").html(texto_salida);

}

</script>