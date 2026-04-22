<script setup>
import { ref } from 'vue'
import AppSidebar from 'components/layout/AppSidebar.vue'
import AppHeader from 'components/layout/AppHeader.vue'

const drawerOpen = ref(true)

function toggleDrawer() {
  drawerOpen.value = !drawerOpen.value
}
</script>

<template>
  <q-layout view="lHh Lpr lFf" class="dashboard-layout">
    <app-header @toggle-drawer="toggleDrawer" />
    <app-sidebar v-model="drawerOpen" />

    <q-page-container class="dashboard-layout__content">
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<style scoped lang="scss">
.dashboard-layout {
  background: var(--bg-page);
}

.dashboard-layout__content {
  background: var(--bg-page);
}
</style>
