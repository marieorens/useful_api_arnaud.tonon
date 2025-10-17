<template>
    <template v-if="$route.path === '/'">
      <div class="min-h-screen flex flex-col items-center justify-center bg-gray-50 font-poppins">
        <h1 class="text-4xl font-bold mb-8 text-gray-800">Useful Dashboard</h1>
        <div class="flex space-x-4">
          <button @click="$router.push('/login')" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold px-6 py-2 rounded shadow">Login</button>
          <button @click="$router.push('/register')" class="bg-green-500 hover:bg-green-600 text-white font-semibold px-6 py-2 rounded shadow">Signup</button>
        </div>
      </div>
    </template>
    <template v-else>
      <router-view />
    </template>
</template>

<style>
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
}

nav {
  padding: 30px;
}

nav a {
  font-weight: bold;
  color: #2c3e50;
}

nav a.router-link-exact-active {
  color: #42b983;
}
  body {
    font-family: 'Poppins', sans-serif;
  }
</style>
