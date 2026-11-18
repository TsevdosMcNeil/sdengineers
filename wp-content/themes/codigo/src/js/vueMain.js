import Vue from 'vue';
//import App from './templates/App.vue'; <-- example
import Posts from './templates/Posts.vue';
import Categories from './templates/Categories.vue';

import Projects from './templates/Projects.vue';
import Sectors from './templates/Sectors.vue';
import Expertises from './templates/Expertises.vue';

import People from './templates/People.vue';
import PeopleModal from './templates/PeopleModal.vue';
import Roles from './templates/Roles.vue';
import PeopleList from './templates/PeopleList.vue';

import Reset from './templates/Reset.vue';
import Display from './templates/Display.vue';

import Emitter from 'tiny-emitter';

//console.log('VUE Main loaded')

export var emitter = new Emitter();

export const config = {
    api: {
        index: REST_POSTS_URL
    },
    nonce:     REST_NONCE,
    loadingif: REST_LOADING_GIF,
    noresults: REST_NO_RESULTS
};


/* //example
if(document.getElementById("journalList")){
    const app = Vue.createApp(App).mount('#journalList')
}
*/
if(document.getElementById("appPosts")){
    const appPosts = Vue.createApp(Posts).mount('#appPosts')
}
if(document.getElementById("appCategories")){
    const appCategories = Vue.createApp(Categories).mount('#appCategories')
}

if(document.getElementById("appProjects")){
    const appProjects = Vue.createApp(Projects).mount('#appProjects')
}
if(document.getElementById("appSectors")){
    const appSectors = Vue.createApp(Sectors).mount('#appSectors')
}
if(document.getElementById("appExpertises")){
    const appExpertises = Vue.createApp(Expertises).mount('#appExpertises')
}

if(document.getElementById("appPeople")){
    const appPeople = Vue.createApp(People).mount('#appPeople')
}
if(document.getElementById("appPeopleModal")){
    const appPeople = Vue.createApp(PeopleModal).mount('#appPeopleModal')
}
if(document.getElementById("appRoles")){
    const appRoles = Vue.createApp(Roles).mount('#appRoles')
}

if(document.getElementById("appReset")){
    const appRoles = Vue.createApp(Reset).mount('#appReset')
}

if(document.getElementById("appDisplay")){
    const appRoles = Vue.createApp(Display).mount('#appDisplay')
}

document.addEventListener('DOMContentLoaded', function(event) {
    //console.log('DOMContentLoaded')
    if(document.getElementById("appPeopleList")){
        const appPeopleList = Vue.createApp(PeopleList).mount('#appPeopleList')
    }
})
