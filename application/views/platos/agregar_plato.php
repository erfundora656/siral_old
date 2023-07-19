<?php
$cantidad_base = 10;
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div>
    Importar menu de:&nbsp;<input id="fecha_importar" size="10" type="text" readonly/>&nbsp;<button id="b_importar">Importar</button>&nbsp;
    <!--Adicionamos dos inpud oculto uno para manajar el id de las sedes y el otro los turnos de alimentación-->
    <input id="sede_idsede" size="10" type="text" value="<?php echo $listasedes;?>" hidden readonly/>
    <input type="hidden" size="2" id="turno" value="<?php echo $turno;?>" />    

</div>
<script type="text/javascript">

var plato_marcado = [];

$( "#fecha_importar" ).datepicker({changeMonth: true,changeYear: true});
$( "#fecha_importar" ).datepicker("option", "dateFormat", "dd/mm/yy");
/*
Incoorporamos la sede y el turno para cuando se importen menús lo haga desde ka misma sede y el turno correspondiente.
*/
    sede_idsede = $("#sede_idsede").val();
    turno=$("#turno").val();
    $("#b_importar").button({
                            text: true,
                            icons: {
                                    primary: "ui-icon-plusthick"
                            }
                        }).click(function(){
                            var fecha_importar = $("#fecha_importar").val();
                            myURL = '<?= $ruta_url; ?>index.php/platos/lista_platos_fecha/'+fecha_importar+'/'+sede_idsede+'/'+turno+'/';
                            var platos_importar;
                            $.ajax({
                                  url: myURL,
                                  dataType: 'json',
                                  async: false,
                                  success: function(data){
                                    platos_importar = data;
                                  }
                            });
        
                            for (var i=0; i < platos_importar.length; i++){
                                $("#plato"+i).iCheck('check');
                                $("#nombreplato"+i).val(platos_importar[i]['nombreplato']);
                                $("#precioplato"+i).val(parseFloat(platos_importar[i]['precio']).toFixed(2));
                                $("#cantidadplato"+i).val(parseFloat(platos_importar[i]['cantidad']).toFixed(2));
                                $("#unidadplato"+i).val(platos_importar[i]['idtipocantidad']);

                                $("#nombreplato"+i).attr("disabled", false);  
                                $("#precioplato"+i).attr("disabled", false);  
                                $("#precioplato"+i).decimalMask('999.99');
                                $("#cantidadplato"+i).attr("disabled", false);             
                                $("#cantidadplato"+i).decimalMask('999.99');
                                $("#unidadplato"+i).attr("disabled", false);
                            }
        
                        });
    
</script>

<table>
    <tr>
        <td>&nbsp;</td>
        <td>Nombre</td>
        <td>Precio</td>
        <td>Cantidad</td>
        <td>Unidad</td>
    </tr>
    <?php
    for($i = 0;$i < $cantidad_base; $i++){
        ?>
            <tr>
                <td><input type="checkbox" class="cb_add_plato" value="<?php echo $i; ?>" id="plato<?php echo $i; ?>" /></td>
                <td><input type="text" size="35" id="nombreplato<?php echo $i; ?>" disabled /></td>
                <td>$&nbsp;<input class="precio" type="text" size="10" id="precioplato<?php echo $i; ?>" disabled /></td>
                <td><input type="text" size="10" id="cantidadplato<?php echo $i; ?>" disabled /></td>
                <td>
                    <select id="unidadplato<?php echo $i; ?>" disabled >
                    <?php
                        foreach ($tipocantidades as $cantidad) {
                            ?>
                        <option value="<?php echo $cantidad['idtipocantidad']; ?>"><?php echo $cantidad['simbolo']; ?> (<?php echo $cantidad['nombre']; ?>)</option>
                            <?php
                        }
                    ?>
                    </select>    
                </td>
            </tr>
            <script type="text/javascript">
                plato_marcado[<?php echo $i; ?>] = false;
            </script>
        <?php
    }
    ?>
    
    
</table>

            <script type='text/javascript'>
                $(".precio").change(function(){
                    var valor = this.value;
                    this.value =parseFloat(valor).toFixed(2);
                });
                
                $('.cb_add_plato').iCheck({
                      checkboxClass: '<?php echo ($iCheckStyleCB); ?>',
                      radioClass: '<?php echo ($iCheckStyleRB); ?>'
                });

                $('.cb_add_plato').on('ifChanged', function(event){
                  idcb = this.id;
                  var nplato = parseInt(idcb.substring(5));
                  if(this.checked){
                    $("#nombreplato"+nplato).attr("disabled", false);             
                    $("#precioplato"+nplato).attr("disabled", false);             
                    $("#cantidadplato"+nplato).attr("disabled", false);             
                    $("#unidadplato"+nplato).attr("disabled", false);
                    $("#precioplato"+nplato).decimalMask('999.99');
                    $("#cantidadplato"+nplato).decimalMask('999.99');
                    plato_marcado[nplato] = true;
                  } else {
                    $("#nombreplato"+nplato).attr("disabled", true);  
                    $("#precioplato"+nplato).attr("disabled", true);  
                    $("#cantidadplato"+nplato).attr("disabled", true);             
                    $("#unidadplato"+nplato).attr("disabled", true);
                    plato_marcado[nplato] = false;
                  }
                }); 
                
            </script>            
<script type="text/javascript">



    function agregar_platos(){
       
    <?php   
    for($i = 0;$i < $cantidad_base; $i++){
        ?>
    if(plato_marcado[<?php echo $i;?>]){
                nombre = jQuery.trim($("#nombreplato<?php echo $i; ?>").val());                
                precio = $("#precioplato<?php echo $i; ?>").val();                        
                cantidad = $("#cantidadplato<?php echo $i; ?>").val();             
                tipocantidad_idtipocantidad = $("#unidadplato<?php echo $i; ?>").val();
                fecha = $("#fecha_platos").val();
                /*
                Agregamos aca la sede a la que responde el plato que se está agregando
                */
                sede_idsede = $("#sede_idsede").val();
                turno=$("#turno").val();  
                
                var datos = "nombre=" + nombre + "&precio="+ precio;
                datos += "&cantidad=" + cantidad + "&tipocantidad_idtipocantidad="+ tipocantidad_idtipocantidad;
                datos += "&fecha=" + fecha;
                datos +="&sede_idsede=" + sede_idsede;
                datos +="&turno="+turno;    
                
                $.ajax({
                   type: "POST",
                   async: false,
                   url: "<?= $ruta_url; ?>index.php/platos/agregar_plato/",
                   data: datos,
                   success: function(){
                        //location.reload();
                   }
                });
                
                
    } 
    <?php
    } 
    ?>
}   

</script>