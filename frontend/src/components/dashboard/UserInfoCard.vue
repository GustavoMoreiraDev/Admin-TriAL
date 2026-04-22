<script setup>
defineProps({
  icon:  { type: String, required: true },
  label: { type: String, required: true },
  value: { type: String, required: true },
  color: { type: String, default: 'cyan' },
})

const colorMap = {
  cyan:   '#11d6ff',
  purple: '#9D50FF',
  teal:   '#00F5D4',
  orange: '#FFB020',
  pink:   '#FF4D6A',
  blue:   '#4D9EFF',
  green:  '#00F5D4',
}
</script>

<template>
  <div class="info-card">
    <div class="info-card__icon-wrap" :style="{ color: colorMap[color] }">
      <q-icon :name="icon" size="20px" />
    </div>
    <div class="info-card__body">
      <span class="info-card__label">{{ label }}</span>
      <span class="info-card__value">{{ value }}</span>
    </div>
  </div>
</template>

<style scoped lang="scss">
.info-card {
  display: flex;
  align-items: center;
  gap: 14px;
  padding: 16px 20px;
  background: var(--bg-card-inner);
  border: 1px solid var(--border-subtle);
  border-radius: 12px;
  box-shadow: var(--shadow-card);
  transition: border-color 0.2s;

  &:hover { border-color: var(--border-default); }
}

.info-card__icon-wrap {
  flex-shrink: 0;
  display: flex;
  align-items: center;
  justify-content: center;
}

.info-card__body {
  display: flex;
  flex-direction: column;
  gap: 3px;
  min-width: 0;
}

.info-card__label {
  font-size: 0.68rem;
  font-weight: 700;
  letter-spacing: 0.1em;
  text-transform: uppercase;
  color: var(--text-label);
}

.info-card__value {
  font-size: 0.92rem;
  font-weight: 600;
  color: var(--text-value);
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}
</style>
