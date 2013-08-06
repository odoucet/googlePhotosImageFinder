<?php

if (!isset($argv[1])) {
    exit('Syntax: php '.$argv[0].' <USERID>'."\n"
    ."  This can be find by going to your album list in G+ and looking at URL : \n
      https://plus.google.com/photos/<THIS IS YOUR USERID>/albums?banner=pwa\n"
    );
}

define('USERID', $argv[1]);


// Get feed of all albums : 
$url = 'http://picasaweb.google.com/data/feed/api/user/'.USERID.'/';
$xml = @file_get_contents($url);
if (strlen($xml) < 1024) exit('Cannot retrieve album list :('."\n");

// Get all album ID
$z = preg_match_all('@picasaweb.google.com/data/feed/api/user/'.USERID.'/albumid/([0-9]{1,})@', $xml, $albumlist);
$albumlist = $albumlist[1];
echo "Found ".count($albumlist)." albums\n";

unset($xml);

// We check each album ...
$totalPhotos = 0;
$totalPhotosLarge = 0;
$bigPhotosArray = array();

$i = 0;
foreach ($albumlist as $id) {
    $bigPhotosArray[$id] = 0;
    $url = 'http://picasaweb.google.com/data/feed/api/user/'.USERID.'/albumid/'.$id;
    $xml = @file_get_contents($url);
    if (strlen($xml) < 1024) {
        echo 'Cannot retrieve album content ('.$url.")\n";
        continue;
    }
    
    $dom = new DOMDocument();
    $dom->loadXML($xml);
    $photos = $dom->getElementsByTagName('entry');
    printf("%3s photos in this album (%.0f %%)  \r", $photos->length, ($i/count($albumlist)*100));
    $totalPhotos += $photos->length;
    
    foreach ($photos as $entry) {
        foreach ($entry->childNodes as $no) {
            if ($no->tagName == 'gphoto:width' || $no->tagName == 'gphoto:height') {
                if ($no->nodeValue > 2048) {
                    $bigPhotosArray[$id]++; 
                    $totalPhotosLarge++;
                    break;
                }
            }
        }
    }
    $i++;
}
foreach ($bigPhotosArray as $id => $cpt) {
    if ($cpt == 0) unset($bigPhotosArray[$id]);
}
printf("There is %s albums with images larger than 2048 pixels. ". 
    "This is %s pics on a total of %s.\n", count($bigPhotosArray), $totalPhotosLarge, $totalPhotos);

foreach ($bigPhotosArray as $id => $cpt) {
    printf('%3s photos larger - http://plus.google.com/photos/'.USERID.'/albums/%s'."\n", $cpt, $id);
}
echo "\n";
