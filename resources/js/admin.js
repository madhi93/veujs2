/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

//require('./bootstrap');

import Vue from 'vue';
import VueRouter from 'vue-router';
import axios from 'axios';
import flatPickr from 'vue-flatpickr-component';
import 'flatpickr/dist/flatpickr.css';
import '../plugins/vue-font-awesome/index';
import './components/ui';
import _ from 'lodash';

Vue.component('flat-pickr', flatPickr);

// import CKEditor from '@ckeditor/ckeditor5-vue';
import CKEditor from '@ckeditor/ckeditor5-vue';
Vue.use( CKEditor );

import router from './router';
import './config/interceptors';


Vue.use(axios);

Vue.use(VueRouter);

import './config/interceptors';



import VueTelInput from 'vue-tel-input'; 
import 'vue-tel-input/dist/vue-tel-input.css';
Vue.component('vue-tel-input', VueTelInput);

import vSelect from 'vue-select';
Vue.component('v-select', vSelect);

import Permissions from './config/Permissions';
Vue.mixin(Permissions);

import Vuelidate from 'vuelidate';
Vue.use(Vuelidate);
 
const options = {
  confirmButtonColor: '#41b882',
  cancelButtonColor: '#ff7674'
}

const app = new Vue({
    el: '#app',
    router
});

Vue.directive('title', {
    inserted: (el, binding) => document.title = binding.value,
    update: (el, binding) => document.title = binding.value
});

Vue.filter('upText', function (text) {
    return text.charAt(0).toUpperCase() + text.slice(1);
});

Vue.filter('myDate', function (created) {
    return moment(created).format('MMMM Do YYYY');
});

Vue.filter('capitalize', function (value) {
    if (!value) return '';
    value = value.toString();
    return value.charAt(0).toUpperCase() + value.slice(1);
});

Vue.filter('UpperCase', function (value) {
    if (!value) 
       return '';
    return value.toString().toUpperCase(); 
});

Vue.directive('tooltip', function(el, binding){
    $(el).tooltip({
             title: binding.value,
             placement: binding.arg,
             trigger: 'hover'             
         })
});