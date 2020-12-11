<?php 
get_header();?>
    <!-- Swiper library -->
    <link href="https://cdn.jsdelivr.net/npm/swiper@5.3.6/css/swiper.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/swiper@5.3.6/js/swiper.min.js"></script>
    <!-- Vue library -->
    <script src="https://cdn.jsdelivr.net/npm/vue"></script>
    <!-- vue-awesome-swiper -->
    <script src="https://cdn.jsdelivr.net/npm/vue-awesome-swiper"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.21.0/axios.min.js"></script>
    <div class="tpl-index">
        <div class="container position-relative">
        <div class="row">
            <div class="col-12 col-md-8">
                <main class="arena">
                    <?php if (function_exists('rank_math_the_breadcrumbs')) rank_math_the_breadcrumbs(); ?>
                    <?php if(have_posts()) while(have_posts()) : the_post();?>
                    <div class="single-post-content">
                        <div class="single-post-header">
                            <h1 class="single-post-title"> <?php the_title();?> </h1>
                            <div class="row no-gutters">
                                <div class="col-12 col-md">
                                    <div class="single-post-time"> <?php echo get_the_date('l, j F Y h:i');?></div>
                                </div>
                                <div class="col-12 col-md text-md-right">
                                </div>
                            </div>
                            <div class="single-post-description">
                                <p><?php if (function_exists('bdtt_excerpt')) { echo bdtt_excerpt();}?></p>
                            </div>
                            <?php echo bdttMatchInfo();?>
                        </div>
                        <div class="single-post-body">
                            <?php
                                global $wp_query;
                                $slug = $wp_query->get('pagename', '');

                                $Sport = new BDTT_SPORT_CRAWL();
                                $navData = $Sport->getNavData($slug);
                                
                            ?>
                            <div id="app">
                                <div class="slider-time-wrapper">
                                    <div class="btn-prev"><i class="icofont-rounded-left"></i></div>
                                        <div
                                            class="swiper-container slide-time"
                                            v-swiper:swiper="swiperOptions"
                                        >
                                            <div class="swiper-wrapper">
                                                <div class="swiper-slide" v-for="(slide, index ) in sliders" :key="slide[0]">
                                                    <div class="date">{{ slide[1] }}</div>
                                                    <div class="day-of-week"> {{ slide[2] }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    <div class="btn-next"><i class="icofont-rounded-right"></i></div>
                                </div>
                                <div id="result">
                                    <template v-if="Object.keys(table).length">
                                        <h3 v-if="Object.keys(table).length">{{ table.title }}</h3>
                                        <div class="load_data_round">
                                            <div class="tbl-schedule-wrap active <?php echo $round;?>">
                                            
                                                <table v-if="tableDate.length" class="table table-bordered tbl-schedule" v-for="(item, index) in tableDate">
                                                    <tbody>
                                                        
                                                        <tr>
                                                            <td class="tournament-name" colspan="4">{{ item }}</td>
                                                        </tr>
                                                    
                                                        <tr v-if="Object.keys(tableRes).length && tableRes[item]" v-for="row in tableRes[item]">
                                                            <td width="10%" class="match-start-time"> <span>{{ row.time }}</span> </td>
                                                            <td width="37.5%" class="team-home"> 
                                                                <a :title="row.home" href="javascript:;">
                                                                    {{ row.home }}
                                                                </a>
                                                                <img width="40" :src="handleImg(row.homeflag)" :alt="row.home">
                                                            </td>
                                                            <td width="15%" class="match-status">{{ row.vs }}</td>
                                                            <td width="37.5%" class="team-away">
                                                                <img width="40" :src="handleImg(row.awayflag)" :alt="row.away">
                                                                <a :title="row.away" href="javascript:;">{{ row.away }}</a>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </template>
                                    <template v-else>
                                        <p class="nodata">Dữ liệu đang được cập nhật!!!</p>
                                    </template>
                                </div>
                                <div class="loading" v-if="loading">
                                    <div class="sk-chase">
                                        <div class="sk-chase-dot"></div>
                                        <div class="sk-chase-dot"></div>
                                        <div class="sk-chase-dot"></div>
                                        <div class="sk-chase-dot"></div>
                                        <div class="sk-chase-dot"></div>
                                        <div class="sk-chase-dot"></div>
                                    </div>
                                </div>
                            </div>
                            <style>
                                .loading{position:fixed;top:0;left:0;bottom:0;right:0;display:flex;align-items:center;justify-content:center; background: rgba(0, 0, 0, 0.5);}#result .nodata{width:100%;text-align:center;padding:20px;font-size:1.2rem}.sk-chase{width:40px;height:40px;position:relative;animation:sk-chase 2.5s infinite linear both}.sk-chase-dot{width:100%;height:100%;position:absolute;left:0;top:0;animation:sk-chase-dot 2s infinite ease-in-out both}.sk-chase-dot:before{content:'';display:block;width:25%;height:25%;background-color:#fff;border-radius:100%;animation:sk-chase-dot-before 2s infinite ease-in-out both}.sk-chase-dot:nth-child(1){animation-delay:-1.1s}.sk-chase-dot:nth-child(2){animation-delay:-1s}.sk-chase-dot:nth-child(3){animation-delay:-.9s}.sk-chase-dot:nth-child(4){animation-delay:-.8s}.sk-chase-dot:nth-child(5){animation-delay:-.7s}.sk-chase-dot:nth-child(6){animation-delay:-.6s}.sk-chase-dot:nth-child(1):before{animation-delay:-1.1s}.sk-chase-dot:nth-child(2):before{animation-delay:-1s}.sk-chase-dot:nth-child(3):before{animation-delay:-.9s}.sk-chase-dot:nth-child(4):before{animation-delay:-.8s}.sk-chase-dot:nth-child(5):before{animation-delay:-.7s}.sk-chase-dot:nth-child(6):before{animation-delay:-.6s}@keyframes sk-chase{100%{transform:rotate(360deg)}}@keyframes sk-chase-dot{100%,80%{transform:rotate(360deg)}}@keyframes sk-chase-dot-before{50%{transform:scale(.4)}0%,100%{transform:scale(1)}}
                            </style>
                            <script>
                                Vue.use(VueAwesomeSwiper);
                                const app = new Vue ({
                                    el: '#app',
                                    data: {
                                        navData: <?php echo $navData;?>,
                                        tableData: {},
                                        table: {},
                                        action: 'AjaxFootballResultByLeague',
                                        url: '<?php echo admin_url("admin-ajax.php");?>',
                                        loading: false,
                                        swiperOptions: {
                                            // loop: true,
                                            slideToClickedSlide: true,
                                            centeredSlides: true,
                                            navigation: {
                                                nextEl: '.btn-next',
                                                prevEl: '.btn-prev'
                                            },
                                            observeParents: true,
                                            observer: true
                                        },
                                        
                                    },
                                    computed: {
                                        sliders() {
                                            return this.navData.round.length ? this.navData.round : []
                                        },
                                        leagueId () {
                                            return this.navData.league_id
                                        },
                                        season () {
                                            return this.navData.season
                                        },
                                        tableDate () {
                                            if (this.table.res) {
                                                return Object.keys(this.table.res);
                                            }
                                            return [];
                                        },
                                        tableRes () {
                                            if (this.table.res) {
                                                return this.table.res;
                                            }
                                            return {};
                                        }
                                    },
                                    created() {
                                        const active = this.activeSlide()
                                        this.swiperOptions.initialSlide = active;
                                        roundId = this.activeRoundId();
                                        if (this.sliders.length > 1) {
                                            this.swiperOptions.slidesPerView = 5;
                                        } else {
                                            this.swiperOptions.slidesPerView = 1;
                                        }
                                        this.getRoundData(roundId, this.leagueId, this.season);
                                    },
                                    mounted() {
                                        this.swiper.on('slideChange', async () => {
                                            const realIndex = this.swiper.realIndex;
                                            const roundId = this.getRoundId(realIndex);
                                            
                                            if (this.tableData[roundId] === undefined) {
                                                this.getRoundData(roundId, this.leagueId, this.season);
                                            } else {
                                                this.table = this.tableData[roundId]
                                            }
                                            
                                            this.swiper.slideTo(index)
                                        })
                                    },
                                    methods: {
                                        // onSwiperClickSlide(index, reallyIndex) {
                                        //     const roundId = this.getRoundId(reallyIndex);
                                        //     if (this.tableData[roundId] === undefined) {
                                        //         this.getRoundData(roundId, this.leagueId, this.season);
                                        //     } else {
                                        //         this.table = this.tableData[roundId]
                                        //     }
                                            
                                        //     this.swiper.slideTo(index)
                                        // },
                                        handleImg (url) {
                                            if (url.startsWith('http')) {
                                                return url;
                                            }
                                            return 'https://bongda24h.vn' + url;
                                        },
                                        activeSlide () {
                                            let index = 0;
                                            if (this.sliders.length) {
                                                
                                                for (let i = 0; i < this.sliders.length; i++) {
                                                    if (this.sliders[i][3] !== '') {
                                                        index = i;
                                                        break;
                                                    }
                                                }
                                            }
                                            return index;
                                        },
                                        activeRoundId () {
                                            let roundId = 0;
                                            if (this.sliders.length) {
                                                
                                                for (let i = 0; i < this.sliders.length; i++) {
                                                    if (this.sliders[i][3] !== '') {
                                                        roundId = this.sliders[i][3];
                                                        break;
                                                    }
                                                }
                                            }
                                            return roundId;
                                        },
                                        getRoundId (index) {
                                            return this.sliders[index][0];
                                        },
                                        getRoundData(roundId, leagueId, season) {
                                            const data = new FormData();
                                            data.append('roundId', roundId);
                                            data.append('leagueId', leagueId);
                                            data.append('sectionId', season);
                                            data.append('action', this.action);
                                            const config = {
                                                headers: {
                                                    'Content-Type': 'application/x-www-form-urlencoded'
                                                }
                                            }
                                            this.loading = true;
                                            axios.post(this.url, data, config).then((res) => {
                                                this.tableData[roundId] = res.data;
                                                this.table = this.tableData[roundId];
                                                this.loading = false;
                                            }).catch(err =>{
                                                this.loading = false;
                                                console.log(err)
                                                this.table = [];
                                            })
                                        }
                                    }

                                });
                            </script>
                            <div class="one-item-content">
                                <?php the_content();?>
                            </div>
                        </div>
                        
                    </div>
                    <?php endwhile; wp_reset_postdata();?>
                </main>
            </div>
            <div class="col-12 col-md-4">
                <!--Top nhà cái--> 
                <?php get_sidebar();?>
            </div>
        </div>
        </div>
    </div>
<?php get_footer();?>