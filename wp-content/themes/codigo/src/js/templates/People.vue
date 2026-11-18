<template>

    <div v-if="empty" 
        class="row index-row"
    >
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


    <div id="peopleGrid"
        v-bind:class = "(loading===false)?'loaded':''"
        class="row people-row loading"
    >
        <div class="col people-col" 
            v-for="(post, key) in posts" 
        >
            <div 
                v-bind:class="'card card-post card-people ' + post.class"
                v-bind:id="'post-' + post.ID" 
            >

                <a rel="bookmark"
                    data-bs-toggle="modal"
                    data-bs-target="#modalMember" 
                    v-on:click="selectMember(post.ID)"
                >
                    <figure v-bind:class="'size-large ratio portrait ratio-2x3'"
                        v-html="post.thumbnail"
                    ></figure>

                    <header class="card-header">
                        <h6 class="card-title mb-0"
                            v-html="post.post_title"
                        ></h6>
                        <div 
                            class="entry-member-role" 
                            v-html="post.role"
                        ></div>
                    </header><!-- .entry-header -->
                </a>

            </div><!-- #post-## -->

        </div>  
        
    </div>

</template>

<script>  
//console.log('VUE People loaded')

import { config, emitter } from '../vueMain.js';

export default {
        name: 'people-cards',
        data: function() {
            return {
            	loading: 	    false,
                loading_gif:    config.loadingif,
            	noresults:      config.noresults,
            	empty: 		    false,
                role: 	        null,
                posts: 	        []
            }
        },
        mounted: function () {
        	this.filterPosts(); 

            emitter.on('fireRole', ($role_id) => {
                this.role = $role_id;
                this.filterTaxonomy();
            });  
            
            emitter.on('fireReset', () => {
                this.resetAll();
            }); 
        },
        methods: {
            filterPosts: function () {
                let $role_id = 'all'
                if(this.role !== null) {
                    $role_id = this.role
                }

	            var self = this;
	            var getURL = config.api.index + 'people/role='+$role_id;

	            self.loading = true;
	            //console.log('loading '+ getURL);
                (function($){
                    $.get( getURL , function( r ){
                        self.loading  = false;
                        self.posts = r;

                        //console.log(r);
                        //self.posts.map((post, key) => { 
                        //});

                    }).done(function() {
                        setTimeout(() => {
                            $('#peopleGrid').removeClass('loading');
                        }, 500)
                        
                    }).fail(function() {
                        //console.log('woops');
                        self.loading = false;
                        self.posts   = [];
                        self.empty   = true;
                    });
                })( jQuery );
                

	        },
            filterTaxonomy: function(){
                console.log('people filterTaxonomy', this.role)
                this.filterPosts();
            },
            resetAll: function(){
                console.log('Reset People');
                this.empty = false;
                this.role  = null,
                this.filterPosts();
            },
            selectMember: function($member_id){
                console.log('select member ' +$member_id)
                emitter.emit('fireMemberSelected', $member_id);
            }
        }
    }
</script>

<style scoped>

</style>