<template>
  <div class="tasks-page">
    <h1>Twoje zadania</h1>

    <div v-if="loading">Ładowanie...</div>

    <div v-if="error" class="error">{{ error }}</div>

    <!-- Brak zadań -->
    <div v-if="!loading && tasks.length === 0" class="no-tasks">
      Brak przypisanych zadań.
    </div>

    <!-- Lista zadań -->
    <div v-for="task in tasks" :key="task.id" class="task-item">
      <div>
        <!-- <p><strong>Zadanie ID:</strong> {{ task.id }}</p> -->
        <h2><strong>Zamówienie </strong>#{{ task.order_id }}</h2>
        <p>
          Status:
          <span
            :class="{
              'status-in-progress': !task.completed,
              'status-completed': task.completed,
            }"
          >
            {{ task.completed ? " Zakończone" : " W trakcie" }}
          </span>
        </p>
      </div>

      <!-- Wyświetlanie produktów powiązanych z zadaniem -->
      <div
        class="products-list"
        v-if="task.products && task.products.length > 0"
      >
        <h3>Produkty:</h3>
        <ul>
          <li v-for="product in task.products" :key="product.id">
            <input
              type="checkbox"
              :id="`product-${task.id}-${product.id}`"
              :checked="product.checked"
              @change="toggleProductCheck(task.id, product.id)"
            />
            <label
              :for="`product-${task.id}-${product.id}`"
              :class="{ 'checked-product': product.checked }"
            >
              {{ product.name }} - {{ product.description }} (Ilość:
              {{ product.quantity }})
            </label>
          </li>
        </ul>
      </div>

      <!-- Przycisk oznaczenia jako zrobione -->
      <button
        v-if="!task.completed"
        @click="markAsDone(task.id, task.order_id)"
        class="done-button"
      >
        Oznacz jako zrobione
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";

const tasks = ref([]);
const loading = ref(false);
const error = ref("");

// Funkcja pobierania zadań przypisanych do użytkownika
const fetchTasks = async () => {
  loading.value = true;
  error.value = "";
  try {
    const { $api } = useNuxtApp();
    const response = await $api.get("/tasks");

    if (response.data && Array.isArray(response.data)) {
      tasks.value = response.data.map((task) => ({
        ...task,
        products:
          task.order?.items.map((item) => ({
            ...item.product,
            quantity: item.quantity,
            checked: false, // Domyślne ustawienie checboxa na pusty
          })) || [],
      }));
    } else {
      error.value = "Niepoprawna struktura danych.";
    }
  } catch (err) {
    error.value = "Nie udało się pobrać zadań.";
  } finally {
    loading.value = false;
  }
};

// Funkcja oznaczania zadania jako zrobione
const markAsDone = async (taskId, orderId) => {
  loading.value = true;
  error.value = "";
  try {
    const { $api } = useNuxtApp();
    await $api.post(`/tasks/${taskId}/mark-as-done`, { order_id: orderId });

    // Lokalna zmiana statusu zadania
    const taskIndex = tasks.value.findIndex((task) => task.id === taskId);
    if (taskIndex > -1) {
      tasks.value[taskIndex].completed = true;
    }
  } catch (err) {
    error.value = "Nie udało się zakończyć zadania.";
  } finally {
    loading.value = false;
  }
};

// Funkcja toggle dla checkboxa
const toggleProductCheck = (taskId, productId) => {
  const task = tasks.value.find((t) => t.id === taskId);
  if (task) {
    const product = task.products.find((p) => p.id === productId);
    if (product) {
      product.checked = !product.checked; // Zmiena stanu zaznaczenia
    }
  }
};

// Pobieranie zadań przy załadowaniu komponentu
onMounted(() => {
  fetchTasks();
});
</script>

<style scoped>
.tasks-page {
  max-width: 800px;
  margin: 0 auto;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
}

h1 {
  text-align: center;
}

.error {
  color: red;
  font-weight: bold;
  text-align: center;
}

.no-tasks {
  text-align: center;
  font-style: italic;
  color: grey;
}

.task-item {
  margin-bottom: 20px;
  padding: 1rem;
  border: 1px solid #ddd;
  border-radius: 0.5rem;
  background-color: #f9f9f9;
  width: 350px;
}

.task-item p {
  margin: 5px 0;
}

ul {
  list-style: none;
  padding: 0;
  margin: 0;
}

li {
  margin-bottom: 0.75rem;
}

label {
  margin-left: 5px;
}

label.checked-product {
  text-decoration: line-through;
  color: grey;
}

.status-in-progress {
  color: orange;
  font-weight: bold;
}

.status-completed {
  color: green;
  font-weight: bold;
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

.products-list ul {
  display: flex;
  justify-content: flex-start;
  flex-direction: column;
  align-items: flex-start;
}

/* Stylizacja dla mniejszych ekranów */
@media (max-width: 768px) {
  .task-item {
    width: 320px;
  }
}
</style>
