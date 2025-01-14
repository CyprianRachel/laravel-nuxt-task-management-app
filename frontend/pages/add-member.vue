<template>
  <div class="add-member-page">
    <h1>Dodaj członka zespołu</h1>

    <!-- Formularz dodawania członka -->
    <form @submit.prevent="addMember">
      <div class="form-group">
        <label for="name">Imię i nazwisko</label>
        <input
          type="text"
          id="name"
          v-model="form.name"
          required
          placeholder="Wpisz imię i nazwisko"
        />
      </div>

      <div class="form-group">
        <label for="email">Adres e-mail</label>
        <input
          type="email"
          id="email"
          v-model="form.email"
          required
          placeholder="Wpisz adres e-mail"
        />
      </div>

      <div class="form-group">
        <label for="password">Hasło</label>
        <input
          type="password"
          id="password"
          v-model="form.password"
          required
          minlength="8"
          placeholder="Wpisz hasło (min. 8 znaków)"
        />
      </div>

      <!-- Przycisk dodania członka -->
      <button type="submit" class="btn-submit">Dodaj członka</button>
    </form>

    <!-- Wiadomość o sukcesie -->
    <div v-if="successMessage" class="success-message">
      {{ successMessage }}
    </div>

    <!-- Wiadomości o błędach -->
    <div v-if="errorMessages.length > 0" class="error-messages">
      <ul>
        <li v-for="(error, index) in errorMessages" :key="index">
          {{ error }}
        </li>
      </ul>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";

const form = ref({
  name: "",
  email: "",
  password: "",
});

const successMessage = ref("");
const errorMessages = ref([]);

// Funkcja dodawania członka
const addMember = async () => {
  successMessage.value = "";
  errorMessages.value = [];

  try {
    const { $api } = useNuxtApp(); // Użycie Nuxt.js do komunikacji z backendem
    const response = await $api.post("/organization/add-member", {
      name: form.value.name,
      email: form.value.email,
      password: form.value.password,
    });

    // Ustawienie wiadomości o sukcesie
    successMessage.value = "Nowy członek został dodany!";
    form.value.name = "";
    form.value.email = "";
    form.value.password = "";
  } catch (error) {
    // Obsługa błędów walidacji
    if (error.response?.status === 403) {
      errorMessages.value = ["Nie masz uprawnień do dodawania członków."];
    } else if (error.response?.status === 422) {
      // Wyciągnięcie szczegółowych błędów z odpowiedzi backendu
      const errors = error.response?.data?.errors;
      if (errors) {
        errorMessages.value = Object.values(errors).flat();
      } else {
        errorMessages.value = [
          "Dane są nieprawidłowe. Sprawdź wprowadzone informacje.",
        ];
      }
    } else {
      errorMessages.value = ["Wystąpił błąd. Spróbuj ponownie."];
    }
  }
};
</script>

<style scoped>
.add-member-page {
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

input:focus {
  border-color: #007bff;
  outline: none;
}

.btn-submit {
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

.error-messages {
  margin-top: 1rem;
  color: red;
  font-weight: bold;
  text-align: center;
}

.error-messages ul {
  list-style: none;
  padding: 0;
  margin: 0;
}

.error-messages li {
  margin: 0.25rem 0;
}
</style>
