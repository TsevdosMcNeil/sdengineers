<template>

    <div v-if="empty" class="row index-row">
        <div class="row">
            <div class="col-12">
                <!-- section -->
                <section class="hv-50">
                    <!-- article -->
                    <article id="post-404">
                        <h2 
                            class="text-center mt-5"
                            v-html="noresults">
                        </h2>
                    </article>
                    <!-- /article -->
                </section>
                <!-- /section -->
            </div><!-- /.col-12 -->
        </div><!-- /.row -->
    </div><!-- /.container -->


    <div id="indexGrid"
        v-if="(mode === 'grid')"
        v-bind:class = "(loading===false)?'loaded':''"
        class="row project-row loading"
    >
        <div class="col project-col" 
            v-for="(post, key) in posts" 
        >

            <div 
                v-bind:class="'card card-post card-project ' + post.class"
                v-bind:id="'post-' + post.ID" 
            >
                <a v-bind:href="post.permalink" 
                    rel="bookmark"
                >
                    <figure v-bind:class="'size-large ratio portrait ' + post.portrait_ratio"
                        v-html="post.portrait_img"
                    ></figure>
                    <figure v-bind:class="'size-large ratio landscape ' + post.landscape_ratio"
                        v-html="post.landscape_img"
                    ></figure>
                
                    <header class="card-header">
                        <h6 class="card-title mb-0"
                            v-html="post.post_title"
                        >
                        </h6>
                        <div 
                            class="entry-location" 
                            v-html="post.location"
                        ></div>
                        <div class="btn btn-primary entry-readmore"></div>
                    </header><!-- .entry-header -->
                </a>
            </div><!-- #post-## -->

        </div>  
    </div>

    <div id="indexList"
        v-if="(mode === 'list')"
        v-bind:class = "(loading===false)?'loaded':''"
        class="row project-row loading"
    >
        <table class="table table-striped table-hover">
            <thead>
            <tr>
                <th scope="col">Project</th>
                <th scope="col">Sector</th>
                <th scope="col">Expertise</th>
                <th scope="col">Location</th>
                <th scope="col">Year</th>
            </tr>
            </thead>
            <tbody>
                <tr v-for="(post, key) in posts">

                    <th scope="row"
                        v-bind:class="post.class"
                        v-bind:id="'post-' + post.ID" 
                    >
                        <a rel="bookmark"
                            v-bind:href="post.permalink" 
                            v-html="post.post_title"
                            class="stretched-link"
                        ></a>
                    </th>
                    <td v-html="post.sector"></td>
                    <td v-html="post.expertise"></td>
                    <td v-html="post.location"></td>
                    <td v-html="post.year"></td>
                </tr><!-- #post-## -->
            </tbody>
        </table>
    </div>

</template>

<script>  
//console.log('VUE Projects loaded')

import { config, emitter } from '../vueMain.js';

export default {
        name: 'projects-list',
        data: function() {
            return {
            	loading: 	    false,
                loading_gif:    config.loadingif,
            	noresults:      config.noresults,
            	empty: 		    false,
                mode: 	        'grid',
                sector: 	    null,
                expertise: 	    null,
                posts: 	        []
            }
        },
        mounted: function () {
        	//this.filterPosts(); 

            emitter.on('fireSector', ($sector_id) => {
                this.sector = $sector_id;
                this.filterTaxonomy();
            }); 

            emitter.on('fireExpertise', ($expertise_id) => {
                this.expertise = $expertise_id;
                this.filterTaxonomy();
            }); 

            emitter.on('fireReset', () => {
                this.resetAll();
            }); 
            emitter.on('changeDisplay', ($mode) => {
                this.mode=$mode;
                this.resetAll();

            });
        },
        methods: {
            filterPosts: function () {
                //console.log(this.mode)

                let $sector_id = 'all'
                if(this.sector !== null) {
                    $sector_id = this.sector
                } 

                let $expertise_id = 'all'
                if(this.expertise !== null) {
                    $expertise_id = this.expertise
                }

                var self = this;
	            var getURL = config.api.index + 'projects/sector='+$sector_id+'/expertise='+$expertise_id;
                //console.log(getURL)

	            self.loading = true;
	            //console.log('loading '+ getURL);
                (function($){

                    $('#phpPosts').addClass('loading');

                    $.get( getURL , function( r ){
                        self.loading  = false;
                        self.posts = r;

                        //console.log(r);
                        //self.posts.map((post, key) => { 
                        //});
                        

                    }).done(function() {

                        //Generate random number 
                        function randomIntFromInterval(min, max) { // min and max included 
                            return Math.floor(Math.random() * (max - min + 1) + min)
                        }
                        
                        function setProjectCardPosition() {
                            document.documentElement.style.setProperty('--cdg-mg-top-1', `${randomIntFromInterval(15, 30)}%`);
                            document.documentElement.style.setProperty('--cdg-mg-left-1', `${randomIntFromInterval(15, 30)}%`);
                            document.documentElement.style.setProperty('--cdg-mg-tx-1', `${randomIntFromInterval(-15, 15)}%`);
                            document.documentElement.style.setProperty('--cdg-mg-ty-1', `${randomIntFromInterval(-15, 15)}%`);

                            document.documentElement.style.setProperty('--cdg-mg-top-2', `${randomIntFromInterval(5, 15)}%`);
                            document.documentElement.style.setProperty('--cdg-mg-left-2', `${randomIntFromInterval(5, 15)}%`);
                            document.documentElement.style.setProperty('--cdg-mg-tx-2', `${randomIntFromInterval(-5, 0)}%`);
                            document.documentElement.style.setProperty('--cdg-mg-ty-2', `${randomIntFromInterval(-5, 0)}%`);

                            document.documentElement.style.setProperty('--cdg-mg-top-3', `${randomIntFromInterval(0, 5)}%`);
                            document.documentElement.style.setProperty('--cdg-mg-left-3', `${randomIntFromInterval(0, 5)}%`);
                            document.documentElement.style.setProperty('--cdg-mg-tx-3', `${randomIntFromInterval(0, 5)}%`);
                            document.documentElement.style.setProperty('--cdg-mg-ty-3', `${randomIntFromInterval(-5, 0)}%`);
                        };
                        //setProjectCardPosition();

                        setTimeout(() => {
                            $('#phpPosts').addClass('hide');
                        }, 250)

                        setTimeout(() => {
                            $('#indexGrid').removeClass('loading');
                        }, 500)

                        self.empty = false;
                        
                    }).fail(function() {
                        //console.log('woops');
                        self.loading = false;
                        self.posts   = [];
                        self.empty   = true;
                    });
                })( jQuery );
                

	        },
            filterTaxonomy: function(){
                //console.log('project filterTaxonomy')
                this.filterPosts();
            },
            resetAll: function(){
                //console.log('Reset Projects');
                this.empty     = false;
                this.sector    = null;
                this.expertise = null;
                this.filterPosts();
            }
        }
    }
</script>

<style scoped>

</style>