<template>
  <div class="login">
    <h1>Zaloguj się</h1>
    <form @submit.prevent="onSubmit">
      <div class="form-group">
        <label>Adres e-mail </label>
        <input
          v-model="form.email"
          type="email"
          placeholder="Enter your email"
          required
        />
      </div>
      <div class="form-group">
        <label>Hasło</label>
        <input
          v-model="form.password"
          type="password"
          placeholder="Enter your password"
          required
        />
      </div>

      <button type="submit">Zaloguj się</button>
    </form>

    <p v-if="error" class="error">{{ error }}</p>
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
    error.value = "Invalid login credentials";
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

.btn-submit:hover {
  background-color: #0056b3;
}

.success-message {
  margin-top: 1rem;
  color: green;
  font-weight: bold;
  text-align: center;
}

.error-message {
  margin-top: 1rem;
  color: red;
  font-weight: bold;
  text-align: center;
}
</style>
