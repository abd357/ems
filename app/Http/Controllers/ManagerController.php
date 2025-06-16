<?php

namespace App\Http\Controllers;

use App\Models\EmployeeData;
use App\Models\ManagerData;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ManagerController extends Controller
 implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth:sanctum',
            new Middleware('role:admin|manager', only: ['update']),
            // new middleware('role:manager', only:['update']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user_id = Auth::user()->id;

        if(Auth::user()) {

            try {
            
                if(Auth::user()->hasRole('admin')) {   
            
                    $managers = ManagerData::with('user')->get();
                    
                    return response()->json([
                        'data' => $managers,
                        'status' => 1,
                        "message" => "All managers are listed."
                    ], 200);

                } elseif(Auth::user()->hasRole('manager')) {

                    $managers = ManagerData::where('user_id', $user_id)->with('user')->get();

                    return response()->json([
                        'data' => $managers,
                        'status' => 1,
                        "message" => "All managers are listed."
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
    public function show(ManagerData $manager)
    {
        $id = $manager->user->id;
        if(Auth::user()->hasRole('admin')) {
            
            return response()->json([
                'data' => $manager,
                'status' => 1,
                'message' => 'Manager Data'
                
            ]);
        } elseif (Auth::user()->hasRole('manager') && Auth::user()->id === $id) {
        
            $manager = ManagerData::whereHas('user', function ($q) {
                $q->where('user_id', Auth::user()->id);
            })->first();

            return response()->json([
                'data' => $manager,
                'status' => 1,
                'message' => 'Manager Data'
            ]);
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
    public function update(Request $request, ManagerData $manager)
    {

        $id = $manager->user->id;

        if(Auth::user()->hasRole('admin') || (Auth::user()->hasRole('manager') && Auth::user()->id == $id)) 
        {
            
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
            
            $user = User::findOrFail($manager->user->id);
            $user->update([
                
                'name' => $request->name,
                'email' => $request->email,
                'department_id' => $request->department_id,
                'password' => $request->filled('password') ? bcrypt($request->password) : $user->password,
                
            ]);

            if ($request->role = 'employee') {
                $user->employeeData()->update([
                    'phone' => $request->phone,
                    'joining_date' => $request->joining_date
                ]);
            }
            if ($request->role = 'manager') {
                $user->managerData()->update([
                    'phone' => $request->phone,
                    'joining_date' => $request->joining_date
                ]);
            }
            
            if (!$user->employeeData) {
                $data = $user->load('managerData');
            } else {
                $data = $user->load('employeeData');
            }
            
            return response()->json([
                'data' => $data,
                'status' => 1,
                'message' => 'Updated'
            ]);
            
        } else {
            
            return response()->json([
                'status' => 0,
                'message' => "You don't have the access to this action"
            ]);
        }
    }
    


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $user = User::find($id);
            if($user) {
                $user->managerData()->delete();
                $user->delete();
                return response()->json([
                    'status' => 1,
                    'message' => 'Manager has been deleted'
                ]);
            } else {
                return response()->json([
                    'status' => 1,
                    'meesage' => 'User not found' 
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'message' => $e->getMessage()
            ]);
        }
    }   
}
