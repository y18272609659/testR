window.Vue = require('vue');

import Vue from 'vue'

import App from './App'
import Outside from './components/outside/outside'
import Home from './components/home/home'
import Building from './components/building/building'

import VueRouter from 'vue-router'
Vue.use(VueRouter)

import VueResource from 'vue-resource'
Vue.use(VueResource)

import ElementUI from 'element-ui'
import 'element-ui/lib/theme-chalk/index.css'
Vue.use(ElementUI)

/* Data init */
import { init } from './support'

let userData = init()
let router = ''
if (localStorage.getItem('loginCheck') === 'true') {
  router = new VueRouter({
    routes: [
      { path: '/', component: Home, props: { 'userData': userData } },
      { path: '/home', component: Home, props: { 'userData': userData } },
      { path: '/building', component: Building, props: { 'userData': userData } },
    ]
  })
} else {
  router = new VueRouter({
    routes: [
      { path: '/', component: Outside },
      { path: '/outside', component: Outside },
    ]
  })
}

// root element
new Vue({
  components: {App},
  template: '<App/>',
  router
}).$mount('#app')