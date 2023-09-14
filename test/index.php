<h1>Тест модуля cdnnow!</h1>
<h4 style="margin: 20px 0 10px">Картинка на стороннем ресурсе</h4>
<img src="https://placehold.co/50" alt="">

<h4 style="margin: 20px 0 10px">Локальные картинки</h4>
<h5 style="margin: 20px 0 10px">Аттрибут src тэга img</h5>
<?php
$imageTypes = [
    'bmp',
    'gif',
    'ico',
    'jpeg',
    'jpg',
    'png',
    'svg',
    'tif',
    'tiff',
    'webp',
];

$otherTypes = [
    'otf',
    'ttf',
    'woff',
    'woff2',
    'gz',
    'rar',
    'z',
    'zip',
    'aac',
    'flac',
    'mp3',
    'ogg',
    'wav',
    'avi',
    'flv',
    'mkv',
    'mp4',
    'mpeg',
    'oga',
    'ogv',
    'ogx',
    'vob',
    'webm',
    'swf',
    'json',
    'wsdl',
    'xsd',
    'xml',
    'csv',
    'doc',
    'docx',
    'odp',
    'ods',
    'odt',
    'pdf',
    'pps',
    'ppsm',
    'ppsx',
    'ppt',
    'pptm',
    'pptx',
    'sldm',
    'sldx',
    'txt',
    'xls',
    'xlsx',
    'com',
    'exe',
    'apk',
];

echo "<ul>\n";
foreach ($imageTypes as $type) {
    $image = "/local/modules/cdnnow.core/test/images/a.$type";
    echo "<li><img style=\"max-width: 100px; margin: 5px;\" src='$image'</li>\n";
}
echo "</ul>\n";
?>

<h5 style="margin: 20px 0 10px">Аттрибут href тэга a</h5>
<?php
echo "<ul>\n";
foreach ($imageTypes as $type) {
    $image = "/local/modules/cdnnow.core/test/a.$type";
    echo "<li><a href = '$image'>$type</a></li>\n";
}
echo "</ul>\n";
?>

<h5 style="margin: 20px 0 10px">Стиль background-image</h5>
<?php
$randomQuote = ['\'', ''];
echo "<ul>\n";
foreach ($imageTypes as $type) {
    $quote = $randomQuote[array_rand($randomQuote)];
    $image = "/local/modules/cdnnow.core/test/images/a.$type";
    echo "<li><div style=\"width: 100px; height: 100px; background-image: url(" . $quote . $image . $quote . ")\"></div></li>\n";
}
echo "</ul>\n";
?>

<h5 style="margin: 20px 0 10px">Остальные файлы</h5>
<?php
echo "<ul>\n";
foreach ($otherTypes as $type) {
    $path = "/local/modules/cdnnow.core/test/other/a.$type";
    echo "<li><a href='$path'>a.$type</a></li>\n";
}
echo "</ul>\n";
?>


<hr style="margin: 100px 0;"/>
