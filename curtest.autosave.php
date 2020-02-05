<?php

$url = "https://europe-west2-angel-eyes-pixel-perfect.cloudfunctions.net/api/bookings/create";
$params = array(
	"name" => "luisito",
	"email" => "luisito@gmail.com",
	"street" => "1 rue jean baptiste charcot",
	"city" => "Curbevoie",
	"zipCode" => "9000",
	"country" => "France",
	"phoneNumber" => "+30 123456789",
	"cellular" => "+33 53234556",
	"dueDate" => "2020-01-10",
	"items" =>array("MOSQUELETEC4"),
	"storeId" =>"C0468232223",
	"notes" => "I want a pice of cake !",
	"storeName" => "mon magasin",
	"storeCity" => "Paris",
	"storeStreet" => "rue de champs de elysée",
	"storeZipCode" => "75008",
	"storePhone" => "+33 539132319"
);

$json = json_encode($params);
header('Content-type: application/json');
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, array("customer"=>$json));
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER,
    array(
        'Content-Type:application/json',
        'Content-Length: ' . strlen($json)
    )
);
$reponse = curl_exec($ch);
echo json_encode($reponse);
curl_close($ch);
?>