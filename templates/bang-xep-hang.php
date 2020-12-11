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
                                global $wp_query;
                                $slug = $wp_query->get('pagename', '');
                            ?>
                            <div id="app">
                                <h3 v-if="title" style="text-transform: capitalize;">{{ title }}</h3>
                                <div class="tbl-schedule-wrap active">
                                    <table class="table table-bordered tbl-schedule" v-if="Object.keys(ranking).length &&type === 'Normal'">
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
                                            <tr v-for="(rank, index) in ranking" :key="index">
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
                                    <table class="table table-bordered tbl-schedule" v-if="Object.keys(ranking).length &&type === 'C1'">
                                        <tbody>
                                            <template v-for="(group, index) in Object.keys(ranking)" :key="index">
                                                <template v-if="group.length > 7">
                                                    <tr class="tr-head">
                                                        <td colspan="8">{{ group }}</td>
                                                    </tr>
                                                </template>
                                                <tr>
                                                    <td class="tournament-name" style="text-align: center">{{ group.length > 7 ? 'TT' : group }}</td>
                                                    <td class="tournament-name">Đội</td>
                                                    <td class="tournament-name" style="text-align: center">Số trận</td>
                                                    <td class="tournament-name" style="text-align: center">Thắng</td>
                                                    <td class="tournament-name" style="text-align: center">Hòa</td>
                                                    <td class="tournament-name" style="text-align: center">Bại</td>
                                                    <td class="tournament-name" style="text-align: center">Hiệu số</td>
                                                    <td class="tournament-name" style="text-align: center">Điểm</td>
                                                </tr>
                                                <template v-if="ranking[group] && ranking[group].length">
                                                    <tr v-for="(rank, indexR) in ranking[group]" :key="indexR">
                                                        <td style="text-align: center; font-weight: 700">{{ rank.stt }}</td>
                                                        <td> <img width="30" class="img-fluid lazy" alt="Paris Saint-Germain FC" :src="handleImage(rank.flag)" style=""> <span>{{ rank.name }}</span> </td>
                                                        <td style="text-align: center">{{ rank.match }}</td>
                                                        <td style="text-align: center">{{ rank.win }}</td>
                                                        <td style="text-align: center">{{ rank.draw }}</td>
                                                        <td style="text-align: center">{{ rank.loss }}</td>
                                                        <td style="text-align: center" v-html="rank.hs"></td>
                                                        <td style="text-align: center; font-weight: 700">{{ rank.point }}</td>
                                                    </tr>
                                                </template>
                                            </template>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <style>
                                .tr-head {
                                    text-align: center;
                                    width: 100%;
                                    background: #dadada;
                                }
                                .tr-head td{
                                    font-weight: bold;
                                    font-size: 1.2rem;
                                }
                            </style>
                            <script>
                                const app = new Vue ({
                                    el: '#app',
                                    data: {
                                        rankingData: [],
                                        slug: '<?php echo $slug;?>',
                                        url: '<?php echo admin_url("admin-ajax.php");?>',
                                        action: 'getRankingData'
                                    },
                                    computed: {
                                        ranking () {
                                            return this.rankingData.res || []
                                        },
                                        title () {
                                            return this.rankingData.title || 'Bảng xếp hạng'
                                        },
                                        type () {
                                            return this.rankingData.type || 'Normal'
                                        },
                                        rankingC () {
                                            return this.rankingData.res || {}
                                        }
                                    },
                                    created () {
                                        this.getRankingData();
                                    },
                                    mounted () {
                                        // console.log(this.rankingData)
                                        // console.log(this.ranking)
                                        // console.log(this.rankingC)
                                    },
                                    methods: {
                                        handleImage (url) {
                                            if (url.startsWith('http')) {
                                                return url;
                                            }
                                            return 'https://bongda24h.vn' + url
                                        },
                                        groupName (name) {
                                            if (name) return name.replace('group', '');
                                            return '';
                                        },
                                        getRankingData () {
                                            const data = new FormData();
                                            data.append('slug', this.slug),
                                            data.append('action', this.action);
                                            const config = {
                                                headers: {
                                                    'Content-Type': 'application/x-www-form-urlencoded'
                                                }
                                            }
                                            axios.post(this.url, data, config).then((res) => {
                                                this.rankingData = res.data;
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