<?php

class BDTT_SPORT_HTML {

    public static function createSliderNav($data, $league_id = '', $league_name = '', $type_page = '', $season = '', $show = '', $index = 0) {
        if (count($data) === 0) return '';
        if(!isset($data['league_round']) || count($data['league_round']) === 0) return '';
        $navs = $data['league_round'];
        $init = 0;
        $slideId = 'tournamentSchedule';
        if ($show !== '') $slideId = 'tblScheduleToday';
        ob_start();
        ?>
        <div class="slider-time-wrapper">
            <div class="btn-prev"><i class="icofont-rounded-left"></i></div>
            <div id="<?php echo $slideId;?>" class="swiper-container slide-time" data-type="<?php echo $type_page;?>" data-name-tournament="<?php echo $league_name;?>" data-season="<?php echo $season;?>" data-nonce="<?php echo wp_create_nonce('bdtt');?>" data-url="<?php echo admin_url('admin-ajax.php');?>">
                <div class="swiper-wrapper">
                <?php foreach($navs as $key => $nav):
                    if ($key === 'active') continue;
                    if($navs['active'] === $nav[0]) $init = $key;

                    $date = $nav[1];
                    $text = $nav[2];

                    if($show !== '') {
                        $dateArr = explode(' - ', $nav[2]);
                        $date = $dateArr[1];
                        $text = $dateArr[0];
                    }
                ?>
                <div class="swiper-slide" data-round="<?php echo $nav[0];?>" data-date="<?php echo $nav[1];?>" data-tournament="<?php echo $league_id;?>" data-index="<?php echo $key;?>" data-date-text="<?php echo $nav[2];?>">
                    <div class="date"><?php echo $date;?></div>
                    <div class="day-of-week"> <?php echo $text;?></div>
                </div>
                <?php endforeach;?>
                </div>
            </div>
            <div class="btn-next"><i class="icofont-rounded-right"></i></div>
        </div>
        <?php $activeSlide = $init - (int) $index;?>
        <script type="text/javascript"> var initial_slide_id = <?php echo $activeSlide;?>; </script>
        <?php return ob_get_clean();
    }


    public static function createBodyScheduleAndResult($data, $active = '') {
        if (count($data) === 0) return '';
        $round = '';
        if ($active !== '') $round = 'data_round_'. $active;
        ob_start();
        ?>
        <div class="load_data_round">
            <div class="tbl-schedule-wrap active <?php echo $round;?>">
                <?php foreach($data as $key => $items):?>
                <table class="table table-bordered tbl-schedule">
                    <tbody>
                        
                        <tr>
                            <td class="tournament-name" colspan="4"><?php echo $key;?></td>
                        </tr>
                        <?php foreach($items as $item):?>
                        <tr>
                            <td width="10%" class="match-start-time"> <span><?php echo $item[0];?></span> </td>
                            <td width="37.5%" class="team-home"> 
                                <a title="<?php echo $item[1];?>" href="javascript:;">
                                    <?php echo $item[1];?>
                                </a>
                                <img width="40" src="<?php echo $item[2];?>" alt="<?php echo $item[1];?>">
                            </td>
                            <td width="15%" class="match-status"><?php echo $item[3];?></td>
                            <td width="37.5%" class="team-away">
                                <img width="40" src="<?php echo $item[4];?>" alt="<?php echo $item[5];?>">
                                <a title="<?php echo $item[5];?>" href="javascript:;"><?php echo $item[5];?></a>
                            </td>
                        </tr>
                        <?php endforeach;?>
                        
                    </tbody>
                </table>
                <?php endforeach;?>
            </div>
        </div>
        <?php return ob_get_clean();
    }

    public static function createScheduleResultTitle($title) {
        return '<div id="textTime" class="time-text"> '. $title .' </div>';
    }

    public static function createRankingChampion($data, $round ='') {
        if(count($data) === 0) return '';
        ob_start();
        ?>
        
        <div class="tbl-schedule-wrap active data_round_<?php echo $round;?>">
            <table class="table table-bordered tbl-schedule">
                <tbody>
                    <?php foreach($data as $key => $items) : ?>
                    <tr>
                        <td class="tournament-name" style="text-align: center"><?php echo $key;?></td>
                        <td class="tournament-name">Đội</td>
                        <td class="tournament-name" style="text-align: center">Số trận</td>
                        <td class="tournament-name" style="text-align: center">Thắng</td>
                        <td class="tournament-name" style="text-align: center">Hòa</td>
                        <td class="tournament-name" style="text-align: center">Bại</td>
                        <td class="tournament-name" style="text-align: center">Hiệu số</td>
                        <td class="tournament-name" style="text-align: center">Điểm</td>
                    </tr>
                    <?php foreach($items as $item) : ?>
                    <tr>
                        <td style="text-align: center; font-weight: 700"><?php echo $item['Order'];?></td>
                        <td> <img width="30" class="img-fluid lazy" alt="Paris Saint-Germain FC" src="https://static.bongda24h.vn/Medias/icon/<?php echo $item['TeamIcon'];?>" style=""> <span><?php echo $item['TeamName'];?></span> </td>
                        <td style="text-align: center"><?php echo $item['MacthesCount'];?></td>
                        <td style="text-align: center"><?php echo $item['WinMatches'];?></td>
                        <td style="text-align: center"><?php echo $item['DrawMatches'];?></td>
                        <td style="text-align: center"><?php echo $item['LossMatches'];?></td>
                        <td style="text-align: center"><?php echo $item['Diffirence'];?></td>
                        <td style="text-align: center; font-weight: 700"><?php echo $item['TotalMark'];?></td>
                    </tr>
                    <?php endforeach;?>
                    <?php endforeach;?>
                </tbody>
            </table>
        </div>
        <?php return ob_get_clean();
    }

    public static function createRanking($data, $round ='') {
        if(count($data) === 0) return '';
        if(!isset($data['TablesList']) || count($data['TablesList']) === 0) return '';
        $items = $data['TablesList'];
        ob_start();
        ?>
        <div class="tbl-schedule-wrap active data_round_<?php echo $round;?>">
            <table class="table table-bordered tbl-schedule">
                <tbody>
                    <tr>
                        <td class="tournament-name" style="text-align: center">#</td>
                        <td class="tournament-name">Đội</td>
                        <td class="tournament-name" style="text-align: center">Số trận</td>
                        <td class="tournament-name" style="text-align: center">Thắng</td>
                        <td class="tournament-name" style="text-align: center">Hòa</td>
                        <td class="tournament-name" style="text-align: center">Bại</td>
                        <td class="tournament-name" style="text-align: center">Hiệu số</td>
                        <td class="tournament-name" style="text-align: center">Điểm</td>
                    </tr>
                    <?php foreach($items as $item) : ?>
                    <tr>
                        <td style="text-align: center; font-weight: 700"><?php echo $item['Order'];?></td>
                        <td> <img width="30" class="img-fluid lazy" alt="Paris Saint-Germain FC" src="https://static.bongda24h.vn/Medias/icon/<?php echo $item['TeamIcon'];?>" style=""> <span><?php echo $item['TeamName'];?></span> </td>
                        <td style="text-align: center"><?php echo $item['MacthesCount'];?></td>
                        <td style="text-align: center"><?php echo $item['WinMatches'];?></td>
                        <td style="text-align: center"><?php echo $item['DrawMatches'];?></td>
                        <td style="text-align: center"><?php echo $item['LossMatches'];?></td>
                        <td style="text-align: center"><?php echo $item['Diffirence'];?></td>
                        <td style="text-align: center; font-weight: 700"><?php echo $item['TotalMark'];?></td>
                    </tr>
                    <?php endforeach;?>
                </tbody>
            </table>
        </div>
        <?php return ob_get_clean();
    }

    public static function createScheduleResultFromApi($data, $round) {
        if (count($data) === 0) return '';
        if (!isset($data['list'])) return '';
        if (!isset($data['list'][0])) return '';
        if (!isset($data['list'][0]['l_Matches'])) return '';

        $matches = $data['list'][0]['l_Matches'];

        $list = self::group_by('StartDate', $matches);

        $IMG_BASE = 'https://static.bongda24h.vn/Medias/Icon/';

        ob_start();
        ?>
        <div class="tbl-schedule-wrap data_round_<?php echo $round;?>">
            <?php foreach($list as $key => $items):
                $new_date = date('d/m/Y', strtotime($key));
                $day = self::getDay($new_date);
                $date = $day. ', ' . $new_date;
            ?>
            <table class="table table-bordered tbl-schedule">
                <tbody>
                    
                    <tr>
                        <td class="tournament-name" colspan="4"><?php echo $date;?></td>
                    </tr>
                    <?php foreach($items as $item):
                        $match_time = substr($item['StartTime'], 0, 6);
                        $vs = 'vs';
                        if ($item['IsLive'] === 0) {
                            $vs = $item['AwayGoals']. '-' .$item['HomeGoals'];
                        }
                    ?>
                    <tr>
                        <td width="10%" class="match-start-time"> <span><?php echo $match_time;?></span> </td>
                        <td width="37.5%" class="team-home"> 
                            <a title="<?php echo $item['AwayName'];?>" href="javascript:;">
                                <?php echo $item['AwayName'];?>
                            </a>
                            <img width="40" src="<?php echo $IMG_BASE.$item['AwayLogo'];?>" alt="<?php echo $item['AwayName'];?>">
                        </td>
                        <td width="15%" class="match-status"><?php echo $vs;?></td>
                        <td width="37.5%" class="team-away">
                            <img width="40" src="<?php echo $IMG_BASE.$item['HomeLogo'];?>" alt="<?php echo $item['HomeName'];?>">
                            <a title="<?php echo $item['HomeName'];?>" href="javascript:;"><?php echo $item['HomeName'];?></a>
                        </td>
                    </tr>
                    <?php endforeach;?>
                    
                </tbody>
            </table>
            <?php endforeach;?>
        </div>
        <?php return ob_get_clean();
    }

    public static function group_by($key, $data) {
        $result = array();
        
        foreach($data as $val) {

            if(array_key_exists($key, $val)){
                $result[$val[$key]][] = $val;
            }else{
                $result[""][] = $val;
            }
            
        }
    
        return $result;
    }

    public static function getDay($date) {
        $day = date('w', strtotime($date));
        switch ($day) {
            case '0':
                return 'Chủ Nhật';
            case '1':
                return 'Thứ hai';
            case '2':
                return 'Thứ ba';
            case '3':
                return 'Thứ tư';
            case '4':
                return 'Thứ năm';
            case '5':
                return 'Thứ sáu';
            case '6':
                return 'Thứ 7';
        }
    }

    public static function createLoadingHtml() {
        return '<div class="bdtt-loading"><img src="'. BDTT_PLUGIN_URL .'/img/loadingAnimation.gif" /></div>';
    }

    public static function createTyleKeoHeader() {
        return '<div class="bdtt-tyle">
                    <div class="hour">Giờ</div>
                    <div class="match">Trận Đấu</div>
                    <div class="fulltime">
                        <div class="ft">Cả Trận</div>
                        <div class="tyle">Tỷ lệ</div>
                        <div class="over">Châu Á</div>
                        <div class="euro">1x2</div>
                    </div>
                    <div class="half">
                        <div class="ft">Hiệp 1</div>
                        <div class="tyle">Tỷ lệ</div>
                        <div class="over">Châu Á</div>
                        <div class="euro">1x2</div>
                    </div>
                </div>';
    }

    public static function createTyleKeo($data, $opts = null) {

        if(!$data || count($data) === 0) return '';

        ob_start();
        $class = $opts !== null ? ' odd-live': '';
        foreach($data as $key => $leagues):
        ?>
        <div class="odd-items<?php echo $class;?>">
            <div class="odd-head"><?php echo $key;?></div>
            <?php foreach($leagues as $name => $league):
                if(!isset($league['keodongtyso'])):    
            ?>
            <div class="odd-item">
                <div class="odd-hour">
                    <?php echo self::createTyleKeoTime($league, $opts);?>
                </div>
                <div class="odd-match">
                    <?php echo self::createMatchInfo($league);?>
                </div>
                <div class="odd-tyle-ft">
                    <?php echo self::createOddInfo($league, 'ratiosObj');?>
                </div>
                <div class="odd-over-ft">
                    <?php echo self::createOddInfo($league, 'overUnder');?>
                </div>
                <div class="odd-euro-ft">
                    <?php echo self::createOddInfo($league, 'euro');?>
                </div>
                <div class="odd-tyle">
                    <?php echo self::createOddInfo($league, 'ratiosObjHalf');?>
                </div>
                <div class="odd-over">
                    <?php echo self::createOddInfo($league, 'overUnderHalf');?>
                </div>
                <div class="odd-euro">
                    <?php echo self::createOddInfo($league, 'euroHalf');?>
                </div>
            </div>
            <?php else:
                echo self::createKeoDongTySo($league);
            endif;
            endforeach;?>
        </div>
        <?php endforeach;return ob_get_clean();
    }
    
    public static function createTyleKeoTime($league, $opts) {
        
        if (isset($league['dateTimeObj'])) {

            $dateStr= isset($league['dateTimeObj']['dateStr']) ? $league['dateTimeObj']['dateStr']: '';
            $timeStr = '';
            if (isset($league['dateTimeObj']['timeStr']) && $league['dateTimeObj']['timeStr'] !== '') {
                if ($opts !== null) {
                    $timeStr = '<img src="https://odds.keopro.com/live-keo.gif">' . $league['dateTimeObj']['timeStr'];
                } else {
                    $timeStr = $league['dateTimeObj']['timeStr'];
                }
            }

            return '<div class="date-result">'. $dateStr .'</div>
                    <div class="time-live">'. $timeStr .'</div>
                    <div></div>';
        }

        return '';
    }

    public static function createMatchInfo($league) {

        if (isset($league['infosObj'])) {

            $info = $league['infosObj'];

            $home = $info['home_name'] !== '' ? $info['home_name'] : '';

            $away = $info['away_name'] !== '' ? $info['away_name'] : '';

            $predict = $info['predict'] !== '' ? $info['predict'] : '';

            $chu = $info['chu'] !== '' ? $info['chu'] : '';

            $strong_ab = $home === $chu ? 'strong' : '';
            $strong_un = $away === $chu ? 'strong' : '';

            return '<div class="team-ab '. $strong_ab .'">'. $home .'</div>
                    <div class="team-un '.  $strong_un .'">'. $away .'</div>
                    <div class="pred">'. $predict .'</div>';
        }

        return '';
    }

    public static function createOddInfo($league, $key) {

        if (isset($league[$key])) {
            $oddObj = $league[$key];
            $first = isset($oddObj['ratioFirst']) ? $oddObj['ratioFirst']: '';
            $second = isset($oddObj['ratioSecound']) ? $oddObj['ratioSecound']: '';
            $third = isset($oddObj['ratioThird']) ? $oddObj['ratioThird']: '';
            $forth = isset($oddObj['ratioFourd']) ? $oddObj['ratioFourd']: '';
            $fith = isset($oddObj['ratioFith']) ? $oddObj['ratioFith']: '';
            $sixth = isset($oddObj['ratioSixth']) ? $oddObj['ratioSixth']: '';

            if($key === 'euro' || $key === 'euroHalf') {

                return '<div class="first">'. $second .'</div>
                        <div class="second">'. $first .'</div>
                        <div class="third">'. $forth .'</div>
                        <div class="forth">'. $third .'</div>
                        <div class="fith">'. $sixth .'</div>
                        <div class="sixth">'. $fith .'</div>';

            } else {

                return '<div class="first">'. $first .'</div>
                        <div class="second">'. $second .'</div>
                        <div class="third">'. $third .'</div>
                        <div class="forth">'. $forth .'</div>
                        <div class="fith">'. $fith .'</div>
                        <div class="sixth">'. $sixth .'</div>';
            }
            
        }

        return;
    }

    public static function createKeoDongTySo($league) {
        $headeTyso = '';
        ob_start();
        ?>
        <div class="keotyso">
            <div class="keotyso-title">Kèo Tỷ Số</div>
            <div class="keotyso-btn"></div>
        </div>
        <div class="odd-keotyso hide">
            <?php $keos = $league['keodongtyso'];
                foreach($keos as $keo) :
                $tyso = isset($keo['tyso']) && $keo['tyso']!== '' ? $keo['tyso'] : '';
                $ratio = isset($keo['ratio']) && $keo['ratio']!== '' ? $keo['ratio'] : '';
                if($tyso === '' || $ratio === '') continue;
            ?>
            <div class="keotyso-item">
                <div class="ts">
                    <?php echo $keo['tyso'];?>
                </div>
                <div class="ts-ratio">
                    <?php echo $keo['ratio'];?>
                </div>
            </div>
            <?php endforeach;?>
        </div>

        <?php return ob_get_clean();
    }
}