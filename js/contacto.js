let map;



function iniciarMap(){
  const map = new google.maps.Map(document.getElementById("map"), {
    zoom: 16,
    center: { lat: 40.619955, lng: -0.101904 },
    mapTypeId: "terrain",
  });
  const marker=[
    {lat: 40.621384887575175, lng: -0.09908491418811713}
    ]
  
  const newMarker = new google.maps.Marker({
    position: marker[0],
    map: map,
  });
  newMarker.setMap(map);
}