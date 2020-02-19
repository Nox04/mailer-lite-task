require('./bootstrap');

import Vue from 'vue';
import router from './router';
import App from './components/App';
import vuetify from './plugins/vuetify';

const app = new Vue({
  router,
  vuetify,
  ...App
}).$mount('#app');
