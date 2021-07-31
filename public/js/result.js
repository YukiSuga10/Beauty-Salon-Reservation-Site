function initMap() {
    // welcome.blade.phpで描画領域を設定するときに、id=mapとしたため、その領域を取得し、mapに格納します。
    map = document.getElementById("map");
    
    var address = document.getElementById("address");
    // 東京タワーの緯度は35.6585769,経度は139.7454506と事前に調べておいた
    let tokyoTower = {lat: 35.6585769, lng: 139.7454506};
    // オプションを設定
    opt = {
        zoom: 13, //地図の縮尺を指定
        center: tokyoTower, //センターを東京タワーに指定
    };
    // 地図のインスタンスを作成します。第一引数にはマップを描画する領域、第二引数にはオプションを指定
    mapObj = new google.maps.Map(map, opt);
    
    marker = new google.maps.Marker({
        // ピンを差す位置を決めます。
        position: tokyoTower,
	// ピンを差すマップを決めます。
        map: mapObj,
	// ホバーしたときに「tokyotower」と表示されるようにします。
        title: 'tokyotower',
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

