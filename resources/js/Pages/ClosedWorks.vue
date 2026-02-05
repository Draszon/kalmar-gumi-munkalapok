<script setup>
import MainLayout from '@/Layouts/MainLayout.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
  worksheets: Object, // Paginated object
  services: Array,
  materials: Array,
});

// Lenyitott kártyák követése
const expandedCards = ref(new Set());

const toggleCard = (id) => {
  if (expandedCards.value.has(id)) {
    expandedCards.value.delete(id);
  } else {
    expandedCards.value.add(id);
  }
};

const isExpanded = (id) => expandedCards.value.has(id);

// Keresés
const searchQuery = ref('');

// Szerkesztő modal
const showEditModal = ref(false);
const editingWorksheet = ref(null);

// used_materials és services mostantól: [{ name: string, qty: number }]
function toggleServiceEdit(serviceName, checked) {
  const idx = editForm.services.findIndex(s => s.name === serviceName);
  if (checked) {
    if (idx === -1) {
      editForm.services.push({ name: serviceName, qty: 1 });
    }
  } else {
    if (idx !== -1) {
      editForm.services.splice(idx, 1);
    }
  }
}

function setServiceQtyEdit(serviceName, qty) {
  const idx = editForm.services.findIndex(s => s.name === serviceName);
  if (idx !== -1) {
    editForm.services[idx].qty = qty > 0 ? qty : 1;
  }
}
const editForm = useForm({
  registration_number: '',
  name: '',
  car_type: '',
  services: [],
  used_materials: [],
  tire_brand: '',
  tire_size: '',
  store: false,
  store_qty: 1,
  store_tire: false,
  store_wheel: false,
  comment: '',
});

function toggleMaterialEdit(materialName, checked) {
  const idx = editForm.used_materials.findIndex(m => m.name === materialName);
  if (checked) {
    if (idx === -1) {
      editForm.used_materials.push({ name: materialName, qty: 1 });
    }
  } else {
    if (idx !== -1) {
      editForm.used_materials.splice(idx, 1);
    }
  }
}

function setMaterialQtyEdit(materialName, qty) {
  const idx = editForm.used_materials.findIndex(m => m.name === materialName);
  if (idx !== -1) {
    editForm.used_materials[idx].qty = qty > 0 ? qty : 1;
  }
}

const openEditModal = (worksheet) => {
  editingWorksheet.value = worksheet;
  editForm.registration_number = worksheet.registration_number || '';
  editForm.name = worksheet.name || '';
  editForm.car_type = worksheet.car_type || '';
  editForm.services = worksheet.services || [];
  editForm.used_materials = worksheet.used_materials || [];
  editForm.tire_brand = worksheet.tire_brand || '';
  editForm.tire_size = worksheet.tire_size || '';
  editForm.store = worksheet.store || false;
  editForm.store_qty = worksheet.store_qty || 1;
  editForm.store_tire = Boolean(worksheet.store_tire);
  editForm.store_wheel = Boolean(worksheet.store_wheel);
  editForm.comment = worksheet.comment || '';
  showEditModal.value = true;
};

const closeEditModal = () => {
  showEditModal.value = false;
  editingWorksheet.value = null;
  editForm.reset();
};

const saveEdit = () => {
  editForm.put(`/update-worksheet/${editingWorksheet.value.id}`, {
    preserveScroll: true,
    onSuccess: () => {
      closeEditModal();
    }
  });
};

// Szűrt lista
const filteredWorksheets = computed(() => {
  let data = props.worksheets?.data || [];
  
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase();
    data = data.filter(ws => 
      ws.registration_number?.toLowerCase().includes(query) ||
      ws.name?.toLowerCase().includes(query) ||
      ws.car_type?.toLowerCase().includes(query)
    );
  }
  
  return data;
});

const formatDate = (dateString) => {
  if (!dateString) return '-';
  const date = new Date(dateString);
  return date.toLocaleDateString('hu-HU', {
    year: 'numeric',
    month: '2-digit',
    day: '2-digit',
    hour: '2-digit',
    minute: '2-digit'
  });
};

// Pagináció
const goToPage = (url) => {
  if (url) {
    router.get(url, {}, { preserveState: true, preserveScroll: false });
  }
};

// Munkalap újranyitása
const reopenWorksheet = (id) => {
  if (confirm('Biztosan újra szeretnéd nyitni ezt a munkalapot?')) {
    router.post(`/reopen-worksheet/${id}`);
  }
};
</script>

<template>
  <Head>
    <title>Zárt munkalapok</title>
  </Head>

  <MainLayout>
    <section class="w-full flex justify-center py-8 px-4">
      <div class="w-full max-w-6xl">
        
        <!-- Header -->
        <div class="mb-8">
          <h1 class="text-2xl font-bold text-slate-800">Zárt munkalapok</h1>
          <p class="text-slate-500 mt-1">Az összes lezárt munka áttekintése</p>
        </div>

        <!-- Keresés és statisztika kártya -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 mb-6">
          <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            
            <!-- Keresés -->
            <div class="relative w-full sm:w-80">
              <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
              <input
                v-model="searchQuery"
                type="text"
                placeholder="Keresés rendszám, név vagy típus alapján..."
                class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50
                  focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 
                  transition-all duration-200 outline-none"
              />
            </div>

            <!-- Statisztika -->
            <div class="flex items-center gap-6">
              <div class="flex items-center gap-2">
                <div class="w-10 h-10 bg-slate-200 rounded-xl flex items-center justify-center">
                  <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                  </svg>
                </div>
                <div>
                  <p class="text-2xl font-bold text-slate-800">{{ worksheets?.total || 0 }}</p>
                  <p class="text-xs text-slate-500">Összes zárt munkalap</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Munkalapok lista -->
        <div class="space-y-3">
          <div 
            v-for="worksheet in filteredWorksheets" 
            :key="worksheet.id"
            class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden"
          >
            <!-- Kompakt fejléc - mindig látszik -->
            <div 
              class="p-4 flex items-center gap-4 cursor-pointer hover:bg-slate-100 transition-colors bg-slate-50 border-b-2 border-slate-300"
              @click="toggleCard(worksheet.id)"
            >
              <!-- Rendszám -->
              <span class="font-mono font-bold text-slate-600 text-base bg-white px-3 py-1.5 rounded-xl border border-slate-300 uppercase shrink-0">
                {{ worksheet.registration_number }}
              </span>
              
              <!-- Név és típus -->
              <div class="flex-1 min-w-0">
                <p class="font-semibold text-slate-800 truncate">{{ worksheet.name || 'Nincs név' }}</p>
                <p class="text-sm text-slate-500 truncate">{{ worksheet.car_type || 'Nincs típus' }}</p>
              </div>

              <!-- Lezárás dátuma -->
              <div class="hidden sm:block text-right shrink-0">
                <p class="text-xs text-slate-400">Lezárva</p>
                <p class="text-sm font-medium text-slate-600">{{ formatDate(worksheet.closed_at) }}</p>
              </div>

              <!-- Nyitó/záró ikon -->
              <button 
                class="p-2 rounded-xl text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors shrink-0"
              >
                <svg class="w-5 h-5 transition-transform duration-300" :class="{ 'rotate-180': isExpanded(worksheet.id) }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
              </button>
            </div>

            <!-- Lenyíló részletek -->
            <div 
              class="grid transition-all duration-300 ease-out"
              :class="isExpanded(worksheet.id) ? 'grid-rows-[1fr] opacity-100' : 'grid-rows-[0fr] opacity-0'"
            >
              <div class="overflow-hidden">
                <!-- Akciógombok -->
                <div class="px-4 pb-3 flex items-center gap-2 border-t border-slate-100 pt-3">
                  <!-- Szerkesztés gomb -->
                  <button 
                    @click.stop="openEditModal(worksheet)"
                    class="flex items-center gap-2 px-3 py-2 rounded-xl text-blue-600 bg-blue-50 hover:bg-blue-100 border border-blue-200 font-medium transition-colors text-sm"
                    title="Szerkesztés"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    <span>Szerkesztés</span>
                  </button>
                  <!-- Letöltés gomb -->
                  <a 
                    :href="`/download-worksheet/${worksheet.id}`"
                    @click.stop
                    class="flex items-center gap-2 px-3 py-2 rounded-xl text-emerald-600 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 font-medium transition-colors text-sm"
                    title="PDF letöltése"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span>Letöltés</span>
                  </a>
                  <!-- Újranyitás gomb -->
                  <button 
                    @click.stop="reopenWorksheet(worksheet.id)"
                    class="flex items-center gap-2 px-3 py-2 rounded-xl text-amber-600 bg-amber-50 hover:bg-amber-100 border border-amber-200 font-medium transition-colors text-sm"
                    title="Munkalap újranyitása"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    <span>Újranyitás</span>
                  </button>
                </div>

                <!-- Kártya tartalom -->
                <div class="p-6 pt-3 border-t border-slate-100">
                  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                
                <!-- Szolgáltatások -->
                <div>
                  <p class="text-sm font-semibold text-slate-500 mb-2 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Szolgáltatások
                  </p>
                  <div class="flex flex-wrap gap-1.5">
                    <span
                      v-for="service in (worksheet.services || [])"
                      :key="service.name || service"
                      class="inline-flex items-center px-2.5 py-1 rounded-lg text-sm font-medium bg-blue-100 text-blue-700"
                    >
                      {{ service.name || service }}<span v-if="service.qty"> × {{ service.qty }}</span>
                    </span>
                    <span v-if="!worksheet.services?.length" class="text-slate-400 text-sm">Nincs megadva</span>
                  </div>
                </div>

                <!-- Felhasznált anyagok -->
                <div>
                  <p class="text-sm font-semibold text-slate-500 mb-2 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    Felhasznált anyagok
                  </p>
                  <div class="flex flex-wrap gap-1.5">
                    <span
                      v-for="material in (worksheet.used_materials || [])"
                      :key="material.name || material"
                      class="inline-flex items-center px-2.5 py-1 rounded-lg text-sm font-medium bg-purple-100 text-purple-700"
                    >
                      {{ material.name || material }}<span v-if="material.qty"> × {{ material.qty }}</span>
                    </span>
                    <span v-if="!worksheet.used_materials?.length" class="text-slate-400 text-sm">Nincs megadva</span>
                  </div>
                </div>

                <!-- Gumiabroncs adatok -->
                <div>
                  <p class="text-sm font-semibold text-slate-500 mb-2 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Gumiabroncs
                  </p>
                  <div class="space-y-1">
                    <p class="text-slate-700">
                      <span class="text-slate-500">Márka:</span> {{ worksheet.tire_brand || '-' }}
                    </p>
                    <p class="text-slate-700">
                      <span class="text-slate-500">Méret:</span> {{ worksheet.tire_size || '-' }}
                    </p>
                  </div>
                </div>

                <!-- Tárolás -->
                <div>
                  <p class="text-sm font-semibold text-slate-500 mb-2 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                    </svg>
                    Tárolás
                  </p>
                  <div v-if="worksheet.store" class="space-y-1.5">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-sm font-medium bg-emerald-100 text-emerald-700">
                      Igen × {{ worksheet.store_qty || 1 }} db
                    </span>
                    <div class="flex flex-wrap gap-1.5">
                      <span v-if="worksheet.store_tire" class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-amber-100 text-amber-700">
                        Gumiabroncs
                      </span>
                      <span v-if="worksheet.store_wheel" class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-blue-100 text-blue-700">
                        Szerelt kerék
                      </span>
                    </div>
                  </div>
                  <span v-else class="inline-flex items-center px-2.5 py-1 rounded-lg text-sm font-medium bg-slate-100 text-slate-500">
                    Nem
                  </span>
                </div>

                <!-- Megjegyzés -->
                <div class="md:col-span-2">
                  <p class="text-sm font-semibold text-slate-500 mb-2 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                    </svg>
                    Megjegyzés
                  </p>
                  <p class="text-slate-700 bg-slate-50 rounded-xl p-3" v-if="worksheet.comment">
                    {{ worksheet.comment }}
                  </p>
                  <p class="text-slate-400 text-sm" v-else>Nincs megjegyzés</p>
                </div>

              </div>
                </div>

                <div class="px-6 py-3 bg-slate-50 border-t border-slate-100 flex items-center justify-between text-sm text-slate-500">
                  <span>Létrehozva: {{ formatDate(worksheet.created_at) }}</span>
                  <span class="text-emerald-600 font-medium">Lezárva: {{ formatDate(worksheet.closed_at) }}</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Üres állapot -->
          <div v-if="filteredWorksheets.length === 0" class="bg-white rounded-2xl shadow-sm border border-slate-100 p-16">
            <div class="flex flex-col items-center">
              <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                </svg>
              </div>
              <p class="text-slate-600 font-medium mb-1">Nincs zárt munkalap</p>
              <p class="text-slate-400 text-sm">A lezárt munkalapok itt fognak megjelenni</p>
            </div>
          </div>
        </div>

        <!-- Pagináció -->
        <div v-if="worksheets?.last_page > 1" class="mt-6 bg-white rounded-2xl shadow-sm border border-slate-100 p-4">
          <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
            <p class="text-sm text-slate-500">
              {{ worksheets.from }}-{{ worksheets.to }} / {{ worksheets.total }} munkalap
            </p>
            <div class="flex items-center gap-1 sm:gap-2">
              <button
                @click="goToPage(worksheets.prev_page_url)"
                :disabled="!worksheets.prev_page_url"
                class="px-2 sm:px-4 py-2 rounded-xl text-slate-600 hover:bg-slate-100 disabled:opacity-50 disabled:cursor-not-allowed transition-colors flex items-center gap-1 text-sm"
              >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                <span class="hidden sm:inline">Előző</span>
              </button>
              
              <span class="px-2 sm:px-4 py-2 text-sm text-slate-600">
                {{ worksheets.current_page }} / {{ worksheets.last_page }}
              </span>
              
              <button
                @click="goToPage(worksheets.next_page_url)"
                :disabled="!worksheets.next_page_url"
                class="px-2 sm:px-4 py-2 rounded-xl text-slate-600 hover:bg-slate-100 disabled:opacity-50 disabled:cursor-not-allowed transition-colors flex items-center gap-1 text-sm"
              >
                <span class="hidden sm:inline">Következő</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
              </button>
            </div>
          </div>
        </div>

      </div>
    </section>

    <!-- Szerkesztő Modal -->
    <Teleport to="body">
      <Transition
        enter-active-class="transition duration-200 ease-out"
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        leave-active-class="transition duration-150 ease-in"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
      >
        <div v-if="showEditModal" class="fixed inset-0 z-50 flex items-center justify-center overflow-hidden">
          <!-- Háttér overlay -->
          <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="closeEditModal"></div>
          
          <!-- Modal tartalom -->
          <div class="relative bg-white rounded-2xl shadow-2xl mx-2 sm:mx-4 w-full max-w-2xl max-h-[calc(100vh-1rem)] sm:max-h-[90vh] overflow-y-auto overflow-x-hidden">
            <!-- Modal fejléc -->
            <div class="sticky top-0 bg-white border-b border-slate-100 px-3 sm:px-6 py-3 sm:py-4 flex items-center justify-between rounded-t-2xl z-10">
              <h2 class="text-lg sm:text-xl font-bold text-slate-800">Munkalap szerkesztése</h2>
              <button 
                @click="closeEditModal"
                class="p-2 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>

            <!-- Modal form -->
            <form @submit.prevent="saveEdit" class="p-3 sm:p-6 space-y-3 sm:space-y-6">
              <!-- Ügyfél adatok -->
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-slate-600 mb-1.5">Rendszám *</label>
                  <input
                    v-model="editForm.registration_number"
                    type="text"
                    required
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 uppercase
                      focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 
                      transition-all duration-200 outline-none"
                  />
                </div>
                <div>
                  <label class="block text-sm font-medium text-slate-600 mb-1.5">Ügyfél neve</label>
                  <input
                    v-model="editForm.name"
                    type="text"
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50
                      focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 
                      transition-all duration-200 outline-none"
                  />
                </div>
                <div>
                  <label class="block text-sm font-medium text-slate-600 mb-1.5">Gépjármű típusa</label>
                  <input
                    v-model="editForm.car_type"
                    type="text"
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50
                      focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 
                      transition-all duration-200 outline-none"
                  />
                </div>
              </div>

              <!-- Szolgáltatások -->
              <div>
                <label class="block text-sm font-medium text-slate-600 mb-2">Szolgáltatások</label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 max-h-32 overflow-y-auto">
                  <div
                    v-for="service in services"
                    :key="service.id"
                    class="flex items-center gap-2 p-2 rounded-lg cursor-pointer hover:bg-slate-50"
                  >
                    <input
                      type="checkbox"
                      :checked="editForm.services.some(s => s.name === service.service_name)"
                      @change="e => toggleServiceEdit(service.service_name, e.target.checked)"
                      class="w-4 h-4 rounded border-slate-300 text-emerald-500 focus:ring-emerald-500/20"
                    >
                    <span class="text-slate-700 text-sm">{{ service.service_name }}</span>
                    <input
                      v-if="editForm.services.some(s => s.name === service.service_name)"
                      type="number"
                      min="1"
                      class="w-16 px-2 py-1 rounded-lg border border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all duration-200 outline-none ml-2"
                      :value="editForm.services.find(s => s.name === service.service_name)?.qty"
                      @input="e => setServiceQtyEdit(service.service_name, parseInt(e.target.value))"
                    >
                  </div>
                </div>
              </div>

              <!-- Felhasznált anyagok -->
              <div>
                <label class="block text-sm font-medium text-slate-600 mb-2">Felhasznált anyagok</label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 max-h-32 overflow-y-auto">
                  <div
                    v-for="material in materials"
                    :key="material.id"
                    class="flex items-center gap-2 p-2 rounded-lg cursor-pointer hover:bg-slate-50"
                  >
                    <input
                      type="checkbox"
                      :checked="editForm.used_materials.some(m => m.name === material.material_name)"
                      @change="e => toggleMaterialEdit(material.material_name, e.target.checked)"
                      class="w-4 h-4 rounded border-slate-300 text-emerald-500 focus:ring-emerald-500/20"
                    >
                    <span class="text-slate-700 text-sm">{{ material.material_name }}</span>
                    <input
                      v-if="editForm.used_materials.some(m => m.name === material.material_name)"
                      type="number"
                      min="1"
                      class="w-16 px-2 py-1 rounded-lg border border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all duration-200 outline-none ml-2"
                      :value="editForm.used_materials.find(m => m.name === material.material_name)?.qty"
                      @input="e => setMaterialQtyEdit(material.material_name, parseInt(e.target.value))"
                    >
                  </div>
                </div>
              </div>

              <!-- Gumiabroncs -->
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-slate-600 mb-1.5">Gumiabroncs márka</label>
                  <input
                    v-model="editForm.tire_brand"
                    type="text"
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50
                      focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 
                      transition-all duration-200 outline-none"
                  />
                </div>
                <div>
                  <label class="block text-sm font-medium text-slate-600 mb-1.5">Gumiabroncs méret</label>
                  <input
                    v-model="editForm.tire_size"
                    type="text"
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50
                      focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 
                      transition-all duration-200 outline-none"
                  />
                </div>
              </div>

              <!-- Tárolás -->
              <div class="space-y-3">
                <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-xl">
                  <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" v-model="editForm.store" class="sr-only peer">
                    <div class="w-11 h-6 bg-slate-300 peer-focus:ring-2 peer-focus:ring-emerald-500/20 rounded-full peer 
                      peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] 
                      after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full 
                      after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                  </label>
                  <span class="text-sm font-medium text-slate-700">Tárolás</span>
                  <input
                    v-if="editForm.store"
                    type="number"
                    min="1"
                    v-model="editForm.store_qty"
                    class="w-16 px-2 py-1 rounded-lg border border-slate-200 bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all duration-200 outline-none ml-2"
                  >
                  <span v-if="editForm.store" class="text-sm text-slate-500">db</span>
                </div>
                
                <!-- Tárolás típusa -->
                <div v-if="editForm.store" class="bg-slate-50 rounded-xl p-3 space-y-2">
                  <p class="text-sm font-medium text-slate-600 mb-2">Tárolás típusa:</p>
                  <label class="flex items-center gap-2 cursor-pointer">
                    <input
                      type="checkbox"
                      v-model="editForm.store_tire"
                      class="w-4 h-4 rounded border-slate-300 text-emerald-500 focus:ring-emerald-500/20"
                    >
                    <span class="text-sm text-slate-700">Gumiabroncs</span>
                  </label>
                  <label class="flex items-center gap-2 cursor-pointer">
                    <input
                      type="checkbox"
                      v-model="editForm.store_wheel"
                      class="w-4 h-4 rounded border-slate-300 text-emerald-500 focus:ring-emerald-500/20"
                    >
                    <span class="text-sm text-slate-700">Szerelt kerék</span>
                  </label>
                </div>
              </div>

              <!-- Megjegyzés -->
              <div>
                <label class="block text-sm font-medium text-slate-600 mb-1.5">Megjegyzés</label>
                <textarea
                  v-model="editForm.comment"
                  rows="3"
                  class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50
                    focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 
                    transition-all duration-200 outline-none resize-none"
                ></textarea>
              </div>

              <!-- Gombok -->
              <div class="flex flex-col sm:flex-row items-stretch sm:items-center sm:justify-end gap-2 sm:gap-3 pt-4 border-t border-slate-100">
                <button 
                  type="button"
                  @click="closeEditModal"
                  class="order-2 sm:order-1 px-4 py-2 sm:py-2.5 rounded-xl text-slate-600 hover:bg-slate-100 font-medium transition-colors text-center"
                >
                  Mégse
                </button>
                <button 
                  type="submit"
                  :disabled="editForm.processing"
                  class="order-1 sm:order-2 px-4 py-2 sm:py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white font-medium rounded-xl 
                    transition-colors disabled:opacity-50 flex items-center justify-center gap-2"
                >
                  <svg v-if="editForm.processing" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  {{ editForm.processing ? 'Mentés...' : 'Mentés' }}
                </button>
              </div>
            </form>
          </div>
        </div>
      </Transition>
    </Teleport>
  </MainLayout>
</template>
