<?php

$cabecalho = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
 <head>
  <title> New Document </title>
  <meta name="Generator" content="EditPlus">
  <meta name="Author" content="">
  <meta name="Keywords" content="">
  <meta name="Description" content="">
  <style  TYPE="text/css">
  <!-- 
	table { background-color: #00ff00;  padding: 0px; margin: 0px; border: none;}
	tr { height: 1px;  padding: 0px; margin: 0px; border: none;}
	td { width: 1px; padding: 0px; margin: 0px; border: none;}
  -->
  </style>
 </head>

 <body>
  <table cellpadding="0" cellspacing="0" border="0">'."\n";

 $rodape = "\n".' </table>
 </body>
</html>';

function convertIntColorHex($intColor) {
	$hex = dechex ($intColor);
	return '#'.str_repeat("0", 6-strlen($hex)).$hex;

	$r = ($intColor >> 16) & 0xFF;
	$g = ($intColor >> 8) & 0xFF;
	$b = $intColor & 0xFF;
	if($r == $g && $g == $b){
		$hex = dechex ($r);
		return '#'.str_repeat("0", 2-strlen($hex)).$hex;
	}else{
	}
}

function abreImagem($img) {
	$dim = getimagesize($img);
//	print_r($dim);
//	exit(0);
	$retorno=false;
	switch($dim[2]) {
		case 2:
			$retorno = @imagecreatefromjpeg ($img);
			break;
		case 3:
			$retorno = @imagecreatefrompng ($img);
			break;
		case 6:
			$retorno = @imagecreatefromwbmp ($img);
			break;
		default:
			$retorno = false;
			break;
	}
	if (!$retorno) { /* See if it failed */
		$retorno  = imagecreate ($dim[0], $dim[1]); /* Create a blank image */
		$bgc = imagecolorallocate($retorno, 255, 255, 255);
		$tc  = imagecolorallocate($retorno, 0, 0, 0);
		imagefilledrectangle($retorno, 0, 0, 10, 10, $bgc);
		/* Output an errmsg */
		imagestring($retorno, 1, 5, 5, "Error loading $img", $tc);
	}
	return $retorno;
}

$img = $argv[1];
if(file_exists($img)){
	$dim = getimagesize($img);
	$cr = abreImagem($img);
	if($cr !== false){
		echo $cabecalho;
		for($j=0; $j<$dim[1]; $j++) {
			$cor_atual = ImageColorAt($cr, 0, $j);
			$cores[$cor_atual] = 1;
			echo "\t".'<tr>'."\n";
			for($i=1; $i<$dim[0]; $i++) {
				$nova_cor = ImageColorAt($cr, $i, $j);
				if($cor_atual == $nova_cor && ($i+1)<$dim[0]) {
					$cores[$cor_atual]++;
				}else{
					$hex = convertIntColorHex($cor_atual);
					echo "\t\t".'<td bgcolor="'.$hex.'"'.($cores[$cor_atual] > 1?' colspan="'.$cores[$cor_atual].'"':'').'></td>'."\n";
					unset($cores[$cor_atual]);
					$cor_atual = $nova_cor;
					$cores[$cor_atual] = 1;
				}
			}
			echo "\t".'</tr>'."\n";
		}
		echo $rodape;
	}
}

?>