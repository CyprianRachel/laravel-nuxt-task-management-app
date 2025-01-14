import axios from "axios";
import { defineNuxtPlugin } from "#app";
import { useAuthStore } from "~/stores/auth";

export default defineNuxtPlugin((nuxtApp) => {
  const api = axios.create({
    baseURL: "https://laravel-nuxt-task-management-app.onrender.com/api", // Adres backendu
  });

  // Dodawanie tokena do każdego żądania
  api.interceptors.request.use((config) => {
    const authStore = useAuthStore();
    if (authStore.token) {
      config.headers.Authorization = `Bearer ${authStore.token}`;
      // console.log("Token dodany do żądania:", authStore.token);
    } else {
      // console.log("Brak tokena w axios podczas wysyłania żądania");
    }
    return config;
  });

  // Obsługa błędów
  api.interceptors.response.use(
    (response) => response,
    (error) => {
      // Możesz dodać globalną obsługę błędów np. wylogowanie przy 401
      if (error.response?.status === 401) {
        const authStore = useAuthStore();
        authStore.logout();
      }
      return Promise.reject(error);
    }
  );

  // Udostępnienie w całej aplikacji
  return {
    provide: {
      api,
    },
  };
});
