<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\EmployeeData;
use App\Models\ManagerData;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    // public static function middleware(): array
    // {
    //     return [
    //         'auth:sanctum',
    //         new Middleware('role:admin')
    //     ];
    // }

    public function createEmployee(Request $request)
    {

        if(Auth::user() && Auth::user()->hasRole('admin')) {
            try {

                $credentials = $request->validate([
                    'name' => 'required',
                    'email' => 'required | email | unique:users,email',
                    'password' => 'required | min:4 | confirmed',
                    'role' => 'required',
                    'phone' => 'required_if:role,employee',
                    'joining_date' => 'required_if:role,employee',
                    'department_id' => 'required_if:role,employee,manager',
                ]);
                
                if($request->joining_date) {
                    $credentials['joining_date'] = date('Y-m-d', strtotime($credentials['joining_date']));
                }
                $user = User::create($credentials);
                $user->assignRole($request->role);
                
                if($request->role == 'employee') {
                    $user->employeeData()->create($credentials);        
                }
                
                if($request->role == 'manager') {
                    $user->managerData()->create($credentials);        
                }
                
                if(!$user->employeeData) {
                    $data = $user->load('managerData');                    
                } else {
                    $data = $user->load('employeeData');
                }
                
                return [
                    'data' => $data,
                    'status' => 1,
                    'message' => 'Created Successfully'
                ];
            } catch (Exception $e) {
                return [
                    'data' => [],
                    'status' => 0,
                    'message' => $e->getMessage()
                ];
            }

        } else {

            return response()->json([
                "message" => "Access Denied"
            ]);

        }
    }

    public function getEmployeeCount(EmployeeData $employees) 
    {
        $employees = EmployeeData::all()->count();

        return response()->json([
            'data' => $employees,
            'status' => 1,
            'message' => 'Number of employees'
        ]);
    }

    public function getManagerCount(ManagerData $managers) 
    {
        $managers = ManagerData::all()->count();

        return response()->json([
            'data' => $managers,
            'status' => 1,
            'message' => 'Number of managers'
        ]);
    }

    public function getDepartmentCount(Department $departments) 
    {
        $departments = Department::all()->count();

        return response()->json([
            'data' => $departments,
            'status' => 1,
            'message' => 'Number of departments'
        ]);
    }
}
