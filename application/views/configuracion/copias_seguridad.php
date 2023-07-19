
<table width="100%" class="ui-widget ui-widget-content" cellpadding="5" cellspacing="0">
    <thead class="ui-widget-header">
        <tr>
            <td style="font-size: 1.5em;">Copias de seguridad creadas:</td>
            <td style="text-align: right;font-size: 1.2em;">
                <button id="crear_c_seguridad">Crear copia de seguridad</button>
            </td>
        </tr>
    </thead>
</table>
<br />
<div id="list_c_seguridad" ></div>

<script type="text/javascript">
    
    function actualiza_lista_cs(){
        myurl = '<?php echo $ruta_url;?>index.php/configuracion/lista_copia_serguridad/';
        $.ajax({
           type: "POST",
           async: false,
           url: myurl,
           success: function(data){
               $("#list_c_seguridad").html(data);
           }
            
        });
    }
    
    function eliminar_cs(archivo){
        if(confirm('Seguro desea eliminar esta copia de seguridad?')){

            myurl = '<?php echo $ruta_url;?>index.php/configuracion/eliminar_copia_serguridad/';
            datos ='archivo='+archivo;

            $.ajax({
               type: "POST",
               async: false,
               data: datos,
               url: myurl,
               success: function(data){
                   actualiza_lista_cs();
               }

            });
        }
    }
    
    $('#crear_c_seguridad').button({
                            text: true,
                            icons: {
                                    primary: "ui-icon-copy"
                            }
                        }).click(function(){
        myurl = '<?php echo $ruta_url;?>index.php/configuracion/salva_BD/';
        $.ajax({
           type: "POST",
           async: false,
           url: myurl,
           success: function(data){
                //window.open(jQuery.trim(data));
                actualiza_lista_cs();
           }
            
        });
    });
    
actualiza_lista_cs();    
</script>