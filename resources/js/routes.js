import Home from './components/Subscribers/Index.vue';
import Fields from './components/Fields/Index.vue';

export default [
  {
      path: '/',
      name: 'index',
      component: Home,
  },
  {
    path: '/fields',
    name: 'fields',
    component: Fields,
  }
];
