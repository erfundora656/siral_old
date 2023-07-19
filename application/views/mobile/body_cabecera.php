<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
//if(isset($titulo)) $titulo = '';
if($tipo_usuario != ''){
  if($tipo_usuario == 'Trabajador'){
    $controladora = 'seguridad';
 } 
}


?>
<body>
   <div data-role="page" class="my-page" data-theme="a">
     <div class="ui-header ui-bar-inherit" data-position="fixed"  role="banner">
      <h1 class="ui-title" role="heading" aria-level="1"><?php echo $titulo;?></h1>
       <?php if($tipo_usuario != ''){        
       if((count(explode('/', $ruta_url))-1) > 2){       
       ?>              
        <a data-iconpos="notext" data-icon="bars" href="<?php echo $ruta_url ?>index.php/mobile/" class="ui-link ui-btn-left ui-btn ui-icon-home ui-btn-icon-notext ui-shadow ui-corner-all" data-role="button" role="button">Inicio</a>
        <?php
        } 
       ?>
        <a data-iconpos="notext" data-icon="bars" href="javascript:cerrar_session('<?php echo $ruta_url ?>','<?php echo $controladora ?>');" class="ui-link ui-btn-right ui-btn ui-icon-power ui-btn-icon-notext ui-shadow ui-corner-all" data-role="button" role="button">Inicio</a>
     <?php } ?>                
  </div><!-- /header -->
