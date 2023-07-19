<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$proximo_mes = $mes + 1;
$anterior_mes = $mes - 1;
if ($proximo_mes > 12){
  $proximo_anno = $anno + 1;
  $proximo_mes = 1;
} else {
  $proximo_anno = $anno;
}    
if ($anterior_mes < 1){
  $anterior_anno = $anno - 1;
  $anterior_mes = 12;
} else {
  $anterior_anno = $anno;
}

?>
<table width="100%" class="ui-widget ui-widget-content" cellpadding="5" cellspacing="0">
  <thead class="ui-widget-header">
    <tr>
      <td style="font-size: 1.5em;">Balance mensual</td>
      <td style="text-align: right;font-size: 1.0em;">
        <button id="b_lista_m_print">Imprimir</button>
      </td>
      <td align="center" style="font-size: 1.0em;">
        <button id="prev" >Anterior</button>
        &nbsp;&nbsp;
        <div style="display:inline-block;" id="fechas">
          <?php echo ($nombre_mes.' de '.$anno); ?>
        </div>
        &nbsp;&nbsp;
        <button id="next" >Próximo</button>
      </td>
      <td style="text-align: right;font-size: 1.0em;">Sede:&nbsp;
        <select id="sede_idsede">
          <option selected value="-1">Seleccionar...</option>
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
<div id="balance" style="background-color: #FFF;">
</br>
  <div id="datos_sede" style="text-align: center;font-size: 1.4em;" width="100%">
    <span>Sede:&nbsp;<?php echo $sede_pertenece;?></span>
  </div>
</br>
<div id="estdist_i_c">    
  <table width="100%" class="ui-widget ui-widget-content" cellpadding="5" cellspacing="0">           
    <tr>
      <td style="vertical-align: top;" width="50%">
        <table width="100%" class="ui-widget ui-widget-content" cellpadding="5" cellspacing="0">
          <thead class="ui-widget-header">
            <tr>
              <td colspan="2">Ingresos:</td>
            </tr>
          </thead>
          <tr>
            <td>Desayunos:</td>
            <td style="text-align: right;"><?php echo '$ '.number_format($total_mes1,2); ?></td>
          </tr>
          <tr>
            <td>Almuerzos:</td>
            <td style="text-align: right;"><?php echo '$ '.number_format($total_mes2,2); ?></td>
          </tr>
          <tr>
            <td>Comidas:</td>
            <td style="text-align: right;"><?php echo '$ '.number_format($total_mes3,2); ?></td>
          </tr>
          <tr>
            <td>Total:</td>
            <td style="text-align: right;"><?php echo '$ '.number_format($total_mes1 + $total_mes2 + $total_mes3,2); ?></td>
          </tr>
        </table>       
      </td>
      <td style="vertical-align: top;" width="50%">
        <table width="100%" class="ui-widget ui-widget-content" cellpadding="5" cellspacing="0">
         <thead class="ui-widget-header">
          <tr>
            <td colspan="2">Comenzales:</td>
          </tr>
        </thead>
        <tr>
          <td>Desayunos:</td>
          <td style="text-align: right;"><?php echo $total_comenzales1; ?></td>
        </tr>
        <tr>
          <td>Almuerzos:</td>
          <td style="text-align: right;"><?php echo $total_comenzales2; ?></td>
        </tr>
        <tr>
          <td>Comidas:</td>
          <td style="text-align: right;"><?php echo $total_comenzales3; ?></td>
        </tr>
        <tr>
          <td>Total:</td>
          <td style="text-align: right;"><?php echo $total_comenzales1 + $total_comenzales2 + $total_comenzales3; ?></td>
        </tr>
      </table>       
    </td>
  </tr>    
</table>
</div>
<h2>Gráficas:</h2>    
<div id="g_importe_alm" style="width:770px;height: 200px;" ></div><br />
<div id="g_comenzales_alm" style="width:770px;height: 200px;" ></div>
<div id="p_ofertados">    
  <h2>Platos más ofertados:</h2>
  <table width="100%" class="ui-widget ui-widget-content" cellpadding="5" cellspacing="0">
    <thead class="ui-widget-header">
      <tr>
        <td width="33%">Desayunos:</td>
        <td width="33%">Almuerzos:</td>
        <td width="33%">Comidas:</td>
      </tr>
    </thead>
    <tr>
      <td style="vertical-align: top;">
        <table width="100%" class="ui-widget ui-widget-content" cellpadding="5" cellspacing="0">
          <thead class="ui-widget-header">
            <tr>
              <td>Nombre</td>
              <td style="text-align: right;">Cantidad</td>
            </tr>
          </thead>
          <?php
          for ($i=0; $i < count($listas_platos_1) && $i < 10; $i++){
            ?>
            <tr>
              <td><?php echo $listas_platos_1[$i]['nombre'] ?></td>
              <td style="text-align: right;"><?php echo $listas_platos_1[$i]['cantidad'] ?></td>
            </tr>
            <?php
          }
          ?>
        </table>
      </td>
      <td style="vertical-align: top;">
        <table width="100%" class="ui-widget ui-widget-content" cellpadding="5" cellspacing="0">
          <thead class="ui-widget-header">
            <tr>
              <td>Nombre</td>
              <td style="text-align: right;">Cantidad</td>
            </tr>
          </thead>
          <?php
          for ($i=0; $i < count($listas_platos_2) && $i < 10; $i++){
            ?>
            <tr>
              <td><?php echo $listas_platos_2[$i]['nombre'] ?></td>
              <td style="text-align: right;"><?php echo $listas_platos_2[$i]['cantidad'] ?></td>
            </tr>
            <?php
          }
          ?>
        </table>
      </td>
      <td style="vertical-align: top;">
        <table width="100%" class="ui-widget ui-widget-content" cellpadding="5" cellspacing="0">
          <thead class="ui-widget-header">
            <tr>
              <td>Nombre</td>
              <td style="text-align: right;">Cantidad</td>
            </tr>
          </thead>
          <?php
          for ($i=0; $i < count($listas_platos_3) && $i < 10; $i++){
            ?>
            <tr>
              <td><?php echo $listas_platos_3[$i]['nombre'] ?></td>
              <td style="text-align: right;"><?php echo $listas_platos_3[$i]['cantidad'] ?></td>
            </tr>
            <?php
          }
          ?>
        </table>

      </td>
    </tr>
    
  </table>
</div>
</div>

<script type="text/javascript">

var cant_trabajadores = 0;
var arrLineas = [];

$( "#sede_idsede" ).change(function(){
  
  myURL = 'index.php/estadisticas/balance/<?php echo $mes ?>/<?php echo $anno ?>/'+$( "#sede_idsede" ).val()+'/';

  carga_contenidos(myURL);
});
$( "#prev" ).button({
  text: false,
  icons: {
    primary: "ui-icon-seek-prev"
  }
}).click(function(){
  myURL = 'index.php/estadisticas/balance/<?php echo $anterior_mes ?>/<?php echo $anterior_anno ?>/'+$( "#sede_idsede" ).val()+'/';

  carga_contenidos(myURL);
});
$( "#next" ).button({
  text: false,
  icons: {
    primary: "ui-icon-seek-next"
  }
}).click(function(){
  myURL = 'index.php/estadisticas/balance/<?php echo $proximo_mes ?>/<?php echo $proximo_anno ?>/'+$( "#sede_idsede" ).val()+'/';
  carga_contenidos(myURL);
});

/*
 $( "#b_lista_m_pdf" ).button({
                            text: true,
                            icons: {
                                    primary: "ui-icon-disk"
                            }
                        }).click(function(){
                                fecha = $("#fecha_platos").val();
                                $( "#dialogo_mensaje" ).html('<h2>Exportar a PDF el Consumo mensual de alimentación de  <?php echo ($nombre_mes.' de '.$anno); ?>.</h2><div id="notif_exp"></div>');
                                $( "#dialogo_mensaje" ).dialog( "open" );
                            
                        });
*/
$( "#b_lista_m_print" ).button({
  text: true,
  icons: {
    primary: "ui-icon-print"
  }
}).click(function(){
                            //$("#listado_dia").printArea();
                            var contenido = '<div id="texto_imprimir">'
                            contenido += '<p><span style="font-size: 1.5em;"><?php echo $nombre_entidad;?></span><br />';
                            contenido += '<span style="font-size: 1.2em;"><?php echo $organismo;?></span></p>';
                            contenido += '<p><span style="font-size: 1.3em;">Balance mensual (<?php echo ($nombre_mes.' de '.$anno); ?>)</span></p>';
                            contenido += '<p><span style="font-size: 1.3em;">Sede: (<?php echo $sede_pertenece; ?>)</span></p>';
                            contenido += $( "#estdist_i_c" ).html();
                            contenido += '<h2>Gráficas:</h2><div id="g_importe_alm_p" style="width:770px;height: 200px;" ></div><div id="g_comenzales_alm_p" style="width:770px;height: 200px;" ></div>';
                            contenido += $( "#p_ofertados" ).html();
                            contenido += '<p style="text-align:center;margin-top:50px;">__________________________________<br /><?php echo $nombre_firma;?><br />';
                            contenido += '<?php echo $cargo_firma;?></p>';
                            contenido += '</div>';
                            $( "#dialogo_imprimir" ).html(contenido);
                            $( "#dialogo_imprimir" ).dialog( "open" );
                            balance_p();
                          });

function balance(){
//  var line1=[['2008-06-30 8:00AM',4], ['2008-7-14 8:00AM',6.5], ['2008-7-28 8:00AM',5.7], ['2008-8-11 8:00AM',9], ['2008-8-25 8:00AM',8.2]];
//Importe almuerzos
var l_importe_d=[<?php echo(implode(',', $gastos_dias1)); ?>];
var l_importe_a=[<?php echo(implode(',', $gastos_dias2)); ?>];
var l_importe_c=[<?php echo(implode(',', $gastos_dias3)); ?>];
var p_g_importe_alm = $.jqplot('g_importe_alm', [l_importe_d,l_importe_a,l_importe_c], {
  title:'Importe durante el mes',
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

// Trabajadores
var l_comenzales_d=[<?php echo(implode(',', $comenzales_dias1)); ?>];
var l_comenzales_a=[<?php echo(implode(',', $comenzales_dias2)); ?>];
var l_comenzales_c=[<?php echo(implode(',', $comenzales_dias3)); ?>];
var l_dirigidos_d=[<?php echo(implode(',', $dirigidos_dias2)); ?>];
var l_dirigidos_a=[<?php echo(implode(',', $dirigidos_dias2)); ?>];
var l_dirigidos_c=[<?php echo(implode(',', $dirigidos_dias3)); ?>];
var p_g_comenzales_alm = $.jqplot('g_comenzales_alm', [l_comenzales_d,l_comenzales_a,l_comenzales_c,l_dirigidos_d,l_dirigidos_a,l_dirigidos_c], {
  title:'Trabajajadores durante el mes',
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
  {label:'Comidas'},
  {label:'Dirigidos Desayuno'},
  {label:'Dirigidos Almuerzo'},
  {label:'Dirigidos Comida'}
  ],
  legend: {
    show: true,
    placement: 'outsideGrid'
  }  
});    

}

function balance_p(){
//  var line1=[['2008-06-30 8:00AM',4], ['2008-7-14 8:00AM',6.5], ['2008-7-28 8:00AM',5.7], ['2008-8-11 8:00AM',9], ['2008-8-25 8:00AM',8.2]];
//Importe almuerzos

var l_importe_d=[<?php echo(implode(',', $gastos_dias1)); ?>];
var l_importe_a=[<?php echo(implode(',', $gastos_dias2)); ?>];
var l_importe_c=[<?php echo(implode(',', $gastos_dias3)); ?>];
var p_g_importe_alm = $.jqplot('g_importe_alm_p', [l_importe_d,l_importe_a,l_importe_c], {
  title:'Importe durante el mes',
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

// Comenzales almuerzos

var l_comenzales_d=[<?php echo(implode(',', $comenzales_dias1)); ?>];
var l_comenzales_a=[<?php echo(implode(',', $comenzales_dias2)); ?>];
var l_comenzales_c=[<?php echo(implode(',', $comenzales_dias3)); ?>];
var l_dirigidos_d=[<?php echo(implode(',', $dirigidos_dias2)); ?>];
var l_dirigidos_a=[<?php echo(implode(',', $dirigidos_dias2)); ?>];
var l_dirigidos_c=[<?php echo(implode(',', $dirigidos_dias3)); ?>];
var p_g_comenzales_alm = $.jqplot('g_comenzales_alm_p', [l_comenzales_d,l_comenzales_a,l_comenzales_c,l_dirigidos_d,l_dirigidos_a,l_dirigidos_c], {
  title:'Comenzales durante el mes',
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
  {label:'Comidas'},
  {label:'Dirigidos Desayuno'},
  {label:'Dirigidos Almuerzo'},
  {label:'Dirigidos Comida'}
  ],
  legend: {
    show: true,
    placement: 'outsideGrid'
  }  
}); 
}

function balance_pdf(){
  myurl = '<?php echo $ruta_url; ?>index.php/estadisticas/balance_pdf/<?php echo $mes ?>/<?php echo $anno ?>/';

  var datos = "cantidad=" + cant_trabajadores;

  for(i=0;i < cant_trabajadores; i++){
    datos += '&linea'+i+'='+arrLineas[i];
  }
    //alert(datos);
    var contenido = $.ajax({
      type: "POST",
      async: false,
      data: datos,
      url: myurl
    }).responseText;
    return contenido;
    
  }

  $( "#dialogo_mensaje" ).attr('title','Exportar a PDF');
  $( "#dialogo_mensaje" ).dialog({
    width: 400,
    height: 320,
    autoOpen: false,
    modal: true,
    buttons: {
      "Exportar": function() {

        fecha = $("#fecha_platos").val();
        $( "#notif_exp" ).html('<div style="text-align: center;"><img width="100" src="../../../images/baile.gif"/><h3>Espere un momento...</h3></div>');
        mensaje = carga_listado_pdf(fecha);
        $( "#notif_exp" ).html('<div style="text-align: center;"><h3>Exportado Correctamente</h3><h3><a target="_blank" href="<?php echo $ruta_url; ?>'+ mensaje +'" >Click</a> para descargar.</h3></div>');

      },
      "Cerrar": function() {
        $( this ).dialog( "close" );
        $( "#dialogo_mensaje" ).html('');
      }
    }
  });

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
  balance();
  </script>