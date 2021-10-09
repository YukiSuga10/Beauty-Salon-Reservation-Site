function initMap() {
    map = document.getElementById("map");
    
    var address = document.getElementById("address").textContent;
    var salonName = document.getElementById("salonName").textContent;
    var geocoder = new google.maps.Geocoder();


    geocoder.geocode({ address : address }, function(results, status){
    if (status == google.maps.GeocoderStatus.OK) {
    var ll = results[0].geometry.location;
    var glat = ll.lat();
    var glng = ll.lng();
    let salon = {lat: glat, lng: glng};
    
    opt = {
        zoom: 13, //地図の縮尺の設定
        center: salon, //センターの設定
    };
    mapObj = new google.maps.Map(map, opt);
    
    marker = new google.maps.Marker({
    //ピンを差す位置の決定
        position: salon,
	// ピンを差すマップ指定
        map: mapObj,
	//ホバー時の表示
        title: salonName,
    });
    
    map.setCenter(ll);
    }else{
    const message = "住所が設定されていません"
    return message;
    }
    });
}


function checkTime(f) {
    var startTime = document.getElementById('startTime').value;
    var startTime = startTime.getTime();
    var endTime = document.getElementById('endTime').value;

    
    if (startTime >"6:00"  && endTime < "20:00"){
        if (startTime < endTime){
            alert(startTime);
            return true
        }else{
            alert('２：開始時刻と終了時刻を正しく入力してください');
            return false;
        }
    }else{
        alert(startTime);
        return false;
    }
    
    
}


function deleteReserve(e){
    'use strict';
    var id = document.getElementById("reserve");
    if(confirm('一度キャンセルすると元には戻せません \n本当にキャンセルしますか？')) {
        document.getElementById(id).submit();
    }else{
        return false;
    }
}

function deleteStylist(e){
    'use strict';
    var id = document.getElementById("stylist");
    if(confirm('一度削除すると元には戻せません \n本当に削除しますか？')) {
        document.getElementById(id).submit();
    }else{
        return false;
    }
}



