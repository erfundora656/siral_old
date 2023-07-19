<div id="div_imprimir">
    
</div>
<?php
    $myURL = $ruta_url.base64_decode($url_imprimir);
    $arrURL = explode("/", $myURL);

    if ($arrURL[5] == 'estadistica'){
        ?>
            <script type="text/javascript" src="<?= $ruta_url; ?>js/gest_church/estadistica.js"></script>
        <?php
    }

    if ($arrURL[5] == 'estadistica_economia'){
        ?>
            <script type="text/javascript" src="<?= $ruta_url; ?>js/gest_church/estadistica_economia.js"></script>
        <?php
    }
    
?>

<script type="text/javascript">

    myURL = "<?= $ruta_url; ?>"+"<?= base64_decode($url_imprimir); ?>";
            $.ajax({
                url: myURL,
                success: function(datos){
                    //alert(datos);
                    $("#div_imprimir").html(datos);
                    window.print();            
                }
            });

</script>