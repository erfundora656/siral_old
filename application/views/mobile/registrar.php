
<div id="d_registrar" class="form_registrar"  title="Acceder">
    
        <!--<a data-iconpos="notext" data-icon="bars" href="" class="ui-link ui-btn-left ui-btn ui-icon-home ui-btn-icon-notext ui-shadow ui-corner-all" data-role="button" role="button">Inicio</a>-->
        
  <input type="button" value="Inicio" id="b_cancelar">

        

    <form id="fautenticar" style="padding: 5px;" method="post" acction="">
        <!--<label style="font-weight: bold;text-align: center;"><?php echo($nombre_entidad);?></label>-->        
        <label><h3>Complete los datos del registro:</h3></label>    
        <label>Nombre:</label>
        <?php echo ("<input class='form_registrar' id='nombre' type='email' value='".$nombre."' disabled='true'/>")?>
        <label>Apellidos:</label>
        <?php echo ("<input class='form_registrar' id='apellidos'  type='email' value='".$apellidos."' disabled='true'/>")?>
        <label>Usuario:</label>
        <?php echo ("<input class='form_registrar' id='usuario'  type='text' value='".$usuario."' disabled='true'/>")?>
        <label>Carné de Id:</label>
        <input class="form_registrar" id="ci" type="text" />
        <button id="b_verificar" class="ui-btn ui-shadow ui-corner-all ui-btn-icon-left ui-icon-check">Verificar</button>
            <?php echo ("<input id='idsede' type='hidden' value='".$idsede."'/>")?> 
            <?php echo ("<input id='email' type='hidden' value='".$email."' />")?>
        </form>
    </div>

    <script type="text/javascript">
        $("#b_verificar").click(function(){
           

           validar_registro();

        });

        
        $( "#b_cancelar" ).click(function(){
            cancelar();
        });

        $("#b_verificar").keypress(function(e){
            if (e.which == 13){
                validar_registro();
            }
        }); 



        function validar_registro(){
            var entero=parseInt($("#ci").val());
            if($("#ci").val().length == 11 && Number.isInteger(entero)){

                $(".form_registrar").attr("disabled", true);
                myURL = "<?php echo $ruta_url ?>index.php/seguridad/validar_registro/";


                $.post(myURL, {nombre: $("#nombre").val(), apellidos:$("#apellidos").val(), usuario:$("#usuario").val(),idsede:$("#idsede").val(),ci: $("#ci").val(),email: $("#email").val()}, 
                    function (data) {
                        $( "#d_autenticar" ).dialog( "close" );
                        var accion = {
                            accion: function(){
                                location.reload(); 
                            }
                        };
                        alertify.set('notifier','position', 'top-center');
                        alertify.success("<h3>"+data+"</h3>");
                    });

                $("#ci").val("");
            }else{
                $("#ci").val("");
                alertify.error("<h3>El CI no es correcto.</h3>");
            }
        }

        function cancelar(){
            $.post("<?php echo $ruta_url ?>index.php/seguridad/cerrar_sesion_trab/", {}, 
                function (data) {
                    location.reload();
                });
        }

        $(".form_autenticar").attr("disabled", true);    
    </script>