<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$reserva_dia = 'trabajador_reserva_turno_dia';
$buscar_trabajador='buscar_trabajador_turno';



//$("#unidadplato"+i).attr("disabled", false);
?>
<table width="100%" class="ui-widget ui-widget-content" cellpadding="5" cellspacing="0">
    <thead class="ui-widget-header">
        <tr>
            <?php
                
                if(date("H:i:s") < "10:00:00"){
                    echo'<td style="font-size: 1.5em;" id="titulo">Chequear desayuno</td>';
                }else if(date("H:i:s") > "10:00:00" && date("H:i:s") < "15:00:00"){
                    echo'<td style="font-size: 1.5em;" id="titulo">Chequear almuerzo</td>';
                }else if(date("H:i:s") > "15:00:00"){
                    echo'<td style="font-size: 1.5em;" id="titulo">Chequear comida</td>';
                }
                ?>
                    
            <td style="text-align: right;font-size: 1.0em;">Fecha:&nbsp;<input id="fecha_platos" size="8" type="text" readonly/></td>
            <td style="text-align: right;font-size: 1.0em;">Turno:&nbsp;<select id="turno">
                <?php
                
                if(date("H:i:s") < "10:00:00"){
                    echo'<option value="1" selected>Desayuno</option>
                    <option value="2">Almuerzo</option>
                    <option value="3">Comida</option>';
                }else if(date("H:i:s") > "10:00:00" && date("H:i:s") < "15:00:00"){
                    echo'<option value="1">Desayuno</option>
                    <option value="2" selected>Almuerzo</option>
                    <option value="3">Comida</option>';
                }else if(date("H:i:s") > "15:00:00"){
                    echo'<option value="1">Desayuno</option>
                    <option value="2" >Almuerzo</option>
                    <option value="3" selected>Comida</option>';
                }
                ?>
                               
            </select></td>                    
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
            </select></td>
        </tr>
    </thead>
</table>
</br>
<table width="100%" class="ui-widget ui-widget-content" cellpadding="5" cellspacing="0">
    <thead class="ui-widget-header">
        <tr>
            <td width="40%" align="center"><input type="radio" class="tipo_usuario" id="rb_E"  name="tipo_usuario" value="E" />&nbsp;<strong>Escanear código QR del Trabajador</strong></td>
            <td align="center"><input type="radio" class="tipo_usuario" id="rb_D" checked name="tipo_usuario" value="D" />&nbsp;<strong>Introducir código del Trabajador</strong></td>
        </tr>
    </thead>
    <table width="100%" border="0">
        <tr>
            <td align="center"><canvas></canvas></td>
            <td align="center">
               <table  class="ui-widget ui-widget-content" cellpadding="5" cellspacing="0">
                <tr>
                    <td><div align="center"><img width="60" id="imgUser" src="../../../images/not_check.png"/></div></td>
                    <td><strong>Código o CI:</strong></td><td align="left"><input id="codigo"   type="text" size="15"/>
                        <button id="b_buscar_trabajador" style="visibility:visible;">Buscar</button></td>
                    </tr>
                    <tr></tr>
                </table>
                <br>
                <table  id="tabla_ticket" class="ui-widget ui-widget-content" cellpadding="5" cellspacing="0" style="border-color: black;"></table> 
            </td>
        </tr>
    </table>
    <table width="100%" class="ui-widget ui-widget-content" cellpadding="5" cellspacing="0">
        <thead class="ui-widget-header">
            <tr><td style="font-size: 1.0em;" align="center" id="footer_messge"><strong></strong></td>                
            </tr>
        </thead>
    </table>
</table>    
<div align="center" style="margin-top: 20px;"><span style="font-size: 3.0em;"  id="footer_messg"><strong>Próximo Trabajador</strong></span></div>


<script type="text/javascript">
    
    function fnBarcodeScanned(jsonObject) {
  //console.log("Barcode Scanned:{" + JSON.stringify(jsonObject) + "}");
  document.getElementById('codigo').value = jsonObject.data;
  
}



    var trabajador_encontrado;
    var platos_reservados;
    var controlWebCam=new WebCodeCamJS("canvas");

    <?php
    if($rol >= 2 ){
        ?>

        $( "#fecha_platos" ).datepicker({changeMonth: true,changeYear: true});
        $( "#fecha_platos" ).datepicker("option", "dateFormat", "dd/mm/yy");
    <?php }?>

    /*No debo cargar todo el listado, sino debo verificar cada trabajador*/
    function carga_reservacion(){
        var fecha_ = $("#fecha_platos").val();
        var sede=$("#sede_idsede").val();
        var t=$("#turno").val();
        var codigo=$("#codigo").val();


        if(codigo!=""){
            var myurl;

            switch(t){
                case '1':            
                myurl = '<?php echo $ruta_url; ?>index.php/estadisticas/<?php echo $buscar_trabajador; ?>/'+fecha_+'/'+sede+'/1/'+codigo+'/';
                break;
                case '2':            
                myurl = '<?php echo $ruta_url; ?>index.php/estadisticas/<?php echo $buscar_trabajador; ?>/'+fecha_+'/'+sede+'/2/'+codigo+'/';
                break;
                case '3':            
                myurl = '<?php echo $ruta_url; ?>index.php/estadisticas/<?php echo $buscar_trabajador; ?>/'+fecha_+'/'+sede+'/3/'+codigo+'/';
                break;
                default:    
                break;
            }

            var usuario;

            $.ajax({
              url: myurl,
              dataType: 'json',
              async: false,
              success: function(data){
                usuario = data;
            }
        });

            trabajador_encontrado=usuario;
        }else{            
            muestra_mensaje("<h2>Debe completar el campo del código.</h2>", 400,null);
        }

    }


    function confirmar(){
        conf=1;
        codigo = trabajador_encontrado[0].idtrabajador;//$("#codigo").val();
        fecha = $("#fecha_platos").val();
        sede=$("#sede_idsede").val();
        turno=$("#turno").val();


        var datos = "codigo_trabajador=" + codigo;                
        datos += "&fecha=" + fecha;
        datos += "&sede_idsede=" + sede;
        datos += "&turno=" + turno;
        datos += "&conf=" + conf;

        $.ajax({
           type: "POST",
           async: false,
           url: "<?= $ruta_url; ?>index.php/estadisticas/confirmar_chequeo/",
           data: datos,
           success: function(){
                        //location.reload();
                    }
                });

    }

    function reserva_trabajador(){
        var fecha_ = $("#fecha_platos").val();
        var idtrabajador = trabajador_encontrado[0].idtrabajador;

        var sede=$("#sede_idsede").val();
        var turno=$("#turno").val();
        myURL = '<?php echo $ruta_url; ?>index.php/admin_tickets/<?php echo $reserva_dia; ?>/'+idtrabajador+'/'+fecha_+'/'+sede+'/'+turno+'/';

        var reserva;
        $.ajax({
          url: myURL,
          dataType: 'json',
          async: false,
          success: function(data){
            reserva = data; 

        }
    }); 


        var ticket='<tr><td align="center" style="border-bottom-style: double;"><strong><label>Ticket - SIRAL</label></strong></td></tr>';
        ticket+='<tr><td><label>Trabajador:</label>&nbsp;<label id="nombres"></label></td></tr>';
        ticket+='<tr><td><strong><strong><label>Menú</label></strong></td></tr> ';
        var platos_selecc_id=[];

        for(let id of reserva['platos_selecc']){
            platos_selecc_id.push(id.idplato);
        }
        
        
        if(reserva['platos_dia'].length > 0){  
            for (var i = 0; i < reserva['platos_dia'].length; i++) {
                
                if($.inArray(reserva['platos_dia'][i].idplato,platos_selecc_id)!= -1){
                    ticket+='<tr><td style="background-color: bisque;"><label>'+(i+1)+'-</label>&nbsp;'+ reserva['platos_dia'][i].nombreplato+'<label style="float: inline-end;">$'+ new Number(reserva['platos_dia'][i].precio).toFixed(2) +'</label></td></tr>';   
                    
                }else{
                    ticket+='<tr><td><label>'+(i+1)+'-</label>&nbsp;'+ reserva['platos_dia'][i].nombreplato+'<label style="float: inline-end;">$'+ new Number(reserva['platos_dia'][i].precio).toFixed(2) +'</label></td></tr>'; 
                }
            }        
        }
        ticket+='<tr><td><strong><strong><label>Total</label><label style="float: inline-end;">'+reserva['importe']+'</label></strong></td></tr>';


        $("#tabla_ticket").html(ticket);
    } 


    function buscar(){  

        carga_reservacion();       

        if(trabajador_encontrado[0]!=undefined){
            if(trabajador_encontrado[0]['chequeo']==0){
                reserva_trabajador();
                document.getElementById("imgUser").src="../../../images/checked.png";
                //$("#usuario").val(trabajador_encontrado[0].usuario);  
                $("#nombres").html(trabajador_encontrado[0].nombres+" "+trabajador_encontrado[0].apellidos);
                //val(trabajador_encontrado[0].nombres);
                //$("#apellidos").val(trabajador_encontrado[0].apellidos);
                var mensaje=document.getElementById("footer_messg");
                mensaje.innerHTML="Próximo Trabajador";    
                confirmar();                    
            }else{
                document.getElementById("imgUser").src="../../../images/not_check.png";
                //$("#usuario").val(trabajador_encontrado[0].usuario);  
                $("#nombres").html(trabajador_encontrado[0].nombres+" "+trabajador_encontrado[0].apellidos);
                //$("#apellidos").val(trabajador_encontrado[0].apellidos);
                var mensaje=document.getElementById("footer_messg");
                mensaje.innerHTML="Ya el trabajador está chequeado";
            }                
        }else{
            document.getElementById("imgUser").src="../../../images/not_check.png";
            var turno=$("#turno").val();
            var mensaje=document.getElementById("footer_messg");
            switch(turno){
                case '1':
                mensaje.innerHTML="No tiene reserva para el desayuno de hoy..";
                break;
                case '2':
                mensaje.innerHTML="No tiene reserva para el almuerzo de hoy..";
                break;
                case '3':
                mensaje.innerHTML="No tiene reserva para el comida de hoy..";
                break;
                default:
                break;
            }

            $("#codigo").val("");
            //$("#usuario").val("");  
            $("#nombres").html("");
            //$("#apellidos").val("");
        }     

    }


    $( "#b_buscar_trabajador" ).button({
        text: false,
        icons: {
            primary: "ui-icon-check"
        }
    }).click(function(){
        buscar();        
    });


    
    $( "#codigo" ).keypress(function(e){
        if (e.which == 13){
            buscar(); 
        }
    });    


    $("#turno").change(function(){        
        var titulo=document.getElementById("titulo");
        var t=$("#turno").val();
        switch(t){
            case '1':            
            titulo.innerHTML="Chequear desayuno";
            break;
            case '2':            
            titulo.innerHTML="Chequear almuerzo";
            break;
            case '3':            
            titulo.innerHTML="Chequear comida";
            break;
            default:    
            break;
        }

    });     

    $("#rb_E").click(function(){

        if(this.checked){       
           $("#codigo").attr("disabled", true);

           document.getElementById("b_buscar_trabajador").style.visibility='hidden';
           var mensaje=document.getElementById("footer_messg");
           mensaje.innerHTML="Escaneando..";

       // var txt = "innerText" in HTMLElement.prototype ? "innerText" : "textContent";
       var txt = "value" in HTMLElement.prototype ? "value" : "value";

       var arg = {
        resultFunction: function(result) {          
         var inputCodigo = document.getElementById("codigo");
         var codigo_str=result.code;
         var codigo="";

         if(codigo_str != ""){
            var limpieza_codigo=codigo_str.split(":");           
            if(limpieza_codigo.length == 1){
                codigo=limpieza_codigo[0];
            }else if(limpieza_codigo.length == 5){
                codigo=limpieza_codigo[3].substr(0,11);
            }
        }

        inputCodigo[txt] = codigo;              
        buscar();
    }

}
controlWebCam.init(arg).play();
} 
});

    $("#rb_D").click(function(){

      if(this.checked){            
        $("#codigo").attr("disabled", false);
        document.getElementById("b_buscar_trabajador").style.visibility='visible';
        var mensaje=document.getElementById("footer_messg");
        mensaje.innerHTML="Esperando código..";        
        controlWebCam.stop();
    }
});  

// Detectar la fecha y ponerla en el datepicker

$("#fecha_platos").val('<?php echo date('d/m/Y')?>');   
</script>

