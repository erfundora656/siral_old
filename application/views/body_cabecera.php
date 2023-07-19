<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<body>
<table width="100%">
    <tr>
        <td>&nbsp;</td>
        <td class="ui-widget-header" width="1020">
<div>    
    <div style="padding: 0 10px;float: left;width: 340px;">
        <div style="font-size: 5em; float: left">SiRAl</div> 
        <div style="font-size: 1.7em;padding-top: 8px;">Sistema de Reservación de Alimentación</div> 
    </div>
    <div style="padding: 0 10px;float: right;width: 340px;text-align: right;">
        <div style="font-size: 1.7em;padding-top: 8px;padding-right: 5px;"><?php echo $nombre_entidad; ?></div> 
        <div class="usuario_nombre_apellidos">
                        <?php if($autenticado){ ?>
            &nbsp;<?php echo $tipo_usuario; ?>:&nbsp;<strong><?php echo $nombre_apellidos; ?></strong>&nbsp;
            <?php } else {?>
            &nbsp;Invitado&nbsp;
            <?php } ?>
            <?php echo '('.$ip_address.')'; ?>
        </div>
    </div>
</div>
        </td>
        <td>&nbsp;</td>
    </tr>
</table>  