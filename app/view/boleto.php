<?php
//header ('Content-type: text/html; charset=UTF-8');
header("Content-Type: application/pdf");
header('Content-disposition: filename="' . $response->payload->id . '.pdf"');

// $dirLogo = $GLOBALS['projectRoot'].'/img/logo/favicon_' . $GLOBALS['file_ext'] . '.ico';
// $linkBoleto = '';
// $erro = $response->erro;

// if(!empty($response->payload->base64pdf))
//  $linkBoleto = 'data:application/pdf;base64,'.$response->payload->base64pdf;

$bin = base64_decode($response->payload->base64pdf, true);

if (strpos($bin, '%PDF') !== 0 || $erro) {
    throw new Exception(($erro) ? $response->message : 'Não foi possível ler este pdf');
}
echo $bin;
?>

<!-- <!DOCTYPE html>
<html>
<head>
    <title>Boleto <?php /*echo $GLOBALS['nomeDoProjeto']; */?></title>
    <link rel="shortcut icon" href="<?php/* echo $dirLogo;*/ ?>" >

    <style type="text/css">
        html, body, div, iframe {margin:0; padding:0; height:100%}
        iframe {display:block; width:100%; border:none}
  </style>

</head>
<body>
    <h1 style="text-align:center;"><?php /*if($erro) echo $response->message*/ ?></h1>
    <iframe src="<?php /*echo $linkBoleto; */?>" name="<?php /*echo $response->payload->documento;*/?>">

        
</body>
</html> -->
