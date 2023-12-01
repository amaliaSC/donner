<?php

include_once("connect.php");

// source https://geo.api.gouv.fr/decoupage-administratif
/* tutos
 https://reqbin.com/req/php/c-vdhoummp/curl-get-json-example
 https://www.delftstack.com/fr/howto/php/how-to-use-curl-to-get-json-data-and-decode-json-data-in-php/
 */

//récupère les données
function getDatas(string $urlApi): array
{
    $handle = curl_init($urlApi);
    curl_setopt($handle, CURLOPT_URL, $urlApi);
    curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);

    $resp = curl_exec($handle);
    if ($resp) {
        curl_close($handle);

        // //converti les données json en array PHP
        $datasApi = json_decode($resp, true);
        // var_dump($datasApi);
        return $datasApi;
    }
    return [];
}

// REGIONS
$urlRegions = "https://geo.api.gouv.fr/regions";

$datasRegions = getDatas($urlRegions);
if (!empty($datasRegions)) {

    $nbInsertRegions = 0;
    foreach ($datasRegions as $key => $region) {
        $query = $pdo->prepare('INSERT INTO region (id, name) VALUES (:id, :name);');
        $query->bindParam(':id', $region['code']);
        $query->bindParam(':name', $region['nom']);
        if ($query->execute() == 0) {
            echo "Erreur lors de l'insertion des régions.";
            break;
        } else {
            $nbInsertRegions++;
        }
    }
    echo "<hr>Nombre de régions insérées : " . $nbInsertRegions;
} else {
    echo "<hr>Echec de la récupération des régions.";
}

// DEPARTEMENTS
$urlDep = "https://geo.api.gouv.fr/departements";

$datasDep = getDatas($urlDep);

if (!empty($datasDep)) {
    $nbInsertDep = 0;

    foreach ($datasDep as $key => $dep) {
        $query = $pdo->prepare('INSERT INTO department (number, name, region_id) VALUES (:number, :name, :region_id);');
        $query->bindParam(':number', $dep['code']);
        $query->bindParam(':name', $dep['nom']);
        $query->bindParam(':region_id', $dep['codeRegion']);
        if ($query->execute() == 0) {
            echo "Erreur lors de l'insertion des départements.";
            break;
        } else {
            $nbInsertDep++;
        }
    }
    echo "<hr>Nombre de départements insérés : " . $nbInsertDep;
} else {
    echo "<hr>Echec de la récupération des départements.";
}

// VILLES
$urlCities = "https://geo.api.gouv.fr/communes";

$datasCities = getDatas($urlCities);

if (!empty($datasCities)) {
    $nbInsertCities = 0;

    foreach ($datasCities as $key => $city) {
        if (!empty($city['codesPostaux'] && !empty($city['codeDepartement']))) {
            $query = $pdo->prepare('INSERT INTO city (zip_code, name, department_id) VALUES (:code, :name, (SELECT id FROM department WHERE number = :department_id));');
            $query->bindParam(':code', $city['codesPostaux'][0]);
            $query->bindParam(':name', $city['nom']);
            $query->bindParam(':department_id', $city['codeDepartement']);
            if ($query->execute() == 0) {
                echo "Erreur lors de l'insertion des villes.";
                break;
            } else {
                $nbInsertCities++;
            }
        }
    }
    echo "<hr>Nombre de villes insérées : " . $nbInsertCities;
} else {
    echo "<hr>Echec de la récupération des villes.";
}
