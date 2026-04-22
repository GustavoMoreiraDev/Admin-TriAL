<script setup>
defineProps({
  label:   { type: String, required: true },
  value:   { type: [String, Number], required: true },
  icon:    { type: String, required: true },
  color:   { type: String, default: 'cyan' },
  trend:   { type: String, default: null },
  trendUp: { type: Boolean, default: true },
})

const colorMap = {
  cyan:   { bg: 'rgba(17, 214, 255, 0.12)',  icon: '#11d6ff',  glow: 'rgba(17, 214, 255, 0.15)' },
  purple: { bg: 'rgba(157, 80, 255, 0.12)',  icon: '#9D50FF',  glow: 'rgba(157, 80, 255, 0.15)' },
  teal:   { bg: 'rgba(0, 245, 212, 0.12)',   icon: '#00F5D4',  glow: 'rgba(0, 245, 212, 0.15)'  },
  orange: { bg: 'rgba(255, 176, 32, 0.12)',  icon: '#FFB020',  glow: 'rgba(255, 176, 32, 0.15)' },
}
</script>

<template>
  <div class="stat-card">
    <div class="stat-card__header">
      <span class="stat-card__label">{{ label }}</span>
      <div
        class="stat-card__icon-wrap"
        :style="{ background: colorMap[color].bg, boxShadow: `0 0 20px ${colorMap[color].glow}` }"
      >
        <q-icon :name="icon" size="18px" :style="{ color: colorMap[color].icon }" />
      </div>
    </div>

    <div class="stat-card__value">{{ value }}</div>

    <div v-if="trend" class="stat-card__trend" :class="trendUp ? 'stat-card__trend--up' : 'stat-card__trend--down'">
      <q-icon :name="trendUp ? 'trending_up' : 'trending_down'" size="14px" />
      <span>{{ trend }}</span>
    </div>
  </div>
</template>

<style scoped lang="scss">
.stat-card {
  background: var(--bg-card-inner);
  border: 1px solid var(--border-subtle);
  border-radius: 16px;
  padding: 22px 24px;
  box-shadow: var(--shadow-card);
  transition: border-color 0.2s, transform 0.2s;
  cursor: default;

  &:hover {
    border-color: var(--border-default);
    transform: translateY(-1px);
  }
}

.stat-card__header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 16px;
}

.stat-card__label {
  font-size: 0.72rem;
  font-weight: 700;
  letter-spacing: 0.08em;
  color: var(--text-label);
  text-transform: uppercase;
}

.stat-card__icon-wrap {
  width: 36px;
  height: 36px;
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.stat-card__value {
  font-size: 2rem;
  font-weight: 800;
  color: var(--text-value);
  line-height: 1;
  letter-spacing: -0.02em;
  margin-bottom: 10px;
}

.stat-card__trend {
  display: flex;
  align-items: center;
  gap: 4px;
  font-size: 0.75rem;
  font-weight: 500;

  &--up   { color: #00C4A7; }
  &--down { color: #FF4D6A; }
}
</style>
