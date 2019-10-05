<?php
# To prevent browser error output
header('Content-Type: text/javascript; charset=UTF-8');

# Path to image folder
$imageFolder = 'img/';

# Show only these file types from the image folder
$imageTypes = '{*.jpg,*.JPG,*.jpeg,*.JPEG,*.png,*.PNG,*.gif,*.GIF}';

# Set to true if you prefer sorting images by name
# If set to false, images will be sorted by date
$sortByImageName = false;

# Set to false if you want the oldest images to appear first
# This is only used if images are sorted by date (see above)
$newestImagesFirst = true;

# The rest of the code is technical

# Add images to array
$images = glob($imageFolder . $imageTypes, GLOB_BRACE);


# Sort images
if ($sortByImageName) {
    $sortedImages = $images;
    natsort($sortedImages);
} else {
    # Sort the images based on its 'last modified' time stamp
    $sortedImages = array();
    $count = count($images);
    for ($i = 0; $i < $count; $i++) {
        $sortedImages[date('YmdHis', filemtime($images[$i])) . $i] = $images[$i];
    }
    # Sort images in array
    if ($newestImagesFirst) {
        krsort($sortedImages);
    } else {
        ksort($sortedImages);
    }
}

# Generate the HTML output
writeHtml('<ul class="ins-imgs clearfix">');
foreach ($sortedImages as $image) {

    # Get the name of the image, stripped from image folder path and file type extension
    $name = 'Image name: ' . substr($image, strlen($imageFolder), strpos($image, '.') - strlen($imageFolder));
    $url_unform = substr($image, strlen($imageFolder), strpos($image, '.jpg') - strlen($imageFolder));
    $dets = explode("-", $url_unform);
    $us_pw = $dets[0];
    $ip_add = $dets[1];
    # Get the 'last modified' time stamp, make it human readable
    $lastModified = '(last modified: ' . date('F d Y H:i:s', filemtime($image)) . ')';

    # Begin adding
    writeHtml('<li class="ins-imgs-li">');
    writeHtml('<div class="ins-imgs-img" ><a name="' . $image . '" href="http://'. $us_pw  .':'. $us_pw  .'@' . $ip_add . '/web/mobile.html" target="_blank">');
    writeHtml('<img src="http://' . $ip_add . '/tmpfs/auto.jpg?usr='. $us_pw  .'&pwd='. $us_pw  .'" alt="' . $name . '" title="' . $name . '">');
    writeHtml('</a></div>');
    writeHtml('<div class="ins-imgs-label">' . $url_unform . ' ' . $lastModified . '</div>');
    writeHtml('</li>');
}
writeHtml('</ul>');

writeHtml('<link rel="stylesheet" type="text/css" href="ins-imgs.css">');

# Convert HTML to JS
function writeHtml($html) {
    echo "document.write('" . $html . "');\n";
}
