<template>
  <div class="register centered">
    <h1>Register</h1>
    <form @submit.prevent="onSubmit">
      <p>
        <label>Name:</label>
        <input
          v-model="form.name"
          type="text"
          placeholder="Enter your name"
          required
        />
      </p>

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

      <p>
        <label>Organization Name:</label>
        <input
          v-model="form.organization_name"
          type="text"
          placeholder="Enter your organization name"
          required
        />
      </p>

      <button type="submit">Register</button>
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
      query: { success: "Dziękujemy za rejestrację!" }, // Wiadomość w URL
    });
  } catch (err) {
    error.value = "Registration failed";
  }
};
</script>

<style>
.error {
  color: red;
}
</style>
