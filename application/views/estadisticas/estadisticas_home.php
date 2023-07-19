<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

if($tipo_usuario == 'Trabajador'){
 $imp_fechas = 'importe_trabajador_fechas';
 $importe = 'importe_trabajador';
 $idusuario = $idtrabajador;
 } /*else {
     $imp_fechas = 'importe_estudiante_fechas';
     $importe = 'importe_estudiante';
     $idusuario = $idestudiante;
   }*/


   ?>
   <table width="100%" class="ui-widget ui-widget-content" cellpadding="5" cellspacing="0">
    <thead class="ui-widget-header">
      <tr>
        <td style="font-size: 1.5em;">Estadísticas.</td>
        <td align="right" style="font-size: 1.2em;">
          Del <input id="fecha_ini" class="fechas_rep" size="10" type="text" readonly/> al <input id="fecha_fin" class="fechas_rep" size="10" type="text" readonly/>
          <button id="b_lista_m_mostrar">Mostrar</button>
        </td>
      </tr>
    </thead>
  </table>
  <br />
  <div id="listado_dia"></div>

  <script type="text/javascript">

  var fechas = ['<?php echo implode("','", $fechas) ?>'];

  $( "#fecha_ini" ).datepicker({changeMonth: true,changeYear: true});
  $( "#fecha_ini" ).datepicker("option", "dateFormat", "dd/mm/yy");
  $( "#fecha_ini" ).val('<?php echo $dia_i.'/'.$mes_i.'/'.$anno_i; ?>');

  $( "#fecha_fin" ).datepicker({changeMonth: true,changeYear: true});
  $( "#fecha_fin" ).datepicker("option", "dateFormat", "dd/mm/yy");
  $( "#fecha_fin" ).val('<?php echo $dia_f.'/'.$mes_f.'/'.$anno_f; ?>');

  $("#b_lista_m_mostrar").button({
    text: true,
    icons: {
      primary: "ui-icon-check"
    }
  }).click(function(){
    fecha_i = $("#fecha_ini").val();
    fecha_f = $("#fecha_fin").val();

    myURL = 'index.php/principal/estadisticas/'+fecha_i+'/'+fecha_f+'/';
                            //alert(myURL);
                            carga_contenidos(myURL);


                          });

  var cant_trabajadores = 0;
  var arrLineas = [];

  function carga_listado(){

    $( "#listado_dia" ).html('<div style="text-align: center;margin-top:150px;"><img width="100" src="images/baile.gif"/><h3><div id="estado_proceso">Espere un momento...</div></h3><div id="progressbar"></div></div>');

    $( "#progressbar" ).progressbar({
     value: 0
   });

    var contenido = '<div id="g_importe_alm" style="width:100%;height: 200px;" ></div><br />';
    contenido +='<table width="100%" class="ui-widget ui-widget-content" cellpadding="5" cellspacing="0">';
    contenido +='<tr><td width="50%">';
    contenido +='<div style="height: 580px;overflow:auto;">';
    contenido +='<table width="100%" class="ui-widget ui-widget-content" cellpadding="5" cellspacing="0"><thead class="ui-widget-header">';
    contenido +='<tr><td><strong>Fecha</strong></td>';
    contenido += '<td align="right"><strong>Desayunos</strong></td>';
    contenido += '<td align="right"><strong>Almuerzos</strong></td>';   
    contenido += '<td align="right"><strong>Comidas</strong></td>';   
    contenido += '<td align="right"><strong>Importe</strong></td></tr></thead>';   


    var par  = false;
    var imp_total_d = 0;
    var imp_total_a = 0;
    var imp_total_c = 0;

    var l_importe_d=[];
    var l_importe_a=[];
    var l_importe_c=[];


    for (i = 0; i < fechas.length;i++){
                    //Obtener importe
                    
                    myurl = '<?php echo $ruta_url; ?>index.php/estadisticas/<?php echo $imp_fechas; ?>/'+fechas[i]+'/'+fechas[i]+'/<?php echo $idusuario; ?>/';

                    var importe;

                    $.ajax({
                      url: myurl,
                      dataType: 'json',
                      async: false,
                      success: function(data){
                        importe = data;
                      }
                    });
                    var imp_d = parseFloat(importe['desayunos']);  
                    var imp_a = parseFloat(importe['almuerzos']);
                    var imp_c = parseFloat(importe['comidas']);

                    var dia_aux = fechas[i].substr(0, 2);
                    var mes_aux = fechas[i].substr(3, 2);
                    var anno_aux = fechas[i].substr(6, 4);

                    var fecha_aux = anno_aux+'-'+mes_aux+'-'+dia_aux;

                //alert(fechas[i] + ' | ' + fecha_aux);
                l_importe_d[i] = [fecha_aux+' 0:00AM',imp_d];
                l_importe_a[i] = [fecha_aux+' 0:00AM',imp_a];
                l_importe_c[i] = [fecha_aux+' 0:00AM',imp_c];
                
                if (par == true){
                  contenido +='<tr class ="fila_par">';
                } else {
                  contenido +='<tr class ="fila_impar">';
                }
                par = !par;
                contenido +='<td>' + fechas[i] + '</td>';

                contenido +='<td align="right">$&nbsp;' + parseFloat(importe['desayunos']).toFixed(2) + '</td>';
                contenido +='<td align="right">$&nbsp;' + parseFloat(importe['almuerzos']).toFixed(2) + '</td>';
                contenido +='<td align="right">$&nbsp;' + parseFloat(importe['comidas']).toFixed(2) + '</td>';
                contenido +='<td align="right"><strong>$&nbsp;' + parseFloat(importe['importe']).toFixed(2) + '</strong></td>';

                imp_total_d += parseFloat(importe['desayunos']);
                imp_total_a += parseFloat(importe['almuerzos']);
                imp_total_c += parseFloat(importe['comidas']);

                contenido +='</tr>';
                   
                    /*arrLineas[pos] = trabajadores[i].codigo+'|'+trabajadores[i].nombres + ' ' + trabajadores[i].apellidos+'|';
                    arrLineas[pos] += parseFloat(importe['almuerzos']).toFixed(2) + '|' + parseFloat(importe['comidas']).toFixed(2) + '|' + parseFloat(importe['importe']).toFixed(2);
                    pos++;
                    */
                //Calcular promedio
                var promedio = (i+1)*100/fechas.length;
                //promedio = promedio.toFixed(0);
                $( "#progressbar" ).progressbar({
                  value: promedio
                });
                $("#estado_proceso").html('Procesando '+ (i+1) +' de ' + fechas.length + ' Días.');

              }    

              var imp_total = imp_total_d + imp_total_a + imp_total_c;
              contenido +='<thead class="ui-widget-header"><tr><td><strong>Total</strong>';
              contenido +='</td><td align="right" >$&nbsp;'+imp_total_d.toFixed(2)+'</td>';
              contenido +='</td><td align="right" >$&nbsp;'+imp_total_a.toFixed(2)+'</td>';
              contenido +='</td><td align="right" >$&nbsp;'+imp_total_c.toFixed(2)+'</td>';
              contenido +='</td><td align="right" >$&nbsp;'+imp_total.toFixed(2)+'</td>';
              contenido +='</tr></thead>';

              contenido +='</table>'; 
              contenido +='</div>'; 
              contenido +='</td><td valign="top" width="50%"><div id="g_re_alm_com" style="width:100%;height: 300px;" ></div>'
              contenido +='<div id="g_gast_meses" style="width:100%;height: 300px;" ></div><div id="g_gast_meses_info" style="width:100%;font-size:1.2em;" >Seleccione una barra en la gráfica.</div><br />';
              contenido +='</td></tr>';
              contenido +='</table>'; 

    //Calcular el importe por meses
    
    var anno = fechas[0].substr(6, 4);
    
    var lista_meses_d = [];
    var lista_meses_a = [];
    var lista_meses_c = [];
    var nombre_meses = ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];
    var nombre_meses_l = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
    
    for (i=0; i<12; i++){
     var mes= i + 1;
     myurl = '<?php echo $ruta_url; ?>index.php/estadisticas/<?php echo $importe; ?>/'+mes+'/'+ anno +'/<?php echo $idusuario; ?>/';

     var importe;

     $.ajax({
      url: myurl,
      dataType: 'json',
      async: false,
      success: function(data){
        importe = data;
      }
    });
     lista_meses_d[i] = parseFloat(importe['desayunos']);
     lista_meses_a[i] = parseFloat(importe['almuerzos']);
     lista_meses_c[i] = parseFloat(importe['comidas']);
   }

   $( "#listado_dia" ).html(contenido);

   var p_g_importe_alm = $.jqplot('g_importe_alm', [l_importe_d,l_importe_a,l_importe_c], {
    title:'Gastos',
    axes:{
      xaxis:{
        renderer:$.jqplot.DateAxisRenderer,
        tickOptions:{formatString:'%#d'},
        tickInterval:'1 day'
      },
      yaxis:{
        min:0  
      }
    },
    series:[
    {label:'Desayunos'},
    {label:'Almuerzos'},
    {label:'Comidas'}
    ],
    legend: {
      show: true,
      placement: 'outsideGrid'
    }  
  });    

   datos = [['Desayunos', imp_total_d],['Almuerzos', imp_total_a],['Comidas', imp_total_c]];


   plot_g_re_alm_com = jQuery.jqplot('g_re_alm_com',
    [datos],
    {
      title: 'Porcientos',
      seriesDefaults: {
        shadow: false,
        renderer: jQuery.jqplot.PieRenderer,
        rendererOptions: {
          startAngle: 180,
          sliceMargin: 4,
          showDataLabels: true }
        },
        legend: { show:true, location: 'e' }
      }
      );

   plot_g_gast_meses = $.jqplot('g_gast_meses', [lista_meses_d,lista_meses_a, lista_meses_c], {
    title:'Gastos por meses ('+anno+')',
    seriesDefaults: {
      renderer:$.jqplot.BarRenderer,
      rendererOptions:{
       barPadding:0, 
       barMargin:0, 
       barDirection: 'vertical'
     }, 
     pointLabels: { show: true }
   },
   axes: {
    xaxis: {
      renderer: $.jqplot.CategoryAxisRenderer,
      ticks: nombre_meses
    },
    yaxis: {
      tickOptions:{prefix: '$'}
    }
  }
});

   var leyenda = ['Desayunos','Almuerzos','Comidas'];

   $('#g_gast_meses').bind('jqplotDataHighlight', 
    function (ev, seriesIndex, pointIndex, data) {

      var str_Data = ''+data;
      var arrData = str_Data.split(',');
      var importe = parseFloat(arrData[1]);
            //alert (data);
            $('#g_gast_meses_info').html('Mes: '+ nombre_meses_l[pointIndex] + ', Tipo: ' + leyenda[seriesIndex]+', Importe: $ '+importe.toFixed(2));
          }
          );

   $('#g_gast_meses').bind('jqplotDataUnhighlight', 
    function (ev) {
      $('#g_gast_meses_info').html('Seleccione una barra en la gráfica.');
    }
    );        

 }

 $( "#dialogo_imprimir" ).attr('title','Imprimir');
 $( "#dialogo_imprimir" ).dialog({
  width: 1000,
  height: 600,
  autoOpen: false,
  modal: true,
  buttons: {
   "Imprimir": function() {
    $("#texto_imprimir").printArea();
  },
  "Cerrar": function() {
    $( this ).dialog( "close" );
    $( "#dialogo_imprimir" ).html('');
  }
}
});


 carga_listado();

 </script>