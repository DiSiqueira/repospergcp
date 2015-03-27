<?php

$result = unserialize(file_get_contents('result.txt'));

$finalResult = [];

foreach ($result as $key => $value) {
    $finalResult[$key] = json_decode($value, true);
}

$maxGDP = 159400;
$maxRepo = 28335;
$minPop = 11323;
$rank = [];
$pop = [];


$handle = fopen('pop.txt', 'r');
if ($handle) {
    while (($line = fgets($handle)) !== false) {
        list($country, $population) = explode(';', $line);

        $population = floatval(str_replace(',', '', $population));
        $country = str_replace(' ', '%20', $country);

        $pop[$country] = $population;


    }
    fclose($handle);
} else {
    throw new Exception('Problem opening file');
}


$handle = fopen('list.txt', 'r');
if ($handle) {
    while (($line = fgets($handle)) !== false) {
        list($number, $country, $perCapita) = explode(';', $line);

        $perCapita = floatval(str_replace(',', '', $perCapita));
        $country = str_replace(' ', '%20', $country);

        /*
         * One-time-run
         */
//        if($finalResult[$country]['total_count'] > $maxRepo)
//        {
//            $maxRepo = $finalResult[$country]['total_count'];
//        }
//        if($perCapita > $maxGDP)
//        {
//            $maxGDP = $perCapita;
//        }
//        if ($pop[$country] < $minPop)
//        {
//            $minPop = $pop[$country];
//        }

        $xPerCapita = 100 * $perCapita / $maxGDP;
        $xRepo = 100 * $finalResult[$country]['total_count'] / $maxRepo;
        $xPop = 100*$minPop/$pop[$country];
        $rank[$xPerCapita + $xRepo+$xPop][] = $country;



    }

    fclose($handle);
} else {
    throw new Exception('Problem opening file');
}

//var_dump($maxRepo); //int(int(28335))
//var_dump($maxGDP);  //float(159400)
//var_dump($minPop); // float(11323)

/*
 * Order by greater rank
 */
ksort($rank);
var_dump($rank);