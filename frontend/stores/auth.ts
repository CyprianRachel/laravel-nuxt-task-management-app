import { defineStore } from "pinia";

export const useAuthStore = defineStore("auth", {
  state: () => ({
    token: null as string | null, // Przechowywany token
    user: null as { id: number; name: string; email: string } | null, // Dane użytkownika
  }),

  getters: {
    isLoggedIn: (state) => !!state.token, // Sprawdza, czy użytkownik jest zalogowany
  },

  actions: {
    setToken(token: string) {
      this.token = token;
    },
    setUser(user: any) {
      this.user = user;
    },
    logout() {
      this.token = null;
      this.user = null;
    },
  },
});
