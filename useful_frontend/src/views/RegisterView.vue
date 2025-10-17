<template>
  <div class="min-h-screen flex flex-col items-center justify-center bg-gradient-to-br from-green-50 to-blue-100 font-poppins">
    <div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-sm transition-all duration-300 hover:shadow-2xl">
  <h2 class="text-3xl font-extrabold mb-6 text-gray-800 text-center tracking-tight">Inscription</h2>
      <form @submit.prevent="handleRegister">
        <div class="mb-4">
          <label class="block text-gray-700 mb-2" for="name">Nom</label>
          <input v-model="name" id="name" type="text" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300" required />
        </div>
        <div class="mb-4">
          <label class="block text-gray-700 mb-2" for="email">Email</label>
          <input v-model="email" id="email" type="email" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300" required />
        </div>
        <div class="mb-6">
          <label class="block text-gray-700 mb-2" for="password">Mot de passe</label>
          <input v-model="password" id="password" type="password" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300" required />
        </div>
  <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white font-semibold py-2 rounded transition-colors duration-200">S'inscrire</button>
      </form>
  <div v-if="auth.error" class="mt-4 text-red-500 text-center animate-pulse">{{ auth.error }}</div>
      <div class="mt-6 text-center">
        <span class="text-gray-600">Déjà un compte ?</span>
        <button @click="router.push('/login')" class="ml-2 text-blue-600 hover:underline font-semibold transition-colors duration-200">Se connecter</button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useAuthStore } from '../stores/authStore'
import { useRouter } from 'vue-router'

const name = ref('')
const email = ref('')
const password = ref('')
const auth = useAuthStore()
const router = useRouter()

const handleRegister = async () => {
  const success = await auth.register(name.value, email.value, password.value)
  if (success) {
    router.push('/dashboard')
  }
}
</script>
