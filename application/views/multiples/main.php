<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
if($tipo_usuario == 'Trabajadores'){
   $listado = 'lista_trabajadores_sede';
   $listado_filtro = 'listado_trabajadores_filtro';
   $id = 'idtrabajador';
   $acciones = 'acciones_trabajador';
}

?>

<table width="100%" class="ui-widget ui-widget-content" cellpadding="5" cellspacing="0">
    <thead class="ui-widget-header">
        <tr>
            <td colspan="3" style="font-size: 1.5em;">Acciones en lote sobre <?php echo $tipo_usuario; ?></td>
        </tr>
    </thead>
    <tr class="fila_impar">
        <td>Rango de fechas:</td>
        <td>Del <input id="fecha_ini" size="10" type="text" readonly/> al <input id="fecha_fin" class="fechas_rep" size="10" type="text" readonly/></td>
    </td>
    <td>Sede:        
        <select id="idsede">
            <?php foreach ($lista_sedes as $sede) {
                ?>
                <option value="<?php echo($sede['idsede']); ?>" ><?php echo($sede['nombre']); ?></option>
                <?php
            } ?>
        </select>
    </td>
</tr>    
<tr class="fila_impar">
    <td colspan="3">Seleccione los <?php echo $tipo_usuario; ?>:</td>
</tr>    
<tr class="fila_impar">
    <td colspan="3">
        <div id="lista_usuarios" style="width: 100%;height: 245px;overflow: auto;"></div>
    </td>
</tr>    
<tr class="fila_par">
 <td>Acción:<select id="mult_accion">
    <option value="0" >Reservar</option>
    <option value="1" >Quitar reservación</option>
</select>
</td>
<td>Actividad:<select id="mult_actividad">
    <option value="0" >Desayuno</option>
    <option value="1" >Almuerzo</option>
    <option value="2" >Comida</option>
    <option value="3" >Desayuno-Almuerzo-Comida</option>
</select>
</td>
<td>Incluir fin de semana:<select id="mult_finde">
    <option value="0" >No</option>
    <option value="1" >Si</option>
</select>
</td>
</tr>    
<tr class="fila_impar">
    <td colspan="3"><button id="b_acciones_realizar">Realizar</button></td>
</tr>    
</table>

<script type="text/javascript">

    $( "#fecha_ini" ).datepicker({changeMonth: true,changeYear: true});
    $( "#fecha_ini" ).datepicker("option", "dateFormat", "dd/mm/yy");
    $( "#fecha_ini" ).val('<?php echo date('d/m/Y',$fecha_admisible) ?>');
    $( "#fecha_ini" ).datepicker("option", "minDate", '<?php echo date('d/m/Y',$fecha_admisible) ?>');

    $( "#fecha_fin" ).datepicker({changeMonth: true,changeYear: true});
    $( "#fecha_fin" ).datepicker("option", "dateFormat", "dd/mm/yy");
    $( "#fecha_fin" ).val('<?php echo date('d/m/Y',$fecha_admisible) ?>');
    $( "#fecha_fin" ).datepicker("option", "minDate", '<?php echo date('d/m/Y',$fecha_admisible) ?>');

    $( "#fecha_ini" ).change(function(){
        $( "#fecha_fin" ).datepicker("option", "minDate", $( "#fecha_ini" ).val());
        //$( "#fecha_fin" ).val($( "#fecha_ini" ).val());
    });

    $( "#fecha_fin" ).change(function(){
        $( "#fecha_ini" ).datepicker("option", "maxDate", $( "#fecha_fin" ).val());
    });

    $( "#b_acciones_realizar" ).button({
     text: true,
     icons: {
         primary: "ui-icon-check"
     }
 }).click(function(){
     realiza_acciones_multiples();
 });

 var lista_ids;
 var lista_ids_select;

 function carga_lista_usuarios(){
    lista_ids = [];
    lista_ids_select = [];

    $("#lista_usuarios").html('<div style="text-align: center;margin-top:50px;"><img width="100" src="../../../images/baile.gif"/><h3><div>Espere un momento...</div></h3></div>');

    
    myurl = '<?php echo $ruta_url; ?>index.php/admin_tickets_mult/<?php echo $listado;?>/'/* + $("#idsede").val() + '/'*/;

    var usuarios;
    
    $.ajax({
      url: myurl,
      dataType: 'json',
      async: false,
      success: function(data){
        usuarios = data;
    }
});
    var par  = false;
    cant_usuarios = usuarios.length;
    var contenido = '';
    contenido +='<table width="100%" class="ui-widget ui-widget-content" cellpadding="5" cellspacing="0"><thead class="ui-widget-header">';
    contenido +='<tr><td><input class="ck_todos" type="checkbox" id="cb_todos"/></td><td><strong>Código</strong></td><td><strong>Usuario</strong></td><td><strong>Nombre y apellidos</strong></td></tr></thead>';
    var par  = false;

    for (i = 0; i < usuarios.length;i++){
        
        lista_ids[i] = usuarios[i].<?php echo $id; ?>;
        lista_ids_select[i] = false;
        
        if (par == true){
            contenido +='<tr class ="fila_par">';
        } else {
            contenido +='<tr class ="fila_impar">';
        }
        par = !par;
        
        contenido += '<td><input class="ck_usuario" onclick="actualiza_usuario(' + usuarios[i].<?php echo $id; ?> + ');" type="checkbox" id="cb_usuario_'+ i +'"/></td>';
        
        contenido += '<td>' + usuarios[i].codigo;
        if(usuarios[i].baja == 1){
            contenido += '[B]';
        }
        contenido += '</td><td>' + usuarios[i].usuario + '</td>';
        elnombre = usuarios[i].nombres + ' ' + usuarios[i].apellidos; 
        contenido +='<td>' + elnombre.toUpperCase() + '</td>';
        contenido +='</tr>';
        
    }
    contenido +='</table>'; 
    
    $("#lista_usuarios").html(contenido);
    
    $('.ck_usuario').iCheck({
      checkboxClass: '<?php echo $iCheckStyleCB; ?>',
      radioClass: '<?php echo $iCheckStyleRB; ?>'
  });

    $('.ck_usuario').on('ifChanged', function(event){
      idcb = this.id;
      var n_user = parseInt(idcb.substring(11));
      
      if(this.checked){
          lista_ids_select[n_user] = true;
      } else {
          lista_ids_select[n_user] = false;
      }
  });


    $('.ck_todos').iCheck({
      checkboxClass: '<?php echo $iCheckStyleCB; ?>',
      radioClass: '<?php echo $iCheckStyleRB; ?>'
  });

    $('.ck_todos').on('ifChanged', function(event){
        
        if(this.checked){
            $('.ck_usuario').iCheck('check');
        } else {
            $('.ck_usuario').iCheck('uncheck');
        }    
    });


}

function realiza_acciones_multiples(){
    //obtener lista de usuarios
    
    var listaprocesar = [];
    
    for (i=0; i < lista_ids.length; i++){
        if (lista_ids_select[i] == true){
            listaprocesar.push(lista_ids[i]);
        }    
    }
    
    if(listaprocesar.length == 0){
        alert('Debe seleccionar algún usuario.');
    } else {
        
        var texto = '<h2>Seguro desea realizar las siguientes acciones en estos '+ listaprocesar.length +' <?php echo $tipo_usuario; ?>:</h2>';
        if($("#mult_accion").val() == 0){
            texto += 'Realizar reservación';
        } else {
            texto += 'Quitar reservación';

        }

        switch ($("#mult_actividad").val()) {
            case '0':
            texto += ' de desayuno';

            break;
            case '1':
            texto += ' de almuerzo';

            break;
            case '2':
            texto += ' de comida';

            break;
            case '3':
            texto += ' de desayuno-almuerzo-comida';

            break;

            default:
            break;
        }    

        texto += ' desde el ' + $("#fecha_ini").val() + ' al ' + $("#fecha_fin").val();
        
        texto += '<div id="realizando_acciones" style="text-align: center;margin-top:50px;"></div>';

        $( "#dialogo_lote" ).html(texto);

        $( "#dialogo_lote" ).dialog( "open" );
        

    }
}

$( "#dialogo_lote" ).attr('title','Acciones múltiples.');
$( "#dialogo_lote" ).dialog({
  width: 500,
  autoOpen: false,
  modal: true,
  buttons: {
     "Realizar": function() {

        $("#realizando_acciones").html('<img width="100" src="../../../images/baile.gif"/><h3><div>Espere un momento...</div><div id="progressbar_acc_mult"></div></h3>');
        

        var listaprocesar = [];

        for (i=0; i < lista_ids.length; i++){
            if (lista_ids_select[i] == true){
                                        // Realizar acciones
                                        
                                        var datos = 'id='+lista_ids[i]+'&accion='+$("#mult_accion").val()+'&actividad='+$("#mult_actividad").val();
                                        datos += '&fecha_ini='+$("#fecha_ini").val()+'&fecha_fin='+$("#fecha_fin").val()+'&finde='+$("#mult_finde").val()+'&sede_idsede='+$("#idsede").val();
                                        
                                        $.ajax({
                                         type: "POST",
                                         url: "<?php echo $ruta_url; ?>index.php/admin_tickets_mult/<?php echo $acciones; ?>/",
                                         data: datos,
                                         async: false,
                                         success: function(){
                                                //location.reload();
                                            }
                                        });
                                        
                                    } 
                                    /*var promedio = (i+1)*100/lista_ids.length;
                                    //promedio = promedio.toFixed(0);
                                    $( "#progressbar_acc_mult" ).progressbar({
                                        value: promedio
                                    });*/
                                    
                                }


                                $( this ).dialog( "close" );

                                $( "#dialogo_lote" ).html('');
                                
                            },
                            "Cerrar": function() {
                                $( this ).dialog( "close" );
                                $( "#dialogo_lote" ).html('');
                            }
                        }
                    });




carga_lista_usuarios();
</script>