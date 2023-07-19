<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
	<table class="flexme3" id="tabla_trabajadores" style="display: none"></table>

	<script type="text/javascript">

		$("#tabla_trabajadores").flexigrid({
			url : '<?= $ruta_url; ?>index.php/trabajadores/lista_trabajadores/',
			dataType : 'json',
			colModel : [{
				display : 'Código',
				name : 'codigo',
				width : 70,
				sortable : true,
				align : 'center'
			},{
				display : 'Nombre',
				name : 'nombres',
				width : 125,
				sortable : true,
				align : 'left'
			},{
				display : 'Apellidos',
				name : 'apellidos',
				width : 125,
				sortable : true,
				align : 'left'
			},{
				display : 'Usuario',
				name : 'usuario',
				width : 125,
				sortable : true,
				align : 'left'
			},{
				display : 'Correo',
				name : 'email',
				width : 125,
				sortable : true,
				align : 'left'
			},{
				display : 'Becado',
				name : 'becado',
				width : 60,
				sortable : true,
				align : 'left'
			}],
			buttons : [ /*{
				name : 'Agregar',
				bclass : 'add',
				onpress : acciones
			}, {
				name : 'Editar',
				bclass : 'edit',
				onpress : acciones
			}, {
				separator : true
			}, */{
				name : 'Eliminar',
				bclass : 'delete',
				onpress : acciones
			}/*, {
				name : 'Dar alta',
				bclass : 'add',
				onpress : acciones
			}, {
				separator : true
			}, {
				name : 'Cambiar contraseña',
				bclass : 'cpass',
				onpress : acciones
			}, {
				separator : true
			}, {
				name : 'Sincronizar con Directorio Activo',
				bclass : 'edit',
				onpress : acciones
			} */],
			searchitems : [ {
				display : 'Código',
				name : 'codigo',
				isdefault : true
			}, {
				display : 'Usuario',
				name : 'usuario',
				isdefault : true
			}, {
				display : 'Nombre',
				name : 'nombres',
				isdefault : true
			}, {
				display : 'Apellidos',
				name : 'apellidos',
				isdefault : true
			} ],
			sortname : "codigo",
			sortorder : "asc",
			usepager : true,
			title : 'Gestor de trabajadores',
			useRp : true,
			rp : 15,
			showTableToggleBtn : true,
			width : 790,
                        resizable: false,
			height : 320
		});

		function acciones(com, grid) {
			if (com == 'Eliminar') {

                             if($('.trSelected', grid).length > 0){

                                 if(confirm('Seguro desea dar baja a estos ' + $('.trSelected', grid).length + ' trabajadores?')){
                                     //eliminar
                                     seleccionados = $('.trSelected', grid);

                                     for(i=0; i < seleccionados.length; i++){
                                        nombre=seleccionados[i].id;

                                        id = nombre.substr(3);

                                        var datos = "idtrabajador=" + id;

                                        $.ajax({
                                           type: "POST",
                                           url: "<?= $ruta_url; ?>index.php/trabajadores/eliminar_trabajador/",
                                           async: false,
                                           data: datos,
                                           success: function(){
                                                //location.reload();
                                           }
                                        });


                                     }
                                     $("#tabla_trabajadores").flexReload();

                                 }
                            } else {
                                alert('Debe seleccionar algún elemento para eliminar.');
                            }
                                
			} 

			/*if (com == 'Dar alta') {

                             if($('.trSelected', grid).length > 0){

                                 if(confirm('Seguro desea dar alta a estos ' + $('.trSelected', grid).length + ' trabajadores?')){
                                     //eliminar
                                     seleccionados = $('.trSelected', grid);

                                     for(i=0; i < seleccionados.length; i++){
                                        nombre=seleccionados[i].id;

                                        id = nombre.substr(3);

                                        var datos = "idtrabajador=" + id;

                                        $.ajax({
                                           type: "POST",
                                           url: "<?= $ruta_url; ?>index.php/trabajadores/alta_trabajador/",
                                           async: false,
                                           data: datos,
                                           success: function(){
                                                //location.reload();
                                           }
                                        });


                                     }
                                     $("#tabla_trabajadores").flexReload();

                                 }
                            } else {
                                alert('Debe seleccionar algún trabajador para darle alta.');
                            }
                                
			} */

                       /* if (com == 'Agregar') {
				//alert('Add New Item');
                                var myurl = "<?= $ruta_url; ?>index.php/trabajadores/agregar_trabajador_dlg/";

                                var contenido_dialog = $.ajax({
                                        async: false,
                                    url: myurl
                                        }).responseText;

                                $( "#dialogo_agregar" ).html(contenido_dialog);

                                $( "#dialogo_agregar" ).dialog( "open" );
                                
			}*/
                        /*if (com == 'Sincronizar con Directorio Activo') {
				//alert('Add New Item');
                                var myurl = "<?= $ruta_url; ?>index.php/trabajadores/ldap_sincronizar_dlg/";

                                var contenido_dialog = $.ajax({
                                        async: false,
                                    url: myurl
                                        }).responseText;

                                $( "#dialogo_mensaje" ).html(contenido_dialog);

                                $( "#dialogo_mensaje" ).dialog( "open" );
                                
			}*/
			/*if (com == 'Editar') {
				//alert('Edit item'+ $('.trSelected', grid).length + ' items?');

                             if($('.trSelected', grid).length > 0){

                                    seleccionados = $('.trSelected', grid);

                                    nombre=seleccionados[0].id;

                                    id = nombre.substr(3);

                                    var myurl = "<?= $ruta_url; ?>index.php/trabajadores/editar_trabajador_dlg/"+id+"/";


                                    var contenido_dialog = $.ajax({
                                            async: false,
                                            url: myurl
                                            }).responseText;

                                    $( "#dialogo_editar" ).html(contenido_dialog);

                                    $( "#dialogo_editar" ).dialog( "open" );
                                
                                } else {
                                    alert('Debe seleccionar algún elemento para editar.');
                                }
                                
			}*/
			
		}
                
                
                
        /*$( "#dialogo_agregar" ).attr('title','Agregar trabajador');
        $( "#dialogo_agregar" ).dialog({
		width: 500,
		autoOpen: false,
		modal: true,
		buttons: {
			"Agregar": function() {

				// validar los datos
                                
                                // Garantizar que no exista el trabajador
                                
                                correcto = true;
                                mensaje = ""; 
				// Agregar el trabajador
                                
                                // Verificar el trabajador no exista y que hallan entrado uno.
                                
                                usuario = jQuery.trim($("#usuario").val());
                                
                                usuario = usuario.toLowerCase();
                                
                                var myurl = "<?= $ruta_url; ?>index.php/trabajadores/existe_trabajador/"+usuario+"/"+$("#sede_idsede").val()+"/";

                                var existe_trabajador = parseInt($.ajax({
                                            async: false,
                                            url: myurl
                                        }).responseText);
                                
                                if (existe_trabajador == 1){
                                    correcto = false;
                                    mensaje += "Ya existe un trabajador con ese usuario "+$("#usuario").val()+" <br />";                                   
                                }
                                
                                if (usuario.length < 2){
                                    correcto = false;
                                    mensaje += "El usuario debe deben tener más de 2 caracteres <br />";                                   
                                }
                                
                                
                                
                                
                                if(correcto){
                                    var datos = "nombres=" + $("#nombres").val() + "&apellidos="+ $("#apellidos").val();
                                    datos += "&usuario=" + $("#usuario").val();
                                    datos += "&codigo=" + $("#codigo").val() + "&sede_idsede=" + $("#sede_idsede").val();

                                    $.ajax({
                                       type: "POST",
                                       url: "<?= $ruta_url; ?>index.php/trabajadores/agregar_trabajador/",
                                       async: false,
                                       data: datos,
                                       success: function(){
                                            //location.reload();
                                       }
                                    });

                                    $("#tabla_trabajadores").flexReload();

                                    $( this ).dialog( "close" );

                                    $( "#dialogo_agregar" ).html('');
                                } else {
                                    $( "#muestra_errores" ).html(mensaje);
                                }
			},
			"Cancelar": function() {
				$( this ).dialog( "close" );
				$( "#dialogo_agregar" ).html('');
			}
		}
	});*/

        $( "#dialogo_editar" ).attr('title','Editar trabajador');
        $( "#dialogo_editar" ).dialog({
		width: 500,
		autoOpen: false,
		modal: true,
		buttons: {
			"Editar": function() {

				// 

				var datos = "idtrabajador=" + $("#idtrabajador").val() + "&nombres=" + $("#nombres").val() + "&apellidos="+ $("#apellidos").val();
                                    datos += "&codigo=" + $("#codigo").val();

                                
				$.ajax({
				   type: "POST",
				   url: "<?= $ruta_url; ?>index.php/trabajadores/editar_trabajador/",
				   data: datos,
				   success: function(){
					//location.reload();
				   }
                                });

                                $("#tabla_trabajadores").flexReload();

                                $( this ).dialog( "close" );

                                $( "#dialogo_editar" ).html('');
                                
			},
			"Cancelar": function() {
				$( this ).dialog( "close" );
				$( "#dialogo_editar" ).html('');
			}
		}
	});

        
      
       /*var lista_trabajadores;
       var lista_sedes;
       var error_ldap;

        $( "#dialogo_mensaje" ).attr('title','Sincronizar con Directorio Activo');
        $( "#dialogo_mensaje" ).dialog({
		width: 500,
		autoOpen: false,
		modal: true,
		buttons: {
			"Sincronizar": function() {
                            //Obtener lista de sedes activas
                            
                              myURL = '<?= $ruta_url; ?>index.php/sedes/lista_sedes_json/';
                              $.ajax({
                                  url: myURL,
                                  dataType: 'json',
                                  async: false,
                                  success: function(data){
                                    lista_sedes = data;
                                  }
                                });
                            var textoSalida = '<table width="100%" border="1" celspacing="3"><tr><td>Sede:</td><td width="50" style="text-align:right;">Estado</td></tr>';
                            for (i = 0; i < lista_sedes.length; i++){
                                textoSalida += '<tr><td>'+lista_sedes[i]['nombre']+'</td><td width="50" style="text-align:right;"><div id="estado'+lista_sedes[i]['idsede']+'">&nbsp;</div></td></tr>'
                            }
                            
                            textoSalida += '</table>';
                            $("#lista_sedes").html(textoSalida);
                            
                            for (i = 0; i < lista_sedes.length; i++){
                                error_ldap = false;
                                $("#estado"+lista_sedes[i]['idsede']).html('<img width="15" src="<?= $ruta_url; ?>images/baile.gif"/>');
                                //Obtener lista de trabajadores
                                  myURL = '<?= $ruta_url; ?>index.php/trabajadores/ldap_lista_trabajadores/'+lista_sedes[i]['idsede']+'/';
                                  $.ajax({
                                      url: myURL,
                                      dataType: 'json',
                                      async: false,
                                      success: function(data){
                                        lista_trabajadores = data;
                                      },
                                      error: function(){
                                          error_ldap = true;
                                      }
                                    });
                                
                                if(error_ldap == false){
                                    $("#p_sinc_cantidad").html(lista_trabajadores.length);    
                                    for (j = 0; j < lista_trabajadores.length; j++){
                                                var datos = "idusuario=" + lista_trabajadores[j] + "&idsede=" + lista_sedes[i]['idsede'];

                                                $.ajax({
                                                   type: "POST",
                                                   async:false,
                                                   url: "<?= $ruta_url; ?>index.php/trabajadores/ldap_sincorniza_trabajador/",
                                                   data: datos,
                                                   success: function(){
                                                        //location.reload();
                                                   }
                                                });
                                                $("#p_sinc_numero").html(i+1);

                                    }
                                    $("#estado"+lista_sedes[i]['idsede']).html('OK');

                                } else {
                                    $("#estado"+lista_sedes[i]['idsede']).html('Error');
                                }
                           }

			},
			"Cerrar": function() {
                                $("#tabla_trabajadores").flexReload();
				$( this ).dialog( "close" );
				$( "#dialogo_mensaje" ).html('');
                                
			}
		}
	});*/

	</script>
