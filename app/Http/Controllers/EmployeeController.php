<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employees = Employee::where('user_id', Auth::id())->orderBy('name')->get();
        return view('employees.index', compact('employees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('employees.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'phone' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $validated['user_id'] = Auth::id();

        Employee::create($validated);

        return redirect()->route('employees.index')->with('success', 'Сотрудник успешно добавлен');
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {
        // Проверка доступа
        if ($employee->user_id !== Auth::id()) {
            abort(403);
        }

        return view('employees.show', compact('employee'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employee $employee)
    {
        // Проверка доступа
        if ($employee->user_id !== Auth::id()) {
            abort(403);
        }

        return view('employees.edit', compact('employee'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employee $employee)
    {
        // Проверка доступа
        if ($employee->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'phone' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $employee->update($validated);

        return redirect()->route('employees.index')->with('success', 'Сотрудник успешно обновлен');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        // Проверка доступа
        if ($employee->user_id !== Auth::id()) {
            abort(403);
        }

        $employee->delete();

        return redirect()->route('employees.index')->with('success', 'Сотрудник успешно удален');
    }

    /**
     * Get employees as JSON for dropdowns
     */
    public function getEmployees()
    {
        $employees = Employee::where('user_id', Auth::id())->orderBy('name')->get();
        return response()->json($employees);
    }
}
