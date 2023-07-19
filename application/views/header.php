<?php
/* 
 * Cabecera.
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Plataforma de gestion de los tickets de alimentación - UCLV</title>       

  <link rel="stylesheet" href="<?php echo $ruta_url; ?>css/temas/<?php echo ($templates[$template_prof]['cssfolder']); ?>/jquery-ui.css">
  <link rel="stylesheet" type="text/css" href="<?php echo $ruta_url; ?>css/temas/<?php echo ($templates[$template_prof]['cssfolder']); ?>/flexigrid.pack.css" />
  <link rel="stylesheet" href="<?php echo $ruta_url; ?>css/temas/<?php echo ($templates[$template_prof]['cssfolder']); ?>/styles.css">

  <link rel="stylesheet" href="<?php echo $ruta_url; ?>css/main.css">
  <link rel="stylesheet" type="text/css" href="<?php echo $ruta_url; ?>js/jqplot/jquery.jqplot.css" />

  <link href="<?php echo $ruta_url; ?>css/iCheck/skins/all.css" rel="stylesheet" />

  <script type="text/javascript" src="<?php echo $ruta_url; ?>js/jquery-1.11.1.js"></script>
  <script type="text/javascript" src="<?php echo $ruta_url; ?>js/jquery-ui.min.js"></script>
  <script type="text/javascript" src="<?php echo $ruta_url; ?>js/external/jquery.bgiframe.js"></script>
  <script type="text/javascript" src="<?php echo $ruta_url; ?>js/icheck.js"></script>



  <script type="text/javascript" src="<?php echo $ruta_url; ?>js/jquery.decimalMask.js"></script>

  <script type="text/javascript" src="<?php echo $ruta_url; ?>js/jquery.PrintArea.js"></script>

  <script type="text/javascript" src="<?php echo $ruta_url; ?>js/flexigrid.pack.js"></script>        
  <script language="javascript" type="text/javascript" src="<?php echo $ruta_url; ?>js/jqplot/jquery.jqplot.js"></script>
  <script language="javascript" type="text/javascript" src="<?php echo $ruta_url; ?>js/jqplot/plugins/jqplot.pieRenderer.js"></script>
  <script language="javascript" type="text/javascript" src="<?php echo $ruta_url; ?>js/jqplot/plugins/jqplot.canvasTextRenderer.js"></script>
  <script language="javascript" type="text/javascript" src="<?php echo $ruta_url; ?>js/jqplot/plugins/jqplot.categoryAxisRenderer.js"></script>
  <script language="javascript" type="text/javascript" src="<?php echo $ruta_url; ?>js/jqplot/plugins/jqplot.barRenderer.js"></script>
  <script language="javascript" type="text/javascript" src="<?php echo $ruta_url; ?>js/jqplot/plugins/jqplot.cursor.js"></script>
  <script language="javascript" type="text/javascript" src="<?php echo $ruta_url; ?>js/jqplot/plugins/jqplot.dateAxisRenderer.js"></script> 
  <script language="javascript" type="text/javascript" src="<?php echo $ruta_url; ?>js/jqplot/plugins/jqplot.canvasAxisTickRenderer.js"></script> 
  <script language="javascript" type="text/javascript" src="<?php echo $ruta_url; ?>js/jqplot/plugins/jqplot.enhancedLegendRenderer.js"></script> 
  <script  type="text/javascript" src="<?php echo $ruta_url; ?>js/qrcodelib.js"></script>
  <script  type="text/javascript" src="<?php echo $ruta_url; ?>js/webcodecamjs.js"></script>
    <script type="text/javascript" src="<?php echo $ruta_url; ?>js/ebapi-modules.js"></script>

  <?php if ($accion == 'ADMIN') {?>
    <script type="text/javascript" src="<?php echo $ruta_url; ?>js/principal.js"></script>
  <?php } else {?>
    <script type="text/javascript" src="<?php echo $ruta_url; ?>js/principal_h.js"></script>
  <?php } ?>      
  <script type="text/javascript" src="<?php echo $ruta_url; ?>js/JSON-js-master/json2.js"></script>

</head>
