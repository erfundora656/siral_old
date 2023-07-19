<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
	<table class="flexme3" id="tabla_sedes" style="display: none"></table>

	<script type="text/javascript">

		$("#tabla_sedes").flexigrid({
			url : '<?= $ruta_url; ?>index.php/sedes/lista_sedes/',
			dataType : 'json',
			colModel : [ {
				display : 'Activa',
				name : 'activa',
				width : 30,
				sortable : true,
				align : 'center'
			}, {
				display : 'Nombre',
				name : 'nombre',
				width : 200,
				sortable : true,
				align : 'left'
			}],
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
			}, {
				name : 'Activar',
				bclass : 'add',
				onpress : acciones
			}, {
				name : 'Desactivar',
				bclass : 'delete',
				onpress : acciones
			}, {
				separator : true
			} ],
			searchitems : [ {
				display : 'Nombre',
				name : 'nombre',
				isdefault : true
			} ],
			sortname : "nombre",
			sortorder : "asc",
			usepager : true,
			title : 'Gestor de sedes',
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

                                 if(confirm('Seguro desea eliminar estas ' + $('.trSelected', grid).length + ' sedes?')){
                                     //eliminar
                                     seleccionados = $('.trSelected', grid);

                                     for(i=0; i < seleccionados.length; i++){
                                        nombre=seleccionados[i].id;

                                        id = nombre.substr(3);

                                        var datos = "idsede=" + id;

                                        $.ajax({
                                           type: "POST",
                                           url: "<?= $ruta_url; ?>index.php/sedes/eliminar_sede/",
                                           data: datos,
                                           success: function(){
                                                //location.reload();
                                           }
                                        });


                                     }
                                     $("#tabla_sedes").flexReload();

                                 }
                            } else {
                                alert('Debe seleccionar algún elemento para eliminar.');
                            }
                                
			} else if (com == 'Agregar') {
				//alert('Add New Item');
                                var myurl = "<?= $ruta_url; ?>index.php/sedes/agregar_sede_dlg/";

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

                                    var myurl = "<?= $ruta_url; ?>index.php/sedes/editar_sede_dlg/"+id+"/";


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
                        
			if (com == 'Activar') {

                             if($('.trSelected', grid).length > 0){

                                 if(confirm('Seguro desea activar estas ' + $('.trSelected', grid).length + ' sedes?')){
                                     //eliminar
                                     seleccionados = $('.trSelected', grid);

                                     for(i=0; i < seleccionados.length; i++){
                                        nombre=seleccionados[i].id;

                                        id = nombre.substr(3);

                                        var datos = "idsede=" + id;

                                        $.ajax({
                                           type: "POST",
                                           url: "<?= $ruta_url; ?>index.php/sedes/activar_sede/",
                                           data: datos,
                                           async: false,
                                           success: function(){
                                                //location.reload();
                                           }
                                        });


                                     }
                                     $("#tabla_sedes").flexReload();

                                 }
                            } else {
                                alert('Debe seleccionar algún elemento para activar.');
                            }
                                
			}
                        
			if (com == 'Desactivar') {

                             if($('.trSelected', grid).length > 0){

                                 if(confirm('Seguro desea desactivar estas ' + $('.trSelected', grid).length + ' sedes?')){
                                     //eliminar
                                     seleccionados = $('.trSelected', grid);

                                     for(i=0; i < seleccionados.length; i++){
                                        nombre=seleccionados[i].id;

                                        id = nombre.substr(3);

                                        var datos = "idsede=" + id;

                                        $.ajax({
                                           type: "POST",
                                           url: "<?= $ruta_url; ?>index.php/sedes/desactivar_sede/",
                                           data: datos,
                                           async: false,
                                           success: function(){
                                                //location.reload();
                                           }
                                        });


                                     }
                                     $("#tabla_sedes").flexReload();

                                 }
                            } else {
                                alert('Debe seleccionar algún elemento para desactivar.');
                            }
                                
			}
		}
                
                
                
        $( "#dialogo_agregar" ).attr('title','Agregar sede');
        $( "#dialogo_agregar" ).dialog({
		width: 500,
		autoOpen: false,
		modal: true,
		buttons: {
			"Agregar": function() {

                        var datos = "nombre=" + $("#nombre").val() + "&activa=1" + "&usarldap=" + use_ldap + "&servidorldap=" + $("#servidor").val() + "&sufijo=" + $("#sufijo").val() + "&dnbase="+ $("#base_dn").val() + "&usuario="+ $("#admin_user").val() + "&contrasena="+ $("#admin_pass").val() + "&gtrabajadores=" + $("#grupos").val() + "&gestudiantes=" + $("#grupos_e").val();

                                    $.ajax({
                                       type: "POST",
                                       url: "<?= $ruta_url; ?>index.php/sedes/agregar_sede/",
                                       data: datos,
                                       async: false,
                                       success: function(){
                                            //location.reload();
                                       }
                                    });

                                    $("#tabla_sedes").flexReload();

                                    $( this ).dialog( "close" );

                                    $( "#dialogo_agregar" ).html('');
			},
			"Cancelar": function() {
				$( this ).dialog( "close" );
				$( "#dialogo_agregar" ).html('');
			}
		}
	});

        $( "#dialogo_editar" ).attr('title','Editar sede');
        $( "#dialogo_editar" ).dialog({
		width: 500,
		autoOpen: false,
		modal: true,
		buttons: {
			"Editar": function() {

                                var datos = "&idsede=" + $("#idsede").val() + "&nombre=" + $("#nombre").val()+ "&activa=1";

                                
				$.ajax({
				   type: "POST",
				   url: "<?= $ruta_url; ?>index.php/sedes/editar_sede/",
				   data: datos,
                                   async: false,
				   success: function(){
					//location.reload();
				   }
                                });

                                $("#tabla_sedes").flexReload();

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
