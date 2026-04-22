<script setup>
defineProps({
  status: { type: String, required: true },
})
</script>

<template>
  <span :class="['status-badge', `status-badge--${status}`]">
    {{ status?.toUpperCase() }}
  </span>
</template>

<style lang="scss">
.status-badge {
  display: inline-flex;
  align-items: center;
  padding: 3px 10px;
  border-radius: 999px;
  font-size: 0.65rem;
  font-weight: 700;
  letter-spacing: 0.08em;

  &--ativo    { background: rgba(0,245,212,.1);  color: #00c4a7; border: 1px solid rgba(0,245,212,.25); }
  &--expirado { background: rgba(255,77,106,.1); color: #ff6b84; border: 1px solid rgba(255,77,106,.25); }
}
</style>
