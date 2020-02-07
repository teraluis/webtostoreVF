<?php
header('Content-type: application/json');
require('recaptcha/autoload.php');

$secret='6LcaWpgUAAAAAHwcYtFilfbsrUGnJWhs857SHs5-';
$recaptcha = new \ReCaptcha\ReCaptcha($secret);
if(isset($_POST['g-recaptcha-response'])){
    $resp = $recaptcha->verify($_POST['g-recaptcha-response']);
    if ($resp->isSuccess()) {
        if(valid_nom($_POST) && valid_phone($_POST) && valid_mail($_POST)){
            $nom = trim($_POST['nom']);
            $prenom = trim($_POST['prenom']);
            $monture =$_POST['monture'];
            $ville = trim($_POST['ville']);
            $departement = $_POST['departement'];
            $mail = trim($_POST['mail']);
            //in variable
            $portable = tel(trim($_POST['telephone']));
            $newsletter = $_POST['newsletter'];
            $date = trim($_POST['date']);
            $date = date("d/m/Y",strtotime($date));
            $opticien = trim($_POST['opticien']);
            $direcion=trim($_POST['direcion']);
            $ciudad = trim($_POST['ciudad']);
            $postal = trim($_POST['postal']);
            $codigo_postal = trim($_POST['codigo_postal']);
            $phoneOpticien = trim($_POST['phoneOpticien']);
            $url = "https://europe-west2-angel-eyes-pixel-perfect.cloudfunctions.net/api/bookings/create";
            $data = array(
                "name" => $nom." ".$prenom ,
                "email" => $mail ,
                "street" =>$direcion,
                "city" => $ville, 
                "zipCode"  => $codigo_postal,
                "country" => "France",
                "phoneNumber" => $portable,
                "cellular" => $portable,
                "dueDate" => date("Y-m-d",strtotime($date)),
                "items" => array($monture),
                "storeId" => "C".$phoneOpticien,
                "notes" => "",
                "storeName" => $opticien,
                "storeCity" => $ciudad,
                "storeStreet" =>$direcion,
                "storeZipCode" => $codigo_postal,
                "storePhone" => $phoneOpticien
            );            
            
/*            $data = array(
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
            );*/
            $json = json_encode($data);
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
                //throw new Exception("Error lors du curl exec".curl_error($ch),curl_errno($ch));
                echo json_encode(array("envoi" => $data,array("reponse" =>"echec","message" => curl_error($ch)." ".curl_errno($ch) ) )); 
            }else {
                
                $reponse = explode(";", $reponse);
                $reponse_json= str_replace(array("ma=2592000"), "", $reponse[count($reponse)-1]);
                $reponse_json =trim($reponse_json);
                $reponse_json = json_decode($reponse_json);
                if( $reponse_json->result=="success" ){
                     $to  =  $mail; 
                     $subject = 'Réservation monture à confirmer';
                     $message = file_get_contents('resa.html');
                     $message = str_replace("#PRENOM#", $prenom, $message);
                     $message = str_replace("#NOM#", $nom, $message);
                     $message = str_replace("#MODELE#", $monture, $message);
                     $message = str_replace("#OPTICIEN#", $opticien, $message);
                     $message = str_replace("#ADRESSE#", $direcion, $message);
                     $message = str_replace("#POSTAL#", $postal, $message);
                     $message = str_replace("#VILLE#", $ville, $message);
                     $message = str_replace("#DATE#", $date, $message);
                     $message = str_replace("#TELEPHONE#", $portable, $message);
                     $message = str_replace("#NUMERORESERVATION#", $reponse_json->payload->id, $message);
                     $headers[] = 'MIME-Version: 1.0';
                     $headers[] = 'Content-type: text/html; charset=UTF-8';
                     $headers[] = 'From: vinylfactory <vinylfactory@vinylfactory.com>';
                     //mail($to, $subject, $message, implode("\r\n", $headers));
                }
                echo json_encode(array("envoi" => $data,"reponse" =>$reponse_json )); 
            }                         
            curl_close($ch);
            }catch(Exception $e){
                //echo 'Exception reçue : ',  $e->getMessage(), "\n";
                //trigger_error(sprintf("echec Curl  avec l erreur #%d: %s",$e->getCode(),$e->getMessage()),E_USER_ERROR);
                
                echo json_encode(array("envoi" => $data,"reponse" =>$reponse_json ));                   
            }       

        }
    } else {
        $errors = $resp->getErrorCodes();
     
        echo json_encode(array("result"=>"ReCaptchaError","desc"=>$errors));
    }
}
function tel($str) {
    if(strlen($str) >= 8) {
    $res = substr($str, 0, 2) .' ';
    $res .= substr($str, 2, 2) .' ';
    $res .= substr($str, 4, 2) .' ';
    $res .= substr($str, 6, 2) .' ';
    $res .= substr($str, 8, 2) .' ';
    return $res;
    }else {
     return $str;
    }
}
function valid_nom($post){
  $valid_taille= strlen($post['nom'])>1;
  
  if(isset($post) && isset($post['nom']) && preg_match("#^([^0-9]*)$#i", $post['nom'])==true && $valid_taille==true){
    return true;
  }else{
    return false;
  }
}
function valid_mail($post){
  $valid_taille= strlen($post['mail'])>3;
  if(isset($post) && isset($post['mail']) && preg_match("#^[0-9a-zA-Z._ éèàçù~^¨-]+@[0-9a-zA-Z_éàù-]{2,}\.[- a-z_]{2,6}$#i", $post['mail'])==true && $valid_taille==true){
    return true;
  }else{
    return false;
  }
}
function valid_date($post){
  $valid_taille= strlen($post['date'])>4;
  if(isset($post) && isset($post['date']) && preg_match("#([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01]))#i", $post['date'])==true && $valid_taille==true){
    return true;
  }else{
    return false;
  }
}
function valid_phone($post){
  $valid_taille= strlen($post['telephone'])>3;
  if(isset($post) && isset($post['telephone']) && preg_match("#^[+]?[0-9]+$#", $post['telephone'])==true && $valid_taille==true ){
    return true;
  }else{
    return false;
  }
}


?>