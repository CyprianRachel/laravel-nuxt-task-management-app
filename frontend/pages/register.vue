<template>
  <div class="register">
    <h1>Zarejestruj się</h1>
    <form @submit.prevent="onSubmit">
      <div class="form-group">
        <label>Imię i nazwisko</label>
        <input
          v-model="form.name"
          type="text"
          placeholder="Wpisz imię i nazwisko"
          required
        />
      </div>

      <div class="form-group">
        <label>Adres e-mail </label>
        <input
          v-model="form.email"
          type="email"
          placeholder="Wpisz adres e-mail"
          required
        />
      </div>

      <div class="form-group">
        <label>Hasło </label>
        <input
          v-model="form.password"
          minlength="8"
          placeholder="Wpisz hasło (min. 8 znaków)"
          type="password"
          required
        />
      </div>

      <div class="form-group">
        <label>Nazwa zespołu:</label>
        <input
          v-model="form.organization_name"
          type="text"
          placeholder="Wpisz nazwę swojego zespołu"
          required
        />
      </div>

      <button type="submit">Zarejestruj się</button>
    </form>

    <p v-if="error" class="error">{{ error }}</p>
  </div>
</template>

<script setup>
import { ref } from "vue";
import { useAuthStore } from "~/stores/auth";

const authStore = useAuthStore();
const form = ref({
  name: "",
  email: "",
  password: "",
  organization_name: "",
});
const error = ref("");

const onSubmit = async () => {
  error.value = "";
  try {
    const { $api } = useNuxtApp();
    const { data } = await $api.post("/register", form.value);
    authStore.setToken(data.access_token);
    authStore.setUser(data.user);
    navigateTo({
      path: "/", // Przekierowanie do strony głównej
      query: { success: "Dziękujemy za rejestrację!" },
    });
  } catch (err) {
    error.value = "Błąd rejestracji";
  }
};
</script>

<style scoped>
.register {
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
