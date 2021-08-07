 var addressStr = document.getElementById("address").value;
 
  $(document).ready(function(){
          // Gmapsを利用してマップを生成
          var map = new GMaps({
              div: '#map',
              lat: -12.043333,
              lng: -77.028333
          });
 
          // 住所からマップを表示
          GMaps.geocode({
              address: addressStr.trim(),
              callback: function(results, status) {
                  if (status == 'OK') {
                      var latlng = results[0].geometry.location;
                      map.setCenter(latlng.lat(), latlng.lng());
                      map.addMarker({
                          lat: latlng.lat(),
                          lng: latlng.lng()
                      });
                  }
              }
          });
      });