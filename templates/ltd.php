<?php 
get_header();?>
 <!-- Swiper library -->
    <link href="https://cdn.jsdelivr.net/npm/swiper@5.3.6/css/swiper.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/swiper@5.3.6/js/swiper.min.js"></script>
    <!-- Vue library -->
    <script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
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
                            <?php $nav = createWeekDate();
                            
                            ?>
                            <div id="app">
                                <div class="slider-time-wrapper">
                                    <div class="btn-prev"><i class="icofont-rounded-left"></i></div>
                                        <div
                                            class="swiper-container slide-time"
                                            v-swiper:swiper="swiperOptions"
                                        >
                                            <div class="swiper-wrapper">
                                                <div class="swiper-slide" v-for="slide in navs" :key="slide.date">
                                                    <div class="date">{{ slide.name }}</div>
                                                    <div class="day-of-week"> {{ slide.date }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    <div class="btn-next"><i class="icofont-rounded-right"></i></div>
                                </div>
                                <div id="result">
                                    <template v-if="Object.keys(renderData).length">
                                        <h3 v-if="title">{{ title }}</h3>
                                        <div class="load_data_round">
                                            <div class="tbl-schedule-wrap active">
                                            
                                                <table v-if="Object.keys(results).length" class="table table-bordered tbl-schedule" v-for="(item, index) in Object.keys(results)">
                                                    <tbody>
                                                        
                                                        <tr>
                                                            <td class="tournament-name" colspan="4">{{ item }}</td>
                                                        </tr>
                                                        <template v-if="Object.keys(results).length && results[item]">
                                                            <tr v-for="row in results[item]">
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
                                                        </template>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </template>
                                    <template v-else>
                                        <p class="nodata">Dữ liệu đang được cập nhật!!!</p>
                                    </template>
                                </div>
                                <div class="loading" v-if="loadingMask">
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
                                        navs: <?php echo json_encode($nav);?>,
                                        dataByDate: [],
                                        renderData: {},
                                        url: '<?php echo admin_url("admin-ajax.php");?>',
                                        action: 'getScheduleResult',
                                        loadingMask: false,
                                        swiperOptions: {
                                            // loop: true,
                                            centeredSlides: true,
                                            slideToClickedSlide: true,
                                            navigation: {
                                                nextEl: '.btn-next',
                                                prevEl: '.btn-prev'
                                            },
                                            observeParents: true,
                                            observer: true
                                        },
                                    },
                                    computed: {
                                        results() {
                                            return this.renderData.res || {}
                                        },
                                        title () {
                                            return this.renderData.title || ''
                                        }
                                    },
                                    async created() {
                                        const active = this.activeIndexSlide();
                                        this.swiperOptions.initialSlide = active;

                                        if (this.navs.length > 1) {
                                            this.swiperOptions.slidesPerView = 5;
                                        } else {
                                            this.swiperOptions.slidesPerView = 1;
                                        }

                                        const activeDate = this.activeDate();
                                        await this.getResult(activeDate);


                                    },
                                    mounted() {
                                        // console.log(this.swiper)
                                        //console.log(this.navs)
                                        this.swiper.on('slideChange', async () => {
                                            const realIndex = this.swiper.realIndex
                                            // console.log(realIndex)
                                            const activeDate = this.navs[realIndex].date;
                                            if (this.dataByDate[activeDate] === undefined) {
                                                await this.getResult(activeDate);
                                            } else {
                                                this.renderData = this.dataByDate[activeDate];
                                            }
                                        })
                                        
                                    },
                                    methods: {
                                        // async onSwiperClickSlide (index, reallyIndex) {
                                        //     this.swiper.slideTo(index)
                                        //     const activeDate = this.navs[reallyIndex].date;
                                        //     if (this.dataByDate[activeDate] === undefined) {
                                        //         await this.getResult(activeDate);
                                        //     } else {
                                        //         this.renderData = this.dataByDate[activeDate];
                                        //     }
                                            
                                        // },
                                        activeIndexSlide() {
                                            let index = 0;
                                            for( let i = 0; i < this.navs.length; i++) {
                                                if (this.navs[i].name === 'Hôm nay') {
                                                    index = i;
                                                    break;
                                                }
                                            }
                                            return index;
                                        },
                                        activeDate() {
                                            let date = '';
                                            for( let i = 0; i < this.navs.length; i++) {
                                                if (this.navs[i].name === 'Hôm nay') {
                                                    date = this.navs[i].date;
                                                    break;
                                                }
                                            }
                                            return date;
                                        },
                                        
                                        getResult (dateD) {
                                            const data = new FormData();
                                            data.append('date', dateD);
                                            data.append('action', this.action);
                                            const config = {
                                                headers: {
                                                    'Content-Type': 'application/x-www-form-urlencoded'
                                                }
                                            }
                                            this.loadingMask = true;
                                            axios.post(this.url, data, config).then((resp) => {
                                               
                                                this.dataByDate[dateD] = resp.data;
                                                this.renderData = this.dataByDate[dateD];
                                                this.loadingMask = false;

                                            }).catch(err =>{
                                                this.loadingMask = false;
                                                console.log(err)
                                                this.renderData = {};
                                            })
                                        },
                                        handleImg (url) {
                                            if (url.startsWith('http')) {
                                                return url;
                                            }
                                            return 'https://bongda24h.vn' + url;
                                        },
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