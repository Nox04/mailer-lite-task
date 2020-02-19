import Vue from 'vue';
import Toasted from 'vue-toasted';

Vue.use(Toasted, {
  theme: 'toasted-primary',
  position: 'bottom-right',
  duration : 5000,
  action : {
    text : 'Dismiss',
    onClick : (e, toastObject) => {
      toastObject.goAway(0);
    }
  }
});
