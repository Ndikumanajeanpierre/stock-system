<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Department;
use App\Models\StockRequisition;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalUsers            = User::count();
        $totalRequisitions     = StockRequisition::count();
        $pendingRequisitions   = StockRequisition::where('status', 'pending')->count();
        $completedRequisitions = StockRequisition::where('status', 'completed')->count();
        $totalPayments         = Payment::sum('amount');
        $recentRequisitions    = StockRequisition::with(['user', 'department'])
                                    ->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalUsers', 'totalRequisitions', 'pendingRequisitions',
            'completedRequisitions', 'totalPayments', 'recentRequisitions'
        ));
    }

    // ── Users ─────────────────────────────────────────────────────
    public function users()
    {
        $users = User::latest()->get();
        return view('admin.users', compact('users'));
    }

    public function createUser()
    {
        return view('admin.create-user');
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:users',
            'password'   => 'required|min:6',
            'role'       => 'required|in:admin,employee,accountant',
            'department' => 'nullable|string',
            'phone'      => 'nullable|string',
        ]);

        User::create([
            'name'       => $request->name,
            'email'      => $request->email,
            'password'   => Hash::make($request->password),
            'role'       => $request->role,
            'department' => $request->department,
            'phone'      => $request->phone,
            'is_active'  => true,
        ]);

        return redirect()->route('admin.users')->with('success', 'User created successfully!');
    }

    public function toggleUser(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);
        return redirect()->route('admin.users')->with('success', 'User status updated!');
    }

    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email,' . $user->id,
            'role'       => 'required|in:admin,employee,accountant',
            'department' => 'nullable|string',
            'phone'      => 'nullable|string',
        ]);

        $data = [
            'name'       => $request->name,
            'email'      => $request->email,
            'role'       => $request->role,
            'department' => $request->department,
            'phone'      => $request->phone,
        ];

        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users')->with('success', 'User updated successfully!');
    }

    public function destroyUser(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users')->with('error', 'You cannot delete yourself!');
        }
        $user->delete();
        return redirect()->route('admin.users')->with('success', 'User deleted successfully!');
    }

    // ── Departments ───────────────────────────────────────────────
    public function departments()
    {
        $departments = Department::latest()->get();
        return view('admin.departments', compact('departments'));
    }

    public function storeDepartment(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|unique:departments',
            'code'        => 'required|string|unique:departments',
            'description' => 'nullable|string',
        ]);

        Department::create($request->all());
        return redirect()->route('admin.departments')->with('success', 'Department created!');
    }

    // ── All Requisitions ──────────────────────────────────────────
    public function requisitions(Request $request)
    {
        $query = StockRequisition::with(['user', 'department']);

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('item_name', 'like', '%'.$request->search.'%')
                  ->orWhere('reference_number', 'like', '%'.$request->search.'%')
                  ->orWhereHas('user', function($q) use ($request) {
                      $q->where('name', 'like', '%'.$request->search.'%');
                  });
            });
        }
        if ($request->status) {
            $query->where('status', $request->status);
        }
        if ($request->department_id) {
            $query->where('department_id', $request->department_id);
        }
        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $requisitions = $query->latest()->paginate(10);
        $departments  = Department::all();

        return view('admin.requisitions', compact('requisitions', 'departments'));
    }
}