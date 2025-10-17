import { createRouter, createWebHashHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'

const routes = [
  {
    path: '/',
    name: 'home',
    component: HomeView
  },
  {
    path: '/login',
    name: 'login',
    component: () => import('../views/LoginView.vue')
  },
  {
    path: '/register',
    name: 'register',
    component: () => import('../views/RegisterView.vue')
  },
  {
    path: '/dashboard',
    name: 'dashboard',
    component: () => import('../views/DashboardView.vue')
  },
  {
    path: '/wallet',
    name: 'wallet',
    component: () => import('../views/WalletView.vue')
  },
  {
    path: '/url-shortener',
    name: 'urlShortener',
    component: () => import('../views/UrlShortenerView.vue')
  },
  {
    path: '/time-tracker',
    name: 'timeTracker',
    component: () => import('../views/TimeTrackerView.vue')
  }
]

const router = createRouter({
  history: createWebHashHistory(),
  routes
})

export default router
