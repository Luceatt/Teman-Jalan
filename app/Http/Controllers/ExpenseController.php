<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Event;
use App\Models\Expense;
use App\Models\ExpenseShare;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ExpenseController extends Controller
{
    /**
     * Display a listing of expenses for a specific rundown (event).
     */
    public function index($rundownId)
    {
        $event = Event::with(['participants.user', 'expenses.paidBy', 'expenses.shares.user', 'expenses.activity'])
            ->findOrFail($rundownId);

        // Calculate total expenses and user's share
        $totalExpenses = $event->expenses->sum('amount');
        $myTotalShare = 0;
        $userId = Auth::id();

        foreach ($event->expenses as $expense) {
            $myShare = $expense->shares->where('user_id', $userId)->first();
            if ($myShare) {
                $myTotalShare += $myShare->amount_owed;
            }
        }

        return view('expenses.index', compact('event', 'totalExpenses', 'myTotalShare'));
    }

    /**
     * Show the form for creating a new expense.
     */
    public function create($rundownId)
    {
        $event = Event::with(['participants.user', 'activities'])->findOrFail($rundownId);
        $activities = $event->activities;
        $participants = $event->participants;

        return view('expenses.create', compact('event', 'activities', 'participants'));
    }

    /**
     * Store a newly created expense in storage.
     */
    public function store(Request $request, $rundownId)
    {
        $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
            'paid_by_user_id' => 'required|exists:users,id',
            'split_with' => 'required|array|min:1',
            'split_with.*' => 'exists:users,id',
            'activity_id' => 'nullable|exists:activities,activity_id'
        ]);

        try {
            DB::beginTransaction();

            $expense = Expense::create([
                'event_id' => $rundownId,
                'activity_id' => $request->activity_id,
                'description' => $request->description,
                'amount' => $request->amount,
                'paid_by_user_id' => $request->paid_by_user_id,
                'expense_date' => $request->expense_date,
            ]);

            // Calculate share logic (currently Equal Split)
            $splitWith = $request->split_with;
            $shareCount = count($splitWith);
            $amountPerPerson = $request->amount / $shareCount;

            foreach ($splitWith as $userId) {
                ExpenseShare::create([
                    'expense_id' => $expense->expense_id,
                    'user_id' => $userId,
                    'amount_owed' => $amountPerPerson,
                    'is_settled' => false, // Default not settled
                ]);
            }

            DB::commit();

            return redirect()->route('rundowns.expenses.index', $rundownId)
                ->with('success', 'Pengeluaran berhasil ditambahkan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal menyimpan pengeluaran: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified expense.
     */
    public function edit($rundownId, $expenseId)
    {
        $event = Event::with(['participants.user', 'activities'])->findOrFail($rundownId);
        $expense = Expense::with('shares')->where('event_id', $rundownId)->findOrFail($expenseId);
        
        $activities = $event->activities;
        $participants = $event->participants;
        
        // Get array of user IDs involved in the split
        $associatedUserIds = $expense->shares->pluck('user_id')->toArray();

        return view('expenses.edit', compact('event', 'expense', 'activities', 'participants', 'associatedUserIds'));
    }

    /**
     * Update the specified expense in storage.
     */
    public function update(Request $request, $rundownId, $expenseId)
    {
        $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
            'paid_by_user_id' => 'required|exists:users,id',
            'split_with' => 'required|array|min:1',
            'split_with.*' => 'exists:users,id',
            'activity_id' => 'nullable|exists:activities,activity_id'
        ]);

        try {
            DB::beginTransaction();

            $expense = Expense::where('event_id', $rundownId)->findOrFail($expenseId);

            $expense->update([
                'activity_id' => $request->activity_id,
                'description' => $request->description,
                'amount' => $request->amount,
                'paid_by_user_id' => $request->paid_by_user_id,
                'expense_date' => $request->expense_date,
            ]);

            // Re-calculate shares
            // First, delete existing shares
            ExpenseShare::where('expense_id', $expenseId)->delete();

            // Then create new ones
            $splitWith = $request->split_with;
            $shareCount = count($splitWith);
            $amountPerPerson = $request->amount / $shareCount;

            foreach ($splitWith as $userId) {
                ExpenseShare::create([
                    'expense_id' => $expense->expense_id,
                    'user_id' => $userId,
                    'amount_owed' => $amountPerPerson,
                    'is_settled' => false,
                ]);
            }

            DB::commit();

            return redirect()->route('rundowns.expenses.index', $rundownId)
                ->with('success', 'Pengeluaran berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal memperbarui pengeluaran: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified expense from storage.
     */
    public function destroy($rundownId, $expenseId)
    {
        try {
            $expense = Expense::where('event_id', $rundownId)->findOrFail($expenseId);
            $expense->delete(); // Cascading delete should handle shares if set up in DB, otherwise we might need manual delete
             // Assuming strict FK constraints might strictly fail if shares exist, but shares usually cascade or we delete them first.
             // Let's safe delete shares first just in case DB doesn't cascade.
             ExpenseShare::where('expense_id', $expenseId)->delete();
             
            return redirect()->route('rundowns.expenses.index', $rundownId)
                ->with('success', 'Pengeluaran berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus pengeluaran: ' . $e->getMessage());
        }
    }
}
