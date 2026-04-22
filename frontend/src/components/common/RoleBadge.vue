<script setup>
defineProps({
  role: { type: String, required: true },
})

const labelMap = { administrador: 'Admin', editor: 'Editor', consultor: 'Consultor' }
</script>

<template>
  <span :class="['role-badge', `role-badge--${role}`]">
    {{ labelMap[role] ?? role }}
  </span>
</template>

<style lang="scss">
.role-badge {
  display: inline-flex;
  align-items: center;
  padding: 2px 10px;
  border-radius: 999px;
  font-size: 0.68rem;
  font-weight: 700;
  letter-spacing: 0.06em;

  &--administrador { background: rgba(157,80,255,.15); color: #b97aff; border: 1px solid rgba(157,80,255,.3); }
  &--editor        { background: rgba(17,214,255,.12); color: #11d6ff; border: 1px solid rgba(17,214,255,.25); }
  &--consultor     { background: rgba(0,245,212,.10);  color: #00c4a7; border: 1px solid rgba(0,245,212,.25); }
}
</style>
