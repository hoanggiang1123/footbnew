<?php 
get_header();?>
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <script src="https://blog.bdtt.tv:8081/socket.io/socket.io.js"></script>
    <div class="tpl-index">
        <div class="container position-relative">
        <div class="row">
            <div class="col-12">
                <main class="arena">
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
                           
                            <div class="hr"></div>
                        </div>
                        <div class="single-post-body">
                        <div id="app">
                            <div class="odd-wrap" v-if="normal.length">
                                <keo-header />
                            </div>
                            <div v-if="live.length">
                                <odd-item v-for="(keos, key) in live" :key="key" :keos="keos" :name="key" :live="'live'" class="odd-live"/>
                            </div>
                            <div v-if="normal.length">
                                <odd-item v-for="(keos, key) in normal" :key="key" :keos="keos" :name="key" :live="'normal'"/>
                            </div>
                        </div>
                            <?php
                                $Sport = new BDTT_SPORT_CRAWL();
                                $data = $Sport->getTyLeKeo();
                                if (count($data) > 0) {

                                    $keoHeader = BDTT_SPORT_HTML::createTyleKeoHeader();
                                    $keoLive = BDTT_SPORT_HTML::createTyleKeo($data[0], 'live');
                                    $keoNormal = BDTT_SPORT_HTML::createTyleKeo($data[1]);
                                    echo '<div id="ssr">'. $keoHeader. $keoLive. $keoNormal .'</div>';
                                }
                            ?>
                            <div class="one-item-content">
                                <?php the_content();?>
                            </div>
                        </div>
                        <?php echo bdttAuthor();?>
                        
                    </div>
                    <?php endwhile; wp_reset_postdata();?>
                </main>
            </div>
        </div>
        </div>
    </div>
    <script>
        var socket=io('https://blog.bdtt.tv:8081/');var app=new Vue({el:'#app',data:{live:[],normal:[]},created(){socket.on("SEND_DATA",(res)=>{if(document.querySelector('#ssr'))document.querySelector('#ssr').style.display='none';this.live=this.transformObj(res[0]);this.normal=this.transformObj(res[1])})},methods:{transformObj(obj){var result=[];for(var key in obj){if(obj.hasOwnProperty(key)){obj[key].leage_title=key;result.push(obj[key])}} return result}},components:{'keo-header':{template:`<div ref="nav" class="bdtt-tyle"><div class="hour">Giờ</div><div class="match">Trận Đấu</div><div class="fulltime"><div class="ft">Cả Trận</div><div class="tyle">Tỷ lệ</div><div class="over">Châu Á</div><div class="euro">1x2</div></div><div class="half"><div class="ft">Hiệp 1</div><div class="tyle">Tỷ lệ</div><div class="over">Châu Á</div><div class="euro">1x2</div></div></div>`,mounted(){window.onscroll=()=>{var sticky=this.$refs.nav.offsetTop;if(window.pageYOffset>=sticky+150){this.$refs.nav.classList.add('sticky');this.$refs.nav.style.width=document.querySelector('#app').offsetWidth+'px'}else{this.$refs.nav.classList.remove('sticky');this.$refs.nav.style.width='auto'}}}},'odd-item':{template:`<div class="odd-items" v-if="keos && keos.length"><div class="odd-head">{{ keos.leage_title }}</div><div  v-for="(keo, key) in keoFilter" :key="key"><template v-if="!keo.keodongtyso"><div class="odd-item"><odd-hour :name="key" :keo="keo" :live="live"/><odd-match :name="key" :keo="keo"/><odd-keo :cls="'odd-tyle-ft'" :type="'ratiosObj'" :keo="keo" :name="key"/><odd-keo :cls="'odd-over-ft'" :type="'overUnder'" :keo="keo" :name="key"/><odd-keo :cls="'odd-euro-ft'" :type="'euro'" :keo="keo" :name="key"/><odd-keo :cls="'odd-tyle'" :type="'ratiosObjHalf'" :keo="keo" :name="key"/><odd-keo :cls="'odd-over'" :type="'overUnderHalf'" :keo="keo" :name="key"/><odd-keo :cls="'odd-euro'" :type="'euroHalf'" :keo="keo" :name="key"/></div></template><template v-else><odd-keotyso :keo="keo.keodongtyso"/></template></div></div>`,props:['keos','name','live'],computed:{keoFilter(){return this.keos.filter((items,index)=>{return isNaN(index)!==!0})}},components:{'odd-hour':{template:`<div class="odd-hour"><div class="date-result">{{ match_date }}</div><div class="time-live" v-html="match_time"></div><div></div></div>`,props:['keo','type','name','live'],computed:{match_date(){if(this.keo.dateTimeObj.dateStr){return this.keo.dateTimeObj.dateStr} return''},match_time(){if(this.keo.dateTimeObj.timeStr){if(this.live==='live'){return'<img src="https://odds.keopro.com/live-keo.gif"> '+this.keo.dateTimeObj.timeStr} return this.keo.dateTimeObj.timeStr} return''}}},'odd-match':{template:`<div class="odd-match"><div :class="home_name === chu ? 'strong ' : '' + 'team-ab'">{{ home_name }}</div><div :class="away_name === chu ? 'strong ' : '' + 'team-un'">{{ away_name }}</div><div class="pred">{{ predict }}</div></div>`,props:['keo','type','name'],computed:{away_name(){if(this.keo.infosObj.away_name&&this.keo.infosObj.away_name!==''){return this.keo.infosObj.away_name} return''},home_name(){if(this.keo.infosObj.home_name&&this.keo.infosObj.home_name!==''){return this.keo.infosObj.home_name} return''},predict(){if(this.keo.infosObj.predict&&this.keo.infosObj.predict!==''){return this.keo.infosObj.predict} return''},chu(){if(this.keo.infosObj.chu&&this.keo.infosObj.chu!==''){return this.keo.infosObj.chu} return''},}},'odd-keo':{template:`<div :class="cls"><template v-if="type !== 'euro' && type !== 'euroHalf'"><div class="first">{{ first }}</div><div class="second">{{ second }}</div><div class="third">{{ third }}</div><div class="forth">{{ fourth }}</div><div class="fith">{{ fith }}</div><div class="sixth">{{ sixth }}</div></template><template v-else><div class="first">{{ second }}</div><div class="second">{{ first }}</div><div class="third">{{ fourth }}</div><div class="forth">{{ third }}</div><div class="fith">{{ sixth }}</div><div class="sixth">{{ fith }}</div></template></div>`,props:['cls','keo','type','name'],computed:{first(){if(this.keo[this.type].ratioFirst){return this.keo[this.type].ratioFirst} return''},second(){if(this.keo[this.type].ratioSecound){return this.keo[this.type].ratioSecound} return''},third(){if(this.keo[this.type].ratioThird){return this.keo[this.type].ratioThird} return''},fourth(){if(this.keo[this.type].ratioFourd){return this.keo[this.type].ratioFourd} return''},fith(){if(this.keo[this.type].ratioFith){return this.keo[this.type].ratioFith} return''},sixth(){if(this.keo[this.type].ratioSixth){return this.keo[this.type].ratioSixth} return''}}},'odd-keotyso':{template:`<div><div class="keotyso" @click="toggleKeoTySo"><div class="keotyso-title">Kèo Tỷ Số</div><div :class="isUp === true ? 'keotyso-btn up' : 'keotyso-btn'"></div></div><div :class="isOpen === true ? 'odd-keotyso ': 'hide' + ' odd-keotyso'"><div class="keotyso-item" v-for="(item, index) in keo" :key="index"><template v-if="item.tyso !== '' && item.ratio !== ''"><div class="ts">{{ item.tyso }}</div><div class="ts-ratio">{{ item.ratio }}</div></template></div></div></div>`,data(){return{isOpen:!1,isUp:!1}},props:['keo'],methods:{toggleKeoTySo(){this.isOpen=!this.isOpen;this.isUp=!this.isUp}}}}}}})
    </script>
<?php get_footer();?>