/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */
import { csrfToken } from './config';

window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;

window.axios.interceptors.response.use((response) => {
  return response;
}, function (error) {
  if (error.response.status === 422) {
    const key = Object.keys(error.response.data.errors)[0];
    return Promise.reject(error.response.data.errors[key][0]);
  }
  return Promise.reject('Could not connect to the server.');
});
