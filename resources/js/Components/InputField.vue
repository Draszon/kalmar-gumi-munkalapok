<script setup>
  const props = defineProps({
    type: String,
    name: String,
    id: String,
    modelValue: [String, Number]
  })

const emit = defineEmits(['update:modelValue'])
</script>

<template>
  <input :type="type" :name="name" :id="id" :value="modelValue" @input="emit('update:modelValue', $event.target.value)"
  class="rounded-md shadow-md focus:shadow-lg border-1 border-slate-200
    focus:border-green-600 focus:ring-0"
  >
</template>