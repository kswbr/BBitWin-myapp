<template>
  <div id="app" class="container">
    <router-view />
  </div>
</template>

<script>
export default {
  name: 'app',
  components: {
  }
}
</script>
<style>
.container {
  max-width: 960px;
}
#app {
  margin:40px auto;
}
.lh-condensed { line-height: 1.25; }
</style>
