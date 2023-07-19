
<div id="d_autenticar" class="form_autenticar"  title="Acceder">
    <form id="fautenticar" style="padding: 5px;" method="post" acction="">
        <label style="font-weight: bold;text-align: center;"><?php echo($nombre_entidad);?></label>

       <div align="center"><img src="<?php echo $ruta_url; ?>images/mobile/reservation.png" class="ui-li-thumb"></div>    
        <label>Comedor a reservar:</label>
        <select id="idsede">
            <option selected value="">Seleccionar...</option>
            <?php
            foreach($listasedes as $sede) {

             echo('<option value="'.$sede['idsede'].'">'.$sede['nombre'].'</option>');
         }
         ?>
     </select>
     <label>Usuario:</label>
     <input id="usuario" class="form_autenticar" type="text" />
     <label>Contraseña:</label>
     <input id="contrasenna" class="form_autenticar" type="password" />
     <button id="b_autenticar" class="ui-btn ui-shadow ui-corner-all ui-btn-icon-left ui-icon-check" >Acceder</button>
     <br>
     <!--<strong><p style="padding-top: 25px; color:#008000 ; font-size: 15px;"> Restablecida la reserva en línea.</p></strong>
        <strong><p style="padding-top: 10px; color:#9c0731 ;"> ¡ Nota importante ! Los trabajadores que se encuentren de Vacaciones no deben utilizar el sistema de reservación. Gracias Dirección de Alimentación</p></strong>-->
 </form>

</div>

<script type="text/javascript">
    var tipo_usuario = 'T';

    $("#b_autenticar").click(function(){
        autenticar();
    });

    $(".form_autenticar").keypress(function(e){
        if (e.which == 13){
            autenticar();
        }
    });     

    function autenticar(){  

        if( $("#idsede").val() != "" && $("#usuario").val() != "" && $("#contrasenna").val() != ""){  
            $(".form_autenticar").attr("disabled", true);



            myURL = "<?php echo $ruta_url ?>index.php/seguridad/autenticar_trab/";

            $.post(myURL, { usuario: $("#usuario").val(), contrasenna: $("#contrasenna").val(), idsede: $("#idsede").val()}, 
                function (data) {
                    var accion = {
                        accion: function(){                    
                            //location.reload();
                        }
                    };
                    alertify.set('notifier','position', 'top-center');
                    alertify.success("<h3>"+data+"</h3>");                    
                    location.reload();                   
                });
        }else{    
            alertify.set('notifier','position', 'top-center');
            alertify.error("<h3>Debe completar los datos del formulario.</h3>");
        //location.reload();


    }
}

$(".form_autenticar").attr("disabled", false);
</script>
