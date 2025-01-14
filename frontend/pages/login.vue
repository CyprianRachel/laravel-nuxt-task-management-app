<template>
  <div class="login">
    <h1>Zaloguj się</h1>
    <form @submit.prevent="onSubmit">
      <div class="form-group">
        <label>Adres e-mail</label>
        <input
          v-model="form.email"
          type="email"
          placeholder="Wpisz swój adres e-mail"
          required
        />
      </div>
      <div class="form-group">
        <label>Hasło</label>
        <input
          v-model="form.password"
          type="password"
          placeholder="Wpisz swoje hasło"
          required
        />
      </div>

      <button type="submit">Zaloguj się</button>
    </form>

    <p v-if="error" class="error">{{ error }}</p>

    <!-- Informacja o możliwościach -->
    <div class="info-section">
      <p>
        Możesz założyć konto lub skorzystać z jednego z poniższych kont
        testowych.
      </p>
    </div>

    <!-- Dane testowe konta administratora -->
    <div class="test-account">
      <h2>Dane testowe - Konto Administratora</h2>
      <p><strong>Login:</strong> stefan@example.com</p>
      <p><strong>Hasło:</strong> example123</p>
    </div>

    <!-- Dane testowe konta użytkownika -->
    <div class="test-account">
      <h2>Dane testowe - Konto Użytkownika</h2>
      <p><strong>Login:</strong> kuncz@example.com</p>
      <p><strong>Hasło:</strong> example123</p>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";
import { useAuthStore } from "~/stores/auth";

const authStore = useAuthStore();
const form = ref({
  email: "",
  password: "",
});
const error = ref("");

const onSubmit = async () => {
  error.value = "";
  try {
    const { $api } = useNuxtApp();
    const { data } = await $api.post("/login", form.value);
    authStore.setToken(data.access_token);
    authStore.setUser(data.user);
    navigateTo("/"); // Przekierowanie do strony głównej
  } catch (err) {
    error.value = "Nieprawidłowe dane logowania.";
  }
};
</script>

<style scoped>
.login {
  max-width: 500px;
  margin: 0 auto;
  font-family: Arial, sans-serif;
}

.form-group {
  margin-bottom: 1rem;
}

label {
  display: block;
  font-weight: bold;
  margin-bottom: 0.5rem;
}

input {
  width: 100%;
  padding: 0.5rem;
  border: 1px solid #ccc;
  border-radius: 5px;
  font-size: 1rem;
}

button {
  width: 100%;
  padding: 0.5rem;
  background-color: #007bff;
  color: white;
  border: none;
  border-radius: 5px;
  font-size: 1rem;
  cursor: pointer;
}

button:hover {
  background-color: #0056b3;
}

.error {
  margin-top: 1rem;
  color: red;
  font-weight: bold;
  text-align: center;
}

.info-section {
  margin-top: 2rem;
  font-size: 1rem;
  text-align: center;
}

.test-account {
  margin-top: 1.5rem;
  padding: 1rem;
  border: 1px solid #ccc;
  border-radius: 5px;
  background-color: #f9f9f9;
  text-align: center;
}

.test-account h2 {
  margin-bottom: 0.5rem;
  color: #007bff;
}

.test-account p {
  margin: 0.5rem 0;
}
</style>
