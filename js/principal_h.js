/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
                function carga_contenidos(myURL){
                    var seguridad = controla_seguridad();
                    
                    
                    if (seguridad){
                        $( "#contenedor_h" ).html('<div style="text-align: center;margin-top:200px;"><img width="100" src="images/baile.gif"/><h3>Espere un momento...</h3></div>');
                        var contenido_dialog = $.ajax({
                            async: false,
                            url: myURL
                        }).responseText;

                        $( "#contenedor_h" ).html(contenido_dialog);
                    }
                }

                function controla_seguridad(){
                    myurl = 'index.php/seguridad/verificar_sesion_f/';
                     $.ajax({
                          url: myurl,
                          dataType: 'json',
                          async: false,
                          success: function(data){
                            seguridad = data;
                          }
                        });

                    if(seguridad != true){
                        location.reload();
                        return false;
                    } else {
                        return true;
                    }
                    
                }

