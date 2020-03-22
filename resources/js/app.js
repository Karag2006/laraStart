/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import moment from 'moment';
moment.locale('de');

import { Form, HasError, AlertError } from 'vform'
import VueRouter from 'vue-router'


window.Form = Form;
Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)

Vue.use(VueRouter)


import VueProgressBar from 'vue-progressbar';

const options = {
    color: 'green',
    failedColor: '#874b4b',
    thickness: '5px',
    transition: {
      speed: '0.2s',
      opacity: '0.6s',
      termination: 300
    },
    autoRevert: true,
    location: 'top',
    inverse: false
  }

Vue.use(VueProgressBar, options)

import Dashboard from './components/Dashboard'
import Profile from './components/Profile'
import Users from './components/Users'

let routes = [
    { path: '/dashboard', component: Dashboard },
    { path: '/profile', component: Profile },
    { path: '/user', component: Users }
  ];

const router = new VueRouter({
    mode: 'history',
    routes // short for `routes: routes`
  })

Vue.filter('upText', function(text){
    return text.charAt(0).toUpperCase() + text.slice(1);
});

Vue.filter('myDate', function(timecode){
    return moment(timecode).format('Do MMMM YYYY')
});

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

//Vue.component('example-component', require('./components/ExampleComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    router
});
