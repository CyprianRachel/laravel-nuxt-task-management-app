<template>
  <div class="centered">
    <h1>Strona główna aplikacji</h1>

    <!-- Wiadomość po rejestracji -->
    <p v-if="successMessage" class="success-message">{{ successMessage }}</p>
    <p>Witaj, {{ authStore.user?.name }}!</p>

    <!-- Guzik wylogowania -->
    <p><button @click="logout">Wyloguj się</button></p>

    <!-- Guzik do orders -->
    <p><button @click="goToOrders">Zobacz listę zamówień</button></p>

    <!-- Guzik do orders -->
    <p><button @click="goToTasks">Zobacz swoje zamówienia</button></p>
  </div>
</template>

<script setup>
import { ref } from "vue";
import { useAuthStore } from "~/stores/auth";

const authStore = useAuthStore();
const successMessage = ref("");
const team = ref([]);
const teamError = ref("");

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

// Funkcja przekierowująca do strony zamówień
const goToOrders = () => {
  navigateTo("/orders"); // Przekierowanie na stronę /orders
};

// Funkcja przekierowująca do strony tasków
const goToTasks = () => {
  navigateTo("/tasks"); // Przekierowanie na stronę /orders
};
</script>

<style>
.success-message {
  color: green;
  font-weight: bold;
}

.error {
  color: red;
  font-weight: bold;
}

.centered {
  text-align: center;
}
</style>
