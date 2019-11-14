<?php

$usmap = 'image.svg';
$im = new Imagick();
$svg = file_get_contents($usmap);

$im->readImageBlob('<?xml version="1.0" encoding="UTF-8" standalone="no"?>'.$svg);

$im->setImageFormat("png24");
$im->resizeImage(720, 445, imagick::FILTER_LANCZOS, 1);  /*Optional, if you need to resize*/

$im->writeImage('blank-us-map.png');

header('Content-type: image/png');
echo $im;

$im->clear();
$im->destroy();
