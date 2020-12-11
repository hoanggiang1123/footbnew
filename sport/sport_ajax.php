<?php

function getContent($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_REFERER, 'auto');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Accept:application/json, text/javascript, */*; q=0.01',
        'X-Requested-With:XMLHttpRequest',
        'Language: en',
        'from: vsite',
        'User-Agent: Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36'
    ));
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    $data = curl_exec($ch);
    curl_close($ch);

    return $data;
}

add_action('wp_ajax_nopriv_AjaxFootballResultByLeague', 'AjaxFootballResultByLeague');
add_action('wp_ajax_AjaxFootballResultByLeague', 'AjaxFootballResultByLeague');

function AjaxFootballResultByLeague() {
    $roundId = isset($_POST['roundId']) ? $_POST['roundId'] : '';
    $leagueId = isset($_POST['leagueId']) ? $_POST['leagueId'] : '';
    $sectionId = isset($_POST['sectionId']) ? $_POST['sectionId'] : '';

    if ($roundId === '' ||  $leagueId === '' || $sectionId === '' ) {
        exit;
    }
    $url = 'https://bongda24h.vn/FootballResult/AjaxFootballResultByLeague?roundId='. $roundId .'&leagueId='.  $leagueId .'&sectionId='.$sectionId;
    $Sport = new BDTT_SPORT_CRAWL();
    $data = $Sport->parseResultScheduleHtml($url);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}


add_action('wp_ajax_nopriv_getRankingData', 'getRankingData');
add_action('wp_ajax_getRankingData', 'getRankingData');

function getRankingData() {
    $slug = isset($_POST['slug']) ? $_POST['slug'] : '';

    if ($slug === '') exit;
    $Sport = new BDTT_SPORT_CRAWL();
    $data = $Sport->getRankingData($slug);
    header('Content-Type: application/json');
    echo $data;
    exit;
}


add_action('wp_ajax_nopriv_getResultSchedule', 'getResultSchedule');
add_action('wp_ajax_getResultSchedule', 'getResultSchedule');

function getResultSchedule() {
    $date = isset($_POST['date']) ? $_POST['date'] : '';

    if ($date === '') exit;
    $url = 'https://bongda24h.vn/FootballResult/AjaxFootballResult?date=' .$date;
    
    $Sport = new BDTT_SPORT_CRAWL();
    $data = $Sport->parseResultScheduleHtml($url, 1);
    
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

