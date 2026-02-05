<script setup>
  const props = defineProps({
    link: String,
    icon: String
  });

  const icons = {
    'plus': 'M12 4v16m8-8H4',
    'folder-open': 'M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z',
    'archive': 'M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4',
    'upload': 'M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12'
  };
</script>

<template>
  <a 
    :href="link" 
    class="flex items-center gap-2 px-4 py-2 rounded-xl text-slate-600 font-medium
      hover:bg-emerald-50 hover:text-emerald-600 
      active:bg-emerald-100
      transition-all duration-200"
  >
    <svg v-if="icon && icons[icon]" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="icons[icon]" />
    </svg>
    <slot />
  </a>
</template>