<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$fecha = time();

if($tipo_usuario == 'Trabajador'){
   $tickets_reservar_d = 'reservar_desayunos';   
   $tickets_reservar_a = 'reservar_almuerzos'; 
   $tickets_reservar_c = 'reservar_comidas';   
   $calendario = 'calendario_t';
   
   $controladora = 'trabajadores';
   $idusuario = $idtrabajador;
   $sede=$sede_idsede;
} 

?>
<div class="usuario_nombre_apellidos" align="center"><br><?php if($autenticado){ ?>
    &nbsp;<?php echo $tipo_usuario; ?>:&nbsp;<strong><?php echo $nombre_apellidos;?></strong>&nbsp;
    <?php }?></div>

    <div role="main" id="contenido" class="ui-content">
        <ul data-role="listview" data-inset="true">
            <li>               
                <a href="<?php echo $tickets_reservar_d; ?>/">
                    <img src="<?php echo $ruta_url; ?>images/mobile/Desayunos.png" class="ui-li-thumb">
                    <h2>Desayunos</h2>
                    <p>Reservar cada día de la semana.</p>
                </a>                
            </li>
            <li><a href="<?php echo $tickets_reservar_a; ?>/">
                <img src="<?php echo $ruta_url; ?>images/mobile/Almuerzo.png" class="ui-li-thumb">
                <h2>Almuerzos</h2>
                <p>Reservar cada día de la semana.</p>
            </a>
        </li>
        <li><a href="<?php echo $tickets_reservar_c; ?>/">
            <img src="<?php echo $ruta_url; ?>images/mobile/Comida.png" class="ui-li-thumb">
            <h2>Comidas</h2>
            <p>Reservar cada día de la semana.</p>
        </a>
    </li>
    <li><a href="multiples/">
        <img src="<?php echo $ruta_url; ?>images/mobile/Multiples.png" class="ui-li-thumb">
        <h2>Asistente</h2>
        <p>Realize múltiples reservaciones en pocas aciones.</p>
    </a>
</li>
<li><a href="calendario/">
    <img src="<?php echo $ruta_url; ?>images/mobile/Calendario.png" class="ui-li-thumb">
    <h2>Calendario</h2>
    <p>Vea sus gastos por mes.</p>
</a>
</li>
</ul>
</div><!-- /content -->


<script type="text/javascript">    

    

</script>