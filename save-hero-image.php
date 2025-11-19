<?php
// Save the uploaded image
$imageData = file_get_contents('php://input');
if ($imageData) {
    file_put_contents('assets/img/hero-curup.jpg', $imageData);
    echo "Image saved successfully";
} else {
    echo "No image data received";
}
?>