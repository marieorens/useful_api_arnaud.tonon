<template>
  <div class="min-h-screen flex bg-gray-100 font-poppins">
    <aside :class="[sidebarCollapsed ? 'w-16' : 'w-64', 'bg-white shadow h-full p-4 transition-all duration-300']">
      <div class="flex items-center justify-between mb-4">
        <h2 v-if="!sidebarCollapsed" class="text-lg font-bold">Modules activés</h2>
        <button @click="sidebarCollapsed = !sidebarCollapsed" class="text-gray-500 hover:text-blue-500">
          <span v-if="sidebarCollapsed">&gt;&gt;</span>
          <span v-else>&lt;&lt;</span>
        </button>
      </div>
      <ul>
        <li v-for="mod in activatedModules" :key="mod.id" class="mb-2">
          <button @click="selectModule(mod)" class="w-full text-left font-semibold hover:text-blue-600 transition-colors duration-200">
            <span v-if="!sidebarCollapsed">{{ mod.name }}</span>
            <span v-else>•</span>
          </button>
        </li>
      </ul>
    </aside>

    <div class="flex-1 p-8">
      <nav class="flex justify-end items-center mb-8">
        <div class="relative group">
          <span class="font-semibold cursor-pointer">{{ auth.user?.name }}</span>
          <div class="absolute right-0 mt-2 bg-white border rounded shadow-lg p-2 opacity-0 group-hover:opacity-100 transition-opacity">
            <button @click="logout" class="text-red-500 hover:underline">Déconnexion</button>
          </div>
        </div>
      </nav>

      <h1 class="text-2xl font-bold mb-6">Tous les modules</h1>
      <div v-if="modulesStore.loading" class="text-gray-500">Chargement...</div>
      <div v-else-if="modulesStore.error" class="text-red-500">{{ modulesStore.error }}</div>
      <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div v-for="mod in modulesList" :key="mod.id" class="bg-white p-4 rounded shadow flex flex-col items-center">
          <span class="font-semibold text-lg mb-2">{{ mod.name }}</span>
          <button @click="toggleModule(mod)" :class="[mod.active ? 'bg-gray-400' : 'bg-blue-500', 'text-white px-4 py-1 rounded transition-colors duration-200']">
            {{ mod.active ? 'Désactiver' : 'Activer' }}
          </button>
        </div>
      </div>

      <div v-if="selectedModule && selectedModule.name === 'URL Shortener'" class="mt-8">
        <UrlShortenerView />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { useAuthStore } from '../stores/authStore'
import { useModulesStore } from '../stores/modulesStore'
import UrlShortenerView from './UrlShortenerView.vue'

const auth = useAuthStore()
const modulesStore = useModulesStore()
const sidebarCollapsed = ref(false)
const selectedModule = ref(null)

const modulesList = computed(() => Array.isArray(modulesStore.modules) ? modulesStore.modules : [])
const activatedModules = computed(() => modulesList.value.filter(m => m.active))

const toggleModule = async (mod) => {
  if (mod.active) {
    await modulesStore.deactivateModule(mod.id, auth.token)
  } else {
    await modulesStore.activateModule(mod.id, auth.token)
  }
  await modulesStore.fetchModules(auth.token)
}
const logout = () => {
  auth.logout()
  window.location.href = '/'
}
const selectModule = (mod) => {
  selectedModule.value = mod
}

onMounted(() => {
  modulesStore.fetchModules(auth.token)
})
</script>
