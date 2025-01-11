<template>
  <div class="login centered">
    <h1>Login</h1>
    <form @submit.prevent="onSubmit">
      <p>
        <label>Email:</label>
        <input
          v-model="form.email"
          type="email"
          placeholder="Enter your email"
          required
        />
      </p>
      <p>
        <label>Password:</label>
        <input
          v-model="form.password"
          type="password"
          placeholder="Enter your password"
          required
        />
      </p>

      <button type="submit">Login</button>
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

<style>
.error {
  color: red;
}
</style>
