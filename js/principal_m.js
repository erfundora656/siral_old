/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
                function carga_contenidos(myURL){
                                //verificar sesión
                                var seguridad = controla_seguridad();


                                if (seguridad){
                                    var contenido_dialog = $.ajax({
                                        async: false,
                                        url: myURL
                                    }).responseText;

                                    $( "#contenido" ).html(contenido_dialog);
                                }

                }
                
                var seguridad;

                function controla_seguridad(rutaURL){
                    myurl = rutaURL+'index.php/seguridad/verificar_sesion_f/';
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

                function cerrar_session(rutaURL,controladora){
                    $.post(rutaURL+'index.php/'+controladora+'/cerrar_sesion_trab/', {}, 
                                function (data) {
                                        location.reload();
                                });
                }
