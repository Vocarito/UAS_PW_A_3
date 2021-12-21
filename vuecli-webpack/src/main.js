import Vue from 'vue'
import App from './App.vue'
import vuetify from "@/plugins/vuetify";
import router from "./router";
import axios from 'axios';

//Carousel Update
import VueCarousel from 'vue-carousel';
Vue.use(VueCarousel);

Vue.config.productionTip = false;

Vue.prototype.$http = axios; // code standar untuk menggunakan axios
Vue.prototype.$api = 'http://uas.vocarito.com/UAS/Project_UAS_PW_Perpus/public/api'; //link api

new Vue({
  vuetify,
  router,
  render: (h) => h(App),
}).$mount('#app')
