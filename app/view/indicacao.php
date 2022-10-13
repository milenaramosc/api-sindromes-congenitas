

 <!DOCTYPE html>
<html lang="en">
    <head>
        <!-- Required meta tags
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
         <meta charset="UTF-8">
        -->
     
        
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Colorlib Templates">
        <meta name="author" content="Colorlib">
        <meta name="keywords" content="Colorlib Templates">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <!-- Title Page-->
        <title><?php echo $GLOBALS['nomeDoProjeto']; ?></title>
         <link rel="shortcut icon" href="<?php echo $GLOBALS['raiz'] . $GLOBALS['dirImg']; ?>/logo/favicon_<?php echo $GLOBALS['file_ext'];?>.ico" >
        <!-- Icons font CSS-->
        <link href='<?php echo $GLOBALS["projectRoot"] . 'app/assets/vendor/mdi-font/css/material-design-iconic-font.min.css'?>' rel="stylesheet" media="all">
        <link href='<?php echo $GLOBALS["projectRoot"] . 'app/assets/vendor/font-awesome-4.7/css/font-awesome.min.css' ?>' rel="stylesheet" media="all">
        <!-- Font special for pages-->
        <link href="https://fonts.googleapis.com/css?family=Poppins:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
        <!-- Vendor CSS-->
        <link href='<?php echo $GLOBALS["projectRoot"] . 'app/assets/vendor/select2/select2.min.css' ?>' rel="stylesheet" media="all">
        <link href='<?php echo $GLOBALS["projectRoot"] . 'app/assets/vendor/datepicker/daterangepicker.css' ?>' rel="stylesheet" media="all">
        <!-- Main CSS-->
        <link href='<?php echo $GLOBALS["projectRoot"] . 'app/assets/css/main.css' ?>' rel="stylesheet" media="all">
        <style type="text/css">
            .panel-body {
                padding: 45px 25px;
            }
        </style>
    </head>
    <body>
        <div class="page-wrapper bg-gra-02 p-t-130 p-b-100 font-poppins">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 col-lg-offset-3 col-md-8 col-md-offset-2 col-xs-12">
                        
                        <div class="wrapper" style="padding-top: 100px;">
                            <div class="panel">
                                <div class="panel-body">
                                    
                                    
                                    <h2 class="title" align="center"><?php echo utf8_decode("Você foi convidado por $nome, para prosseguir preencha o campo abaixo:"); ?></h2>
                                    <div class="row">
                                        <div class="col-md-8 col-md-offset-2 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1">
                                            <div class="form-group">
                                                <label class="label" for="CPFCNPJ">CPF:</label>
                                                <input type="text" class="form-control input-lg" id="CPFCNPJ" name="CPFCNPJ">
                                            </div>                                                      
                                            
                                        </div>
                                     </div>
                                    <div class="row">
                                        <div class="col-md-8 col-md-offset-2 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1">
                                            <button id="btnConfirmar" type="button" class="btn btn--blue btn-block">Confirmar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="myModal" role="dialog">
            <div class="modal-dialog">
                
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header btn--blue">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Cadastro realizado com sucesso!</h4>
                    </div>
                    <div class="modal-body">
                        <p>Para continuar com o registro, por gentileza, realize o download do app pelo link:</p><br>                    
                        <center  style="padding-left: 10% !important;" >
                        <table width="60%">
                            <tr>
                                <!--
                                <td><a href=""><img src="icone_android.png" width="48px"></td></a>
                                <td><a href=""><img src="icone_apple.png" width="48px"></a></td>
                                -->
                                <td><a href=""><img src="<?php echo $GLOBALS['raiz'] . $GLOBALS['dirImg']; ?>/logo/icone_playstore.png" width="48px"></td></a>
                                <!--
                                <td><a href=""><img src="icone_appstore.png" width="48px"></a></td>
                                -->
                                <td><a href=""><img src="<?php echo $GLOBALS['raiz'] . $GLOBALS['dirImg']; ?>/logo/app-store.png" width="40px"></a></td>
                            </tr>
                        </table>
                    </center>
                        <!--<button type="button" class="btn btn--radius-2 btn--blue" data-dismiss="modal">Close</button>-->
                    </div>
                </div>
                </div>
            </div>
        </div>
        
    </div>
    <!-- Jquery JS-->
    <script src='<?php echo $GLOBALS["projectRoot"] . 'app/assets/vendor/jquery/jquery.min.js' ?>'></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>
    <script>

    $("#CPFCNPJ").keydown(function(){
   

    var tamanho = $("#CPFCNPJ").val().length;
    
        if(tamanho <= 11){

            $("#CPFCNPJ").mask("999.999.999-99");

        }
    
  
    });


    
    $('#btnConfirmar').click(function(e) {
    
        e.preventDefault();
        var cpfcnpj = $('#CPFCNPJ').val();

            if(cpfcnpj != ''){


                var url = window.location.href;
                url = url.split('?cod=');
                cod = url[1];
        

                
                $.ajax({
                    type: "POST",
                    url: '<?php echo $GLOBALS["projectRoot"] . 'app/indicacao/' ?>',
                    dataType: 'json',
                    data: JSON.stringify({
                                            CPFCNPJ:cpfcnpj,
                                            COD:cod
                                           
                                        }),
                }).done(function(data){
                    data = JSON.parse(JSON.stringify(data));
                    $("#CPFCNPJ").val('');

                    if(data.retorno == 'sucesso'){
                        jQuery.noConflict(); 
                        $('#myModal').modal('show');

                    }else if(data.retorno == 'erro'){

                        alert(data.mensagem);
                    }

                   
                  
                });
                
    
              
            }

    });

    



</script>
<!-- Vendor JS-->
<script src='<?php echo $GLOBALS["projectRoot"] . 'app/assets/vendor/select2/select2.min.js'?>'></script>
<script src='<?php echo $GLOBALS["projectRoot"] . 'app/assets/vendor/datepicker/moment.min.js'?>'></script>
<script src='<?php echo $GLOBALS["projectRoot"] . 'app/assets/vendor/datepicker/daterangepicker.js' ?>'></script>
<!-- Main JS-->
<script src='<?php echo $GLOBALS["projectRoot"] . 'app/assets/js/global.js' ?>'></script>
</body><!-- This templates was made by Colorlib (https://colorlib.com) -->
</html>
<!-- end document-->
