<template>
  <div class="app-layout">
    <!-- Nagłówek -->
    <header class="app-header">
      <h1>Aplikacja Organizacyjna</h1>
    </header>

    <!-- Menu nawigacyjne -->
    <nav class="app-menu">
      <ul>
        <li>
          <NuxtLink to="/orders">Lista zamówień</NuxtLink>
        </li>
        <li>
          <NuxtLink to="/tasks">Twoje zadania</NuxtLink>
        </li>
        <li>
          <NuxtLink to="/add-member">Dodaj członka zespołu</NuxtLink>
        </li>
        <li>
          <NuxtLink to="/register">Zarejestruj się</NuxtLink>
        </li>
        <li>
          <button class="logout" @click="logout">Wyloguj się</button>
        </li>
      </ul>
    </nav>

    <!-- Główna zawartość -->
    <main class="app-content">
      <slot />
      <!-- Wyświetla zawartość strony -->
    </main>
  </div>
</template>

<script setup>
import { useAuthStore } from "~/stores/auth";

const authStore = useAuthStore();

// Funkcja wylogowania
const logout = () => {
  const { $api } = useNuxtApp();
  $api.post("/logout").finally(() => {
    authStore.logout();
    navigateTo("/login");
  });
};
</script>

<style scoped>
.app-layout {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
  font-family: Arial, sans-serif;
}

.app-header {
  background-color: #007bff;
  color: white;
  padding: 1rem;
  text-align: center;
}

.app-menu {
  background-color: #f8f9fa;
  padding: 1rem 0rem;
  border-bottom: 1px solid #ddd;
  display: flex;
  flex-direction: row;
  width: 100%;
  justify-content: center;
  align-items: center;
}

.app-menu ul {
  list-style: none;
  padding: 0;
  margin: 0;
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  gap: 1rem;
  align-items: center;
}

.app-menu li {
  display: inline;
}

.app-menu a,
.app-menu button {
  text-decoration: none;
  background-color: #007bff;
  color: white;
  border: none;
  padding: 0.5rem 1rem;
  border-radius: 5px;
  cursor: pointer;
  font-size: 1rem;
}

.app-menu a:hover,
.app-menu button:hover {
  background-color: #0056b3;
}

.app-content {
  flex: 1;
  padding: 2rem;
  text-align: center;
}
</style>
