<?php

$handle = fopen("list.txt", "r");
if ($handle) {
    while (($line = fgets($handle)) !== false) {
        list($number, $country, $percapita) = explode(';', $line);
    }

    fclose($handle);
} else {
    throw new Exception('Não abriu não');
}
?>