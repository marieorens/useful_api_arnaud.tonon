import { defineStore } from 'pinia';

export const useTimeTrackerStore = defineStore('timeTracker', {
  state: () => ({
    sessions: [],
    loading: false,
    error: null,
  }),
  actions: {
    // fetchSessions, startSession, stopSession, etc.
  },
});