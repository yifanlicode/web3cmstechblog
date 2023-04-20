<?php
// Path: app/includes/comments.php

// Start session
if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}

$captcha = rand(1000, 9999);
$_SESSION['captcha'] = $captcha;

// 生成验证码图像
$width = 120;
$height = 40;
$image = imagecreate($width, $height);

// 设置图像背景色
$background_color = imagecolorallocate($image, 255, 255, 255);

// 设置验证码文字颜色
$text_color = imagecolorallocate($image, 0, 0, 0);

// 在图像上写字
imagestring($image, 5, 30, 10, $captcha, $text_color);

// 输出图像
header('Content-type: image/png');
imagepng($image);

// 清除图像
imagedestroy($image);

?>
