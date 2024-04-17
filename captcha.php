<?php
session_start();

// Generate random CAPTCHA value
$captcha = substr(md5(rand()), 0, 6);

// Store CAPTCHA value in session
$_SESSION['captcha'] = $captcha;

// Generate CAPTCHA image
$image = imagecreatetruecolor(100, 30);
$bgColor = imagecolorallocate($image, 255, 255, 255);
$textColor = imagecolorallocate($image, 0, 0, 0);
imagestring($image, 5, 5, 5, $captcha, $textColor);

// Set appropriate headers to output image
header('Content-Type: image/png');

// Output image
imagepng($image);
imagedestroy($image);
?>

