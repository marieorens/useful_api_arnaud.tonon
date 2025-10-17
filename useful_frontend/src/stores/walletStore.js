import { defineStore } from 'pinia';

export const useWalletStore = defineStore('wallet', {
  state: () => ({
    balance: 0,
    transactions: [],
    loading: false,
    error: null,
  }),
  actions: {
    // fetchBalance, fetchTransactions, topUp, transfer, etc.
  },
});