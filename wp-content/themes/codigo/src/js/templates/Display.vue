<template>
    <button v-on:click="changeDisplay()"
        v-bind:class="mode"
        class="btn-display"
    ></button>
</template>

<script>  
//console.log('VUE Display loaded')

import { config, emitter } from '../vueMain.js';
 
export default {
        name: 'button-display',
        data: function() {
            return {
            	loading: 	    false,
                mode: 	        'grid'
            }
        },
        mounted: function () {
        	//this.filterTerms();            
        },
        methods: {
            changeDisplay: function(){
                if(this.mode === 'list') {
                    this.mode = 'grid'
                } else {
                    this.mode = 'list'
                }
                console.log('changing display')
                emitter.emit('changeDisplay', this.mode );
            }
        }
    }
</script>

<style scoped>

</style>