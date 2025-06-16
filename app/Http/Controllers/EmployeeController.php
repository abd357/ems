<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use App\Models\EmployeeData;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class EmployeeController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth:sanctum',
            // new Middleware('role:admin|manager', only:['index']),
            new Middleware('role:admin', only:['destroy'])
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user_id = Auth::user()->id;
        $department_id = Auth::user()->department_id;

        if (Auth::user()) {
            
            try {
                
                if (Auth::user()->hasRole('admin')) {
                    
                    $employees = EmployeeData::with('user')->get();
                    
                    return response()->json([
                        'data' => $employees,
                        'status' => 1,
                        "message" => "All managers are listed."
                    ], 200);

                } elseif (Auth::user()->hasRole('manager')) {
                    
                    $employees = EmployeeData::whereHas('user.department', function($q){
                        $q->where('department_id', Auth::user()->department_id);
                    })->get();
                    
                    return response()->json([
                        'data' => $employees,
                        'status' => 1,
                        "message" => "All employees are listed."
                    ], 200);
                
                } elseif (Auth::user()->hasRole('employee')) {
                
                    $employee = EmployeeData::where('user_id', $user_id)->with('user')->get();

                    return response()->json([
                        'data' => $employee,
                        'status' => 1,
                        "message" => "Employee is listed."
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(EmployeeData $employee)
    {
        if(Auth::user()->hasRole('manager')) {

            try {
                $employee = EmployeeData::whereHas('user.department', function($q){
                    $q->where('department_id', Auth::user()->department_id);
                })->first();
                
                return response()->json([
                    'data' => $employee,
                    'status' => 1,
                    'message' => "Employee data"
                ]);
            } catch (\Throwable $th) {
                return response()->json([
                    'message' => 'Action not allowed!'
                ]);
            }
        } elseif (Auth::user()->hasRole('admin')) {
            return response()->json([
                'data' => $employee,
                'status' => 1,
                'message' => 'Employee fetched'
            ]);
        } elseif (Auth::user()->hasRole('employee') ) {
            try {
                $employee = EmployeeData::where('user_id', Auth::user()->id)->first();

                return response()->json([
                    'data' => $employee,
                    'status' => 1,
                    'message' => "Employee data"
                ]);
            } catch (\Throwable $th) {
                return response()->json([
                    'message' => 'Action not allowed!'
                ]);
            }
        }
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
    public function update(Request $request, EmployeeData $employee, User $user)
    {

        $id = $employee->user->id;

        $credentials = $request->validate([
            'name' => 'string|max:255',
            'email' => "email|unique:users,email,$id", // Ignore current user's email
            'department_id' => 'exists:departments,id',
            'phone' => 'nullable|string|max:20',
            'joining_date' => 'date',
            'role' => 'in:admin,employee,manager',
            'password' => 'nullable|min:3|confirmed',
        ]);

        if ($request->joining_date) {
            $credentials['joining_date'] = date('Y-m-d', strtotime($credentials['joining_date']));
        };

        $user = User::findOrFail($employee->user->id);
        $user->update([

            'name' => $request->name,
            'email' => $request->email,
            'department_id' => $request->department_id,
            'password' => $request->filled('password') ? bcrypt($request->password) : $user->password,
        
        ]);

        if($request->role = 'employee') {
            $user->employeeData()->update([
                'phone' => $request->phone,
                'joining_date' => $request->joining_date
            ]);
        }

        $data = $user->load('employeeData');

        return response()->json([
            'data' => $data,
            'status' => 1,
            'message' => 'Updated'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $user = User::find($id);
            if($user) {
                $user->employeeData()->delete();
                $user->delete();
                return response()->json([
                    "status" => 1,
                    'message' => 'Employee has been deleted'
                ]);
            } else {
                return response()->json([
                    "status" => 0,
                    "message" => "User not found"
                ]);
            }
        } catch(\Exception $e) {
            return response()->json([
                'status' => 0,
                'message' => $e->getMessage()
            ]);
        }
    }
}
