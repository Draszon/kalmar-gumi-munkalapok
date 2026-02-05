<script setup>
import InputField from '@/Components/InputField.vue';
import MainLayout from '@/Layouts/MainLayout.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
  services: Array,
  materials: Array,
});

const page = usePage();


// used_materials és services mostantól: [{ name: string, qty: number }]
const workForm = useForm({
  registration_number: '',
  name: '',
  car_type: '',
  used_materials: [],
  services: [],
  tire_brand: '',
  tire_size: '',
  store: false,
  store_qty: 1,
  store_tire: false,
  store_wheel: false,
  comment: ''
});

// Segédfüggvény: szolgáltatás kiválasztása vagy mennyiség módosítása
function toggleService(serviceName, checked) {
  const idx = workForm.services.findIndex(s => s.name === serviceName);
  if (checked) {
    if (idx === -1) {
      workForm.services.push({ name: serviceName, qty: 1 });
    }
  } else {
    if (idx !== -1) {
      workForm.services.splice(idx, 1);
    }
  }
}

function setServiceQty(serviceName, qty) {
  const idx = workForm.services.findIndex(s => s.name === serviceName);
  if (idx !== -1) {
    workForm.services[idx].qty = qty > 0 ? qty : 1;
  }
}

// Segédfüggvény: anyag kiválasztása vagy mennyiség módosítása
function toggleMaterial(materialName, checked) {
  const idx = workForm.used_materials.findIndex(m => m.name === materialName);
  if (checked) {
    if (idx === -1) {
      workForm.used_materials.push({ name: materialName, qty: 1 });
    }
  } else {
    if (idx !== -1) {
      workForm.used_materials.splice(idx, 1);
    }
  }
}

function setMaterialQty(materialName, qty) {
  const idx = workForm.used_materials.findIndex(m => m.name === materialName);
  if (idx !== -1) {
    workForm.used_materials[idx].qty = qty > 0 ? qty : 1;
  }
}

const store = () => {
  workForm.post('/store-worksheet', {
    preserveScroll: true,
    onSuccess: () => {
      workForm.reset();
    }
  });
}

</script>

<template>
  <Head>
    <title>Új munkalap</title>
  </Head>

  <MainLayout>
    <section class="w-full flex justify-center py-8 px-4">
      <div class="w-full max-w-4xl">
        <!-- Header -->
        <div class="mb-8">
          <h1 class="text-2xl font-bold text-slate-800">Új munkalap</h1>
          <p class="text-slate-500 mt-1">Adatok kitöltése a munkalap rögzítéséhez</p>
        </div>

        <form @submit.prevent="store()">
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            <!-- Bal oldal: Ügyfél és jármű adatok -->
            <div class="space-y-6">
              <!-- Ügyfél adatok kártya -->
              <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <div class="flex items-center gap-3 mb-5">
                  <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                  </div>
                  <h2 class="text-lg font-semibold text-slate-800">Ügyfél adatok</h2>
                </div>
                
                <div class="space-y-4">
                  <div>
                    <label for="registration-number" class="block text-sm font-medium text-slate-600 mb-1.5">Rendszám *</label>
                    <input
                      required
                      type="text"
                      id="registration-number"
                      v-model="workForm.registration_number"
                      class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 uppercase
                        focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 
                        transition-all duration-200 outline-none"
                      placeholder="ABC-123"
                    />
                  </div>
                  
                  <div>
                    <label for="customer-name" class="block text-sm font-medium text-slate-600 mb-1.5">Ügyfél neve</label>
                    <input
                      type="text"
                      id="customer-name"
                      v-model="workForm.name"
                      class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50
                        focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 
                        transition-all duration-200 outline-none"
                      placeholder="Kiss János"
                    />
                  </div>
                  
                  <div>
                    <label for="car-type" class="block text-sm font-medium text-slate-600 mb-1.5">Gépjármű típusa</label>
                    <input
                      type="text"
                      id="car-type"
                      v-model="workForm.car_type"
                      class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50
                        focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 
                        transition-all duration-200 outline-none"
                      placeholder="Opel Astra"
                    />
                  </div>
                </div>
              </div>

              <!-- Gumiabroncs adatok kártya -->
              <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <div class="flex items-center gap-3 mb-5">
                  <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                  </div>
                  <h2 class="text-lg font-semibold text-slate-800">Gumiabroncs</h2>
                </div>
                
                <div class="space-y-4">
                  <div>
                    <label for="tire-brand" class="block text-sm font-medium text-slate-600 mb-1.5">Márka</label>
                    <input
                      type="text"
                      id="tire-brand"
                      v-model="workForm.tire_brand"
                      class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50
                        focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 
                        transition-all duration-200 outline-none"
                      placeholder="Michelin"
                    />
                  </div>
                  
                  <div>
                    <label for="tire-size" class="block text-sm font-medium text-slate-600 mb-1.5">Méret</label>
                    <input
                      type="text"
                      id="tire-size"
                      v-model="workForm.tire_size"
                      class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50
                        focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 
                        transition-all duration-200 outline-none"
                      placeholder="205/55 R16"
                    />
                  </div>
                  
                  <!-- Tárolás toggle -->
                  <div class="space-y-3">
                    <div class="flex items-center gap-3 bg-slate-50 rounded-xl p-3">
                      <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" v-model="workForm.store" class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-300 peer-focus:ring-2 peer-focus:ring-emerald-500/20 rounded-full peer 
                          peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] 
                          after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full 
                          after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                      </label>
                      <span class="text-sm font-medium text-slate-700">Tárolás</span>
                      <input
                        v-if="workForm.store"
                        type="number"
                        min="1"
                        v-model="workForm.store_qty"
                        class="w-16 px-2 py-1 rounded-lg border border-slate-200 bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all duration-200 outline-none ml-2"
                        placeholder="db"
                      >
                      <span v-if="workForm.store" class="text-sm text-slate-500">db</span>
                    </div>
                    
                    <!-- Tárolás típusa -->
                    <div v-if="workForm.store" class="bg-slate-50 rounded-xl p-3 space-y-2">
                      <p class="text-sm font-medium text-slate-600 mb-2">Tárolás típusa:</p>
                      <label class="flex items-center gap-2 cursor-pointer">
                        <input
                          type="checkbox"
                          v-model="workForm.store_tire"
                          class="w-4 h-4 rounded border-slate-300 text-emerald-500 focus:ring-emerald-500/20"
                        >
                        <span class="text-sm text-slate-700">Gumiabroncs</span>
                      </label>
                      <label class="flex items-center gap-2 cursor-pointer">
                        <input
                          type="checkbox"
                          v-model="workForm.store_wheel"
                          class="w-4 h-4 rounded border-slate-300 text-emerald-500 focus:ring-emerald-500/20"
                        >
                        <span class="text-sm text-slate-700">Szerelt kerék</span>
                      </label>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Jobb oldal: Szolgáltatások és anyagok -->
            <div class="space-y-6">
              <!-- Szolgáltatások kártya -->
              <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <div class="flex items-center gap-3 mb-5">
                  <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                  </div>
                  <h2 class="text-lg font-semibold text-slate-800">Szolgáltatások</h2>
                </div>
                
                <div class="space-y-2 max-h-48 overflow-y-auto pr-2 scrollbar-thin">
                  <div
                    v-for="service in services"
                    :key="service.id"
                    class="flex items-center gap-3 p-3 rounded-xl cursor-pointer hover:bg-slate-50 transition-colors"
                  >
                    <input
                      type="checkbox"
                      :checked="workForm.services.some(s => s.name === service.service_name)"
                      @change="e => toggleService(service.service_name, e.target.checked)"
                      :id="service.service_name"
                      class="w-5 h-5 rounded-lg border-2 border-slate-300 text-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:ring-offset-0 transition-colors"
                    >
                    <span class="text-slate-700">{{ service.service_name }}</span>
                    <input
                      v-if="workForm.services.some(s => s.name === service.service_name)"
                      type="number"
                      min="1"
                      class="w-16 px-2 py-1 rounded-lg border border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all duration-200 outline-none ml-2"
                      :value="workForm.services.find(s => s.name === service.service_name)?.qty"
                      @input="e => setServiceQty(service.service_name, parseInt(e.target.value))"
                    >
                  </div>
                </div>
              </div>

              <!-- Felhasznált anyagok kártya -->
              <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <div class="flex items-center gap-3 mb-5">
                  <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                  </div>
                  <h2 class="text-lg font-semibold text-slate-800">Felhasznált anyagok</h2>
                </div>
                
                <div class="space-y-2 max-h-48 overflow-y-auto pr-2 scrollbar-thin">
                  <div
                    v-for="material in materials"
                    :key="material.id"
                    class="flex items-center gap-3 p-3 rounded-xl cursor-pointer hover:bg-slate-50 transition-colors"
                  >
                    <input
                      type="checkbox"
                      :checked="workForm.used_materials.some(m => m.name === material.material_name)"
                      @change="e => toggleMaterial(material.material_name, e.target.checked)"
                      :id="material.material_name"
                      class="w-5 h-5 rounded-lg border-2 border-slate-300 text-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:ring-offset-0 transition-colors"
                    >
                    <span class="text-slate-700">{{ material.material_name }}</span>
                    <input
                      v-if="workForm.used_materials.some(m => m.name === material.material_name)"
                      type="number"
                      min="1"
                      class="w-16 px-2 py-1 rounded-lg border border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all duration-200 outline-none ml-2"
                      :value="workForm.used_materials.find(m => m.name === material.material_name)?.qty"
                      @input="e => setMaterialQty(material.material_name, parseInt(e.target.value))"
                    >
                  </div>
                </div>
              </div>

              <!-- Megjegyzés kártya -->
              <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <div class="flex items-center gap-3 mb-5">
                  <div class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                    </svg>
                  </div>
                  <h2 class="text-lg font-semibold text-slate-800">Megjegyzés</h2>
                </div>
                
                <textarea 
                  id="comment"
                  v-model="workForm.comment"
                  rows="4"
                  class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50
                    focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 
                    transition-all duration-200 outline-none resize-none"
                  placeholder="További megjegyzések..."
                ></textarea>
              </div>
            </div>
          </div>

          <!-- Hibaüzenet és Submit gomb -->
          <div class="mt-8 flex flex-col sm:flex-row items-center justify-between gap-4">
            <p v-if="page.props.flash.message" class="text-red-500 font-medium flex items-center gap-2">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              {{ page.props.flash.message }}
            </p>
            <div v-else></div>
            
            <button 
              type="submit"
              :disabled="workForm.processing"
              class="w-full sm:w-auto px-8 py-3 bg-emerald-500 hover:bg-emerald-600 active:bg-emerald-700
                text-white font-semibold rounded-xl shadow-lg shadow-emerald-500/25
                transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed
                flex items-center justify-center gap-2"
            >
              <svg v-if="workForm.processing" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>
              {{ workForm.processing ? 'Mentés...' : 'Munkalap rögzítése' }}
            </button>
          </div>
        </form>
      </div>
    </section>
  </MainLayout>
</template>

<style scoped>
/* Custom scrollbar styling */
.scrollbar-thin::-webkit-scrollbar {
  width: 6px;
}

.scrollbar-thin::-webkit-scrollbar-track {
  background: #f1f5f9;
  border-radius: 3px;
}

.scrollbar-thin::-webkit-scrollbar-thumb {
  background: #cbd5e1;
  border-radius: 3px;
}

.scrollbar-thin::-webkit-scrollbar-thumb:hover {
  background: #94a3b8;
}

/* Firefox */
.scrollbar-thin {
  scrollbar-width: thin;
  scrollbar-color: #cbd5e1 #f1f5f9;
}
</style>