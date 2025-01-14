<template>
  <div class="centered">
    <h1>Strona główna aplikacji</h1>

    <!-- Wiadomość po rejestracji -->
    <p v-if="successMessage" class="success-message">{{ successMessage }}</p>
    <p>
      Witaj, <span class="user-name">{{ authStore.user?.name }}!</span>
    </p>
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

.user-name {
  color: green;
  font-weight: bold;
}
</style>
