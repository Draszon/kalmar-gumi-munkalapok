<script setup>
import MainLayout from '@/Layouts/MainLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
  services: Array,
  materials: Array,
});

// Szolgáltatások kezelése
const serviceForm = useForm({
  service_name: '',
});

const editingService = ref(null);
const editServiceForm = useForm({
  service_name: '',
});

const addService = () => {
  serviceForm.post('/data/services', {
    preserveScroll: true,
    onSuccess: () => {
      serviceForm.reset();
    }
  });
};

const startEditService = (service) => {
  editingService.value = service.id;
  editServiceForm.service_name = service.service_name;
};

const cancelEditService = () => {
  editingService.value = null;
  editServiceForm.reset();
};

const updateService = (id) => {
  editServiceForm.put(`/data/services/${id}`, {
    preserveScroll: true,
    onSuccess: () => {
      editingService.value = null;
      editServiceForm.reset();
    }
  });
};

const deleteService = (id) => {
  if (confirm('Biztosan törölni szeretnéd ezt a szolgáltatást?')) {
    useForm({}).delete(`/data/services/${id}`, {
      preserveScroll: true,
    });
  }
};

// Anyagok kezelése
const materialForm = useForm({
  material_name: '',
});

const editingMaterial = ref(null);
const editMaterialForm = useForm({
  material_name: '',
});

const addMaterial = () => {
  materialForm.post('/data/materials', {
    preserveScroll: true,
    onSuccess: () => {
      materialForm.reset();
    }
  });
};

const startEditMaterial = (material) => {
  editingMaterial.value = material.id;
  editMaterialForm.material_name = material.material_name;
};

const cancelEditMaterial = () => {
  editingMaterial.value = null;
  editMaterialForm.reset();
};

const updateMaterial = (id) => {
  editMaterialForm.put(`/data/materials/${id}`, {
    preserveScroll: true,
    onSuccess: () => {
      editingMaterial.value = null;
      editMaterialForm.reset();
    }
  });
};

const deleteMaterial = (id) => {
  if (confirm('Biztosan törölni szeretnéd ezt az anyagot?')) {
    useForm({}).delete(`/data/materials/${id}`, {
      preserveScroll: true,
    });
  }
};
</script>

<template>
  <Head>
    <title>Adatok feltöltése</title>
  </Head>

  <MainLayout>
    <section class="w-full flex justify-center py-8 px-4">
      <div class="w-full max-w-4xl">
        
        <!-- Header -->
        <div class="mb-8">
          <h1 class="text-2xl font-bold text-slate-800">Adatok feltöltése</h1>
          <p class="text-slate-500 mt-1">Szolgáltatások és anyagok kezelése</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
          
          <!-- Szolgáltatások kártya -->
          <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-6 border-b border-slate-100 bg-slate-50">
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                  <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                  </svg>
                </div>
                <div>
                  <h2 class="text-lg font-semibold text-slate-800">Szolgáltatások</h2>
                  <p class="text-sm text-slate-500">{{ services?.length || 0 }} db</p>
                </div>
              </div>
            </div>

            <!-- Új szolgáltatás hozzáadása -->
            <div class="p-4 border-b border-slate-100">
              <form @submit.prevent="addService" class="flex gap-2">
                <input
                  v-model="serviceForm.service_name"
                  type="text"
                  placeholder="Új szolgáltatás neve..."
                  required
                  class="flex-1 px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50
                    focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 
                    transition-all duration-200 outline-none"
                />
                <button 
                  type="submit"
                  :disabled="serviceForm.processing"
                  class="px-4 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white font-medium rounded-xl 
                    transition-colors disabled:opacity-50 flex items-center gap-2"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                  </svg>
                </button>
              </form>
            </div>

            <!-- Szolgáltatások listája -->
            <div class="max-h-96 overflow-y-auto">
              <div 
                v-for="service in services" 
                :key="service.id"
                class="px-4 py-3 border-b border-slate-100 last:border-b-0 hover:bg-slate-50 transition-colors"
              >
                <!-- Szerkesztés mód -->
                <div v-if="editingService === service.id" class="flex gap-2">
                  <input
                    v-model="editServiceForm.service_name"
                    type="text"
                    class="flex-1 px-3 py-2 rounded-lg border border-slate-200 bg-white
                      focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 
                      transition-all duration-200 outline-none text-sm"
                  />
                  <button 
                    @click="updateService(service.id)"
                    :disabled="editServiceForm.processing"
                    class="p-2 rounded-lg text-emerald-600 hover:bg-emerald-50 transition-colors"
                  >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                  </button>
                  <button 
                    @click="cancelEditService"
                    class="p-2 rounded-lg text-slate-500 hover:bg-slate-100 transition-colors"
                  >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                  </button>
                </div>

                <!-- Normál mód -->
                <div v-else class="flex items-center justify-between">
                  <span class="text-slate-700">{{ service.service_name }}</span>
                  <div class="flex items-center gap-1">
                    <button 
                      @click="startEditService(service)"
                      class="p-2 rounded-lg text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition-colors"
                    >
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                      </svg>
                    </button>
                    <button 
                      @click="deleteService(service.id)"
                      class="p-2 rounded-lg text-slate-400 hover:text-red-600 hover:bg-red-50 transition-colors"
                    >
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                      </svg>
                    </button>
                  </div>
                </div>
              </div>

              <!-- Üres állapot -->
              <div v-if="!services?.length" class="p-8 text-center">
                <p class="text-slate-400 text-sm">Nincs még szolgáltatás felvéve</p>
              </div>
            </div>
          </div>

          <!-- Anyagok kártya -->
          <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-6 border-b border-slate-100 bg-slate-50">
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center">
                  <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                  </svg>
                </div>
                <div>
                  <h2 class="text-lg font-semibold text-slate-800">Felhasznált anyagok</h2>
                  <p class="text-sm text-slate-500">{{ materials?.length || 0 }} db</p>
                </div>
              </div>
            </div>

            <!-- Új anyag hozzáadása -->
            <div class="p-4 border-b border-slate-100">
              <form @submit.prevent="addMaterial" class="flex gap-2">
                <input
                  v-model="materialForm.material_name"
                  type="text"
                  placeholder="Új anyag neve..."
                  required
                  class="flex-1 px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50
                    focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 
                    transition-all duration-200 outline-none"
                />
                <button 
                  type="submit"
                  :disabled="materialForm.processing"
                  class="px-4 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white font-medium rounded-xl 
                    transition-colors disabled:opacity-50 flex items-center gap-2"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                  </svg>
                </button>
              </form>
            </div>

            <!-- Anyagok listája -->
            <div class="max-h-96 overflow-y-auto">
              <div 
                v-for="material in materials" 
                :key="material.id"
                class="px-4 py-3 border-b border-slate-100 last:border-b-0 hover:bg-slate-50 transition-colors"
              >
                <!-- Szerkesztés mód -->
                <div v-if="editingMaterial === material.id" class="flex gap-2">
                  <input
                    v-model="editMaterialForm.material_name"
                    type="text"
                    class="flex-1 px-3 py-2 rounded-lg border border-slate-200 bg-white
                      focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 
                      transition-all duration-200 outline-none text-sm"
                  />
                  <button 
                    @click="updateMaterial(material.id)"
                    :disabled="editMaterialForm.processing"
                    class="p-2 rounded-lg text-emerald-600 hover:bg-emerald-50 transition-colors"
                  >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                  </button>
                  <button 
                    @click="cancelEditMaterial"
                    class="p-2 rounded-lg text-slate-500 hover:bg-slate-100 transition-colors"
                  >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                  </button>
                </div>

                <!-- Normál mód -->
                <div v-else class="flex items-center justify-between">
                  <span class="text-slate-700">{{ material.material_name }}</span>
                  <div class="flex items-center gap-1">
                    <button 
                      @click="startEditMaterial(material)"
                      class="p-2 rounded-lg text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition-colors"
                    >
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                      </svg>
                    </button>
                    <button 
                      @click="deleteMaterial(material.id)"
                      class="p-2 rounded-lg text-slate-400 hover:text-red-600 hover:bg-red-50 transition-colors"
                    >
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                      </svg>
                    </button>
                  </div>
                </div>
              </div>

              <!-- Üres állapot -->
              <div v-if="!materials?.length" class="p-8 text-center">
                <p class="text-slate-400 text-sm">Nincs még anyag felvéve</p>
              </div>
            </div>
          </div>

        </div>
      </div>
    </section>
  </MainLayout>
</template>
