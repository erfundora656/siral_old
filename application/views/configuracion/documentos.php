    <table with="100%">               
        <tr title="Nombre de la entidad">
            <td>Nombre de la entidad:</td>
                <td colspan="2"><input id="nombre_entidad" type="text" size="40" value="<?php echo htmlentities(($nombre_entidad)); ?>" /></td>
        </tr>
        <tr title="Organismo">
            <td>Organismo:</td>
            <td colspan="2"><input id="organismo" type="text" size="40" value="<?php echo htmlentities(($organismo)); ?>" /></td>
        </tr>
        <tr title="Nombre del directivo que firma los documentos">
            <td>Nombre del directivo:</td>
                <td colspan="2"><input id="nombre_firma" type="text" size="40" value="<?php echo htmlentities(($nombre_firma)); ?>" /></td>
        </tr>
        <tr title="Cargo del directivo que firma">
            <td>Cargo del directivo:</td>
                <td colspan="2"><input id="cargo_firma" type="text" size="40" value="<?php echo htmlentities(($cargo_firma)); ?>" /></td>
        </tr>
    </table>
<button id="b_actualizar_conf_doc">Actualizar</button>
<script type="text/javascript">

// La función es la encargada de mandar los datos validados a la controladora.

    $("#b_actualizar_conf_doc").button({
                            text: true,
                            icons: {
                                    primary: "ui-icon-check"
                            }
                        }).click(function(){
        
                            var datos = "nombre_entidad=" + $("#nombre_entidad").val() + "&organismo=" + $("#organismo").val() + "&nombre_firma=" + $("#nombre_firma").val() + "&cargo_firma="+ $("#cargo_firma").val();

                            $.ajax({
                               type: "POST",
                               url: "<?php echo $ruta_url;?>index.php/configuracion/actualizar_documentos/",
                               data: datos,
                               async: false,
                               success: function(){
                                   alert("Datos actualizados");
                               }
                            });

                        });

</script>