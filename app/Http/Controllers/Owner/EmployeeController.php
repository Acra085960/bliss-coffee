<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = User::whereIn('role', ['penjual', 'manajer'])->get();
        return view('owner.employees', compact('employees'));
    }

    public function create()
    {
        return view('owner.create.employee');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:penjual,manajer',
            'password' => 'required|string|min:6',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
            'email_verified_at' => now(), 
        ]);

        return redirect()->route('owner.employees')->with('success', 'Employee created successfully.');
    }

    public function edit($id)
    {
        $employee = User::findOrFail($id);
        return view('owner.edit.employee', compact('employee'));
    }

    public function update(Request $request, $id)
    {
        $employee = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $employee->id,
            'role' => 'required|in:penjual,manajer',
            'password' => 'nullable|string|min:6',
        ]);

        $employee->name = $request->name;
        $employee->email = $request->email;
        $employee->role = $request->role;
        if ($request->filled('password')) {
            $employee->password = bcrypt($request->password);
        }
        $employee->save();

        return redirect()->route('owner.employees')->with('success', 'Employee updated successfully.');
    }

    public function destroy($id)
    {
        $employee = User::findOrFail($id);
        $employee->delete();

        return redirect()->route('owner.employees')->with('success', 'Employee deleted successfully.');
    }
}