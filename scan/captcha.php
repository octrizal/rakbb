<?php
session_start();

// Set the correct CAPTCHA answer for internal use
$_SESSION["captcha_code"] = "17845";

// Generate a random number for the CAPTCHA image
$random_code = rand(1000, 9999);

header("Content-type: image/png");
$image = imagecreate(60, 30);
$background = imagecolorallocate($image, 255, 255, 255);
$text_color = imagecolorallocate($image, 0, 0, 0);
imagestring($image, 5, 5, 5, $random_code, $text_color);
imagepng($image);
imagedestroy($image);
?>
