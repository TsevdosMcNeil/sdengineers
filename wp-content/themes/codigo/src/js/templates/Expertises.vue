<template>
    <div class="accordion-body" 
        v-if="loading===false" >
        <h3 class="m-0" 
            v-for="(term, key) in terms"
        >
            <a v-on:click="filterTaxonomy(term)"
                v-bind:class="{ active: term.isActive }"
                v-html:href="term.name" 
                rel="bookmark"
            ></a>
        </h3>
    </div>
</template>

<script>  
//console.log('VUE Expertises loaded')

import { config, emitter } from '../vueMain.js';
 
export default {
        name: 'expertise-list',
        data: function() {
            return {
            	loading: 	    false,
                loading_gif:    config.loadingif,
            	noresults:      config.noresults,
            	empty: 		    false,
                terms: 	        []
            }
        },
        mounted: function () {
        	this.filterTerms();  

            emitter.on('fireReset', () => {
                this.inactiveAll();
            });        
        },
        methods: {
            filterTerms: function () {
	            var self = this;
	            var getURL = config.api.index + 'taxonomy=expertise';

	            self.loading = true;
	            //console.log('loading '+ getURL);
                (function($){
                    $.get( getURL , function( r ){
                        self.loading  = false;
                        self.terms = r;

                        self.terms.map((term, key) => { 
                            term.isActive = false
                        });

                    }).done(function() {
                        //
                    }).fail(function() {
                        //console.log('woops');
                        self.loading    = false;
                        self.terms = [];
                        self.empty      = true;
                    });
                })( jQuery );
                

	        },
            filterTaxonomy: function($term){
                //console.log('expertises filterTaxonomy ', $term.term_id);
                this.inactiveAll();   
                $term.isActive = true;
                emitter.emit('fireExpertise', $term.term_id );
            },
            inactiveAll(){
                //console.log('Inactive Expertises');
                this.terms.map((term, key) => { 
                    term.isActive = false
                });
            }
        }
    }
</script>

<style scoped>

</style>