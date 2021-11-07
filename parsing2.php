<?php    

    $jsonFile = file_get_contents('facility-bbqs.json');
    $decodedJson = json_decode($jsonFile, true);
    //echo ($decodedJson[0]['District_en']);
    $style = "<style>";
    $style .= "table td { border-top: thin solid; border-bottom: thin solid;}";
    $style .= "table td:first-child { border-left: thin solid; }";
    $style .= "table td:last-child { border-right: thin solid; }";
    $style .= "</style>";
    $output = "<table>";
    $index = 0;
    foreach ($decodedJson as $entry) {
        $index++;
        $output .= "<tr>";
        $output .= "<td>".$index.".</td>";
        $output .= "<td>".$entry["District_en"]."</td>";
        $output .= "<td>".$entry["District_cn"]."</td>";
        $output .= "<td>".$entry["Name_en"]."</td>";
        $output .= "<td>".$entry["Name_cn"]."</td>";
        $output .= "<td>".$entry["Address_en"]."</td>";
        $output .= "<td>".$entry["Address_cn"]."</td>";
        $output .= "<td>".$entry["GIHS"]."</td>";
        $output .= "<td>".$entry["Facilities_en"]."</td>";
        $output .= "<td>".$entry["Facilities_b5"]."</td>";
        $output .= "</tr>";
    }
    $output .= "</table>";
    echo ($style.$output);
?>