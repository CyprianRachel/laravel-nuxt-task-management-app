<template>
  <div class="centered">
    <h1>Strona główna aplikacji</h1>

    <!-- Wiadomość po rejestracji -->
    <p v-if="successMessage" class="success-message">{{ successMessage }}</p>
    <p>Witaj, {{ authStore.user?.name }}!</p>
    <!-- Guzik wylogowania -->
    <button @click="logout">Wyloguj się</button>
  </div>
</template>

<script setup>
import { ref } from "vue";
import { useAuthStore } from "~/stores/auth";

const authStore = useAuthStore();
const successMessage = ref("");

if (!authStore.isLoggedIn) {
  navigateTo("/login");
}

// Odbieranie wiadomości z przekierowania
const route = useRoute();
if (route.query.success) {
  successMessage.value = route.query.success;
}

// Funkcja wylogowania
const logout = () => {
  const { $api } = useNuxtApp();
  $api.post("/logout").finally(() => {
    authStore.logout();
    navigateTo("/login"); // Przekierowanie na stronę logowania
  });
};
</script>

<style>
.centered {
  width: 100%;
  display: flex;
  justify-content: center;
  align-items: center;
  flex-direction: column;
}
.success-message {
  color: green;
  font-weight: bold;
}
</style>
