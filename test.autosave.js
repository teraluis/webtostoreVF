 var geocoder;
  var map;
  const mapStyle = [
  {
    "featureType": "administrative.land_parcel",
    "elementType": "labels",
    "stylers": [
      {
        "visibility": "off"
      }
    ]
  },
  {
    "featureType": "poi",
    "elementType": "labels.text",
    "stylers": [
      {
        "visibility": "off"
      }
    ]
  },
  {
    "featureType": "poi.business",
    "stylers": [
      {
        "visibility": "off"
      }
    ]
  },
  {
    "featureType": "poi.park",
    "elementType": "labels.text",
    "stylers": [
      {
        "visibility": "off"
      }
    ]
  },
  {
    "featureType": "road.arterial",
    "elementType": "labels",
    "stylers": [
      {
        "visibility": "off"
      }
    ]
  },
  {
    "featureType": "road.highway",
    "elementType": "labels",
    "stylers": [
      {
        "visibility": "off"
      }
    ]
  },
  {
    "featureType": "road.local",
    "stylers": [
      {
        "visibility": "off"
      }
    ]
  },
  {
    "featureType": "road.local",
    "elementType": "labels",
    "stylers": [
      {
        "visibility": "off"
      }
    ]
  }
]
function sanitizeHTML(strings) {
  //const entities = {'&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'};
  const entities = {'&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'};
  let result = strings[0];
  for (let i = 1; i < arguments.length; i++) {
    result += String(arguments[i]).replace(/[]/g, (char) => {
      return entities[char];
    });
    result += strings[i];
  }
  return result;
}
var user_data=[];
function retourne_position(){
  var x = document.getElementById("inscription");
  document.getElementById("magasin_selectione").innerHTML = "magasin selectione "+user_data[0];
  x.style.display = "block";
  window.scrollTo(0,document.querySelector("#inscription").scrollHeight);
  
}
function initMap(){
    codeAddress("37 ue Jean-Baptiste Charcot 92400 Courbevoie");
}
  function codeAddress(address) {
  const map = new google.maps.Map(document.getElementsByClassName('map')[0], {
    zoom: 15,
    center: {lat: 48.897880, lng: 2.266800},
    styles: mapStyle
  });
  map.data.setStyle(feature => {
    return {
      icon: {
        url: `img/vinyl_logo.png`,
        scaledSize: new google.maps.Size(30, 30)
      }
    };
  });
    const apiKey = 'AIzaSyAjK0ZMfrYfOd0vAyXJJW3xf-cmuxRdAeI';
  const infoWindow = new google.maps.InfoWindow();
  infoWindow.setOptions({pixelOffset: new google.maps.Size(0, -30)});
   map.data.loadGeoJson('revendeurs.json');
   geocoder = new google.maps.Geocoder();
    geocoder.geocode( { 'address': address}, function(results, status) {
      if (status == 'OK') {
        map.setCenter(results[0].geometry.location);
        var marker = new google.maps.Marker({
            map: map,
            position: results[0].geometry.location
        });
      
      } else {
        alert('Addresse incorecte: ' + status);
      }
    });
  map.data.addListener('click', event => {

    const opticien = event.feature.getProperty('title');
    const adresse = event.feature.getProperty('rue_revendeurs');
    const postal_code = event.feature.getProperty('code_postal');
    const ville = event.feature.getProperty('ville_revendeurs');
    const phone = event.feature.getProperty('telephone');
    const position = event.feature.getGeometry().get();
    user_data =[];
    user_data.push(opticien);
    user_data.push(adresse);
    user_data.push(ville);
    user_data.push(phone);
    const content = sanitizeHTML`
      <div style="width:300px; margin-bottom:20px;font-size:1em">
      <br>
        <p><b>Opticien:</b> ${opticien}</p>
        <p><b>Adresse:</b> ${adresse}<br/><b>Code Postal :</b> ${postal_code} <br> <b>Ville:</b> ${ville} </p>
        <br>
        <button onclick='retourne_position()' class="button" >Sélectionner</button>
      </div>
    `;

    infoWindow.setContent(content);
    infoWindow.setPosition(position);
    infoWindow.open(map);
  });
  }


$(document).ready(function(){
var today = new Date();
var dd = today.getDate()+3;
var mm = today.getMonth() + 1; //January is 0!

var yyyy = today.getFullYear();
if (dd < 10) {
  dd = '0' + dd;
} 
if (mm < 10) {
  mm = '0' + mm;
} 
var today = yyyy+"-"+mm+"-"+dd;
document.getElementById('date').setAttribute("min",today);
  $(window).keydown(function(event){
    if(event.keyCode == 13) {
      event.preventDefault();
      return false;
    }
  });
  let modal2 = document.getElementById("modal_body2");
  modal2.style.display="none";
  codeAddress("paris");
  var x = document.getElementById("inscription");
  x.style.display = "none";
  $("#search").blur(function(){
    var address = $( this ).val();
    
    var geocode = codeAddress(address);

  });
   
  function submitWebToStore() {
      var fromulaire = $('#inscription');
      var champs = {};
      let tmp = user_data[0];
      tmp = escapeHtml(tmp);
      console.log(tmp);
      champs['opticien']=tmp;
      var tmp2 = escapeHtml(user_data[1]);
      champs['adresse']=tmp2;
      champs['ville']=user_data[2];
      champs['phone']=user_data[3];

      var form = fromulaire.serialize();

      
      $.ajax({
          type: "POST",
          dataType: "json",
          url: "prisse_rdv.php",
          data: form+"&opticien="+champs['opticien']+"&direcion="+champs['adresse']+"&ciudad="+champs['ville'],
          reponseType:'json',
          beforeSend: function () {
            $("#chargement").show();
          },
          success: function (data) {
              $("#chargement").hide();
              var obj=data;
              console.log(obj);
              $("#recapitulatif").text("vous serez attendu à "+obj['adresse']+" le "+obj['date']+" chez "+obj['opticien']);
              let modal = document.getElementById("modal-body");
              modal.style.display="none";
              let modal2 = document.getElementById("modal_body2");
              modal2.style.display="block";
          },
          complete: function () {
            $("#chargement").hide();
          },
          error: function (jqXHR, textStatus, errorThrown) {
              $("#chargement").hide();
              console.log(jqXHR + " :: " + textStatus + " :: " + errorThrown);
          }
      });
  }
  $("#commander").click(function(e){
    console.log("on rentre")
    e.preventDefault();
    if($("#nom").val()!="" 
      && $("#prenom").val()!=""
      && $("#ville").val()!=""
      && $("#mail").val()!="" 
      && $("#telephone").val()!=""
      && $("#date").val()!=""
      ){
      let date =$("#date").val();
      if(validation_jour(date)==true){
        let modal = document.getElementById("modal-body");
        modal.style.display="none";
        $("#chargement").show();
        submitWebToStore();
        return false;
      }else {
        alert("la date ne dois pas être un dimanche ou un lundi et elle doit être 3 jours plus tard à compter de l date d'aujord'hui")
      }

    }else {
      alert("veuillez completer touts les champs  svp.");
    }
    
    });
});
function escapeHtml(text) {
  return text
      .replace(/&amp;/g, "")
      .replace(/&rsquo;/g, "")
      .replace(/&amp;/g, "")
      .replace(/#8211;/g, "")
      .replace(/&/g, "ET")
      .replace(/&#039;/g, "");
}
function validation_jour(date){
  var aujordhui_date = new Date();
  var aujordhui= aujordhui_date.getDate();
  var ce_mois = aujordhui_date.getMonth()+1;
  var cette_annee=aujordhui_date.getFullYear();
  var date = new Date(date);
  var dd = date.getDate();
  var mm = date.getMonth() + 1;
  var yyyy = date.getFullYear();
  var day = date.getDay();

  let date_rdv=(mm*10)+dd;
  let date_courante=(ce_mois*10)+aujordhui;

  var validacion = parseInt(mm*10)+parseInt(date_rdv)>parseInt(ce_mois*10)+parseInt(date_courante+3);
  console.log("validation"+validacion);
  if(day == 0 || day == 1  ){//si c' est dimanche = 0 ou lundi = 1
    return false
  }else {
    if(mm>ce_mois || yyyy>cette_annee){ //valeur absolue du dernier jour du mois - aujordhui
      return true
    }else if(mm<ce_mois) {
      return false;
    }else if(mm==ce_mois) {
      if(validacion==true){
        return true;
      }else {
        return false;
      }
    }
  }
}