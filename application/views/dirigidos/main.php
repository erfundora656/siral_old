<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<table width="100%" class="ui-widget ui-widget-content" cellpadding="5" cellspacing="0">
	<thead class="ui-widget-header">
		<tr>
			<td style="font-size: 1.5em;">Administrar dirigidos de <?php if($turno==1) echo "desayunos"; else if($turno==2) echo "almuerzos"; else if($turno==3) echo "comidas";?></td>
			<?php
				/*
				Agragamos aca el listado de las sedes agregadas al sistema.
				*/ 
				?> 
				<td style="text-align: right;font-size: 1.0em;">
					Fecha:<input id="fecha_dirigidos" size="8" type="text" readonly/>
				</td>
				<td style="text-align: right;font-size: 1.0em;">Sede:&nbsp;<select id="sede_idsede">
					<?php
					if($listasedes!=""){
						foreach($listasedes as $sede){
							echo("<option value='".$sede['idsede']."'>".$sede['nombre']."</option>");
						}
					}else{
						echo("<option value='".$id_sede."'>".$sede_pertenece."</option>");	
					}
					
					?>
				</select>
			</td>
			
		</tr>
	</thead>
</table>
<br />
<table class="flexme3" id="tabla_dirigidos" style="display: none"></table>

<script type="text/javascript">
if(<?php echo $this->session->userdata('rol')?>==3 || <?php echo $this->session->userdata('rol')?> ==2){
		$( "#fecha_dirigidos" ).datepicker({changeMonth: true,changeYear: true});
		$( "#fecha_dirigidos" ).datepicker("option", "dateFormat", "dd/mm/yy");
	}else{
		$( "#fecha_dirigidos" ).datepicker({changeMonth: true,changeYear: true,minDate: '0'});
		$( "#fecha_dirigidos" ).datepicker("option", "dateFormat", "dd/mm/yy");
	}
	
// Detectar la fecha y ponerla en el datepicker

$("#fecha_dirigidos").val('<?php echo date('d/m/Y') ?>');

function carga_menu(fecha_,sede_,turno_){
	$("#tabla_dirigidos").flexOptions({
		url : '<?= $ruta_url; ?>index.php/dirigidos/lista_dirigidos/'+fecha_+'/'+sede_+'/'+turno_+'/',
		dataType : 'json',
		colModel : [ {
			display : 'Cantidad',
			name : 'cantidad',
			width : 90,
			sortable : true,
			align : 'left'
		}, {
			display : 'Detalles',
			name : 'detalles',
			width : 300,
			sortable : true,
			align : 'left'
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
			display : 'Detalles',
			name : 'detalles',
			isdefault : true
		} ],
		sortname : "dirigido.detalles",
		sortorder : "asc",
		usepager : true,
		title : 'Lista de dirigidos',
		useRp : true,
		rp : 10,
		showTableToggleBtn : true,
		width : 790,
		resizable: false,
		height : 260
	});
	
	$("#tabla_dirigidos").flexReload();
}

function acciones(com, grid) {
	if (com == 'Eliminar') {

		if($('.trSelected', grid).length > 0){

			if(confirm('Seguro desea eliminar estos ' + $('.trSelected', grid).length + ' dirigidos?')){
                                     //eliminar
                                     seleccionados = $('.trSelected', grid);

                                     for(i=0; i < seleccionados.length; i++){
                                     	nombre=seleccionados[i].id;

                                     	id = nombre.substr(3);

                                     	var datos = "iddirigido=" + id;

                                     	$.ajax({
                                     		type: "POST",
                                     		url: "<?= $ruta_url; ?>index.php/dirigidos/eliminar_dirigido/",
                                     		data: datos,
                                     		success: function(){
                                                //location.reload();
                                            }
                                        });


                                     }
                                     $("#tabla_dirigidos").flexReload();

                                 }
                             } else {
                             	alert('Debe seleccionar algún elemento para eliminar.');
                             }
                             
                         } else if (com == 'Agregar') {
				//alert('Add New Item');
				var sede=$("#sede_idsede").val();
				var myurl = "<?= $ruta_url; ?>index.php/dirigidos/agregar_dirigido_dlg/"+sede+"/";

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

					var myurl = "<?= $ruta_url; ?>index.php/dirigidos/editar_dirigido_dlg/"+id+"/";


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

		function agregar_dirigidos(){
			var datos = "cantidad=" + $('#cantidad_dir').val() + "&detalles="+ $('#detalles_dir').val();
			datos += "&fecha=" + fecha;
			datos += "&sede_idsede=" + $('#sede_idsede').val();
			datos += "&turno=" + <?php echo $turno;?>;
			


			$.ajax({
				type: "POST",
				async: false,
				url: "<?= $ruta_url; ?>index.php/dirigidos/agregar_dirigido/",
				data: datos,
				success: function(){
            //location.reload();
        }
    });
			
		}

		function editar_dirigido(){
			var datos = "iddirigido=" + $('#iddirigido').val() + "&cantidad=" + $('#cantidad_dir').val() + "&detalles="+ $('#detalles_dir').val() + "&sede_idsede="+ $('#sede_idsede').val()+ "&turno="+ <?php echo $turno;?>;

			$.ajax({
				type: "POST",
				async: false,
				url: "<?= $ruta_url; ?>index.php/dirigidos/editar_dirigido/",
				data: datos,
				success: function(){
            //location.reload();
        }
    });
			
		}

		$( "#dialogo_agregar" ).attr('title','Agregar dirigidos');
		$( "#dialogo_agregar" ).dialog({
			width: 620,
			autoOpen: false,
			modal: true,
			buttons: {
				"Agregar": function() {

					agregar_dirigidos();    

					$("#tabla_dirigidos").flexReload();

					$( this ).dialog( "close" );

					$( "#dialogo_agregar" ).html('');
					
				},
				"Cancelar": function() {
					$( this ).dialog( "close" );
					$( "#dialogo_agregar" ).html('');
				}
			}
		});

		$( "#dialogo_editar" ).attr('title','Editar dirigido');
		$( "#dialogo_editar" ).dialog({
			width: 620,
			autoOpen: false,
			modal: true,
			buttons: {
				"Editar": function() {

				// 

				editar_dirigido(); 
				
				$("#tabla_dirigidos").flexReload();

				$( this ).dialog( "close" );

				$( "#dialogo_editar" ).html('');
				
			},
			"Cancelar": function() {
				$( this ).dialog( "close" );
				$( "#dialogo_editar" ).html('');
			}
		}
	});


		

		
		$("#fecha_dirigidos").change(function(){
    //alert($("#fecha_dirigidos").val());
    fecha = $("#fecha_dirigidos").val();
    sede = $("#sede_idsede").val();
    turno = <?php echo $turno;?>;
    carga_menu(fecha,sede,turno);
    
});
		$("#sede_idsede").change(function(){
	   // alert($("#sede_idsede").val());   
	   fecha=$("#fecha_dirigidos").val();
	   sede = $("#sede_idsede").val();
	   turno = <?php echo $turno;?>;
	   carga_menu(fecha,sede,turno);
	});

		fecha = $("#fecha_dirigidos").val();

		sede = $("#sede_idsede").val();
		turno = <?php echo $turno;?>;
		
		$("#tabla_dirigidos").flexigrid({
			url : '<?= $ruta_url; ?>index.php/dirigidos/lista_dirigidos/'+fecha+'/'+sede+'/'+turno+'/',
			dataType : 'json',
			colModel : [ {
				display : 'Cantidad',
				name : 'cantidad',
				width : 90,
				sortable : true,
				align : 'center'
			}, {
				display : 'Detalles',
				name : 'detalles',
				width : 300,
				sortable : true,
				align : 'left'
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
				display : 'Detalles',
				name : 'detalles',
				isdefault : true
			} ],
			sortname : "dirigido.detalles",
			sortorder : "asc",
			usepager : true,
			title : 'Lista de dirigidos',
			useRp : true,
			rp : 10,
			showTableToggleBtn : true,
			width : 790,
			resizable: false,
			height : 260
		});
		
		

	</script>