<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use App\Models\Task;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Dodaj ten import


class TaskController extends Controller
{

       // Metoda do pobierania zadań tylko dla użytkowników, którzy nie są adminami
       public function getTasksForUser(Request $request)
       {
           try {
               // Pobierz aktualnie zalogowanego użytkownika
               $user = Auth::user();
   
               // Jeśli użytkownik nie jest administratorem, zwróć jego zadania
               if ($user->role === 'admin') {
                   return response()->json([], 200);  // Zwrócenie pustej tablicy dla admina
               }
   
               // Pobranie zadań przypisanych do tego użytkownika
               $tasks = Task::where('user_id', $user->id)->get();
   
               return response()->json($tasks);  // Zwrócenie zadań w formacie JSON
           } catch (\Exception $e) {
               // W przypadku błędu, zwróć komunikat błędu i kod 500
               return response()->json(['message' => 'Wystąpił błąd podczas pobierania zadań', 'error' => $e->getMessage()], 500);
           }
       }
    

    public function assignTask(Request $request, $orderId)
    {
        // Sprawdzenie, czy zamówienie istnieje
        $order = Order::findOrFail($orderId);

        // Pobranie użytkownika na podstawie ID
        $user = User::findOrFail($request->user_id);

        // Sprawdzenie, czy użytkownik jest administratorem
        if ($user->role === 'admin') {
            // Jeśli użytkownik jest administratorem, nie przypisujemy zadania
            return response()->json(['message' => 'Nie można przypisać zadania administratorowi.'], 400);  // Status 400: Zły request
        }

        // Tworzymy nowe zadanie, przypisując je do zamówienia i użytkownika
        $task = Task::create([
            'order_id' => $order->id,
            'user_id' => $user->id,
            'completed' => false,  // Domyślnie zadanie jest niezakończone
        ]);

        // Zwrócenie przypisanego zadania
        return response()->json($task, 201);  // Status 201: Stworzony zasób
    }

    public function markAsDone(Request $request, $taskId)
{
    $task = Task::findOrFail($taskId);

    // Zakończenie zadania
    $task->completed = true;
    $task->save();

    // Usunięcie zadania z tabeli tasks (opcjonalnie)
    $task->delete();

    return response()->json(['message' => 'Zadanie zostało zakończone']);
}






}
