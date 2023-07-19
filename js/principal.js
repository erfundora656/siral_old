/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
 function carga_contenidos(myURL){

  $( "#contenedor" ).html('<div style="text-align: center;margin-top:200px;"><img width="100" src="../../../images/baile.gif"/><h3>Espere un momento...</h3></div>');


                    //verificar sesión
                    

                    if(myURL!='index.php/principal/admin_inicio/0/'){
                      controla_seguridad();
                    }

                    
                    var contenido_dialog = $.ajax({
                     async: false,
                     url: '../../../'+myURL
                   }).responseText;

                    $( "#contenedor" ).html(contenido_dialog);
                    
                    
                  }
                  
                  function controla_seguridad(){
                    myurl = '../../../index.php/seguridad/verificar_sesion_a/';
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
                  
                  function autenticar(){
                    $.post("../../../index.php/seguridad/autenticar_admin/", { usuario: $("#acceder_usuario").val(),contrasenna: $("#acceder_contrasenna").val()}, 
                      function (data) {

                        var accion = {
                          accion: function(){
                            location.reload();
                          }
                        };
                        muestra_mensaje("<h2>"+data+"</h2>", 400,accion);
/*                                    
                                        alert(data);
                                        location.reload(); */
                                      });
                  }


                  $(function() {

                    var icons = {
                     header: "ui-icon-circle-arrow-e",
                     headerSelected: "ui-icon-circle-arrow-s"
                   };
                   $( "#accordion" ).accordion({
                     icons: icons
                   });
                   $( "#toggle" ).button().toggle(function() {
                     $( "#accordion" ).accordion( "option", "icons", false );
                   }, function() {
                     $( "#accordion" ).accordion( "option", "icons", icons );
                   });


                   $("#b_Menus_d").click(function(){
                    //Mostrar el gestor de los combustibles

                    myURL = 'index.php/principal/admin_menus_d/';

                    carga_contenidos(myURL)

                  });

                   $("#b_Menus").click(function(){
                    //Mostrar el gestor de los combustibles

                    myURL = 'index.php/principal/admin_menus_a/';

                    carga_contenidos(myURL)

                  });

                   $("#b_Menus_c").click(function(){
                    //Mostrar el gestor de los combustibles

                    myURL = 'index.php/principal/admin_menus_c/';
                    

                    carga_contenidos(myURL)

                  });

                   $("#b_D_A_Tickets").click(function(){
                    //Mostrar el gestor de los combustibles
                    
                    myURL = 'index.php/principal/dirigidos_d/';

                    carga_contenidos(myURL)

                  });

                   $("#b_D_Tickets").click(function(){
                    //Mostrar el gestor de los combustibles
                    
                    myURL = 'index.php/principal/dirigidos_a/';

                    carga_contenidos(myURL)

                  });
                   $("#b_D_C_Tickets").click(function(){
                    //Mostrar el gestor de los combustibles

                    myURL = 'index.php/principal/dirigidos_c/';
                    carga_contenidos(myURL)

                  });

                  /* $("#b_D_C_Tickets").click(function(){
                    //Mostrar el gestor de los combustibles

                    myURL = 'index.php/dirigido_comidas/';

                    carga_contenidos(myURL)

                  });*/

                   /*$("#b_D_E_Tickets").click(function(){
                    //Mostrar el gestor de los combustibles

                    myURL = 'index.php/dirigidos_e/';

                    carga_contenidos(myURL)

                });

                   $("#b_D_C_E_Tickets").click(function(){
                    //Mostrar el gestor de los combustibles

                    myURL = 'index.php/dirigido_comidas_e/';

                    carga_contenidos(myURL)

                  });*/

$("#b_List_Desayuno").click(function(){
                    //Mostrar el gestor de los combustibles

                   myURL = 'index.php/estadisticas/listado_turnos/1/';

                   carga_contenidos(myURL)

                 });


$("#b_List_Almuerzo").click(function(){
                    //Mostrar el gestor de los combustibles

                   // myURL = 'index.php/estadisticas/listado_almuerzos/';
                   myURL = 'index.php/estadisticas/listado_turnos/2/';

                   carga_contenidos(myURL)

                 });

                   /*$("#b_List_Almuerzo_e").click(function(){
                    //Mostrar el gestor de los combustibles

                    myURL = 'index.php/estadisticas/listado_almuerzos_e/';

                    carga_contenidos(myURL)

                  });*/

$("#b_List_Comidas").click(function(){
                    //Mostrar el gestor de los combustibles

                    //myURL = 'index.php/estadisticas/listado_comidas/';
                    myURL = 'index.php/estadisticas/listado_turnos/3/';

                    carga_contenidos(myURL)

                  });

                   /*$("#b_List_Comidas_e").click(function(){
                    //Mostrar el gestor de los combustibles

                    myURL = 'index.php/estadisticas/listado_comidas_e/';

                    carga_contenidos(myURL)

                  });*/


$("#b_List_Chequear").click(function(){
                    //Mostrar el gestor de los combustibles

                   myURL = 'index.php/estadisticas/chequear_listado/';

                   carga_contenidos(myURL)

                 });



$("#b_List_M_Almuerzo").click(function(){
                    //Mostrar el gestor de los combustibles

                    myURL = 'index.php/estadisticas/listado_mensual/0/0/';

                    carga_contenidos(myURL)

                  });

                   /*$("#b_List_M_Almuerzo_e").click(function(){
                    //Mostrar el gestor de los combustibles

                    myURL = 'index.php/estadisticas/listado_mensual_e/0/0/';

                    carga_contenidos(myURL)

                  });*/

$("#b_List_R_F_Almuerzo").click(function(){
                    //Mostrar el gestor de los combustibles

                    myURL = 'index.php/estadisticas/listado_rango_fechas/0/0/0/0/0/0/';

                    carga_contenidos(myURL)

                  });

                   /*$("#b_List_R_F_Almuerzo_e").click(function(){
                    //Mostrar el gestor de los combustibles

                    myURL = 'index.php/estadisticas/listado_rango_fechas_e/0/0/0/0/0/0/';

                    carga_contenidos(myURL)

                  });*/

$("#b_Balance").click(function(){
                    //Mostrar el gestor de los combustibles

                    myURL = 'index.php/estadisticas/balance/0/0/0/';

                    carga_contenidos(myURL)

                  });


$("#b_Rep_Ventas").click(function(){
                    //Mostrar el gestor de los combustibles

                    myURL = 'index.php/estadisticas/reporte_ventas/';

                    carga_contenidos(myURL)

                  });

$("#b_Rep_S_Ventas").click(function(){
                    //Mostrar el gestor de los combustibles

                    myURL = 'index.php/estadisticas/reporte_s_ventas/0/0/';

                    carga_contenidos(myURL)

                  });

$("#b_Usuarios").click(function(){
                    //Mostrar el gestor de los usuarios

                    myURL = 'index.php/usuarios/';

                    carga_contenidos(myURL)

                  });

$("#b_Sedes").click(function(){
                    //Mostrar el gestor de los usuarios

                    myURL = 'index.php/sedes/';

                    carga_contenidos(myURL)

                  });

$("#b_Trabajadores").click(function(){
                    //Mostrar el gestor de los usuarios

                    myURL = 'index.php/trabajadores/';

                    carga_contenidos(myURL)

                  });

                   /*$("#b_Estudiantes").click(function(){
                    //Mostrar el gestor de los usuarios

                    myURL = 'index.php/estudiantes/';

                    carga_contenidos(myURL)

                  });*/

$("#b_Configuracion").click(function(){
                    //Mostrar el gestor de los usuarios

                    myURL = 'index.php/configuracion/';

                    carga_contenidos(myURL)

                  });

$("#b_Logs").click(function(){
                    //Mostrar el gestor de los usuarios

                    myURL = 'index.php/logs/';

                    carga_contenidos(myURL)

                  });

$("#b_Mantenmiento").click(function(){
                    //Mostrar el gestor de los usuarios

                    myURL = 'index.php/mantenimiento/';

                    carga_contenidos(myURL)

                  });
$("#b_Tickets_d").click(function(){
                    //Mostrar el gestor de los usuarios

                    myURL = 'index.php/principal/gest_reserva_d/';

                    carga_contenidos(myURL)

                  });

$("#b_Tickets").click(function(){
                    //Mostrar el gestor de los usuarios

                    myURL = 'index.php/principal/gest_reserva/';

                    carga_contenidos(myURL)

                  });

                   /*$("#b_Tickets_e").click(function(){
                    //Mostrar el gestor de los usuarios

                    myURL = 'index.php/admin_tickets_e/';

                    carga_contenidos(myURL)

                  });*/

$("#b_Tickets_c").click(function(){
                    //Mostrar el gestor de los usuarios

                    myURL = 'index.php/principal/gest_reserva_c/';

                    carga_contenidos(myURL)

                  });

                  /* $("#b_Tickets_e_c").click(function(){
                    //Mostrar el gestor de los usuarios

                    myURL = 'index.php/admin_tickets_e_c/';

                    carga_contenidos(myURL)

                  });*/

$("#b_Tickets_m_t").click(function(){
                    //Mostrar el gestor de los usuarios

                    myURL = 'index.php/admin_tickets_mult/mult_trabajadores/';

                    carga_contenidos(myURL)

                  });

                  /* $("#b_Tickets_m_e").click(function(){
                    //Mostrar el gestor de los usuarios

                    myURL = 'index.php/admin_tickets_mult/mult_estudiantes/';

                    carga_contenidos(myURL)

                  });*/

$("#b_Acceder").click(function(){
                    //Autenticar
                    autenticar();

                  });

$("#b_salir").click(function(){
                    //Autenticar

                    $.post("../../../index.php/seguridad/cerrar_sesion_admin/", {}, 
                      function (data) {
                                        //alert(data);
                                        location.reload();
                                      });

                  });

$("#b_inicio").click(function(){
  myURL = 'index.php/principal/admin_inicio/0/';
  carga_contenidos(myURL)

});

myURL = 'index.php/principal/admin_inicio/0/';

carga_contenidos(myURL);

});

principal ={

  carga_contenidos:function(myURL){

    var contenido_dialog = $.ajax({
      async: false,
      url: myURL
    }).responseText;

    $( "#contenedor" ).html(contenido_dialog);


  }


};

