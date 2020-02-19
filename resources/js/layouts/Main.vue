<template>
  <v-app id="inspire" :dark="isDarkTheme">
    <v-navigation-drawer v-model="drawer" :clipped="$vuetify.breakpoint.lgAndUp" app>
      <v-list dense>
        <template v-for="item in items">
          <v-list-item :key="item.text" :to="item.path">
            <v-list-item-action>
              <v-icon>{{ item.icon }}</v-icon>
            </v-list-item-action>
            <v-list-item-content>
              <v-list-item-title>{{ item.text }}</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
        </template>
      </v-list>
    </v-navigation-drawer>

    <v-app-bar :clipped-left="$vuetify.breakpoint.lgAndUp" app color="tertiary" dark>
      <v-app-bar-nav-icon @click.stop="drawer = !drawer" />
      <v-toolbar-title style="width: 300px" class="ml-0 pl-4">
        <span class="hidden-sm-and-down">Mailer Lite</span>
      </v-toolbar-title>
      <v-spacer />
      <v-btn icon @click="switchTheme">
        <v-icon v-if="isDarkTheme">mdi-lightbulb-on-outline</v-icon>
        <v-icon v-else>mdi-weather-night</v-icon>
      </v-btn>
    </v-app-bar>
    <v-content>
      <v-container fluid>
        <slot name="children"></slot>
      </v-container>
    </v-content>
  </v-app>
</template>

<script>
export default {
  props: {
    source: String
  },
  computed: {
    isDarkTheme() {
      return this.$vuetify.theme.dark;
    }
  },
  data: () => ({
    dialog: false,
    drawer: null,
    items: [
      { icon: 'mdi-contacts', text: 'Subscribers', path: '/' },
      { icon: 'mdi-focus-field-horizontal', text: 'Fields', path: '/fields' }
    ]
  }),
  methods: {
    switchTheme() {
      this.$vuetify.theme.dark = !this.$vuetify.theme.dark;
    }
  }
};
</script>
