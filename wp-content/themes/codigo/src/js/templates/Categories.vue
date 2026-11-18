<template>
    <div class="accordion-body" 
        v-if="loading===false" >
        <h3 class="m-0" 
            v-for="(category, key) in categories" 
        >
            <a v-on:click="filterTaxonomy(category)"
                v-bind:class="{ active: category.isActive }"
                v-html:href="category.name" 
                rel="bookmark"
            ></a>
        </h3>
    </div>
</template>

<script>  
//console.log('VUE Categories loaded')

import { config, emitter } from '../vueMain.js';
 
export default {
        name: 'categories-list',
        data: function() {
            return {
            	loading: 	    false,
                loading_gif:    config.loadingif,
            	noresults:      config.noresults,
            	empty: 		    false,
                categories: 	[]
            }
        },
        mounted: function () {
        	this.filterCategories(); 
            
            emitter.on('fireReset', () => {
                this.inactiveAll();
            }); 
        },
        methods: {
            filterCategories: function () {
                //console.log('filterCategories ');
	            var self = this;
	            var getURL = config.api.index + 'post_cats';

	            self.loading = true;
	            
                (function($){
                    $.get( getURL , function( r ){
                        self.loading  = false;
                        self.categories = r;
                        //console.log(r);
                        self.inactiveAll();

                    }).done(function() {
                        //
                    }).fail(function() {
                        //console.log('woops');
                        self.loading    = false;
                        self.categories = [];
                        self.empty      = true;
                    });
                })( jQuery );
                

	        },
            filterTaxonomy: function($category){
                this.inactiveAll()
                //console.log('category filterTaxonomy ', $category.term_id);
                emitter.emit('fireTaxonomy', $category.term_id );
                $category.isActive = true;
                //console.log('category '+$category.term_id+' active: '+ $category.isActive);
            },
            inactiveAll(){
                //console.log('Switch categories inactive');
                if(this.categories.constructor === Array) {
                    this.categories.map((category, key) => { 
                        category.isActive = false
                    });
                }
                
            }
        }
    }
</script>

<style scoped>

</style>