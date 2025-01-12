<template>
  <div class="centered">
    <h1>Zarządzanie zamówieniami</h1>

    <!-- Guzik do generowania zamówień tylko dla adminów -->
    <button v-if="isAdmin" @click="generateOrders">Odbierz zamówienia</button>

    <div v-if="loading">Ładowanie...</div>
    <div v-if="error" class="error">{{ error }}</div>

    <!-- Lista zamówień -->
    <div class="bordered" v-for="order in orders" :key="order.id">
      <h2>Zamówienie #{{ order.id }}</h2>
      <p>Status: {{ order.status }}</p>
      <ul>
        <li v-for="item in order.items" :key="item.id">
          {{ item.product.name }} - Ilość: {{ item.quantity }}
        </li>
      </ul>

      <!-- Przycisk przypisania zadania do zamówienia tylko dla adminów -->
      <button
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
      <button @click="assignTask">Wyślij</button>
      <button @click="closeModal">Anuluj</button>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useAuthStore } from "~/stores/auth";

const authStore = useAuthStore();
const orders = ref([]);
const users = ref([]);
const loading = ref(false);
const error = ref("");
const showModal = ref(false);
const selectedOrderId = ref(null);
const selectedUserId = ref(null);

// Sprawdzamy, czy użytkownik jest administratorem
const isAdmin = ref(authStore.user?.role === "admin");

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
}

.modal {
  position: fixed;
  top: 20%;
  left: 50%;
  transform: translate(-50%, -20%);
  background: white;
  border: 1px solid black;
  padding: 20px;
  z-index: 1000;
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

.bordered {
  border: 1px solid black;
  padding: 1rem;
  margin-bottom: 1rem;
  width: 300px;
  border-radius: 1rem;
}
</style>
