<?php

/*
 * list.txt contains list of all countries by GDP (nominal) per capita.
 * font: http://en.wikipedia.org/wiki/List_of_countries_by_GDP_(nominal)_per_capita
 * CIA World Factbook (2013)
 *
 * I excluded countries without rank.
 */


DEFINE('CLIENT_ID', '');
DEFINE('CLIENT_SECRET', '');
DEFINE('GITHUB_API_URL', 'https://api.github.com/search/users?q=repos:%3E0+location:{COUNTRYNAME}+sort:followers&client_id=' . CLIENT_ID . '&client_secret=' . CLIENT_SECRET);

$i = 0;
$result = [];

$handle = fopen('list.txt', 'r');
if ($handle) {
    while (($line = fgets($handle)) !== false) {
        list($number, $country, $perCapita) = explode(';', $line);

        /*
         * Sanitize var from list.
         */
        $perCapita = floatval(str_replace(',', '', $perCapita));
        $country = str_replace(' ', '%20', $country);

        $url = str_replace('{COUNTRYNAME}', $country, GITHUB_API_URL);

        /*
         * Set User Agent so github wont block me.
         */
        $options = array('http' => array('user_agent' => $_SERVER['HTTP_USER_AGENT']));
        $context = stream_context_create($options);
        /*
         * Get info from Github and create an associative array form it.
         */
        $response = file_get_contents($url, false, $context);

        $objGithub = json_decode($response, true);

        echo $country . '  -   ' . $objGithub['total_count'] . '<br>';
        $result[$country] = $response;

        $i++;

        /*
         * Avoid API limit :(
         */
        if ($i > 15) {
            $i = 0;
            sleep(60);
        }
    }

    fclose($handle);
} else {
    throw new Exception('Problem opening file');
}

file_put_contents('result.txt',serialize($result));

?>