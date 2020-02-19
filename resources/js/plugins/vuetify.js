import Vue from 'vue'
import Vuetify from 'vuetify'
import 'vuetify/dist/vuetify.min.css'

Vue.use(Vuetify)

const opts = {
  theme: {
    themes: {
      light: {
        primary: '#00a859',
        secondary: '#f0820b',
        tertiary: '#00a859',
        accent: '#00a154',
        error: '#b71c1c',
      },
      dark: {
        primary: '#00ea86',
        secondary: '#f0820b',
        tertiary: '#00a859',
        accent: '#00ea86',
        error: '#df1c1c',
      },
    },
    dark: false,
  },
}

export default new Vuetify(opts)
