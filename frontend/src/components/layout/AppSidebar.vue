<script setup>
import { computed } from 'vue'
import { useRoute } from 'vue-router'
import { useAuthStore } from 'stores/auth'

defineProps({
  modelValue: { type: Boolean, required: true }
})

defineEmits(['update:modelValue'])

const route     = useRoute()
const authStore = useAuthStore()

const role = computed(() => authStore.usuario?.role ?? 'consultor')

const navItems = computed(() => {
  const items = [
    { label: 'Inicio',        icon: 'dashboard',       name: 'home',     roles: null },
    { label: 'Usuários',      icon: 'people_alt',       name: 'users',    roles: ['administrador', 'editor'] },
    { label: 'Preferências',  icon: 'manage_accounts',  name: 'profile',  roles: null },
    { label: 'Configurações', icon: 'settings',         name: 'settings', roles: null },
  ]
  return items.filter(i => !i.roles || i.roles.includes(role.value))
})

const bottomItems = [
  { label: 'Suporte', icon: 'help_outline', name: 'suporte' },
]

function isActive(item) {
  return route.name === item.name
}
</script>

<template>
  <q-drawer
    :model-value="modelValue"
    @update:model-value="$emit('update:modelValue', $event)"
    show-if-above
    :width="240"
    :breakpoint="1024"
    class="sidebar"
  >
    <div class="sidebar__inner">

      <div class="sidebar__brand">
        <span class="brand-title">TriAL</span>
        <span class="brand-sub">SMART SOFTWARES</span>
      </div>

      <div class="sidebar__role-row">
        <span :class="['role-pill', 'role-pill--' + role]">
          {{ role === 'administrador' ? 'Admin' : role === 'editor' ? 'Editor' : 'Consultor' }}
        </span>
      </div>

      <nav class="sidebar__nav">
        <template v-for="item in navItems" :key="item.name">
          <router-link
            v-if="item.name"
            :to="{ name: item.name }"
            class="nav-item"
            :class="{ 'nav-item--active': isActive(item) }"
          >
            <q-icon :name="item.icon" size="18px" class="nav-item__icon" />
            <span class="nav-item__label">{{ item.label }}</span>
            <span v-if="isActive(item)" class="nav-item__indicator" />
          </router-link>
        </template>
      </nav>

      <div class="sidebar__spacer" />

      <nav class="sidebar__nav sidebar__nav--bottom">
        <router-link
          v-for="item in bottomItems"
          :key="item.label"
          :to="{ name: item.name }"
          class="nav-item"
          target="_blank"
        >
          <q-icon :name="item.icon" size="18px" class="nav-item__icon" />
          <span class="nav-item__label">{{ item.label }}</span>
        </router-link>
      </nav>
    </div>
  </q-drawer>
</template>

<style scoped lang="scss">
.sidebar {
  background: var(--bg-sidebar) !important;
  border-right: 1px solid var(--border-subtle) !important;
  box-shadow: var(--shadow-card) !important;
}

.sidebar__inner {
  display: flex;
  flex-direction: column;
  height: 100%;
  padding: 0 0 24px;
}

.sidebar__brand {
  padding: 28px 24px 20px;
  border-bottom: 1px solid var(--border-subtle);
  margin-bottom: 8px;
}

.brand-title {
  display: block;
  font-size: 1.3rem;
  font-weight: 800;
  letter-spacing: 0.06em;
  color: var(--text-primary);
  line-height: 1;
}

.brand-sub {
  display: block;
  margin-top: 5px;
  font-size: 0.56rem;
  font-weight: 700;
  letter-spacing: 0.28em;
  color: var(--color-primary);
}

.sidebar__role-row {
  padding: 8px 24px 16px;
}

.role-pill {
  display: inline-flex;
  align-items: center;
  padding: 3px 12px;
  border-radius: 999px;
  font-size: 0.66rem;
  font-weight: 700;
  letter-spacing: 0.08em;
  text-transform: uppercase;

  &--administrador { background: rgba(157,80,255,.15); color: #b97aff; border: 1px solid rgba(157,80,255,.3); }
  &--editor        { background: rgba(17,214,255,.12); color: #11d6ff; border: 1px solid rgba(17,214,255,.25); }
  &--consultor     { background: rgba(0,245,212,.10);  color: #00c4a7; border: 1px solid rgba(0,245,212,.25); }
}

.sidebar__nav {
  display: flex;
  flex-direction: column;
  gap: 2px;
  padding: 0 12px;
}

.sidebar__nav--bottom {
  padding-top: 12px;
  border-top: 1px solid var(--border-subtle);
}

.sidebar__spacer { flex: 1; }

.nav-item {
  position: relative;
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 10px 12px;
  border-radius: 10px;
  cursor: pointer;
  text-decoration: none;
  color: var(--text-secondary);
  font-size: 0.875rem;
  font-weight: 500;
  transition: background 0.15s, color 0.15s;
  user-select: none;

  &:hover {
    background: var(--bg-overlay);
    color: var(--text-primary);
  }

  &--active {
    background: rgba(17, 214, 255, 0.1);
    color: var(--color-primary);
    font-weight: 600;

    .nav-item__icon { color: var(--color-primary); }
  }
}

.nav-item__icon  { flex-shrink: 0; color: inherit; }
.nav-item__label { flex: 1; }

.nav-item__indicator {
  width: 3px;
  height: 16px;
  border-radius: 2px;
  background: var(--color-primary);
  box-shadow: 0 0 8px rgba(17, 214, 255, 0.5);
}
</style>
