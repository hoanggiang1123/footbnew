<?php
global $league_data;
$league_data = [
    [
        'name' => 'Ngoại Hạng Anh',
        'logo' => BDTT_PLUGIN_URL. 'img/17.png',
        'slug' => 'ngoai-hang-anh/'
    ],
    [
        'name' => 'La Liga',
        'logo' => BDTT_PLUGIN_URL. 'img/8.png',
        'slug' => 'laliga/'
    ],
    [
        'name' => 'Serie A',
        'logo' => BDTT_PLUGIN_URL. 'img/23.png',
        'slug' => 'serie-a/'
    ],
    [
        'name' => 'Bundesliga',
        'logo' => BDTT_PLUGIN_URL. 'img/35.png',
        'slug' => 'bundesliga/'
    ],
    [
        'name' => 'Ligue 1',
        'logo' => BDTT_PLUGIN_URL. 'img/34.png',
        'slug' => 'ligue-1/'
    ],
    [
        'name' => 'V-League',
        'logo' => BDTT_PLUGIN_URL. 'img/626.png',
        'slug' => 'v-league/'
    ],
    [
        'name' => 'Champions League',
        'logo' => BDTT_PLUGIN_URL. 'img/7.png',
        'slug' => 'champions-league/'
    ],
    [
        'name' => 'Europa League',
        'logo' => BDTT_PLUGIN_URL. 'img/679.png',
        'slug' => 'europa-league/'
    ]
    
];
global $mapType;
$mapType = [
    'result' => [
        'name' => 'Kết quả',
        'slug' => '/bong-da/ket-qua/kqbd',
        'sum' => 'Kết quả',
        'id' => 'kq-tab',
        'target' => 'kq'
    ],
    'schedule' => [
        'name' => 'Lịch thi đấu',
        'slug' => '/bong-da/lich-thi-dau/ltd',
        'sum' => 'LTĐ',
        'id' => 'ltd-tab',
        'target' => 'ltd'
    ],
    'ranking' => [
        'name' => 'Bảng xếp hạng',
        'slug' => '/bong-da/bang-xep-hang/bxh',
        'sum' => 'BXH',
        'id' => 'bxh-tab',
        'target' => 'bxh'
    ]
];