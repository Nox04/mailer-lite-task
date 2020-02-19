import Vue from 'vue';
import router from './router';
import App from './components/App';
import vuetify from './plugins/vuetify';
require('./bootstrap');
require('./plugins/toasted');

new Vue({
  router,
  vuetify,
  ...App
}).$mount('#app');
