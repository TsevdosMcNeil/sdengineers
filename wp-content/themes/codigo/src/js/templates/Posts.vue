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
        v-bind:class = "(loading===false)?'loaded':''"
        class="row index-row loading"
    >
        <div class="col" 
            v-for="(post, key) in posts" 
        >
            <div 
                v-if="key<1" 
                v-bind:class="'card card-post card-main-post ' + post.class"
                v-bind:id="'post-' + post.ID" 
            >
                <a 
                    v-bind:href="post.permalink" 
                    rel="bookmark"
                >
                    <div class="row">
                        <div class="col-xxl-9">
                            
                                <figure 
                                    class="size-large ratio ratio-3x2 landscape"
                                    v-html="post.landscape_img"
                                ></figure>
                            
                        </div>
                        <div class="col-xxl-3">
                            <header class="card-header">
                                <div 
                                    class="entry-category mb-0" 
                                    v-html="post.category"
                                ></div>

                                <h3 class="card-title mb-0"
                                    v-html="post.post_title"
                                >
                                </h3>

                                <div class="entry-meta" 
                                    v-html="post.posted_on"
                                ></div>

                                <div class="btn btn-primary entry-readmore"></div>

                            </header><!-- .entry-header -->
                        </div>
                    </div><!-- .row -->
                </a>
            </div>


            <div 
                v-if="key>=1" 
                v-bind:class="'card card-post ' + post.class"
                v-bind:id="'post-' + post.ID" 
            >
                <a v-bind:href="post.permalink" 
                    rel="bookmark"
                >
                    <div 
                        class="entry-category"
                        v-html="post.category"
                    ></div>

                    <figure 
                        v-bind:class="'size-large ratio portrait ' + post.portrait_ratio"
                        v-html="post.portrait_img"
                    ></figure>
                    <figure 
                        v-bind:class="'size-large ratio landscape ' + post.landscape_ratio"
                        v-html="post.landscape_img"
                    ></figure>

                    <header class="card-header">
                        <div 
                            class="entry-meta" 
                            v-html="post.posted_on"
                        ></div>

                        <h6 class="card-title mb-0"
                            v-html="post.post_title"
                        ></h6>

                        <div 
                            class="entry-excerpt" 
                            v-html="post.excerpt"
                        ></div>
                        
                        <div class="btn btn-primary entry-readmore" 
                            v-bind:href="post.permalink" 
                        ></div>
                    </header><!-- .entry-header -->
                </a>
            </div><!-- #post-## -->

        </div>  
    </div>
</template>

<script>  
//console.log('VUE Posts loaded')

import { config, emitter } from '../vueMain.js';

export default {
        name: 'posts-list',
        data: function() {
            return {
            	loading: 	    false,
                loading_gif:    config.loadingif,
            	noresults:      config.noresults,
            	empty: 		    false,
                posts: 	        []
            }
        },
        mounted: function () {
        	//this.filterPosts(); 

            emitter.on('fireTaxonomy', ($term_id) => {
                this.filterTaxonomy($term_id);
            });
            
            emitter.on('fireReset', () => {
                this.resetAll();
            });     
        },
        methods: {
            filterPosts: function ($term_id) {
                if($term_id === '') {
                    $term_id = 'all'
                }
	            var self = this;
	            var getURL = config.api.index + 'posts/category='+$term_id;

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
                        
                        setTimeout(() => {
                            $('#phpPosts').addClass('hide');
                        }, 250)
                        setTimeout(() => {
                            $('#indexGrid').removeClass('loading');
                        }, 500)
                        
                    }).fail(function() {
                        //console.log('woops');
                        self.loading = false;
                        self.posts   = [];
                        self.empty   = true;
                    });
                })( jQuery );
                

	        },
            filterTaxonomy: function($term_id){
                console.log('post filterTaxonomy', $term_id)
                this.filterPosts($term_id);
            },
            resetAll: function(){
                console.log('Reset Posts');
                this.empty = false;
                this.filterPosts();
            }
        }
    }
</script>

<style scoped>

</style>