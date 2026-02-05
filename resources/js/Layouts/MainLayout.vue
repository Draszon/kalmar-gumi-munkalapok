<script setup>
  import MenuBtn from '@/Components/MenuBtn.vue';
  import { ref } from 'vue';

  const mobileMenuOpen = ref(false);
</script>

<template>
  <div class="min-h-screen bg-slate-50 flex flex-col">
    <!-- Modern Header -->
    <header class="sticky top-0 z-50 bg-white/80 backdrop-blur-lg border-b border-slate-200/50">
      <div class="max-w-6xl mx-auto px-4 sm:px-6">
        <div class="flex items-center justify-between h-16">
          
          <!-- Logo -->
          <a href="/" class="flex items-center gap-3 group">
            <div class="w-10 h-10 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg shadow-emerald-500/25 group-hover:shadow-emerald-500/40 transition-shadow">
              <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
              </svg>
            </div>
            <div>
              <h1 class="font-bold text-slate-800 text-lg leading-tight">Kalmár Gumiszerviz Kft.</h1>
              <p class="text-xs text-slate-500 -mt-0.5">Munkalapkezelő</p>
            </div>
          </a>

          <!-- Navigation -->
          <nav class="hidden md:block">
            <ul class="flex items-center gap-1">
              <li>
                <MenuBtn link="/" icon="plus">
                  Új munkalap
                </MenuBtn>
              </li>
              <li>
                <MenuBtn link="/opened-works" icon="folder-open">
                  Nyitott
                </MenuBtn>
              </li>
              <li>
                <MenuBtn link="/closed-works" icon="archive">
                  Zárt
                </MenuBtn>
              </li>
              <li>
                <MenuBtn link="/data-upload" icon="upload">
                  Adatok
                </MenuBtn>
              </li>
            </ul>
          </nav>

          <!-- Mobile menu button -->
          <button 
            @click="mobileMenuOpen = !mobileMenuOpen"
            class="md:hidden p-2 rounded-lg hover:bg-slate-100 transition-colors"
          >
            <!-- Hamburger icon -->
            <svg v-if="!mobileMenuOpen" class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
            <!-- Close icon -->
            <svg v-else class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>

        </div>

        <!-- Mobile Menu Dropdown -->
        <Transition
          enter-active-class="transition duration-200 ease-out"
          enter-from-class="opacity-0 -translate-y-2"
          enter-to-class="opacity-100 translate-y-0"
          leave-active-class="transition duration-150 ease-in"
          leave-from-class="opacity-100 translate-y-0"
          leave-to-class="opacity-0 -translate-y-2"
        >
          <nav v-if="mobileMenuOpen" class="md:hidden pb-4">
            <ul class="flex flex-col gap-1">
              <li>
                <a href="/" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-600 font-medium hover:bg-emerald-50 hover:text-emerald-600 transition-colors">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                  </svg>
                  Új munkalap
                </a>
              </li>
              <li>
                <a href="/opened-works" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-600 font-medium hover:bg-emerald-50 hover:text-emerald-600 transition-colors">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z" />
                  </svg>
                  Nyitott munkalapok
                </a>
              </li>
              <li>
                <a href="/closed-works" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-600 font-medium hover:bg-emerald-50 hover:text-emerald-600 transition-colors">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                  </svg>
                  Zárt munkalapok
                </a>
              </li>
              <li>
                <a href="/data-upload" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-600 font-medium hover:bg-emerald-50 hover:text-emerald-600 transition-colors">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                  </svg>
                  Adatok feltöltése
                </a>
              </li>
            </ul>
          </nav>
        </Transition>
      </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1">
      <slot />
    </main>

    <!-- Modern Footer -->
    <footer class="bg-white border-t border-slate-200/50">
      <div class="max-w-6xl mx-auto px-4 sm:px-6 py-6">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
          <p class="text-slate-500 text-sm">
            © {{ new Date().getFullYear() }} KalmárGumi. Minden jog fenntartva.
          </p>
          <p class="text-slate-400 text-sm">
            PoweredBy: 
            <a 
              href="https://peterdev.hu" 
              target="_blank" 
              class="font-medium text-emerald-600 hover:text-emerald-700 transition-colors"
            >
              PeterDev
            </a>
          </p>
        </div>
      </div>
    </footer>
  </div>
</template>