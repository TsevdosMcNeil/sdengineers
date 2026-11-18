<template>
        <!-- Member -->
        <div v-for="(post, key) in posts" 
            v-bind:data-key = "key"
            v-bind:class = "(post.ID===selected)? post.slideclass + ' active' : post.slideclass "
            v-html="post.membercontent"
        ></div>
        <!-- /.Member -->
</template>

<script>  
//console.log('VUE People Modal loaded')

import { config, emitter } from '../vueMain.js';

export default {
        name: 'peoplemodal-list',
        data: function() {
            return {
            	loading: 	    false,
                role: 	        null,
                selected: 	    null,
                members:        null,
                posts: 	        []
            }
        },
        mounted: function () {

            let element  = this.$el;
            if(element.parentElement.dataset.members != ''){
                this.members = JSON.parse(element.parentElement.dataset.members);
            }

        	this.filterPosts(); 

            emitter.on('fireRole', ($role_id) => {
                this.role = $role_id;
                this.filterTaxonomy();
            });
            
            emitter.on('fireReset', () => {
                this.resetAll();
            }); 

            emitter.on('fireMemberSelected', ($member_id) => {
                //console.log('Member in modal ' +$member_id);
                this.selected = $member_id;
            }); 
        },
        methods: {
            filterPosts: function () {

                //console.log('members');
                //console.log(this.members);
                if(this.members !== null) {
                    //[{"id":689,"title":"Harry Samplename"},{"id":687,"title":"Jill Samplename"}]
                    let memberString = this.members.map(ele => ele.id).join(",");
                    var getURL = config.api.index + 'people/members='+memberString;
                } else {

                    let $role_id = 'all'
                    if(this.role !== null) {
                        $role_id = this.role
                    }

                    
                    var getURL = config.api.index + 'people/role='+$role_id;
                }

                var self = this;

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

                        
                    }).fail(function() {
                        //console.log('woops');
                        self.loading = false;
                        self.posts   = [];
                    });
                })( jQuery );
                    
                
	        },
            filterTaxonomy: function(){
                //console.log('people modal filterTaxonomy', this.role)
                this.filterPosts();
            },
            resetAll: function(){
                //console.log('Reset PeopleModal');
                this.role  = null,
                this.filterPosts();
            }
        }
    }
</script>

<style scoped>

</style>