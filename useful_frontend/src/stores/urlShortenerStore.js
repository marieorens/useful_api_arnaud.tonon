import { defineStore } from 'pinia';

export const useUrlShortenerStore = defineStore('urlShortener', {
  state: () => ({
    urls: [],
    loading: false,
    error: null,
  }),
  actions: {
    // fetchUrls, shortenUrl, etc.
  },
});