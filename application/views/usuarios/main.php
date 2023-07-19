<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<table class="flexme3" id="tabla_usuarios" style="display: none"></table>

<script type="text/javascript">

	$("#tabla_usuarios").flexigrid({
		url : '<?= $ruta_url; ?>index.php/usuarios/lista_usuarios/',
		dataType : 'json',
		colModel : [ {
			display : 'Usuario',
			name : 'usuario',
			width : 125,
			sortable : true,
			align : 'left'
		}, {
			display : 'Nombre',
			name : 'nombres',
			width : 125,
			sortable : true,
			align : 'left'
		}, {
			display : 'Apellidos',
			name : 'apellidos',
			width : 125,
			sortable : true,
			align : 'left'
		}, {
			display : 'Rol',
			name : 'rol',
			width : 100,
			sortable : true,
			align : 'center'
		} ],
		buttons : [ {
			name : 'Agregar',
			bclass : 'add',
			onpress : acciones
		}, {
			name : 'Eliminar',
			bclass : 'delete',
			onpress : acciones
		}, {
			name : 'Editar',
			bclass : 'edit',
			onpress : acciones
		}, {
			separator : true
		} ],
		searchitems : [ {
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
		sortname : "nombres",
		sortorder : "asc",
		usepager : true,
		title : 'Gestor de usuarios',
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

				if(confirm('Seguro desea eliminar estos ' + $('.trSelected', grid).length + ' usuarios?')){
                                     //eliminar
                                     seleccionados = $('.trSelected', grid);

                                     for(i=0; i < seleccionados.length; i++){
                                     	nombre=seleccionados[i].id;

                                     	id = nombre.substr(3);

                                     	var datos = "idusuario=" + id;

                                     	$.ajax({
                                     		type: "POST",
                                     		url: "<?= $ruta_url; ?>index.php/usuarios/eliminar_usuario/",
                                     		data: datos,
                                     		success: function(){
                                                //location.reload();
                                            }
                                        });


                                     }
                                     $("#tabla_usuarios").flexReload();

                                 }
                             } else {
                             	alert('Debe seleccionar algún elemento para eliminar.');
                             }

                         } else if (com == 'Agregar') {
				//alert('Add New Item');
				var myurl = "<?= $ruta_url; ?>index.php/usuarios/agregar_usuario_dlg/";

				var contenido_dialog = $.ajax({
					async: false,
					url: myurl
				}).responseText;

				$( "#dialogo_agregar" ).html(contenido_dialog);

				$( "#dialogo_agregar" ).dialog( "open" );

			}
			if (com == 'Editar') {
				//alert('Edit item'+ $('.trSelected', grid).length + ' items?');

				if($('.trSelected', grid).length > 0){

					seleccionados = $('.trSelected', grid);

					nombre=seleccionados[0].id;

					id = nombre.substr(3);

					var myurl = "<?= $ruta_url; ?>index.php/usuarios/editar_usuario_dlg/"+id+"/";


					var contenido_dialog = $.ajax({
						async: false,
						url: myurl
					}).responseText;

					$( "#dialogo_editar" ).html(contenido_dialog);

					$( "#dialogo_editar" ).dialog( "open" );

				} else {
					alert('Debe seleccionar algún elemento para editar.');
				}

			}
			
		}



		$( "#dialogo_agregar" ).attr('title','Agregar usuario');
		$( "#dialogo_agregar" ).dialog({
			width: 400,
			autoOpen: false,
			modal: true,
			buttons: {
				"Agregar": function() {

				// validar los datos

                                // Garantizar que no exista el usuario
                                
                                correcto = false;
                                mensaje = ""; 
				// Agregar el usuario

                                // Verificar el usuario no exista y que hallan entrado uno.
                                
                                usuario = jQuery.trim($("#usuario").val());
                                
                                usuario = usuario.toLowerCase();
                                
                                var myurl = "<?= $ruta_url; ?>index.php/usuarios/existe_usuario/"+usuario+"/";

                                var existe_usuario =parseInt($.ajax({
                                	async: false,
                                	url: myurl
                                }).responseText);

                                myurl = "<?= $ruta_url; ?>index.php/trabajadores/existe_trabajador/"+usuario+"/";

                                var existe_trabajador = parseInt($.ajax({
                                	async: false,
                                	url: myurl
                                }).responseText);

                                if (existe_usuario == 1){
                                	correcto = false;
                                	muestra_mensaje('<h2>Ya existe el usuario '+$("#usuario").val()+'.</h2>', 400,null);
                                	                                 
                                }else if (existe_trabajador == 1){
                                	correcto = true;
                                	muestra_mensaje('<h2>Importando datos del usuario'+$("#usuario").val()+'.</h2>', 400,null);
                                }else{
                                	muestra_mensaje('<h2>El usuario '+$("#usuario").val()+' debe acceder al sistema como trabajador y completar su registro.</h2>', 600,null);                               	
                                }

                                if (usuario.length < 2){
                                	correcto = false;
                                	muestra_mensaje('<h2>El usuario debe deben tener más de 2 caracteres.</h2>', 400,null);
                                }

                                if(correcto){
                                	var datos= "&usuario=" + $("#usuario").val();

                                	$.ajax({
                                		type: "POST",
                                		url: "<?= $ruta_url; ?>index.php/trabajadores/datos_trabajador/"+usuario+"/",
                                		dataType:"json",
                                		async: false,                                       
                                		success: function(dat){
                                			datos+="&nombres="+dat[0]["nombres"];
                                			datos+="&apellidos="+dat[0]["apellidos"]; 
                                            //location.reload();
                                        }
                                    });
                                	datos += "&rol=" + $("#rol").val();
                                	datos += "&sede_idsede=" + $("#sede_idsede").val();

                                	$.ajax({
                                		type: "POST",
                                		url: "<?= $ruta_url; ?>index.php/usuarios/agregar_usuario/",
                                		data: datos,
                                		success: function(){
                                            //location.reload();
                                        }
                                    });

                                	$("#tabla_usuarios").flexReload();

                                	$( this ).dialog( "close" );

                                	$( "#dialogo_agregar" ).html('');
                                } else {
                                	//$( "#muestra_errores" ).html(mensaje);
                                	$( this ).dialog( "close" );
                                	$( "#dialogo_agregar" ).html('');
                                }
                            },
                            "Cancelar": function() {
                            	$( this ).dialog( "close" );
                            	$( "#dialogo_agregar" ).html('');
                            }
                        }
                    });

$( "#dialogo_editar" ).attr('title','Editar usuario');
$( "#dialogo_editar" ).dialog({
	width: 400,
	autoOpen: false,
	modal: true,
	buttons: {
		"Editar": function() {

				// 

				var datos = "idusuario=" + $("#idusuario").val() + "&nombres=" + $("#nombres").val() + "&apellidos="+ $("#apellidos").val();
				datos += "&rol=" + $("#rol").val();
				datos += "&usuario=" + $("#usuario").val();
				datos += "&sede_idsede=" + $("#sede_idsede").val();


				$.ajax({
					type: "POST",
					url: "<?= $ruta_url; ?>index.php/usuarios/editar_usuario/",
					data: datos,
					success: function(){
					//location.reload();
				}
			});

				$("#tabla_usuarios").flexReload();

				$( this ).dialog( "close" );

				$( "#dialogo_editar" ).html('');

			},
			"Cancelar": function() {
				$( this ).dialog( "close" );
				$( "#dialogo_editar" ).html('');
			}
		}
	});



</script>
