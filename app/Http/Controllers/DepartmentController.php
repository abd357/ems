<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DepartmentController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth:sanctum',
            new Middleware('role:admin', only: ['update', 'store', 'show'])
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user_id = Auth::user()->id;

        if (Auth::user()) {

            try {

                if (Auth::user()->hasRole('admin')) {
                    $departments = Department::with('user')->get();
                    
                    return response()->json([
                        'data' => $departments,
                        'status' => 1,
                        "message" => "All managers are listed."
                    ], 200);
                    
                } elseif (Auth::user()->hasRole('manager') || Auth::user()->hasRole('employee')) {
                    
                    $department = Department::whereHas('user.department', function ($q) {
                        $q->where('department_id', Auth::user()->department_id);
                    })->get();

                    return response()->json([
                        'data' => $department,
                        'status' => 1,
                        "message" => "All departments are listed."
                    ], 200);
                }
            } catch (\Throwable $th) {

                return response()->json([
                    'status' => 0,
                    'message' => 'Something went wrong.'
                ]);
            }
        } else {

            return response()->json([
                'status' => 0,
                'message' => 'You must be logged in.'
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'name' => 'required',
            'code' => 'required',
            'description' => 'required' 
        ]);

        $department = Department::create($validate);
        return response()->json([
            'data' => $department,
            'status' => 1,
            'message' => 'Department Created'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Department $department)
    {
        $data = $department->where('id', $department->id)->get();

        return response()->json([
            'data' => $data,
            'status' => 1,
            'message' => 'Department Fetched'
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Department $department)
    {
        $validator = $request->validate([
            'name' => 'max:50',
            'code' => "unique:departments,code, $department->id",
            'description' => 'max:50'
        ]);

        $department = Department::findOrFail($department->id);
        $department->update([
            'name' => $request->name,
            'code' => $request->code,
            'description' => $request->description,
        ]);
        return response()->json([
            'data' => $department,
            'status' => 1,
            'message' => 'Department Updated'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Department $department)
    {
        $department->delete();

        return response()->json([
            'message' => 'Department Deleted'
        ]);
    }
}
