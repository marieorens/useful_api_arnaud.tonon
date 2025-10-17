import { defineStore } from 'pinia';

const API_URL = "http://127.0.0.1:8000";

export const useModulesStore = defineStore('modules', {
  state: () => ({
    modules: [],
    loading: false,
    error: null,
  }),
  actions: {
    async fetchModules(token) {
      this.loading = true;
      this.error = null;
      try {
        const response = await fetch(`${API_URL}/api/modules`, {
          headers: {
            'Authorization': `Bearer ${token}`
          }
        });
        const data = await response.json();
        if (!response.ok) {
          this.error = data.message || 'Erreur chargement modules.';
          this.loading = false;
          return;
        }
        this.modules = data;
        this.loading = false;
      } catch (err) {
        this.error = 'Erreur lors de la récuppération des modules.';
        console.log(err);
        this.loading = false;
      }
    },
    async activateModule(id, token) {
      try {
        await fetch(`${API_URL}/api/modules/${id}/activate`, {
          method: 'POST',
          headers: {
            'Authorization': `Bearer ${token}`,
            'Content-Type': 'application/json'
          }
        });
      } catch(error){
        console.log("Erreurlors dde l'activation du module")
      }
    },
    async deactivateModule(id, token) {
      try {
        await fetch(`${API_URL}/api/modules/${id}/deactivate`, {
          method: 'POST',
          headers: {
            'Authorization': `Bearer ${token}`,
            'Content-Type': 'application/json'
          }
        });
      } catch(error){
        console.log("Erreur lors de la désactivation du module",error)
      }
    }
  },
});