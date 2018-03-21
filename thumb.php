<?php
#recebendo a url da imagem
$filename = $_GET['img'];
$extensao = pathinfo ( $_GET['img'], PATHINFO_EXTENSION );
#Cabeçalho que ira definir a saida da pagina
header('Content-type: image/' . $extensao);

#pegando as dimensoes reais da imagem, largura e altura
list($width, $height) = getimagesize($filename);

#setando a largura da miniatura
$new_width = 120;
#setando a altura da miniatura
$new_height = 120;
  if( $height > $width ){
   /* Portrait */
    $newW = $new_width;
    $newH = $height * ( $new_height / $newW );
    //$newH = $new_height * $ratio;
  }else{
   /* Landscape */
    $newH = $new_height;
    $newW = $width * ( $new_width / $newH );
    //$newW = $width / $ratio;
  }

#gerando a a miniatura da imagem
$image_p = imagecreatetruecolor($new_width, $new_height);
if($extensao == 'png'){
	$image = imagecreatefrompng($filename);
	imagecopyresampled($image_p, $image, 0, 0, ( $newW-$new_width )/2 , ( $newH-$new_height )/2 , $newW , $newH , $width , $height);
	#o 3º argumento é a qualidade da miniatura de 0 a 100
	imagepng($image_p);
}else if($extensao == 'gif'){
	$image = imagecreatefromgif($filename);
	imagecopyresampled($image_p, $image, 0, 0, ( $newW-$new_width )/2 , ( $newH-$new_height )/2 , $newW , $newH , $width , $height);
	#o 3º argumento é a qualidade da miniatura de 0 a 100
	imagegif($image_p, null, 100);
}else{
	$image = imagecreatefromjpeg($filename);
	imagecopyresampled($image_p, $image, 0, 0, ( $newW-$new_width )/2 , ( $newH-$new_height )/2 , $newW , $newH , $width , $height);
	#o 3º argumento é a qualidade da miniatura de 0 a 100
	imagejpeg($image_p, null, 100);
}
imagedestroy($image_p);
?>