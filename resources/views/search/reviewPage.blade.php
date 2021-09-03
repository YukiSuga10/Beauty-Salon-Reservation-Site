<div class="col-md-3">
    <div class="card" style="margin-bottom:10px;">
        <div class="card-header" >メニューから検索</div>
            <div class="card-body">
                <div class = "search"　>
                <form method="POST", action="/salon/search_admin">
                    @csrf
                    <input type = "text" name = "search[salonName]" placeholder = "美容院名" size =15>
                    <input type = "submit" value = "検索">
                </form>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">点数から検索</div>
            <div class="card-body">
            <div class = "search[]">
                <form method="POST", action="/salon/search_region">
                    @csrf
                    <input type = "radio" name = "search[]" value="北海道" >5点
                    
                    <br>
                    <input type = "radio" name = "search[]" value="北信越">4点
                    
                    <br>
                    <input type = "radio" name = "search[]" value="東海">3点
                    
                    <br>
                    <input type = "radio" name = "search[]" value="関西">2点
                    
                    <br>
                    <input type = "radio" name = "search[]" value="九州・沖縄">1点
                    
                </form>
            </div>
            
        </div>
    </div>
</div>