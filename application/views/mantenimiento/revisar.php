<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

 if($tipo_usuario == 'Trabajadores'){
     $listado = 'lista_trabajadores';
     $existe = 'existe_trabajador_ldap';

     $dias = 'dias_trabajador';
     $mover_tickets = 'mover_tickets_trabajador';
     $dar_baja = 'trabajadores/eliminar_trabajador/';
     $id = 'idtrabajador';
 }
?>
<table width="100%" class="ui-widget ui-widget-content" cellpadding="5" cellspacing="0">
    <thead class="ui-widget-header">
        <tr>
            <td style="font-size: 1.2em;">Comprobar contra LDAP </td>
            <!--<td style="font-size: 1.2em;">Sede:
                <select id="idsede">
                    <?php foreach ($lista_sedes as $sede) {
                        ?>
                    <option value="<?php echo($sede['idsede']); ?>" ><?php echo($sede['nombre']); ?></option>
                        <?php
                    } ?>
                </select>
            </td>-->
            <td style="text-align: right;"><button id="b_Comprobar_<?php echo($tipo_usuario); ?>">Comprobar</button></td>
        </tr>
    </thead>
</table>
<div id="listatrabajadores_<?php echo($tipo_usuario); ?>"></div>
<div id="dialogo_dar_baja<?php echo($tipo_usuario); ?>"></div>
<div id="dialogo_mover_reservaciones<?php echo($tipo_usuario); ?>"></div>
<script type="text/javascript">
    
     $( "#b_Comprobar_<?php echo($tipo_usuario); ?>" ).button({
                                text: true,
                                icons: {
                                        primary: "ui-icon-check"
                                }
                            }).click(function(){
                                comprueba_trabajadores();
                            });

     var select_usuarios_ldap = '';
     var idusuario_origen;

     function comprueba_trabajadores(){
        select_usuarios_ldap = ''; 
         
        $( "#listatrabajadores_<?php echo($tipo_usuario); ?>" ).html('<div style="text-align: center;margin-top:150px;"><img width="100" src="../../../images/baile.gif"/><h3><div id="estado_proceso<?php echo($tipo_usuario); ?>">Espere un momento...</div></h3><div id="progressbar<?php echo($tipo_usuario); ?>"></div></div>');

        $( "#progressbar<?php echo($tipo_usuario); ?>" ).progressbar({
            value: 0
        });
         
         // Obtener lista de usuarios de una sede
         
         // Para cada usuario verificar si existe en el servidor ldap
         
        myurl = '<?php echo $ruta_url; ?>index.php/mantenimiento/<?php echo $listado; ?>/';

        var usuarios;
        
          $.ajax({
              url: myurl,
              dataType: 'json',
              async: false,
              success: function(data){
                usuarios = data;
              }
            });
            
            // Hasta aqui tengo los usuarios registrados como trabajadores

            var par  = false;
            cant_usuarios = usuarios.length;
            
            var contenido = '<h2>Usuarios que no aparecen en el servidor LDAP.</h2>';
                contenido +='<table width="100%" class="ui-widget ui-widget-content" cellpadding="5" cellspacing="0"><thead class="ui-widget-header">';
                contenido +='<tr><td><strong>Código</strong></td><td><strong>Usuario</strong></td><td><strong>Nombre y apellidos</strong></td>';
                contenido +='<td><strong>Desayunos</strong></td><td><strong>Almuerzos</strong></td><td><strong>Comidas</strong></td><td><strong>Acción</strong></td>';
                contenido +='</tr></thead>';

            for (i = 0; i < usuarios.length;i++){
                
                    //Comprobar si existe el usuario en el ldap
                    
                    var existe_usuario;
                    myurl = '<?php echo $ruta_url; ?>index.php/seguridad/<?php echo $existe; ?>/' + usuarios[i].usuario + '/';

                      $.ajax({
                          url: myurl,
                          dataType: 'json',
                          async: false,
                          success: function(data){
                            existe_usuario = data;
                          }
                        });
                    
                    
                    if (existe_usuario == false){
                        
                        if (par == true){
                            contenido +='<tr class ="fila_par fila_' + usuarios[i].<?php echo $id; ?> + '">';
                        } else {
                            contenido +='<tr class ="fila_impar fila_' + usuarios[i].<?php echo $id; ?> + '">';
                        }
                        par = !par;
                        

                        //Saber cuantos días con reservaciones tiene
                        var dias;
                        myurl = '<?php echo $ruta_url; ?>index.php/mantenimiento/<?php echo $dias; ?>/' + usuarios[i].<?php echo $id; ?> + '/';

                          $.ajax({
                              url: myurl,
                              dataType: 'json',
                              async: false,
                              success: function(data){
                                dias = data;                                
                              }
                            });
                          
           
            
                        var acciones = '<select id = "sacc_'+ usuarios[i].<?php echo $id; ?> +'" class="action_trab<?php echo($tipo_usuario); ?>">';
                            acciones += '<option value="0">Seleccionar...</option>';

                            if(dias.desayunos != 0 ||dias.almuerzos != 0 || dias.comidas != 0){
                                acciones += '<option value="1">Mover reservaciones</option>';
                            }
                            
                            /*if(usuarios[i].baja == 0){    
                                acciones += '<option value="2">Dar baja</option>';
                            } else {
                                if(dias.almuerzos == 0 && dias.comidas == 0){
                                    acciones += '<option value="2">Dar baja(Borrar)</option>';
                                }
                            } */
                            
                            acciones += '</select>';

                            
                        contenido += '<td>' + usuarios[i].codigo;
                        
                        /*if(usuarios[i].baja == 1){
                            contenido += '[B]';
                        }*/
                        contenido += '</td><td>' + usuarios[i].usuario + '</td>';
                        elnombre = usuarios[i].nombres + ' ' + usuarios[i].apellidos; 
                        contenido +='<td>' + elnombre.toUpperCase() + '</td>';
                        contenido +='<td>' + dias.desayunos + '</td>';
                        contenido +='<td>' + dias.almuerzos + '</td>';
                        contenido +='<td>' + dias.comidas + '</td>';
                        contenido +='<td>' + acciones + '</td>';
                        contenido +='</tr>';
                    } else {
                        elnombre = usuarios[i].nombres + ' ' + usuarios[i].apellidos;
                        select_usuarios_ldap +='<option value="' + usuarios[i].<?php echo $id; ?> + '">' + usuarios[i].codigo + ' ' + usuarios[i].usuario + ' ' + elnombre.toUpperCase() + '</option>';
                    }
                    
                    //Calcular promedio
                    var promedio = (i+1)*100/usuarios.length;
                    //promedio = promedio.toFixed(0);
                    $( "#progressbar<?php echo($tipo_usuario); ?>" ).progressbar({
                        value: promedio
                    });
                    $("#estado_proceso<?php echo($tipo_usuario); ?>").html('Procesando '+ (i+1) +' de ' + usuarios.length + ' <?php echo($tipo_usuario); ?>');
                    
                    
            }

            contenido +='</table>'; 
            
            $("#listatrabajadores_<?php echo($tipo_usuario); ?>").html(contenido);
            
            $(".action_trab<?php echo($tipo_usuario); ?>").change(function(){
                var id_usuario = parseInt(this.id.substring(5));
                //alert(idtrabajador + " " + this.value);
                if(this.value == 1){

                idusuario_origen = id_usuario;

                myurl = '<?php echo $ruta_url; ?>index.php/mantenimiento/<?php echo $listado; ?>/' + $("#idsede").val() + '/';

                var usuarios;

                  $.ajax({
                      url: myurl,
                      dataType: 'json',
                      async: false,
                      success: function(data){
                        usuarios = data;
                      }
                    });

                    var contenido = 'Seleccione usuario:<select id="usuario_cambiar">';
                        contenido +='<option value="0">Seleccionar...</option>';
                        contenido += select_usuarios_ldap;
                        contenido += '</select>';

                    $( "#dialogo_mover_reservaciones<?php echo($tipo_usuario); ?>" ).html(contenido);
                    $( "#dialogo_mover_reservaciones<?php echo($tipo_usuario); ?>" ).dialog( "open" );
                }
                
                if(this.value == 2){

                idusuario_origen = id_usuario;


                    var contenido = '<span style="text-align:center;">';
                        contenido +='<h2>Desea dar baja a este usuario</h2><h3>Si este no tiene ningua reservación será borrado de la base de datos.</h3><h3>Si tienes reservaciones a paritr de mañana estás seran eliminadas.</h3>';
                        contenido += '</span>';

                    $( "#dialogo_dar_baja<?php echo($tipo_usuario); ?>" ).html(contenido);
                    $( "#dialogo_dar_baja<?php echo($tipo_usuario); ?>" ).dialog( "open" );
                }
                
            });
            
            
     }

        $( "#dialogo_mover_reservaciones<?php echo($tipo_usuario); ?>" ).attr('title','Mover reservaciones a un usuario del ldap');
        $( "#dialogo_mover_reservaciones<?php echo($tipo_usuario); ?>" ).dialog({
		width: 600,
		autoOpen: false,
		modal: true,
		buttons: {
			"Mover": function() {
                                

                                var datos = "idusuario_origen=" + idusuario_origen + "&idusuario_destino="+ $("#usuario_cambiar").val();
                                $.ajax({
                                   type: "POST",
                                   url: "<?= $ruta_url; ?>index.php/mantenimiento/<?php echo $mover_tickets; ?>/",
                                   async: false,
                                   data: datos,
                                   success: function(){
                                        //location.reload();
                                   }
                                });

                                $( this ).dialog( "close" );

                                $( "#dialogo_mover_reservaciones<?php echo($tipo_usuario); ?>" ).html('');
                                    
			},
			"Cancelar": function() {
				$( this ).dialog( "close" );
				$( "#dialogo_mover_reservaciones<?php echo($tipo_usuario); ?>" ).html('');
			}
		}
	});

        $( "#dialogo_dar_baja<?php echo($tipo_usuario); ?>" ).attr('title','Dar baja');
        $( "#dialogo_dar_baja<?php echo($tipo_usuario); ?>" ).dialog({
		width: 600,
		autoOpen: false,
		modal: true,
		buttons: {
			"Dar baja": function() {
                                

                                var datos = "<?php echo $id; ?>=" + idusuario_origen;
                                $.ajax({
                                   type: "POST",
                                   url: "<?= $ruta_url; ?>index.php/<?php echo $dar_baja; ?>",
                                   async: false,
                                   data: datos,
                                   success: function(){
                                        //location.reload();
                                   }
                                });
                                
                                $(".fila_"+idusuario_origen).html('<td colspan="6">&nbsp;</td>');
                                
                                $( this ).dialog( "close" );

                                $( "#dialogo_dar_baja<?php echo($tipo_usuario); ?>" ).html('');
                                    
			},
			"Cancelar": function() {
				$( this ).dialog( "close" );
				$( "#dialogo_dar_baja<?php echo($tipo_usuario); ?>" ).html('');
			}
		}
	});

</script>