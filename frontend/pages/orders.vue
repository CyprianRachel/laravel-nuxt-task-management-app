<template>
  <div class="centered">
    <h1>Zarządzanie zamówieniami</h1>

    <!-- Guzik do generowania zamówień tylko dla adminów -->
    <button class="get-tasks-button" v-if="isAdmin" @click="generateOrders">
      Odbierz zamówienia
    </button>

    <div v-if="loading">Ładowanie...</div>
    <div v-if="error" class="error">{{ error }}</div>

    <!-- Lista zamówień -->
    <div
      class="bordered"
      v-for="order in sortedOrders"
      :key="order.id"
      :class="{ 'closed-order': order.status === 'closed' }"
    >
      <h2>Zamówienie #{{ order.id }}</h2>
      <p>
        Status:
        <span
          :class="{
            'status-open': order.status === 'open',
            'status-closed': order.status === 'closed',
          }"
        >
          {{ order.status === "open" ? "Do wysłania" : "Zakończone" }}
        </span>
      </p>
      <h3>Produkty:</h3>

      <ul>
        <li v-for="item in order.items" :key="item.id">
          {{ item.product.name }} - {{ item.product.description }} - Ilość:
          {{ item.quantity }}
        </li>
      </ul>

      <!-- Przycisk przypisania zadania do zamówienia tylko dla adminów -->
      <button
        class="add-task-button"
        v-if="order.status === 'open' && isAdmin"
        @click="showUserSelection(order.id)"
      >
        Przypisz zadanie
      </button>
    </div>

    <!-- Modal wyboru użytkownika -->
    <div v-if="showModal" class="modal-overlay" @click="closeModal"></div>
    <div v-if="showModal" class="modal">
      <h3>Wybierz użytkownika do przypisania zadania</h3>
      <ul>
        <li v-for="user in users" :key="user.id">
          <input type="radio" :value="user.id" v-model="selectedUserId" />
          {{ user.name }} ({{ user.email }})
        </li>
      </ul>
      <div class="modal-buttons">
        <button @click="assignTask">Wyślij</button>
        <button @click="closeModal">Anuluj</button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import { useAuthStore } from "~/stores/auth";

const authStore = useAuthStore();
const orders = ref([]);
const users = ref([]);
const loading = ref(false);
const error = ref("");
const showModal = ref(false);
const selectedOrderId = ref(null);
const selectedUserId = ref(null);

// Sprawdzenie, czy użytkownik jest administratorem
const isAdmin = computed(() => authStore.user?.role === "admin");

// Posortowane zamówienia: najpierw "open", potem "closed"
const sortedOrders = computed(() => {
  return orders.value.sort((a, b) =>
    a.status === "open" && b.status === "closed" ? -1 : 0
  );
});

// Funkcja do pobierania zamówień
const fetchOrders = async () => {
  loading.value = true;
  error.value = "";
  try {
    const { $api } = useNuxtApp();
    const { data } = await $api.get("/orders");
    orders.value = data;
  } catch (err) {
    error.value = "Nie udało się pobrać zamówień";
  } finally {
    loading.value = false;
  }
};

// Funkcja do pobierania użytkowników
const fetchUsers = async () => {
  loading.value = true;
  error.value = "";
  try {
    const { $api } = useNuxtApp();
    const { data } = await $api.get("/users");
    users.value = data;
  } catch (err) {
    error.value = "Nie udało się pobrać użytkowników";
  } finally {
    loading.value = false;
  }
};

// Funkcja do generowania zamówień
const generateOrders = async () => {
  loading.value = true;
  error.value = "";
  try {
    const { $api } = useNuxtApp();
    const { data } = await $api.post("/orders/generate");
    orders.value.push(data.order);
  } catch (err) {
    error.value = "Nie udało się wygenerować zamówienia";
  } finally {
    loading.value = false;
  }
};

// Funkcja do pokazania modalnego okna wyboru użytkownika
const showUserSelection = async (orderId) => {
  selectedOrderId.value = orderId;
  await fetchUsers(); // Pobierz użytkowników
  showModal.value = true; // Pokaż modal
};

// Funkcja do zamknięcia modalnego okna
const closeModal = () => {
  showModal.value = false;
  selectedUserId.value = null;
};

// Funkcja do przypisania zadania
const assignTask = async () => {
  if (!selectedUserId.value) {
    error.value = "Wybierz użytkownika, zanim przypiszesz zadanie.";
    return;
  }

  loading.value = true;
  error.value = "";
  try {
    const { $api } = useNuxtApp();
    // Wysłanie zapytania do backendu z user_id oraz orderId
    await $api.post(`/orders/${selectedOrderId.value}/assign-task`, {
      user_id: selectedUserId.value, // ID wybranego użytkownika
    });
    alert("Zadanie zostało przypisane!");
    closeModal();
    await fetchOrders(); // Odśwież zamówienia
  } catch (err) {
    error.value = "Nie udało się przypisać zadania";
  } finally {
    loading.value = false;
  }
};

// Ładowanie zamówień po załadowaniu komponentu
onMounted(async () => {
  await fetchOrders();
});
</script>

<style scoped>
.error {
  color: red;
  font-weight: bold;
  margin-bottom: 1rem;
}

.modal {
  position: fixed;
  top: 20%;
  left: 50%;
  transform: translate(-50%, -20%);
  background: white;
  border: 1px solid #ddd;
  border-radius: 0.5rem;
  padding: 20px;
  z-index: 1000;
}

.modal ul {
  list-style-type: none;
  margin: 0;
  padding: 0;
}

li {
  margin-bottom: 0.75rem;
}

.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.5);
  z-index: 999;
}

.modal-buttons {
  display: flex;
  flex-direction: row;
  justify-content: center;
  gap: 1rem;
  margin-top: 1rem;
}

.bordered {
  padding: 1rem;
  margin-bottom: 1rem;
  width: 350px;
  margin-bottom: 20px;
  border: 1px solid #ddd;
  border-radius: 0.5rem;
  background-color: #f9f9f9;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
}

.closed-order {
  background-color: #d4edda; /* Jasnozielone tło */
  border-color: #c3e6cb;
}

.status-open {
  color: orange;
  font-weight: bold;
}

.status-closed {
  color: green;
  font-weight: bold;
}

.get-tasks-button {
  margin-bottom: 2rem;
}

ul {
  margin: 0;
  padding: 0;
  padding-left: 3rem;
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  width: 100%;
}

.add-task-button {
  margin-top: 1rem;
}
</style>
