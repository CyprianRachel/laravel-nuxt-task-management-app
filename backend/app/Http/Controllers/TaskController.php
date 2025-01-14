<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use App\Models\Task;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class TaskController extends Controller
{

       // Metoda do pobierania zadań tylko dla użytkowników, którzy nie są adminami
       public function getTasksForUser(Request $request)
            {
                try {
                    // Pobieranie aktualnie zalogowanego użytkownika
                    $user = Auth::user();

                    // Jeśli użytkownik nie jest administratorem, pobierz jego zadania
                    if ($user->role !== 'admin') {
                        // Pobranie zadań użytkownika z powiązanymi produktami i zamówieniami
                        $tasks = Task::where('user_id', $user->id)
                            ->with(['order.items.product']) // Ładowanie powiązanych produktów przez zamówienie
                            ->get();

                        return response()->json($tasks); // Zwrócenie zadań z produktami w formacie JSON
                    }

                    return response()->json([], 200); 
                } catch (\Exception $e) {
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
            // Jeśli użytkownik jest administratorem, nie przypisuj zadania
            return response()->json(['message' => 'Nie można przypisać zadania administratorowi.'], 400); 
        }

        // Tworzenie nowego zadania, przypisując je do zamówienia i użytkownika
        $task = Task::create([
            'order_id' => $order->id,
            'user_id' => $user->id,
            'completed' => false,  // Domyślnie zadanie jest niezakończone
        ]);

        // Zwrócenie przypisanego zadania
        return response()->json($task, 201); 
    }

    public function markAsDone(Request $request, $taskId)
{
    $task = Task::findOrFail($taskId);

    // Zakończenie zadania
    $task->completed = true;
    $task->save();

    // Zmiana statusu na zakończone
    $order = $task->order;
    $order->status = 'closed';
    $order->save();

    // Usunięcie zadania z tabeli tasks
    $task->delete();

    return response()->json(['message' => 'Zadanie zostało zakończone']);
}






}
