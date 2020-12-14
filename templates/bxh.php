<?php 
get_header();?>
    <!-- Vue library -->
    <script src="https://cdn.jsdelivr.net/npm/vue"></script>
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

                                $Sport = new BDTT_SPORT_CRAWL();

                                // echo "<pre>";
                                // print_r($Sport->getAllRankingData());
                                // echo "</pre>";
                            ?>
                            <div id="app">
                                <template v-if="Object.keys(res).length">
                                    <template v-for="(item, index) in Object.keys(res)" :key="index">
                                        <h3 style="text-transform: capitalize;">{{ item }}</h3>
                                        <div class="tbl-schedule-wrap active">
                                            <table class="table table-bordered tbl-schedule" v-if="res[item].length">
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
                                                    <tr v-for="(rank, index1) in res[item]" :key="index1">
                                                        <td style="text-align: center; font-weight: 700">{{ rank.stt }}</td>
                                                        <td> <img width="30" class="img-fluid lazy" alt="Paris Saint-Germain FC" :src="handleImage(rank.flag)" style=""> <span>{{ rank.name }}</span> </td>
                                                        <td style="text-align: center">{{ rank.match }}</td>
                                                        <td style="text-align: center">{{ rank.win }}</td>
                                                        <td style="text-align: center">{{ rank.draw }}</td>
                                                        <td style="text-align: center">{{ rank.loss }}</td>
                                                        <td style="text-align: center" v-html="rank.hs"></td>
                                                        <td style="text-align: center; font-weight: 700">{{ rank.point }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </template>
                                </template>
                            </div>
                            <script>
                                const app = new Vue ({
                                    el: '#app',
                                    data: {
                                        rankingData: <?php echo $Sport->getAllRankingData();?>,
                                        url: '<?php echo admin_url("admin-ajax.php");?>',
                                        action: 'getAllRankingData'
                                    },
                                    computed: {
                                        res() {
                                            return this.rankingData.res || {}
                                        }
                                    },
                                    mounted() {
                                        // console.log(this.rankingData)
                                    },
                                    methods: {
                                        handleImage (url) {
                                            if (url.startsWith('http')) {
                                                return url;
                                            }
                                            return 'https://bongda24h.vn' + url
                                        },
                                    }
                                })
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