<?php
session_start();

// Check if a refresh parameter is present
if (isset($_GET['refresh']) && $_GET['refresh'] === 'true') {
    // Generate a new CAPTCHA value
    $captcha = substr(md5(rand()), 0, 6);

    // Store the new CAPTCHA value in session
    $_SESSION['captcha'] = $captcha;
} else {
    // Use the existing CAPTCHA value if not refreshing
    $captcha = isset($_SESSION['captcha']) ? $_SESSION['captcha'] : '';
}

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

