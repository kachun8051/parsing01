<?php
    $jsonFile = file_get_contents('customers.json');
    $decodedJson = json_decode($jsonFile, true);
    echo("third country: " . $decodedJson['customers'][0]['countries'][2]['name']);
    $arrCustomer = $decodedJson['customers'];
    $output = "<table style='border: 1px solid black;'>";
    $output .= "<tr><th>Name</th><th>Email</th><th>Age</th><th>Countries</th></tr>";
    foreach ($arrCustomer as $customer) {
        //echo('<br/>'.$customer['name']);
        $name = $customer['name'];
        $email = $customer['email'];
        $age = $customer['age'];
        $arrCountry = $customer['countries'];
        $output .= "<tr style='outline: thin solid'><td>".$name."</td><td>".$email."</td><td>".$age."</td><td>";
        $outputinLoop = '<ol>';
        foreach ($arrCountry as $country) {
            $cname = $country['name'];
            $year = $country['year'];
            $outputinLoop .= "<li>".$cname."<br/>".$year."</li>";
        }        
        $outputinLoop .= "</ol>";
        $output .= $outputinLoop . "</tr>";
    }
    $output .= "</table>";
    echo $output;
?>