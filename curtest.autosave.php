<?php
$url = "https://europe-west2-angel-eyes-pixel-perfect.cloudfunctions.net/api/bookings/create";
$params = array(
	"name" => "caroline",
	"email" => "caroline@gmail.com",
	"street" => "1 rue cithulu ",
	"city" => "disneyland",
	"zipCode" => "666",
	"country" => "France",
	"phoneNumber" => "+30 183456789",
	"cellular" => "+33 532859534556",
	"dueDate" => "2020-01-10",
	"items" =>array("MOSQUELETEC4"),
	"storeId" =>"C09468232223",
	"notes" => "I want a pice oeef cake !",
	"storeName" => "mon magasin2",
	"storeCity" => "Paris",
	"storeStreet" => "rue machin",
	"storeZipCode" => "75088908",
	"storePhone" => "+33 539789132319"
);


$json = json_encode($params);
try {
$ch = curl_init($url);
if($ch ===false){
	throw new Exception("Erreur lors d appel url".$url, 1);
	
}
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
curl_setopt($ch, CURLOPT_HTTPHEADER,
    array(
        'Content-Type:application/json',
        'Content-Length: ' . strlen($json)
    )
);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

$reponse = curl_exec($ch);

if($reponse === false){
	throw new Exception("Error lors du curl exec".curl_error($ch),curl_errno($ch));
}else {
	header('Content-type: application/json');
	$reponse = explode(";", $reponse);
	var_dump($reponse);die();
	$reponse_json= str_replace(array("ma=2592000"), "", $reponse[count($reponse)-1]);
	$reponse_json =trim($reponse_json);
	echo json_encode(array("envoi" => $params,"reponse" => json_decode($reponse_json) ));	
}
curl_close($ch);
}catch(Exception $e){
	echo 'Exception reçue : ',  $e->getMessage(), "\n";
	trigger_error(sprintf("echec Curl  avec l erreur #%d: %s",$e->getCode(),$e->getMessage()),E_USER_ERROR);
} finally {
	ob_start();
	echo $reponse;
	ob_clean();
}
?>