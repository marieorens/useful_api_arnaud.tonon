import { defineStore } from 'pinia';
const API_URL = "http://127.0.0.1:8000";

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    token: localStorage.getItem('token') || null,
    loading: false,
    error: null,
  }),
  actions: {
    async login(email, password) {
      this.loading = true;
      this.error = null;
      try {
        const response = await fetch(`${API_URL}/api/login`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({ email, password })
        });
        const data = await response.json();
        if (!response.ok) {
          this.error = data.message || 'Erreur de connexion.';
          this.loading = false;
          return false;
        }
        this.token = data.token;
        localStorage.setItem('token', data.token);
        this.user = data.user || null;
        this.loading = false;
        return true;
      } catch (err) {
        this.error = 'Erreur serveur.';
        this.loading = false;
        return false;
      }
    },
    async register(name, email, password) {
      this.loading = true;
      this.error = null;
      try {
        const response = await fetch(`${API_URL}/api/register`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({ name, email, password })
        });
        const data = await response.json();
        if (!response.ok) {
          this.error = data.message || "Erreur lors de l'inscription.";
          this.loading = false;
          return false;
        }
        this.token = data.token;
        localStorage.setItem('token', data.token);
        this.user = data.user || null;
        this.loading = false;
        return true;
      } catch (err) {
        this.error = 'Erreur réseau ou serveur.';
        this.loading = false;
        return false;
      }
    },
    logout() {
      this.token = null;
      this.user = null;
      localStorage.removeItem('token');
    }
  },
});
