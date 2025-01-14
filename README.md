# Task Management App

Aplikacja do zarządzania zadaniami w zespołach, zaprojektowana z myślą o efektywnym przypisywaniu i monitorowaniu realizacji zadań w ramach organizacji. Aplikacja pozwala administratorom generować zadania, przypisywać je członkom zespołu oraz monitorować postępy w realizacji.

## **Demo**

[Kliknij tutaj, aby zobaczyć wersję demo aplikacji](https://laravel-nuxt-task-management-app.vercel.app/login)

---

| ![laravel-nuxt-task-management-app vercel app_orders (8)](https://github.com/user-attachments/assets/92256dd8-1c40-48e8-9269-404dd993024e) |
|-------------------------------------------------------|

| ![Image 1](https://github.com/user-attachments/assets/53fbbad4-5eeb-41ff-9a77-1675b69672bc) | ![Image 2](https://github.com/user-attachments/assets/430a92cf-6a6f-4836-80dd-63b169a6dfa4) | ![Image 7](https://github.com/user-attachments/assets/0b96d053-6598-4bbc-91c0-f4a64238bd6d) |
|-------------------------------------------------------|-------------------------------------------------------|-------------------------------------------------------|

| ![Image 4](https://github.com/user-attachments/assets/aef170a8-791f-42ef-82a6-e7203b288651) | ![Image 5](https://github.com/user-attachments/assets/94e09d88-fd7f-4695-82bc-95b7dcc59ba9) | ![Image 7](https://github.com/user-attachments/assets/0b96d053-6598-4bbc-91c0-f4a64238bd6d) |
|-------------------------------------------------------|-------------------------------------------------------|-------------------------------------------------------|

---

## **Technologie**

- **Backend:**
  - [Laravel 11](https://laravel.com/) – framework PHP do budowy API i obsługi logiki backendu.
  - [Laravel Sanctum](https://laravel.com/docs/11.x/sanctum) – uwierzytelnianie z użyciem tokenów.
  - [Docker](https://www.docker.com/) – środowisko dla bazy danych.

- **Frontend:**
  - [Vue 3](https://vuejs.org/) – framework do budowy dynamicznych interfejsów użytkownika.
  - [Nuxt 3](https://nuxt.com/) – framework oparty na Vue do budowy aplikacji uniwersalnych.
  - [Pinia](https://pinia.vuejs.org/) – system zarządzania stanem aplikacji.
  - [Axios](https://axios-http.com/) – biblioteka do obsługi żądań HTTP.

- **Baza danych:**
  - [PostgreSQL](https://www.postgresql.org/) – relacyjna baza danych uruchomiona w kontenerze Docker.

---

## **Funkcjonalności**

1. **Autoryzacja i role użytkowników:**
   - Rejestracja i logowanie z użyciem tokenów autoryzacyjnych.
   - Obsługa ról użytkowników: `admin` (administrator) i `member` (członek zespołu).

2. **Zarządzanie zadaniami:**
   - Generowanie zadań dla organizacji na żądanie administratora.
   - Przypisywanie zadań członkom zespołu.
   - Monitorowanie postępów w realizacji zadań.

3. **Zarządzanie zespołem:**
   - Administrator może przeglądać listę członków zespołu w organizacji.
   - Lista zawiera użytkowników z przypisaną rolą `member`.

4. **Dynamiczny interfejs użytkownika:**
   - Wyświetlanie list zadań i członków zespołu.
   - Obsługa błędów i powiadomień w czasie rzeczywistym.

5. **Bezpieczeństwo:**
   - Uwierzytelnianie oparte na tokenach.
   - Dostęp do zasobów ograniczony na podstawie roli użytkownika.
