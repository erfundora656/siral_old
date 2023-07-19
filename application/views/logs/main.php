<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<table width="100%" class="ui-widget ui-widget-content" cellpadding="5" cellspacing="0">
    <thead class="ui-widget-header">
        <tr>
            <td style="font-size: 1.5em;">Analizador de logs.</td>
            <td style="text-align: right;"><button id="b_basico">Básico</button>&nbsp;<button id="b_avanzado">Avanzado</button></td>
        </tr>
    </thead>
</table>
<div id="filtro"></div>
<div id="listatrazas"></div>
<script type="text/javascript">
function carga_lista_trazas(){
    
     $( "#listatrazas" ).html('<div style="text-align: center;margin-top:150px;"><img width="100" src="<?php echo $ruta_url; ?>images/baile.gif"/><h3>Espere un momento...</h3></div>');
    

     $( "#b_basico" ).button({
                                text: true,
                                icons: {
                                        primary: "ui-icon-lightbulb"
                                }
                            }).click(function(){
                                 $("#filtro").html('');
                                carga_lista_trazas();
                            });


     $( "#b_avanzado" ).button({
                                text: true,
                                icons: {
                                        primary: "ui-icon-lightbulb"
                                }
                            }).click(function(){

                                var contenido = '<table width="100%" class="ui-widget ui-widget-content" cellpadding="5" cellspacing="0"><thead class="ui-widget-header">';
                                    contenido +='<tr><td><strong>Fecha: </strong>de <input type="text" size="10" readonly id="e_f_inicio"> a <input type="text" size="10" readonly id="e_f_fin"></td><td>IP:<input type="text" size="12" id="ip_log" onchange="filtrar_log_a();" /> Usuario:<input type="text" size="12" id="usuario_log" onchange="filtrar_log_a();" /></td><td>Panel:<select id="b_a_panel"><option value="0">Principal</option><option value="1">Administración</option></select></td></tr>';
                                    contenido +='<tr><td colspan="4" style="text-align: right;"><button id="b_a_buscar_logs">Buscar</button></td>';
                                    contenido +='</tr></thead>';
                                    contenido +='</table>';

                                    $("#filtro").html(contenido);

                                     $( "#b_a_buscar_logs").button({
                                                                text: true,
                                                                icons: {
                                                                        primary: "ui-icon-search"
                                                                }
                                                            }).click(function(){
                                                                //muestra_mensaje('Funcionalidad en desarrollo.', 300,null);
                                                                
                                                                filtrar_log_a();
                                                                
                                                            });
                                                            
                                                            
                                      
                                      var lista_fechas;      

                                          myurl = '<?php echo $ruta_url; ?>index.php/logs/fecha_trazas_json/';

                                          $.ajax({
                                              url: myurl,
                                              dataType: 'json',
                                              async: false,
                                              success: function(data){
                                                lista_fechas = data;
                                              }
                                            });
                                                            
                                                            
                                     $( "#e_f_inicio" ).datepicker({changeMonth: true,changeYear: true});
                                     $( "#e_f_inicio" ).datepicker("option", "dateFormat", "dd/mm/yy");
                                     $( "#e_f_inicio" ).datepicker("option", "minDate", lista_fechas.fecha_ini);
                                     $( "#e_f_inicio" ).datepicker("option", "maxDate", lista_fechas.fecha_fin);
                                     $("#e_f_inicio").val(lista_fechas.fecha_ini);
                                     
                                     $( "#e_f_fin" ).datepicker({changeMonth: true,changeYear: true});
                                     $( "#e_f_fin" ).datepicker("option", "dateFormat", "dd/mm/yy");
                                     $( "#e_f_fin" ).datepicker("option", "minDate", lista_fechas.fecha_ini);
                                     $( "#e_f_fin" ).datepicker("option", "maxDate", lista_fechas.fecha_fin);
                                     $( "#e_f_fin" ).val(lista_fechas.fecha_fin);
                                     
                                     $( "#e_f_inicio" ).change(function(){
                                         $( "#e_f_fin" ).datepicker("option", "minDate", $( "#e_f_inicio" ).val());
                                     });
                       
                                     $( "#e_f_fin" ).change(function(){
                                         $( "#e_f_inicio" ).datepicker("option", "maxDate", $( "#e_f_fin" ).val());
                                     });
                                                            
                                  $("#listatrazas").html("");

                            });
    
    
      var lista_trazas;      
          
          myurl = '<?php echo $ruta_url; ?>index.php/logs/lista_trazas_json/';
          
          $.ajax({
              url: myurl,
              dataType: 'json',
              async: false,
              success: function(data){
                lista_trazas = data;
              }
            });

    var contenido = '<table width="100%" class="ui-widget ui-widget-content" cellpadding="5" cellspacing="0"><thead class="ui-widget-header">';
        contenido +='<tr><td><strong>Fecha</strong></td><td><strong>Panel</strong></td><td>&nbsp;</td></tr></thead>';

        var par=false;

        for (i = 0; i < lista_trazas.length;i++){
            if(par){
                contenido +='<tr class="fila_par">';
                par = false;
            } else {
                contenido +='<tr class="fila_impar">';
                par = true;
            }
            contenido +='<td align="left"><strong>' + lista_trazas[i].fecha + '</strong></td>';
            if(lista_trazas[i].admin == 1){
                contenido +='<td align="left"><strong>Administración</strong></td>';
            } else {
                contenido +='<td align="left"><strong>Principal</strong></td>';
            }
            contenido +='<td style="text-align: right;"><button id="b_log_'+i+'" onclick="muestra_traza(\''+lista_trazas[i].fecha+'\','+lista_trazas[i].admin+');">Ver</button></td>';
            contenido +='</tr>';
        }
        contenido +='</table>';
        
        $("#listatrazas").html(contenido);
        for (i = 0; i < lista_trazas.length;i++){
             $( "#b_log_"+i ).button({
                                        text: true,
                                        icons: {
                                                primary: "ui-icon-search"
                                        }
                                    });

        }
}

function muestra_traza(fecha, admin){
    

    var contenido = '<table width="100%" class="ui-widget ui-widget-content" cellpadding="5" cellspacing="0"><thead class="ui-widget-header">';
        contenido +='<tr><td><strong>Analizando logs del dia: '+fecha+'</strong></td><td>IP:<input onchange="filtrar_log(\''+fecha+'\','+admin+');" type="text" id="ip_log" /></td><td>Usuario:<input onchange="filtrar_log(\''+fecha+'\','+admin+');" type="text" id="usuario_log" /></td>';
        contenido +='<td><button id="b_buscar_log" onclick="filtrar_log(\''+fecha+'\','+admin+');">Buscar</button></td></tr></thead>';
        contenido +='</table>';

        $("#filtro").html(contenido);

         $( "#b_buscar_log").button({
                                    text: true,
                                    icons: {
                                            primary: "ui-icon-search"
                                    }
                                });
        
    carga_trazas(fecha,null, admin,'_todos','_todos');

}

function filtrar_log(fecha, admin){
    var usuario = $.trim($("#usuario_log").val());
    var ip = $.trim($("#ip_log").val());
    
    if (usuario == ''){ usuario = '_todos';}
    if (ip == ''){ ip = '_todos';}
    
    carga_trazas(fecha,null,admin,ip,usuario);
}

function filtrar_log_a(){
                                                                
        var fecha_ini = $( "#e_f_inicio" ).val();
        var fecha_fin = $( "#e_f_fin" ).val();

        var usuario = $.trim($("#usuario_log").val());
        var ip = $.trim($("#ip_log").val());

        if (usuario == ''){ usuario = '_todos';}
        if (ip == ''){ ip = '_todos';}

        var admin = $( "#b_a_panel" ).val();

        carga_trazas(fecha_ini,fecha_fin,admin,ip,usuario);
}


function carga_trazas(fecha_ini,fecha_fin,admin,ip,usuario){
    
    
         $( "#listatrazas" ).html('<div style="text-align: center;margin-top:150px;"><img width="100" src="<?php echo $ruta_url; ?>images/baile.gif"/><h3>Espere un momento...</h3></div>');
            
    
          var lista_trazas;      
          
          if(fecha_fin == null){          
            myurl = '<?php echo $ruta_url; ?>index.php/logs/analiza_traza_json/'+fecha_ini+'/'+admin+'/'+usuario+'/'+ip+'/';
          } else  {
            myurl = '<?php echo $ruta_url; ?>index.php/logs/analiza_traza_f_json/'+fecha_ini+'/'+fecha_fin+'/'+admin+'/'+usuario+'/'+ip+'/';
          }
          
          $.ajax({
              url: myurl,
              dataType: 'json',
              async: false,
              success: function(data){
                lista_trazas = data;
              }
            });

        var contenido = '<table width="100%" class="ui-widget ui-widget-content" cellpadding="5" cellspacing="0"><thead class="ui-widget-header">';
        contenido +='<tr><td><strong>Fecha</strong></td><td><strong>Hora</strong></td><td><strong>IP</strong></td><td>Usuario</td><td><strong>Acción</strong></td></tr></thead>';

        var par=false;

        for (i = 0; i < lista_trazas.length;i++){
            
            if(par){
                contenido +='<tr class="fila_par">';
                par = false;
            } else {
                contenido +='<tr class="fila_impar">';
                par = true;
            }
            
            contenido +='<td>'+lista_trazas[i].fecha+'</td>';
            contenido +='<td>'+lista_trazas[i].hora+'</td>';
            contenido +='<td>'+lista_trazas[i].IP+'</td>';
            contenido +='<td>'+lista_trazas[i].usuario+'</td>';
            contenido +='<td>'+lista_trazas[i].accion+'</td>';
            contenido +='</tr>';
        }
        contenido +='</table>';

        $("#listatrazas").html(contenido);

}

carga_lista_trazas();

</script>