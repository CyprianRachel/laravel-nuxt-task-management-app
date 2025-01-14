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
      localStorage.setItem("authToken", token);
      // console.log("Token zapisany:", token); // Logowanie tokena
    },
    setUser(user: any) {
      this.user = user;
      localStorage.setItem("authUser", JSON.stringify(user));
      // console.log("Użytkownik zapisany:", user); // Logowanie użytkownika
    },
    loadAuthData() {
      const token = localStorage.getItem("authToken");
      const user = localStorage.getItem("authUser");

      if (token) {
        this.token = token;
        // console.log("Token załadowany:", token);
      } else {
        // console.log("Brak tokena w localStorage");
      }

      if (user) {
        this.user = JSON.parse(user);
        // console.log("Użytkownik załadowany:", this.user);
      } else {
        // console.log("Brak użytkownika w localStorage");
      }
    },
    logout() {
      this.token = null;
      this.user = null;
      localStorage.removeItem("authToken");
      localStorage.removeItem("authUser");
      // console.log("Wylogowano użytkownika"); // Logowanie wylogowania
    },
  },
});
