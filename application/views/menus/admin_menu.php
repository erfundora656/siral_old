	<?php

	/*
	 * To change this template, choose Tools | Templates
	 * and open the template in the editor.
	 */

	?>
	<table width="100%" class="ui-widget ui-widget-content" cellpadding="5" cellspacing="0">
		<thead class="ui-widget-header">
			<tr>
				<td style="font-size: 1.5em;">Administrar menús de <?php if($turno==1) echo "desayunos"; else if($turno==2) echo "almuerzos"; else if($turno==3) echo "comidas";?></td>
				<?php
				/*
				Agragamos aca el listado de las sedes agregadas al sistema.
				*/ 
				?> 
				<td style="text-align: right;font-size: 1.0em;">
					Fecha:<input id="fecha_platos" size="8" type="text" readonly/>
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
<table class="flexme3" id="tabla_platos" style="display: none">

</table>

<script type="text/javascript">
	if(<?php echo $this->session->userdata('rol')?>==3 || <?php echo $this->session->userdata('rol')?> ==2){
		$( "#fecha_platos" ).datepicker({changeMonth: true,changeYear: true});
		$( "#fecha_platos" ).datepicker("option", "dateFormat", "dd/mm/yy");
	}else{
		$( "#fecha_platos" ).datepicker({changeMonth: true,changeYear: true,minDate: '0'});
		$( "#fecha_platos" ).datepicker("option", "dateFormat", "dd/mm/yy");
	}
	


	// Detectar la fecha y ponerla en el datepicker

	$("#fecha_platos").val('<?php echo date('d/m/Y') ?>');
	
	/*
	Pasar los dos parámetros a esta función nos permite utilizar tanto la fecha como la sede para filtrar los platos. 
	*/
	
	function carga_menu(fecha_,sede_,turno_){
		$("#tabla_platos").flexOptions({
			url : '<?= $ruta_url; ?>index.php/platos/lista_platos/'+fecha_+'/'+sede_+'/'+turno_+'/',
			dataType : 'json',
			colModel : [ {
				display : 'Nombre',
				name : 'nombre',
				width : 125,
				sortable : true,
				align : 'left'
			}, {
				display : 'Precio',
				name : 'precio',
				width : 125,
				sortable : true,
				align : 'left'
			}, {
				display : 'Cantidad',
				name : 'cantidad',
				width : 125,
				sortable : true,
				align : 'left'
			}, {
				display : 'Unidad',
				name : 'tipocantidad',
				width : 70,
				sortable : true,
				align : 'center'
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
			} ],
						searchitems : [ {
				display : 'Nombre',
				name : 'nombre',
				isdefault : true
			} ],
			sortname : "plato.nombre",
			sortorder : "asc",
			usepager : true,
			title : 'Platos',
			useRp : true,
			rp : 10,
			showTableToggleBtn : true,
			width : 790,
			resizable: false,
			height : 280
		});

		$("#tabla_platos").flexReload();
	}

	function acciones(com, grid) {
		if (com == 'Eliminar') {

			if($('.trSelected', grid).length > 0){

				if(confirm('Seguro desea eliminar estos ' + $('.trSelected', grid).length + ' platos?')){
	                                     //eliminar
	                                     seleccionados = $('.trSelected', grid);

	                                     var turno="<?php echo $turno?>";
	                                     var sede=$("#sede_idsede").val();

	                                     for(i=0; i < seleccionados.length; i++){
	                                     	nombre=seleccionados[i].id;

	                                     	id = nombre.substr(3);

	                                     	var datos = "idplato=" + id;
	                                     	datos += "&sede_idsede=" + sede;
	                                     	datos += "&turno=" + turno;

	                                     	$.ajax({
	                                     		type: "POST",
	                                     		async: false,
	                                     		url: "<?= $ruta_url; ?>index.php/platos/eliminar_plato/",
	                                     		data: datos,
	                                     		success: function(){
	                                                //location.reload();
	                                            }
	                                        });


	                                     }
	                                     $("#tabla_platos").flexReload();

	                                 }
	                             } else {
	                             	alert('Debe seleccionar algún elemento para eliminar.');
	                             }
	                             
	                         } else if (com == 'Agregar') {
					//alert('Add New Item');
					/*
					Pasamos el valos de la sede seleccionada en el Panel general para que se pueda tener el id en 
					el formulario generado para adicionar Platos.	
					El valor del turno responde a si es Desayuno, Almuerzo o Comida.
					*/
					
					var data=$("#sede_idsede").val();
					var turno="<?php echo $turno?>";
					
					var myurl = "<?= $ruta_url; ?>index.php/platos/agregar_plato_dlg/"+data+"/"+turno+"/";
					
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

						var myurl = "<?= $ruta_url; ?>index.php/platos/editar_plato_dlg/"+id+"/";


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

			$( "#dialogo_agregar" ).attr('title','Agregar platos');

			$( "#dialogo_agregar" ).dialog({
				width: 620,
				autoOpen: false,
				modal: true,
				buttons: {
					"Agregar": function() {
						
						agregar_platos();  
						convertir_pre_reservas()

						$("#tabla_platos").flexReload();

						$( this ).dialog( "close" );
						
						$( "#dialogo_agregar" ).html('');
						
					},
					"Cancelar": function() {
						$( this ).dialog( "close" );
						$( "#dialogo_agregar" ).html('');
					}
				}
			});

			$( "#dialogo_editar" ).attr('title','Editar plato');
			$( "#dialogo_editar" ).dialog({
				width: 500,
				autoOpen: false,
				modal: true,
				buttons: {
					"Editar": function() {

					// 

					editar_plato(); 
					
					$("#tabla_platos").flexReload();

					$( this ).dialog( "close" );

					$( "#dialogo_editar" ).html('');
					
				},
				"Cancelar": function() {
					$( this ).dialog( "close" );
					$( "#dialogo_editar" ).html('');
				}
			}
		});

	/*
	Aca se cargan los menús de alimentación en una sede y una fecha determinada.
	*/
	$("#fecha_platos").change(function(){
		fecha=$("#fecha_platos").val();
		sede = $("#sede_idsede").val();
		turno = <?php echo $turno;?>;
		carga_menu(fecha,sede,turno);

	});
	$("#sede_idsede").change(function(){
		fecha=$("#fecha_platos").val();
		sede = $("#sede_idsede").val();
		turno = <?php echo $turno;?>;
		carga_menu(fecha,sede,turno);
	});

	fecha = $("#fecha_platos").val();
	sede = $("#sede_idsede").val();
	turno = <?php echo $turno;?>;
	
	$("#tabla_platos").flexigrid({
		url : '<?= $ruta_url; ?>index.php/platos/lista_platos/'+fecha+'/'+sede+'/'+turno+'/',
		dataType : 'json',
		colModel : [ {
			display : 'Nombre',
			name : 'nombre',
			width : 125,
			sortable : true,
			align : 'left'
		}, {
			display : 'Precio',
			name : 'precio',
			width : 125,
			sortable : true,
			align : 'left'
		}, {
			display : 'Cantidad',
			name : 'cantidad',
			width : 125,
			sortable : true,
			align : 'left'
		}, {
			display : 'Unidad',
			name : 'tipocantidad',
			width : 70,
			sortable : true,
			align : 'center'
		}],
		buttons : [{
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
		},{
			separator : true
		}],	

		searchitems : [ {
			display : 'Nombre',
			name : 'nombre',
			isdefault : true
		}],
		sortname : "plato.nombre",
		sortorder : "asc",
		usepager : true,
		title : '<label align="left">Platos</label><div style="float: inline-end;margin-right: 20px;"><input type="checkbox" id="pEspecial">&nbsp;<label align="right" >Precio especial:</label>&nbsp;<input style="width: 30px;" type="text" id="pFijo" disabled="true"></div>',
		useRp : true,
		rp : 10,
		showTableToggleBtn : true,
		width : 790,
		resizable: false,
		height : 280

	});

$('#pEspecial').click(function(){
	if($("#pEspecial").is(":checked")){	
		$("#pFijo").attr("disabled",false);
		$("#pEspecial").attr("disabled",true);
	}
});

$("#pFijo").change(function(){
	if($("#pFijo").val() != "" && !isNaN($("#pFijo").val())){
		myURL = '<?php echo $ruta_url;?>index.php/platos/lista_platos/'+fecha+'/'+sede+'/'+turno+'/';

            var platos=[];

            $.ajax({
              url: myURL,
              dataType: 'json',
              async: false,
              success: function(data){
                platos = data;

            }
        });
            
            if(platos['rows'].length > 0){            	
            	let pcientoG = ajustePrecio(platos);
            var id_precio =[];
            
            if(pcientoG > 0){
            id_precio =Array.from(ajustePlatos(platos,pcientoG));
            
             myURL = '<?php echo $ruta_url;?>index.php/platos/actualizar_precios_platos/';
            datos='ids_precio='+id_precio;

            $.ajax({
              url: myURL,
              type: 'POST',
              data:datos,
              async: false,
              success: function(){
              }
        });
			$("#tabla_platos").flexReload();
            $("#pFijo").val("");
            	$("#pFijo").attr("disabled",true);
            	$("#pEspecial").attr("checked",false);
            	$("#pEspecial").attr("disabled",false);
            
            }else{
            	alert('El precio fijado es mayor que el precio total del menú.'); 

            }

            }else{
            	alert('Debe definir los platos.');
            	$("#pFijo").val("");
            	$("#pFijo").attr("disabled",true);
            	$("#pEspecial").attr("checked",false);
            	$("#pEspecial").attr("disabled",false);            	
            	
            }
}else{
	$("#pFijo").val("");
	alert('Debe fijar el precio con un valor numérico.');
}
});


function ajustePrecio(platos){
	let tPrecio=0;
	let pcientoG=0;
	for (var i = 0; i < platos['rows'].length; i++) {
		tPrecio+=parseFloat(platos['rows'][i]['cell']['cal_precio']);			
	}
	
	if($("#pFijo").val() < tPrecio){
		pcientoG = ($("#pFijo").val() * 100 / tPrecio).toFixed(2);
	}
	return pcientoG; 
}

function ajustePlatos(platos,pcientoG){
	let ids_platos= [];
	let platos_mod= [];
	let id_precio = new Map();

for (var i = 0; i < platos['rows'].length; i++) {
		ids_platos[i]=platos['rows'][i]['cell']['idplato'];		
		platos_mod[i]=(parseFloat(platos['rows'][i]['cell']['cal_precio']) * pcientoG / 100).toFixed(2);		
		id_precio.set(ids_platos[i],platos_mod[i]);	
			}
	
	return id_precio;
}

 function convertir_pre_reservas(){ 

                fecha_p = $("#fecha_platos").val();                
                sede_idsede = $("#sede_idsede").val();
                turno=$("#turno").val();  
                alert(fecha_p);
                var datos = "fecha_p=" + fecha_p;
                datos +="&sede_idsede=" + sede_idsede;
                datos +="&turno="+turno;    
               
                $.ajax({
                   type: "POST",
                   async: false,
                   url: "<?= $ruta_url; ?>index.php/principal/actualiza_prereservacion/",
                   data: datos,
                   success: function(){
                        //location.reload();
                   }
                });        
    }

	    //carga_menu(fecha);

	</script>