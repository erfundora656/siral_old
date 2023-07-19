<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<form id="feditar_usuarios" method="post" acction="">
    <input id="idusuario" type="hidden"  value="<?php echo($usuario['idusuario']); ?>" />
    
    <table with="100%">
     <tr>
        <td>Usuario:</td>
        <td><input id="usuario" type="text" size="30" readonly value="<?php echo($usuario['usuario']); ?>" /></td>
    </tr>
    <tr>
        <td>Nombre:</td>
        <td><input id="nombres" type="text" size="30" readonly value="<?php echo($usuario['nombres']); ?>" /></td>
    </tr>
    <tr>
      <td>Apellidos:</td>
      <td><input id="apellidos" type="text" size="30" readonly value="<?php echo($usuario['apellidos']); ?>" /></td>
  </tr>
  <tr>
      <td>Rol:</td>
      <td>
        <select id="rol">
            <?php if($usuario['rol'] == 4){ ?>
                <option value="0">Vendedor</option>
                <option value="4" selected>Jefe Departamento</option>
                <option value="5">Planificador</option>
                <option value="1">Gestor Económico</option>
                <option value="2">Administrador</option>
                <option value="3">Super Administrador</option>                
            <?php } ?>
            <?php if($usuario['rol'] == 0){ ?>
                <option value="0"selected>Vendedor</option>
                <option value="4" >Jefe Departamento</option>
                <option value="5">Planificador</option>
                <option value="1">Gestor Económico</option>
                <option value="2">Administrador</option>
                <option value="3">Super Administrador</option>   
            <?php } ?>
            <?php if($usuario['rol'] == 1){ ?>
                <option value="0">Vendedor</option>
                <option value="4" >Jefe Departamento</option>
                <option value="5">Planificador</option>
                <option value="1" selected>Gestor Económico</option>
                <option value="2">Administrador</option>
                <option value="3">Super Administrador</option>
            <?php } ?>
            <?php if($usuario['rol'] == 2){ ?>
                <option value="0">Vendedor</option>
                <option value="4" >Jefe Departamento</option>
                <option value="5">Planificador</option>
                <option value="1">Gestor Económico</option>
                <option value="2" selected>Administrador</option>
                <option value="3">Super Administrador</option>
            <?php } ?>
            <?php if($usuario['rol'] == 5){ ?>
                <option value="0">Vendedor</option>
                <option value="4" >Jefe Departamento</option>
                <option value="5" selected>Planificador</option>
                <option value="1">Gestor Económico</option>
                <option value="2">Administrador</option>
                <option value="3" >Super Administrador</option>
            <?php } ?>
            <?php if($usuario['rol'] == 3){ ?>
                <option value="0">Vendedor</option>
                <option value="4" >Jefe Departamento</option>
                <option value="5">Planificador</option>
                <option value="1">Gestor Económico</option>
                <option value="2">Administrador</option>
                <option value="3" selected>Super Administrador</option>
            <?php } ?>
        </select>
    </td>
</tr>
<tr id="selectSede" style="visibility:hidden;">
    <td>Sede:</td>
    <td>
        <select id="sede_idsede">
            <option value="0" selected>Seleccionar...</option>
            <?php
            foreach($listasedes as $sede){
                echo("<option value='".$sede['idsede']."'>".$sede['nombre']."</option>");
            }
            ?>
        </select>
    </td>    
</tr>
</table>
</form>
<div id="muestra_errores"></div>
<script type="text/javascript">
    if($("#rol").val()==5 || $("#rol").val()==0){        
        var rowSede=document.getElementById("selectSede");
        if(rowSede.style.visibility=="hidden"){
            rowSede.style.visibility="visible";
        }
    }

    $("#rol").change(function(){    
        var rowSede=document.getElementById("selectSede");
        if($("#rol").val()==5 || $("#rol").val()==0){
            if(rowSede.style.visibility=="hidden"){
                rowSede.style.visibility="visible";
            }
        }else{
            if(rowSede.style.visibility=="visible"){
                rowSede.style.visibility="hidden";
            }
            
        }

    });


</script>