require('./bootstrap');

window.Vue = require('vue').default;
import Vue from 'vue';

import ElementUI from 'element-ui';
import locale from 'element-ui/lib/locale/lang/vi'
import 'element-ui/lib/theme-chalk/index.css';
Vue.use(ElementUI, { locale });

import VueRouter from 'vue-router';
Vue.use(VueRouter);
import { routes } from './routes';
const router = new VueRouter({ mode: 'history', routes });

import { Mixin } from './mixin'
Vue.mixin(Mixin);

import store from './store';

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))
store.dispatch('getUser', localStorage.getItem('accessToken')).then(() => {
    const app = new Vue({
        el: '#app',
        router,
        store
    });
});
