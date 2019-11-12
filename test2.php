<?php
require('recaptcha/autoload.php');
//$monture ="VOGAROFT";
if(isset($_GET['monture'])){
  $monture = $_GET['monture'];
}else {
  $monture ="VOGAROFT";
}

$ip_addr = $_SERVER['REMOTE_ADDR'];
$geoplugin = unserialize( file_get_contents('http://www.geoplugin.net/php.gp?ip='.$ip_addr) );
if ( is_numeric($geoplugin['geoplugin_latitude']) && is_numeric($geoplugin['geoplugin_longitude']) ) {

$lat = $geoplugin['geoplugin_latitude'];
$long = $geoplugin['geoplugin_longitude'];

//echo $ip_addr.';'.$lat.';'.$long;

}else {
  $lat= '48.8980015';
  $long = '2.2649493';
}
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

//$address = getaddress($lat,$long);

?>
    <script type="text/javascript">
        function maPosition(position) {
            var infopos = "\n";
            infopos += +position.coords.latitude + "\n";
            infopos += +position.coords.longitude + "\n";
            console.log(infopos);
        }

        if (navigator.geolocation){
            var pos = navigator.geolocation.getCurrentPosition(maPosition);
            
        }
    </script>