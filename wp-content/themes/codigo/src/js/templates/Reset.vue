<template>
    <a v-on:click="launchReset()" >{{title}}</a>
</template>

<script>  
//console.log('VUE Reset loaded')

import { config, emitter } from '../vueMain.js';
 
export default {
        name: 'reset-item',
        data: function() {
            return {
            	loading: 	    false,
                title: 	        'Reset'
            }
        },
        mounted: function () {
        	//this.filterTerms();            
        },
        methods: {
            launchReset: function(){
                console.log('reseting')
                emitter.emit('fireReset', true );
            }
        }
    }
</script>

<style scoped>

</style>