<template>
    <strong v-for="(member, key) in members" >
        <a  rel="bookmark"
            href="#"
            data-bs-toggle="modal"
            data-bs-target="#modalMember" 
            v-on:click="selectMember(member.id)"
            v-html="member.title"
        >
        </a><br/>
    </strong>
</template>

<script>  
//console.log('VUE People list loaded')

import { config, emitter } from '../vueMain.js';

export default {
        name: 'people-list',
        data: function() {
            return {
                members: []
            }
        },
        mounted: function () {
            let element  = this.$el;
            this.members = JSON.parse(element.parentElement.dataset.members);
        },
        methods: {
            selectMember: function($member_id){
                //console.log('select member ' +$member_id)
                emitter.emit('fireMemberSelected', $member_id);
            }
        }
    }
</script>

<style scoped>

</style>