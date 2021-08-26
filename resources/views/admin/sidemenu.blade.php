<div class="col-md-2">
    <div class="card">
        <div class="menu_head">
            <div class="card-header"><i class="fas fa-th-list"></i> MENU</div>
        </div>

        <div class="card-body">
            <main class="mt-4">
                @yield('content')
            </main>
            <ul class="menu_content">
            	<li class="menu"><a class = 'new-reserve' href = '/admin/{{ $salon_id }}/register_stylist'><i class="fas fa-cut"></i> 美容師の登録</a></li>
            	<li class="menu"><a class = 'confirm-reserve' href = '/admin/{{ $salon_id }}/info_stylist'><i class="fas fa-cut"></i> 美容師の編集</a></li>
            	<li class="menu"><a class = 'config-time' href = '/admin/{{ $salon_id }}/config_time'><i class="fas fa-cut"></i> 営業時間設定</a></li>
            	<li class="menu"><a class = 'config-menu' href = '/admin/{{ $salon_id }}/config_menu'><i class="fas fa-cut"></i> メニュー設定</a></li>
            	<li class="menu"><a class = 'confirm_calender' href = '/admin/{{ $salon_id }}/show_calender'><i class="fas fa-cut"></i> 予約カレンダーの確認</a></li>
            	<li class="menu"><a class = 'edit_access' href = '/admin/{{ $salon_id }}/admin_access'><i class="fas fa-cut"></i> アクセス編集</a></li>
            	<li class="menu"><a class = 'salon_images' href = '/admin/{{ $salon_id }}/salon_images'><i class="fas fa-cut"></i> 美容院の紹介画像投稿</a></li>
            </ul>
        </div>
    </div>
</div>