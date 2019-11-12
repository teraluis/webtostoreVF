<?php
header('Content-Type: application/json');
$nom = trim($_POST['nom']);
$prenom = trim($_POST['prenom']);

$ville = trim($_POST['ville']);
$departement = $_POST['departement'];
$mail = trim($_POST['mail']);
$portable = trim($_POST['telephone']);
$newsletter = $_POST['newsletter'];
$date = $_POST['date'];
$opticien = trim($_POST['opticien']);
$direcion=trim($_POST['direcion']);
$ciudad = trim($_POST['ciudad']);

sleep(1);
$data = array("nom" => $nom ,"prenom"=>$prenom,"ville" => $ville, "departement" => $departement , "mail" => $mail ,
	"portable" => $portable,
	"newsletter" => $newsletter,
	"date" => $date,
	"opticien" =>$opticien,
	"adresse" =>$direcion,
	"ciudad" =>$ciudad
);
     // Plusieurs destinataires
     $to  = $mail; // notez la virgule

     // Sujet
     $subject = 'Prisse de RDV';

     // message
     $message = '
     <html>
      <head>
       <title>Prisse de RDV</title>
      </head>
      <body>
       <p>Merci d\'avoir pris rdv chez nous pour une seance d\'esseyage des montures</p>
       <table>
        <tr>
         <th>Opticien</th><th>Adresse</th><th>Date</th>
        </tr>
        <tr>
         <td>'.$opticien.'</td><td>3</td><td>'.$direcion.' '.$ville.'</td><td>'.$date.'</td>
        </tr>
       </table>
      </body>
     </html>
     ';

     // Pour envoyer un mail HTML, l'en-tête Content-type doit être défini
     $headers[] = 'MIME-Version: 1.0';
     $headers[] = 'Content-type: text/html; charset=iso-8859-1';

     // En-têtes additionnels
     $headers[] = 'To: MR <mr@example.com>, Mr <mr@example.com>';
     $headers[] = 'From: vinylfactory <vinylfactory@vinylfactory.com>';
     $headers[] = 'Cc: vinylfactory@vinylfactory.com';
     $headers[] = 'Bcc: vinylfactory@vinylfactory.com';

     // Envoi
     mail($to, $subject, $message, implode("\r\n", $headers));
echo json_encode($data);


?>