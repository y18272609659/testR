window.Vue = require('vue');

import Vue from 'vue'
import VueRouter from 'vue-router'
import VueResource from 'vue-resource'

import App from './App'
import Home from './components/home/home'

Vue.use(VueRouter)
Vue.use(VueResource)

const router = new VueRouter({
  routes: [
    { path: '/', component: Home },
    { path: '/home', component: Home },
  ]
})

new Vue({
  components: { App },
  template: '<App/>',
  router
}).$mount('#app')