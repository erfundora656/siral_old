<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$fecha = time();

if($tipo_usuario == 'Trabajador'){
 $tickets_alm = 'reservar_almuerzos';     
 $controladora = 'trabajadores';
 $idusuario = $idtrabajador;
 $sede=$sede_idsede;
} 

?>
<div role="main" id="contenido" class="ui-content">
  
  <div id="precio_mes">Total en el mes:<div style="font-size: 0.9em;float: center;" id="saldo_mes">&nbsp;</div></div><br>
  <div id="calendario_g"></div>
  
</div><!-- /content -->
<script type="text/javascript">
 
    function actualiza_calendario(iniciosemana,idtrabajador,sede_){

      myURL = '<?php echo $ruta_url; ?>index.php/mobile/calendario_t/'+iniciosemana+'/'+idtrabajador+'/'+sede_+'/';

      var contenido_semana = $.ajax({
        async: false,
        url: myURL
      }).responseText;

      $( "#calendario_g" ).html(contenido_semana);

      myURL = '<?php echo $ruta_url; ?>index.php/admin_tickets/gastos_mes/'+iniciosemana+'/'+idtrabajador+'/'+sede_+'/';

      var saldo_mes_d;
      var saldo_mes_a;
      var saldo_mes_c;

      $.ajax({
        url: myURL,
        dataType: 'json',
        async: false,
        success: function(data){
          saldo_mes_d = data['saldo_r_1'];
          saldo_mes_a = data['saldo_r_2'];
          saldo_mes_c = data['saldo_r_3'];
        }
      });
        
            $( "#saldo_mes" ).html('| Desayunos = $ '+ saldo_mes_d + '| Almuerzos = $ '+ saldo_mes_a + ' | Comidas = $ '+ saldo_mes_c +' |');


          }
  
      <?php
      if($tipo_usuario == 'Trabajador'){
        ?>    
        actualiza_calendario('<?php echo $inicio_semana; ?>','<?php echo $idusuario; ?>','<?php echo $sede;?>');
      <?php } ?>

    </script>

