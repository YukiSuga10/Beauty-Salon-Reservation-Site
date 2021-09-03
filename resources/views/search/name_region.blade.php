<div class="col-md-3">
    <div class="card" style="margin-bottom:10px;">
        <div class="card-header" >美容院名から検索</div>
            <div class="card-body">
                <div class = "search">
                    <form method="POST", action="/salon/search_admin">
                        @csrf
                        <input type = "text" name = "search[salonName]" placeholder = "美容院名" size =15>
                        <input type = "submit" value = "検索">
                    </form>
                </div>
            </div>
    </div>
    <div class="card" style="margin-bottom:10px;">
        <div class="card-header">地域から検索</div>
            <div class="card-body">
                <div class = "search[]">
                    <form method="POST", action="/salon/search_region">
                        @csrf
                        <input type = "radio" name = "search[]" value="北海道" >北海道
                        <input type = "radio" name = "search[]" value="東北" style="margin-left:10px">東北
                        <br>
                        <input type = "radio" name = "search[]" value="北信越">北信越
                        <input type = "radio" name = "search[]" value="関東" style="margin-left:10px">関東　
                        <br>
                        <input type = "radio" name = "search[]" value="東海">東海
                        <input type = "radio" name = "search[]" value="中国" style="margin-left:24px">中国
                        <br>
                        <input type = "radio" name = "search[]" value="関西">関西
                        <input type = "radio" name = "search[]" value="四国" style="margin-left:24px">四国
                        <br>
                        <input type = "radio" name = "search[]" value="九州・沖縄"　>九州・沖縄
                        <br>
                        <input type = "submit" value = "検索" style="margin-top:10px">
                    </form>
                </div>
        </div>
    </div>
    
</div>