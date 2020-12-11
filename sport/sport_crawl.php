<?php

class BDTT_SPORT_CRAWL {

    private $base_url = 'https://blog.bdtt.tv:8081/tylekeo';
    private $ltd_url = 'https://bongda365.com/lich-thi-dau-va-ket-qua-bong-da-hom-nay';

    public function __construct() {
        require_once BDTT_PLUGIN_DIR . '/sport/simple_html_dom.php';
    }


    public function getNavData ($slug) {
        $result = [];
        global $baseUrl;
        $base = $baseUrl[$slug];

        $file = $slug.'.txt';

        $data = $this->getDataCache($file);

        if($data !== '') {
            $check = $this->checkDataCache(json_decode($data, true));

            if($check === false) {
                $data = $this->createNavData($base['url'], $base['id'], $file);
            } 
        } else {
            $data = $this->createNavData($base['url'], $base['id'], $file);
        }
        return $data;
    }
    public function createNavData ($url, $league_id, $file) {
        $result = [];
        $html = $this->getHtml($url);
        $nav_data = $this->parseSliderNavNew($html);
        $season = $this->parserSeason($html);
        $result['round'] = $nav_data;
        $result['season'] = $season;
        $result['league_id'] = $league_id;

        $time = time() + 18000;

        $result['cache'] = $time;

        $content = json_encode($result);
        $this->saveCache($file, $content);
        return $content;
    }

    public function saveCache($file, $html) {
        $file = BDTT_PLUGIN_DIR. '/sport/sport_cache/'.$file;
        file_put_contents($file, $html);
    }

    public function getHtmlCache($file) {
        $file = BDTT_PLUGIN_DIR. '/sport/sport_cache/'.$file;
        if(file_exists($file)) {
            return file_get_contents($file);
        }

        return '';   
    }

    public function getDataCache ($file) {
        $file = BDTT_PLUGIN_DIR. '/sport/sport_cache/'.$file;
        if(file_exists($file)) {
            $content = file_get_contents($file);
            return $content;
        }

        return '';
    }

    public function checkCache($content) {
        preg_match('/<!--time=(\d+)-->/', $content,$matches);
        if(count($matches) > 0) {
            if (isset($matches[1])) {
                $time = (int) $matches[1];
                if($time - time() <= 0) {
                    return false;
                }
            }
        }
        return true;
    }

    public function getHtml($url) {
        $content = $this->getContent($url);
        return str_get_html($content);
    }

    public function getContent($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_REFERER, 'https://bongda24h.vn/');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Accept:application/json, text/javascript, */*; q=0.01',
            'X-Requested-With:XMLHttpRequest',
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

    public function postContent($url,$params) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_REFERER, 'auto');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Accept:application/json, text/javascript, */*; q=0.01',
            'X-Requested-With:XMLHttpRequest',
            'User-Agent: Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36'
        ));
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $data = curl_exec($ch);
        curl_close($ch);

        return $data;
    }


    public function checkDataCache ($data) {
        if ($data && count($data) > 0) {
            $time = (int) $data['cache'];

            if($time - time() <= 0) {
                return false;
            }
            return true;
        }
        
        return false;
    }
    public function getNavData1 ($slug) {
        $result = [];
        list($url, $league_id, $league_name, $type_page) = $this->mapUrl($slug);

        $file = $league_id.'_'.$type_page.'.txt';

        $data = $this->getDataCache($file);
        if($data !== '') {
            $check = $this->checkDataCache(json_decode($data, true));

            if($check === false) {
                $data = $this->createNavHtml($url, $league_name, $league_id, $type_page, $file);
            } 
        } else {
            $data = $this->createNavHtml($url, $league_name, $league_id, $type_page, $file);
        }

        return $data;
    }

    public function parseSliderNavNew($html) {
        $result = [];

        if ($html->find('#resultSection .swiper-wrapper', 0)) {
            $items = $html->find('#resultSection .swiper-wrapper', 0)->find('a');

            if (isset($items) && count($items) > 0) {
                foreach($items as $item) {
                    $active = '';
                    $round_id = $item->getAttribute('data-id');
                    $class = $item->getAttribute('class');
                    if (strpos($class, 'active')) $active = $round_id;
                    $round_name = $item->find('span', 0)->text();
                    $round_date = $item->find('span', 1)->text();
                    $result[] = [$round_id, $round_name, $round_date, $active];
                }
            }
        }
        return $result;
    }

    public function parserSeason($html) {
        $season = '';
        if ($html ->find('form.form-right.hiden-mobi', 0)->find('select#sectionId', 0)) {
            $season = $html ->find('form.form-right.hiden-mobi', 0)->find('select#sectionId', 0)->find('option[selected]', 0)->value;
            
        }
        return $season;
    }

    public function createNav ($slug) {
        $result = [];
        list($url, $league_id, $league_name, $type_page) = $this->mapUrl($slug);

        $file = $league_id.'_'.$type_page.'.txt';

        $content = $this->getHtmlCache($file);

        if(trim($content) !== '') {
            $check = $this->checkCache($content);

            if($check === false) {
                $content = $this->createNavHtml($url, $league_name, $league_id, $type_page, $file);
            } 
        } else {
            $content = $this->createNavHtml($url, $league_name, $league_id, $type_page, $file);
        }

        return $content;
    }

    public function createNavHtml ($url, $league_name, $league_id, $type_page, $file) {
        $result = [];
        $html = $this->getHtml($url);
        $nav_data = $this->parseSliderNavNew($html);
        $season = $this->parserSeason($html);
        $result['round'] = $nav_data;
        $result['season'] = $season;
        $result['league_name'] = $league_name;
        $result['league_id'] = $league_id;
        $result['type_page'] = $type_page;

        $time = time() + 18000;

        $result['cache'] = $time;

        $content = json_encode($result);
        $this->saveHtml($file, $content);
        return $content;
    }

    public static function createSliderNav($data, $show = '') {
        if (count($data) === 0) return '';
        $navs = $data['round'];
        $init = 0;
        $slideId = 'tournamentSchedule';
        if ($show !== '') $slideId = 'tblScheduleToday';
        ob_start();
        ?>
        <div class="slider-time-wrapper">
            <div class="btn-prev"><i class="icofont-rounded-left"></i></div>
            <div id="<?php echo $slideId;?>" class="swiper-container slide-time" data-type="<?php echo $data['type_page'];?>" data-name-tournament="<?php echo $data['league_name'];?>" data-season="<?php echo $data['season'];?>" data-nonce="<?php echo wp_create_nonce('bdtt');?>" data-url="<?php echo admin_url('admin-ajax.php');?>">
                <div class="swiper-wrapper">
                <?php foreach($navs as $key => $nav):
                    if($nav[3] !== '') $init = $key;

                    $date = $nav[1];
                    $text = $nav[2];

                    if($show !== '') {
                        $dateArr = explode(' - ', $nav[2]);
                        $date = $dateArr[1];
                        $text = $dateArr[0];
                    }
                ?>
                <div class="swiper-slide" data-round="<?php echo $nav[0];?>" data-date="<?php echo $nav[1];?>" data-tournament="<?php echo $data['league_id'];?>" data-index="<?php echo $key;?>" data-date-text="<?php echo $nav[2];?>">
                    <div class="date"><?php echo $date;?></div>
                    <div class="day-of-week"> <?php echo $text;?></div>
                </div>
                <?php endforeach;?>
                </div>
            </div>
            <div class="btn-next"><i class="icofont-rounded-right"></i></div>
        </div>
        <script type="text/javascript"> var initial_slide_id = <?php echo $init;?>; </script>
        <?php return ob_get_clean();
    }

    public function createBxh ($slug) {
        $result = [];
        list($url, $league_id, $league_name, $type_page) = $this->mapUrl($slug);

        $file = $league_id.'_'.$type_page.'.txt';

        $content = $this->getHtmlCache($file);

        if(trim($content) !== '') {
            $check = $this->checkCache($content);

            if($check === false) {
                $content = $this->createBxhHtml($url, $file);
            } 
        } else {
            $content = $this->createBxhHtml($url, $file);
        }

        return $content;
    }

    public function createBxhHtml ($url, $file) {
        if ($league_id === 18 || $league_id === 7) {
            $bxhData = $this->createBxhDataGroup($url);
            $bxhHtml = $this->createBxhChampionGroup($bxhData);
            $this->saveHtml($file, $bxhHtml);
            return $bxhHtml;
        } else {
            $bxhData = $this->createBxhData($url);
            $bxhHtml = $this->createBxhChampion($bxhData);
            $this->saveHtml($file, $bxhHtml);
            return $bxhHtml;
        }
    }

    public function createBxhData($url) {
        $html = $this->getHtml($url);
        $title = $html->find('.box-bxh h2.title-giaidau.fixleft', 0) ? $html->find('.box-bxh h2.title-giaidau.fixleft', 0)->text() : '';
        $table = $html->find('.table-content.calc table.table-bxh', 0);
        $data = [];
        $result = [];
        if ($table) {
            $items = $table->find('tr');
            if ($items && count($items) > 0) {
                for($i = 1; $i < count($items); $i++) {
                    $item = $items[$i];

                    $stt = $flag = $name = $match = $win = $draw = $loss = $hs = $point = '';
                    if ($item->find('td', 0)) $stt = $item->find('td', 0)->plaintext;

                    if ($item->find('td', 1)->find('img', 0)) $flag = $item->find('td', 1)->find('img', 0)->getAttribute('src');

                    if ($item->find('td', 1)) $name = $item->find('td', 1)->plaintext;

                    if ($item->find('td', 2)) $match = $item->find('td', 2)->text();
                    if ($item->find('td', 3)) $win = $item->find('td', 3)->text();
                    if ($item->find('td', 4)) $draw = $item->find('td', 4)->text();
                    if ($item->find('td', 5)) $loss = $item->find('td', 5)->text();
                    if ($item->find('td', 6)) $hs = $item->find('td', 6)->text();
                    if ($item->find('td', 7)) $point = $item->find('td', 7)->text();
                    $result[] = [
                        'stt' => $stt,
                        'flag' => $flag,
                        'name' => $name,
                        'match' => $match,
                        'win' => $win,
                        'draw' => $draw,
                        'loss' => $loss,
                        'hs' => $hs,
                        'point' => $point
                    ];
                }
            }
        }
        $data['title'] = $title;
        $data['rank'] = $result;
        return $data;
    }

    public function createBxhDataGroup($url) {
        $html = $this->getHtml($url);

        $title = $html->find('.box-bxh h2.title-giaidau.fixleft', 0) ? $html->find('.box-bxh h2.title-giaidau.fixleft', 0)->text() : '';

        $table = $html->find('.table-content.calc table.table-bxh', 0);
        $result = [];
        $data = [];
        $key = 'groupA';
        if ($table) {
            $items = $table->find('tr');
            if ($items && count($items) > 0) {
                foreach($items as $item) {
                    if ($item->getAttribute('id') !== '') {
                        $key = $item->getAttribute('id');
                    } else {
                        if (!$item->find('td', 1)) continue;
                        $stt = $flag = $name = $match = $win = $draw = $loss = $hs = $point = '';
                        if ($item->find('td', 0)) $stt = $item->find('td', 0)->text();

                        if ($item->find('td', 1)->find('img', 0)) $flag = $item->find('td', 1)->find('img', 0)->getAttribute('src');

                        if ($item->find('td', 1)) $name = $item->find('td', 1)->text();

                        if ($item->find('td', 2)) $match = $item->find('td', 2)->text();
                        if ($item->find('td', 3)) $win = $item->find('td', 3)->text();
                        if ($item->find('td', 4)) $draw = $item->find('td', 4)->text();
                        if ($item->find('td', 5)) $loss = $item->find('td', 5)->text();
                        if ($item->find('td', 6)) $hs = $item->find('td', 6)->text();
                        if ($item->find('td', 7)) $point = $item->find('td', 7)->text();
                        $result[$key][] = [
                            'stt' => $stt,
                            'flag' => $flag,
                            'name' => $name,
                            'match' => $match,
                            'win' => $win,
                            'draw' => $draw,
                            'loss' => $loss,
                            'hs' => $hs,
                            'point' => $point
                        ];
                    }
                }
            }
        }
        $data['title'] = $title;
        $data['rank'] = $result;
        return $data;
    }


    public function createBxhChampionGroup($data) {
        if(count($data) < 2) return '';
        $ranking = $data['rank'];
        ob_start();
        ?>
        <h2 class="title-giaidau fixleft"><?php echo $data['title'];?></h2>
        <div class="tbl-schedule-wrap active data_round_<?php echo $round;?>">
            <table class="table table-bordered tbl-schedule">
                <tbody>
                    <?php foreach($ranking as $key => $items) : 
                        $group = str_replace('group', '', $key);
                    ?>
                    <tr>
                        <td class="tournament-name" style="text-align: center"><?php echo $group;?></td>
                        <td class="tournament-name">Đội</td>
                        <td class="tournament-name" style="text-align: center">Số trận</td>
                        <td class="tournament-name" style="text-align: center">Thắng</td>
                        <td class="tournament-name" style="text-align: center">Hòa</td>
                        <td class="tournament-name" style="text-align: center">Bại</td>
                        <td class="tournament-name" style="text-align: center">Hiệu số</td>
                        <td class="tournament-name" style="text-align: center">Điểm</td>
                    </tr>
                    <?php foreach($items as $item) : 

                        $flag = strpos($item['flag'], 'http') !== false ? $item['flag'] : 'https://bongda24h.vn' + $item['flag'];
                    ?>
                    <tr>
                        <td style="text-align: center; font-weight: 700"><?php echo $item['stt'];?></td>
                        <td> <img width="30" class="img-fluid lazy" alt="Paris Saint-Germain FC" src="<?php echo $flag;?>" style=""> <span><?php echo $item['name'];?></span> </td>
                        <td style="text-align: center"><?php echo $item['match'];?></td>
                        <td style="text-align: center"><?php echo $item['win'];?></td>
                        <td style="text-align: center"><?php echo $item['draw'];?></td>
                        <td style="text-align: center"><?php echo $item['loss'];?></td>
                        <td style="text-align: center"><?php echo $item['hs'];?></td>
                        <td style="text-align: center; font-weight: 700"><?php echo $item['point'];?></td>
                    </tr>
                    <?php endforeach;?>
                    <?php endforeach;?>
                </tbody>
            </table>
        </div>
        <?php return ob_get_clean();
    }

    public function createBxhChampion($data) {
        if(count($data) < 2) return '';
        $ranking = $data['rank'];
        ob_start();
        ?>
        <h2 class="title-giaidau fixleft"><?php echo $data['title'];?></h2>
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
                    <?php foreach($ranking as $item) : 
                        $flag = 'https://bongda24h.vn' + $item['flag'];
                        if (strpos($item['flag'], 'http') !== false) {
                            $flag = $item['flag'];
                        }   
                    ?>
                    <tr>
                        <td style="text-align: center; font-weight: 700"><?php echo $item['stt'];?></td>
                        <td> <img width="30" class="img-fluid lazy" alt="Paris Saint-Germain FC" src="<?php echo $flag;?>" style=""> <span><?php echo $item['name'];?></span> </td>
                        <td style="text-align: center"><?php echo $item['match'];?></td>
                        <td style="text-align: center"><?php echo $item['win'];?></td>
                        <td style="text-align: center"><?php echo $item['draw'];?></td>
                        <td style="text-align: center"><?php echo $item['loss'];?></td>
                        <td style="text-align: center"><?php echo $item['hs'];?></td>
                        <td style="text-align: center; font-weight: 700"><?php echo $item['point'];?></td>
                    </tr>
                    <?php endforeach;?>
                </tbody>
            </table>
        </div>
        <?php return ob_get_clean();
    }

    public function parseResultScheduleHtml($url, $key = 0) {
        $html = $this->getHtml($url);
        $result = [];
        $title = '';
        $index = '0';
        if ($html->find('.result-livescore', $key)) {
            $items = $html->find('.result-livescore', $key)->find('*');
            if (isset($items) && count($items) > 0) {
                foreach($items as $item) {
                    if ($item->getAttribute('class') === 'title-giaidau frow text-center') {
                        $title = $item->text();
                    }
                    if ($item->getAttribute('class') === 'frow-cup') {
                        $index = $item->text();
                    }
                    if ($item->getAttribute('class') === 'frow') {
                        $time = $item->find('div', 0) ? $item->find('div', 0)->text() : '';
                        $homeflag = $item->find('div', 1)->find('img', 0) ? $item->find('div', 1)->find('img', 0)->getAttribute('src'): '';
                        $home = $item->find('div', 1) ? $item->find('div', 1)->text() : '';
                        $vs = $item->find('div', 2) ? $item->find('div', 2)->text() : '';
                        $awayflag = $item->find('div', 3)->find('img', 0) ? $item->find('div', 3)->find('img', 0)->getAttribute('src') : '';
                        $away = $item->find('div', 3) ? $item->find('div', 3)->text() : '';

                        $result[$index][] = [
                            'time' => $time,
                            'homeflag' => $homeflag,
                            'home' => $home,
                            'vs' => $vs,
                            'awayflag' => $awayflag,
                            'away' => $away
                        ];
                    }
                }
            }
        }
        $data = [];
        if(count($result) > 0) $data['res'] = $result;
        if ($title !== '') $data['title'] = $title;

        return $data;
    }

    public function parserRankingHtmlNormal ($url) {
        $html = $this->getHtml($url);
        $result = [];
        $title = '';
        if ($html) {
            if ($html->find('h2.title-giaidau.fixleft', 0)) {
                $title = $html->find('h2.title-giaidau.fixleft', 0)->text();
            }
            if ($html->find('.table-content.calc', 0)) {
                
                if ($html->find('.table-content.calc', 0)) {
                    $items = $html->find('.table-content.calc', 0)->find('tr');
                    if (isset($items) && count($items) > 0) {
                        for ($i = 1; $i < count($items); $i++) {
                            $item = $items[$i];
                            if ($item->find('th', 0)) break;
                            $stt = $item->find('td', 0) ?  $item->find('td', 0)->text() : '';
                            $flag = $item->find('td', 1)->find('.link-clb img.bxhclb-icon', 0) ?  $item->find('td', 1)->find('.link-clb img.bxhclb-icon', 0)->getAttribute('src') : '';
                            $name = $item->find('td', 1) ?  $item->find('td', 1)->text() : '';
                            $match = $item->find('td', 2) ?  $item->find('td', 2)->text() : '';
                            $win = $item->find('td', 3) ?  $item->find('td', 3)->text() : '';
                            $draw = $item->find('td', 4) ?  $item->find('td', 4)->text() : '';
                            $loss = $item->find('td', 5) ?  $item->find('td', 5)->text() : '';
                            $hs = $item->find('td', 6) ?  $item->find('td', 6)->text() : '';
                            $point = $item->find('td', 7) ?  $item->find('td', 7)->text() : '';
                            $result[] = [
                                'stt' => $stt,
                                'flag' => $flag,
                                'name' => $name,
                                'match' => $match,
                                'win' => $win,
                                'draw' => $draw,
                                'loss' => $loss,
                                'hs' => $hs,
                                'point' => $point
                            ];
                        }
                    }
                }
            }
        }
        $data = [];
        if (count($result) > 0) {
            $data['title'] = $title;
            $data['res'] = $result;
            $data['cache'] = time() + 18000;
            $data['type'] = 'Normal';
        }
        return $data;
    }

    public function parserRankingHtmlC1($url) {
        $html = $this->getHtml($url);
        $result = [];
        $data = [];
        if ($html) {
            $title = $html->find('.box-bxh h2.title-giaidau.fixleft', 0) ? $html->find('.box-bxh h2.title-giaidau.fixleft', 0)->text() : '';
            $table = $html->find('.table-content.calc table.table-bxh', 0);
            
            $key = 'groupA';
            if ($table) {
                $items = $table->find('tr');
                if ($items && count($items) > 0) {
                    foreach($items as $item) {
                        if ($item->getAttribute('id')) {
                            $key = $item->find('th', 0)->text();
                        }
                        if (!$item->find('td', 1)) continue;
                        $stt = $flag = $name = $match = $win = $draw = $loss = $hs = $point = '';
                        if ($item->find('td', 0)) $stt = $item->find('td', 0)->text();

                        if ($item->find('td', 1)->find('img', 0)) $flag = $item->find('td', 1)->find('img', 0)->getAttribute('src');

                        if ($item->find('td', 1)) $name = $item->find('td', 1)->text();

                        if ($item->find('td', 2)) $match = $item->find('td', 2)->text();
                        if ($item->find('td', 3)) $win = $item->find('td', 3)->text();
                        if ($item->find('td', 4)) $draw = $item->find('td', 4)->text();
                        if ($item->find('td', 5)) $loss = $item->find('td', 5)->text();
                        if ($item->find('td', 6)) $hs = $item->find('td', 6)->text();
                        if ($item->find('td', 7)) $point = $item->find('td', 7)->text();
                        $result[$key][] = [
                            'stt' => $stt,
                            'flag' => $flag,
                            'name' => $name,
                            'match' => $match,
                            'win' => $win,
                            'draw' => $draw,
                            'loss' => $loss,
                            'hs' => $hs,
                            'point' => $point
                        ];
                    }
                }
            }
        }

        $data = [];
        if (count($result) > 0) {
            $data['title'] = $title;
            $data['res'] = $result;
            $data['cache'] = time() + 18000;
            $data['type'] = 'C1';
        }
        
        return $data;
    }

    public function parserRankingHtmlUefa($url) {
        $html = $this->getHtml($url);
        $result = [];
        $data = [];
        if ($html) {
            $title = $html->find('.box-bxh h2.title-giaidau.fixleft', 0) ? $html->find('.box-bxh h2.title-giaidau.fixleft', 0)->text() : '';
            $table = $html->find('.table-content.calc table.table-bxh', 0);
            $key = 'groupA';
            if ($table) {
                $items = $table->find('tr');
                if ($items && count($items) > 0) {
                    foreach($items as $item) {
                        if ($item->getAttribute('id')) {
                            $key = $item->find('th', 0)->text();
                        }

                        if ($item->find('td', 0)) {
                            if ($item->find('td', 0)->text() === 'TT') continue;
                        }

                        if (!$item->find('td', 1)) continue;
                        $stt = $flag = $name = $match = $win = $draw = $loss = $hs = $point = '';
                        if ($item->find('td', 0)) $stt = $item->find('td', 0)->text();

                        if ($item->find('td', 1)->find('img', 0)) $flag = $item->find('td', 1)->find('img', 0)->getAttribute('src');

                        if ($item->find('td', 1)) $name = $item->find('td', 1)->text();

                        if ($item->find('td', 2)) $match = $item->find('td', 2)->text();
                        if ($item->find('td', 3)) $win = $item->find('td', 3)->text();
                        if ($item->find('td', 4)) $draw = $item->find('td', 4)->text();
                        if ($item->find('td', 5)) $loss = $item->find('td', 5)->text();
                        if ($item->find('td', 6)) $hs = $item->find('td', 6)->text();
                        if ($item->find('td', 7)) $point = $item->find('td', 7)->text();
                        $result[$key][] = [
                            'stt' => $stt,
                            'flag' => $flag,
                            'name' => $name,
                            'match' => $match,
                            'win' => $win,
                            'draw' => $draw,
                            'loss' => $loss,
                            'hs' => $hs,
                            'point' => $point
                        ];
                    }
                }
            }
        }
        $data = [];
        if (count($result) > 0) {
            $data['title'] = $title;
            $data['res'] = $result;
            $data['cache'] = time() + 18000;
            $data['type'] = 'C1';
        }
        
        return $data;
    }



    public function createRankingData ($url,$id, $file) {
        $mapFunc = ['7' => 'parserRankingHtmlC1', '18' => 'parserRankingHtmlC1', '73' => 'parserRankingHtmlUefa', '82' => 'parserRankingHtmlUefa'];
        $func = isset($mapFunc[$id]) ? $mapFunc[$id] : 'parserRankingHtmlNormal';
        $data = $this->{$func}($url);
        $content = json_encode($data);
        $this->saveCache($file, $content);
        return $content;

    }
    public function getRankingData($slug) {
        $result = [];
        global $baseUrl;
        $base = $baseUrl[$slug];

        $file = $slug.'.txt';

        $data = $this->getDataCache($file);

        if($data !== '') {
            $check = $this->checkDataCache(json_decode($data, true));

            if($check === false) {
                $data = $this->createRankingData($base['url'], $base['id'], $file);
            } 
        } else {
            $data = $this->createRankingData($base['url'],$base['id'], $file);
        }
        return $data;
    }
}