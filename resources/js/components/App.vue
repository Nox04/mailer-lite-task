<template>
  <Main>
    <template v-slot:children>
      <transition name="fade" mode="out-in">
        <router-view />
      </transition>
    </template>
  </Main>
</template>

<script>
import Main from '../layouts/Main';
export default {
  name: 'App',
  components: { Main }
};
</script>
