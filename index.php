<?php
require('recaptcha/autoload.php');
//$monture ="VOGAROFT";
if(isset($_GET['monture'])){
  $monture = $_GET['monture'];
}else {
  $monture ="VOGAROFT";
}

$ip_addr = $_SERVER['REMOTE_ADDR'];
/*$geoplugin = unserialize( file_get_contents('http://www.geoplugin.net/php.gp?ip='.$ip_addr) );
if ( is_numeric($geoplugin['geoplugin_latitude']) && is_numeric($geoplugin['geoplugin_longitude']) ) {

$lat = $geoplugin['geoplugin_latitude'];
$long = $geoplugin['geoplugin_longitude'];

//echo $ip_addr.';'.$lat.';'.$long;

}else {
  $lat= '48.8980015';
  $long = '2.2649493';
}*/
  $lat= '48.8879594';
  $long = '2.2683648';
  function getaddress($lat,$lng)
  {
     $url = 'https://maps.googleapis.com/maps/api/geocode/json?latlng='.trim($lat).','.trim($lng).'&key=AIzaSyCw7IF5dgrLYfevSM2pHzENz0ungw0dt88';
     $json = @file_get_contents($url);
     $data=json_decode($json);
     $status = $data->status;
     if($status=="OK")
     {
       return $data->results[0]->formatted_address;
     }
     else
     {
       return false;
     }
  }

$address = getaddress($lat,$long);

?>
<html>
<head>
  <title>Store Locator</title>
  <meta charset="utf-8">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <link rel="stylesheet" type="text/css" href="test.css">
  <link rel="icon" href="favicon.ico" />
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
  <script type="text/javascript">
  	var monture = <?php echo "\"".$monture."\""; ?>;
    var lat = <?php echo "\"".$lat."\""; ?>;
    
    var long =<?php echo "\"".$long."\""; ?>;
    
    var address =<?php echo "\"".$address."\""; ?>;
  </script>
<head>
<body>
  
  <div style="margin-left: -0.5%">
      <div class="modal-body" id="modal-body" >
        <br>
        <div style="text-align: center;">
        <img src="img/livraison_vinyl.jpg" width="150px">
        <br><br>
        <h4 style="width: 100%;max-width: 100%">NOUS VOUS ENVOYONS LES LUNETTES SANS ENGAGEMENT <br> POUR ESSAYAGE A LA BOUTIQUE DE VOTRE CHOIX</h4>
    
        <span class="lien">SÉLECTIONNEZ LA BOUTIQUE DANS LAQUELLE VOUS SOUHAITEZ RÉCUPÉRER LA MONTURE</span>
        </div>
        <form id="form" style="width: 50%;position: relative;">
          <div class="form-group" >
            <label for="recipient-name" class="col-form-label">Rechercher:</label>
            <input type="text" class="form-control" id="search" placeholder="Ville,Pays,CP" >
            <i class="fas fa-search"></i>
          </div>
        </form>
        <button class="meLocaliser" onclick="getLocation()">
          <i class="fas fa-map-marker-alt"></i>
        &nbsp; me géolocaliser
        </button>
        <span id="magasin_selectione"></span>
        <div id="map" class="map"></div>
        <br>
          <div class="alert alert-danger" id="alert_error" role="alert" style="display: none"></div>
          <div class="alert alert-danger" id="alert_error_postal" role="alert" style="display: none"></div>
          <div class="alert alert-danger" id="alert_error_nom" role="alert" style="display: none"></div>
          <div class="alert alert-danger" id="alert_error_prenom" role="alert" style="display: none"></div>
          <div class="alert alert-danger" id="alert_error_ville" role="alert" style="display: none"></div>
          <div class="alert alert-danger" id="alert_error_telephone" role="alert" style="display: none"></div>
          <div class="alert alert-danger" id="alert_error_mail" role="alert" style="display: none"></div>
          <div class="alert alert-danger" id="alert_error_date" role="alert" style="display: none"></div>
        <form id="inscription" method="POST" action="prisse_rdv.php"  style="display: none">
          <h4 style="width: 100%;max-width: 100%;text-align: center;text-transform: uppercase;">INDIQUEZ-NOUS VOS DONNées PERSONNElleS</h4>
          <div class="formulaire" >
          
          <div class="row">
            <div class="col">
              <label for="nom"  class="col-form-label">Nom</label>
              <input type="text" class="form-control" name="nom" placeholder="Nom"  required="true">
            </div>
            <div class="col">
              <label for="prenom"  class="col-form-label">Prénom</label>
              <input type="text" class="form-control" name="prenom" placeholder="Prénom"  required>
            </div>
          </div>
          </div>
          <br>
        <div class="form-row">
          <div class="form-group col-md-6">
            <label for="ville">Ville</label>
            <input type="text" class="form-control" id="ville" name="ville" placeholder=""  required>
          </div>
          <div class="form-group col-md-4">
            <label for="departement">Département</label>
            <select id="departement" class="form-control" name="departement">
            <!--   <option selected>Ain</option> -->
              <script type="text/javascript">
                $.get("departements.json", function(result){
                  //console.log(result.departements);
                    var departements = result.departements; //JSON.stringify(result.departements);
                   
                   for( var departement in departements){
                      let departement_name = departements[departement];
                      //console.log(departement);
                      let name_departement = departement_name.name;
                      if(name_departement=="Paris"){
                        $("#departement").append("<option selected>"+name_departement+" - "+departement+"</option>");
                      }else {
                        $("#departement").append("<option>"+name_departement+" - "+departement+"</option>");
                      }
                    }
                });
              </script>
            </select>
          </div>
          <div class="form-group col-md-2">
            <label for="postal">Code Postal</label>
            <input type="number" class="form-control" id="postal" name="postal" placeholder=""  min="1000" required>
          </div>
        </div>

          <div class="formulaire" >
            <label for="mail" class="col-form-label">Mail</label>
            <input type="mail" class="form-control" id="mail" name="mail"  required>
          </div>
          <div class="formulaire" >
            <label for="telephone" class="col-form-label" >Portable</label>
            
            <input type="tel" class="form-control" id="telephone" name="telephone" placeholder=""   required>
          </div>

          <br>

          <div class="form-group">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="newsletter" checked id="newsletter">
              <label class="form-check-label" for="newsletter">
                J'accepte de recevoir la newsletter et/ou  sms ainsi que les conditions de la RGPD <a href="https://www.vinylfactory.fr/politique-de-protection-des-donnees-personnelles-de-la-societe-angel-eyes/">ici</a>
              </label>
            </div>
          </div>

          <div class="formulaire" >
            <label for="date" class="col-form-label">A quel moment souhaitez-vous passer ?</label>
            <?php $startDate = time(); ?>
            <input type="date" class="form-control" id="date" name="date" min="<?= date("Y-m-d",strtotime('+5 day', $startDate)) ?>" max="<?= date("Y-m-d",strtotime('+2 month', $startDate)) ?>"  required> 
          </div>
          <br><br>
          <div class="g-recaptcha" data-sitekey="6LcaWpgUAAAAAFPVrg_NCw52jidXGWLZVlm2A4wJ"></div>
          <br>
          <div class="formulaire" >
            <label>&nbsp;</label>
          <div  id="commander" class="button" style="text-transform: uppercase;margin-left: 20%;margin-right: 20%;width: auto;text-align: center" >je réserve</div>
         </div>
        </form>
      </div>
      <div class="modal-body" id="modal_body2" style="text-align: center;padding: 15%;display: none">
        <i class="fas fa-check-circle fa-10x"></i><br><br>
        <h4 style="text-transform: uppercase;">NOUS VOUS REMERCIONS POUR VOTRE RÉSERVATION.<br>UN MAIL DE CONFIRMATION VIENT DE VOUS ÊTRE ENVOYÉ.</h4><br>
        <div  id="recapitulatif"></div>
        <p>Pour toute question,
        veuillez nous contacter au +33 (0)1 56 83 03 85
        ou par mail à  <a href="mailto:contact@angeleyes-eyewear.com">contact@angeleyes-eyewear.com</a> </p>
      </div>
      <div class="modal-body" id="error" style="text-align: center;padding: 15%;display: none">
        <i class="fas fa-exclamation-triangle fa-10x"></i><br><br>
        <h4 style="text-transform: uppercase;">Il semblerait qu'il y ai une erreur.  </h4><br>
        
        <p>Pour toute question,
        veuillez nous contacter au +33 (0)1 56 83 03 85
        ou par mail à  <a href="mailto:contact@angeleyes-eyewear.com">contact@angeleyes-eyewear.com</a> </p>
      </div>
      <div class="modal-body" id="chargement" style="text-align: center;padding: 15%;display: none">
        <img src="patienter_fr.gif">
      </div>
  </div>


<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="test.js"></script>
  <script 
  src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCw7IF5dgrLYfevSM2pHzENz0ungw0dt88&callback=initMap">
  google.maps.event.addDomListener(window,'load', initMap);
  </script>
</body>
</html>