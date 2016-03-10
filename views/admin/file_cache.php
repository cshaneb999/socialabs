<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


$filename = getDataPath() . "cache" . "/" . "*";
$files = glob($filename);
foreach ($files as $file) {
    if (file_exists($file)) {
        $contents = gzuncompress(file_get_contents($file));
        if (@unserialize($contents)) {
            $contents = unserialize($contents);
        }
    }
    echo "<p><strong>$file</strong></p>";
    ob_start();
    var_dump($contents);
    $var = ob_get_contents();
    ob_end_clean();
    echo $var;
}    