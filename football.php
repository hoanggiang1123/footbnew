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

require BDTT_PLUGIN_DIR. '/constant.php';

function bdttPostExist($slug) {
    global $wpdb;
    $id = $wpdb->get_var($wpdb->prepare("SELECT ID FROM " . $wpdb->posts . " WHERE post_name = '%s' AND ( post_type = 'page' OR post_type = 'post') ", $slug) );
    if($id) return true;
    return false;
}

function createWeekDate() {
    $monday = strtotime('last monday');
    $monday = date('w', $monday) === date('w') ? $monday + 7 * 86400 : $monday;
    $last = [];
    if (date('w') === '1' || date('w')  === '2') {
        $lastsaturday = strtotime(date('Y-m-d', $monday)." -2 days");
        $lastsunday = strtotime(date('Y-m-d', $monday)." -1 days");
        $last = [
            'Thứ 7l' => date('d-m-Y', $lastsaturday),
            'Chủ nhậtl' => date('d-m-Y', $lastsunday)
        ];
        
    }

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
    // $datall = [];

    if (count($last) > 0) {
        $dates = array_merge($last, $dates);
    }

    $res = [];

    foreach($dates as $key => $date) {
        if(date('d-m-Y') === $date) {
            $res[] = ['name' => 'Hôm nay', 'date' => $date];
        } else {
            $res[] = ['name' => preg_replace('/(1|l)/', '', $key), 'date' => $date];
        }
    }
    return $res;
}

function createLeagueInfoTabNav () {
    global $mapType;

    $html = '';

    $html.= '<ul class="nav nav-tabs nav-tabs-custom" id="myTab" role="tablist">';

    foreach ($mapType as $key => $val) {

        $active = $key === 'result' ? 'active': '';

        $html.= '<li class="nav-item"> 
                    <a class="nav-link nav-link-custom '. $active .'" id="'. $val['id'] .'" data-toggle="tab" href="#'. $val['target'] .'" role="tab" aria-controls="'. $val['target'] .'" aria-selected="true">'. $val['sum'] .'</a>
                </li>';
    }

    $html.= '</ul>';

    return $html;
}

function createLeagueInfoTabBody () {
    global $mapType;
    global $league_data;

    $html = '';

    $html.= '<div class="tab-content" id="myTabContent">';

    foreach ($mapType as $key => $val) {

        $active = $key === 'result' ? 'active show': '';

        $html.= '<div class="tab-pane fade '. $active .'" id="'. $val['target'] .'" role="tabpanel">
                    <div class="widget widget-tournament-hot">
                        <ul class="list-tournament">';

        foreach ($league_data as $key1 => $val1) {

            $name = $val['name'] .' '. $val1['name'];
            
            $slug = $val['slug']. '-' . $val1['slug'];

            $html.= '<li> 
                        <img class="lazy img-fluid" src="'. $val1['logo'] .'" alt="'. $name .'"> 
                        <a href="'. home_url() . $slug .'">'. $name .'</a>
                    </li>';
        }

        $html.= '</ul></div></div>';
    }

    $html.= '</div>';

    return $html;
}

function createLeagueInfoTab() {
    $script = '<script>
                    jQuery(document).ready(function($) {
                        function setCookie(name,value,days) {
                            var expires = "";
                            if (days) {
                                var date = new Date();
                                date.setTime(date.getTime() + (days*24*60*60*1000));
                                expires = "; expires=" + date.toUTCString();
                            }
                            document.cookie = name + "=" + (value || "")  + expires + "; Path=/";
                        }
                        function getCookie(name) {
                            var nameEQ = name + "=";
                            var ca = document.cookie.split(";");
                            for(var i=0;i < ca.length;i++) {
                                var c = ca[i];
                                while (c.charAt(0)==" ") {
                                    c = c.substring(1,c.length)
                                }
                                if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
                            }
                            return null;
                        }
                        function eraseCookie(name) {   
                            document.cookie = name +"=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;";
                        }
                        $("#myTab li").click(function(){
                            let id = $(this).find(".nav-link").attr("id");
                            eraseCookie("active_nav");
                            setCookie("active_nav", id, 1);
                        })
                        let id = getCookie("active_nav");
                        if(!id) return false;
                        $("#myTab li a").removeClass("active");
                        $("#myTab li a#"+id).addClass("active");
                        let tabId = $("#myTab li a#"+id).attr("href");
                        $("#myTabContent .tab-pane").removeClass("active show");
                        $("#myTabContent " + tabId).addClass("active show");
                    })
                </script>';
    return createLeagueInfoTabNav() . createLeagueInfoTabBody();
}

function bdtt_excerpt() {
    
    $des = get_post_meta(get_the_ID(), 'rank_math_description', true);

    if(isset($des) && $des !== '') {
        return $des;
    }

    return get_the_excerpt();
}


require BDTT_PLUGIN_DIR. '/page-template.php';

require BDTT_PLUGIN_DIR. '/pre-active.php';
new BDTT_ACTIVATION(__FILE__);

require BDTT_PLUGIN_DIR. '/sport/sport_html.php';

require BDTT_PLUGIN_DIR. '/sport/sport_crawl.php';

require BDTT_PLUGIN_DIR. '/sport/sport_ajax.php';