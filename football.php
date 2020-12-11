<?php
/*
Plugin Name: New FootBall 
Plugin URI: https://blog.bdtt.tv/
Description: Bdtt plugins for FootBall Schedule, Results And Ranking
Author: DevG
Version: 1.0
Author URI: https://blog.bdtt.tv/
 */

define('BDTT_PLUGIN_URL', plugin_dir_url(__FILE__));
define('BDTT_PLUGIN_DIR', plugin_dir_path(__FILE__));

function bdttPostExist($slug) {
    global $wpdb;
    $id = $wpdb->get_var($wpdb->prepare("SELECT ID FROM " . $wpdb->posts . " WHERE post_name = '%s' AND ( post_type = 'page' OR post_type = 'post') ", $slug) );
    if($id) return true;
    return false;
}

function createWeekDate() {
    $monday = strtotime('last monday');
    $monday = date('w', $monday) === date('w') ? $monday + 7 * 86400 : $monday;

    $tuesday = strtotime(date('Y-m-d', $monday)." +1 days");
    $wednesday = strtotime(date('Y-m-d', $monday)." +2 days");
    $thurday = strtotime(date('Y-m-d', $monday)." +3 days");
    $friday = strtotime(date('Y-m-d', $monday)." +4 days");
    $saturday = strtotime(date('Y-m-d', $monday)." +5 days");
    $sunday = strtotime(date('Y-m-d', $monday)." +6 days");
    $monday1 = strtotime(date('Y-m-d', $monday)." +7 days");
    $tuesday1 = strtotime(date('Y-m-d', $monday)." +8 days");
    $wednesday1 = strtotime(date('Y-m-d', $monday)." +9 days");
    $thurday1 = strtotime(date('Y-m-d', $monday)." +10 days");
    $friday1 = strtotime(date('Y-m-d', $monday)." +11 days");
    $saturday1 = strtotime(date('Y-m-d', $monday)." +12 days");
    $sunday1 = strtotime(date('Y-m-d', $monday)." +13 days");

    $dates = [
        'Thứ 2' => date('d-m-Y', $monday),
        'Thứ 3' => date('d-m-Y', $tuesday),
        'Thứ 4' => date('d-m-Y', $wednesday),
        'Thứ 5' => date('d-m-Y', $thurday),
        'Thứ 6' => date('d-m-Y', $friday),
        'Thứ 7' => date('d-m-Y', $saturday),
        'Chủ nhật' => date('d-m-Y', $sunday),
        'Thứ 21' => date('d-m-Y', $monday1),
        'Thứ 31' => date('d-m-Y', $tuesday1),
        'Thứ 41' => date('d-m-Y', $wednesday1),
        'Thứ 51' => date('d-m-Y', $thurday1),
        'Thứ 61' => date('d-m-Y', $friday1),
        'Thứ 71' => date('d-m-Y', $saturday1),
        'Chủ nhật1' => date('d-m-Y', $sunday1)
    ];
    $res = [];

    foreach($dates as $key => $date) {
        if(date('d-m-Y') === $date) {
            $res[] = ['name' => 'Hôm nay', 'date' => $date];
        } else {
            $res[] = ['name' => str_replace('1', '', $key), 'date' => $date];
        }
    }
    return $res;
}

require BDTT_PLUGIN_DIR. '/constant.php';

require BDTT_PLUGIN_DIR. '/page-template.php';

require BDTT_PLUGIN_DIR. '/pre-active.php';
new BDTT_ACTIVATION(__FILE__);

// require BDTT_PLUGIN_DIR. '/sport/sport_html.php';

require BDTT_PLUGIN_DIR. '/sport/sport_crawl.php';

require BDTT_PLUGIN_DIR. '/sport/sport_ajax.php';