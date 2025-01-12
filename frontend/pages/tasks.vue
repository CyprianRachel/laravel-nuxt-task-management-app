<template>
  <div class="centered">
    <h1>Twoje zadania</h1>

    <!-- Ładowanie -->
    <div v-if="loading">Ładowanie...</div>

    <!-- Błąd -->
    <div v-if="error" class="error">{{ error }}</div>

    <!-- Wyświetlanie zadań -->
    <div v-if="tasks.length === 0" class="no-tasks">
      Brak przypisanych zadań.
    </div>

    <div v-for="task in tasks" :key="task.id" class="task-item">
      <div>
        <p><strong>Zadanie ID:</strong> {{ task.id }}</p>
        <p><strong>Zamówienie ID:</strong> {{ task.order_id }}</p>
        <p>
          <strong>Status:</strong>
          {{ task.completed ? "Zakończone" : "W trakcie" }}
        </p>
        <button
          v-if="!task.completed"
          @click="markAsDone(task.id, task.order_id)"
          class="done-button"
        >
          Zrobione
        </button>
      </div>
      <hr />
    </div>

    <!-- Komunikat o sukcesie -->
    <div v-if="successMessage" class="success-message">
      {{ successMessage }}
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";

// Ref do przechowywania zadań, stanu ładowania i błędów
const tasks = ref([]);
const loading = ref(false);
const error = ref("");
const successMessage = ref("");

// Funkcja do pobierania zadań przypisanych do użytkownika
const fetchTasks = async () => {
  loading.value = true;
  error.value = ""; // Resetowanie błędów
  try {
    const { $api } = useNuxtApp(); // Jeśli używasz Nuxt 3, pozostaw to w tej formie
    const response = await $api.get("/tasks"); // Wywołanie API do pobrania zadań

    if (response.data && Array.isArray(response.data)) {
      tasks.value = response.data; // Przypisanie otrzymanych zadań
    } else {
      error.value = "Zadania są puste lub niepoprawnie sformatowane."; // Jeśli dane nie są w odpowiedniej strukturze
    }
  } catch (err) {
    error.value = "Nie udało się pobrać zadań."; // Ustawienie błędu, jeśli nie uda się pobrać danych
  } finally {
    loading.value = false; // Kończenie ładowania
  }
};

// Funkcja do oznaczenia zadania jako zakończone
const markAsDone = async (taskId, orderId) => {
  loading.value = true;
  error.value = ""; // Resetowanie błędów
  successMessage.value = ""; // Resetowanie komunikatu o sukcesie
  try {
    const { $api } = useNuxtApp();

    // Wyślij zapytanie do backendu, aby usunąć zadanie i zaktualizować status zamówienia
    await $api.post(`/tasks/${taskId}/mark-as-done`, { order_id: orderId });

    // Zaktualizowanie statusu zadania lokalnie
    tasks.value = tasks.value.filter((task) => task.id !== taskId);

    // Ustawienie komunikatu o sukcesie
    successMessage.value = "Zadanie zostało zakończone!";

    // Zmiana statusu na 'closed' w zamówieniu
    const updatedOrder = await $api.put(`/orders/${orderId}/close`);
    console.log(updatedOrder.data);
  } catch (err) {
    error.value = "Nie udało się zakończyć zadania."; // Obsługa błędów
  } finally {
    loading.value = false; // Kończenie ładowania
  }
};

// Ładowanie zadań po załadowaniu komponentu
onMounted(() => {
  fetchTasks(); // Wywołanie funkcji pobierania zadań
});
</script>

<style scoped>
.error {
  color: red;
  font-weight: bold;
}

.no-tasks {
  font-style: italic;
  color: grey;
}

.task-item {
  margin-bottom: 20px;
  padding: 10px;
  border: 1px solid #ddd;
  border-radius: 5px;
  background-color: #f9f9f9;
}

.task-item p {
  margin: 5px 0;
}

.done-button {
  background-color: green;
  color: white;
  padding: 10px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

.done-button:hover {
  background-color: darkgreen;
}

.success-message {
  color: green;
  font-weight: bold;
}
</style>
