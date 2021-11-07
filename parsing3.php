<?php
    require_once "dbConn.php";
    //$jsonFile = file_get_contents('facility-bbqs.json');
    $jsonFile = file_get_contents('https://www.lcsd.gov.hk/datagovhk/facility/facility-bbqs.json');
    if (!isset($jsonFile)) {
        die ("Url is invalid. Json file not found!");
    }
    $bbqs = json_decode($jsonFile, true);
    echo "<ol>";
    foreach ($bbqs as $bbq) {
        $district = str_replace("'", "&apos;", $bbq['District_en']);
        //$name = $bbq['Name_en']   .replace("'", "&quot;");
        $district_cn = str_replace("'", "&apos;", $bbq['District_cn']);
        $name = str_replace("'", "&apos;", $bbq['Name_en']);
        $address = str_replace("'", "&apos;", $bbq['Address_en']);  
        $longitude = str_replace("'", "&apos;", $bbq['Longitude']);
        $latitude = str_replace("'", "&apos;", $bbq['Latitude']);        
        $sql = "INSERT INTO tblbbq VALUES ('$name', '$district', '$district_cn', '$address', '$longitude', '$latitude')";
        echo "<li>" . $sql . "</li>";
        if (!$result=$conn->query($sql)) {
            die ("insertion failed");
        }
    }
    echo "</ol>";
    echo "Insertion successfully.<br>";
    $conn->close();

?>