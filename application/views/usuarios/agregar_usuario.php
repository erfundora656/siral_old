<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<input id="sede_idsede" type="hidden"  value="" />
<form id="fagregar_usuarios" method="post" acction="">
    <table with="100%">
       <tr>
        <td>Usuario:</td>
        <td><input id="usuario" type="text" size="20"/></td>
    </tr>
  <tr>
      <td>Rol:</td>
      <td>
        <select id="rol"> 
            <option value="">Seleccionar..</option>                  
            <option value="0">Vendedor</option>
            <option value="4">Jefe Departamento</option>
            <option value="5">Planificador</option>
            <option value="1">Gestor Económico</option>
            <option value="2">Administrador</option>
            <option value="3">Super Administrador</option>           
        </select>
    </td>    
</tr>
<tr id="selectSede" style="visibility:visible;">
    <td>Sede:</td>
    <td>
        <select id="sede_">
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

<!--Agregar al formulario del usuario cuando sea administrador seleccionar la sede a la que pertenece -->
<script type="text/javascript">
    var rowSede=document.getElementById("selectSede");
    $("#rol").change(function(){	
        
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

    $("#sede_").change(function(){
        if(rowSede.style.visibility=="visible"){
         $("#sede_idsede").val($("#sede_").val()); 
     }



 });
    
</script>