<template>
  <q-input
    v-bind="$attrs"
    :model-value="modelValue"
    :label="label"
    :type="inputType"
    :rules="rules"
    :outlined="outlined"
    :dense="dense"
    :error="!!error"
    :error-message="error"
    lazy-rules
    @update:model-value="$emit('update:modelValue', $event)"
  >
    <template v-if="icon" #prepend>
      <q-icon :name="icon" />
    </template>

    <template v-if="type === 'password'" #append>
      <q-icon
        :name="showPassword ? 'visibility_off' : 'visibility'"
        class="cursor-pointer"
        @click="showPassword = !showPassword"
      />
    </template>
  </q-input>
</template>

<script setup>
import { ref, computed } from 'vue'

defineOptions({ inheritAttrs: false })

const props = defineProps({
  modelValue: {
    type: [String, Number],
    default: ''
  },
  label: {
    type: String,
    required: true
  },
  type: {
    type: String,
    default: 'text'
  },
  icon: {
    type: String,
    default: ''
  },
  rules: {
    type: Array,
    default: () => []
  },
  outlined: {
    type: Boolean,
    default: true
  },
  dense: {
    type: Boolean,
    default: false
  },
  error: {
    type: String,
    default: ''
  }
})

defineEmits(['update:modelValue'])

const showPassword = ref(false)

const inputType = computed(() => {
  if (props.type === 'password') {
    return showPassword.value ? 'text' : 'password'
  }
  return props.type
})
</script>
