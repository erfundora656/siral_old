<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

if($tipo_usuario == 'Trabajador'){    
    $estadisticas = 'estadisticas';
    $controladora = 'trabajadores';
    $idusuario = $idtrabajador;
    $calendario = 'calendario';
    $acciones = 'acciones_trabajador';
} 
?>

<div role="main" id="contenido" class="ui-content">
    <label>Reservar del</label> 
    
    <input type="date" id="fecha_ini"  value="<?php echo date('d/m/Y',$fecha_admisible) ?>"/>
    <label>al</label>
    <input type="date" id="fecha_fin" value="<?php echo date('d/m/Y',$fecha_admisible) ?>"/>
    <label>Incluir fin de semana:</label>
    <select id="mult_finde">
        <option value="0" >No</option>
        <option value="1" >Si</option>
    </select>
    <label>Acción:</label>
    <select id="mult_accion">
        <option value="0" >Reservar</option>
        <option value="1" >Quitar reservación</option>
    </select>
    <label>Actividad:</label>
    <select id="mult_actividad">
        <option value="0" >Desayuno</option>
        <option value="1" >Almuerzo</option>
        <option value="2" >Comida</option>
        <option value="3" >Desayuno-Almuerzo-Comida</option>
    </select>
    <br />
    <button id="b_acciones_realizar" class="ui-btn ui-shadow ui-corner-all ui-btn-icon-left ui-icon-check">Realizar</button>

</div>
<script type="text/javascript">




    $( "#b_acciones_realizar" ).click(function(){
        realiza_acciones_multiples();
    });

    function realiza_acciones_multiples(){
        var texto = 'Seguro desea ';
        if($("#mult_accion").val() == 0){
            texto += 'realizar reservación';
        } else {
            texto += 'quitar reservación ';

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
       if($("#mult_finde").val() == 1){
        texto += ' incluyendo fines de semanas.';
    } else {
        texto += ' excluyendo los fines de semanas.';
    }



alertify.confirm('Acciones a realizar', texto, 
    function(){ 
        var f_ini=$("#fecha_ini").val().split("-");
        f_ini=f_ini[2]+"/"+f_ini[1]+"/"+f_ini[0];
        var f_fin=$("#fecha_fin").val().split("-");
        f_fin=f_fin[2]+"/"+f_fin[1]+"/"+f_fin[0];
        
        var datos = 'id='+<?php echo $idusuario; ?>+'&accion='+$("#mult_accion").val()+'&actividad='+$("#mult_actividad").val();
        datos += '&fecha_ini='+f_ini+'&fecha_fin='+f_fin +'&finde='+$("#mult_finde").val()+'&sede_idsede='+<?php echo $sede_idsede?>;

        $.ajax({
            type: "POST",
            url: "<?php echo $ruta_url; ?>index.php/admin_tickets_mult/<?php echo $acciones; ?>/",
            data: datos,
            async: false,
            success: function(){
                                    
                    //location.reload();
                }
            });
        alertify.set('notifier','position', 'top-center');
        alertify.success('<h3>Actualizado correctamente</h3>') 
    }, function(){ alertify.error('Cancel')});
    
}
</script>