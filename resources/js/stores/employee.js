import axios, { formToJSON } from "axios";
import { defineStore } from "pinia";
import { useAuthStore } from "./auth";
import { toast } from "vue3-toastify";

export const useEmployeeStore = defineStore('employeeStore', {
    state: () => {
        return {
            errors: {},
            employee: {}
        }
    },
    getters: {

    },
    actions: {

        async getAllEmployees() {
            if (!localStorage.getItem("token")) {
                toast.error("Must be logged in");
                return [];
            }

            try {
                const res = await fetch('/api/employee', {
                    headers: {
                        Authorization: `Bearer ${localStorage.getItem('token')}`,
                        Accept: 'application/json'
                    }
                });

                const data = await res.json();

                if (!res.ok) {
                    toast.error(data.message || 'Failed to fetch employees');
                    return [];
                }

                return data;

            } catch (error) {
                console.error("Error fetching employees:", error);
                toast.error("Network error. Please try again.");
                return [];
            }
        },
            
        async createEmployee(formData) {
            if (!localStorage.getItem("token")) {
                toast.error("Unauthorized: No user found");
                return;
            }

            try {
                console.log("create employee", formData);

                const res = await fetch('/api/create', {
                    method: 'POST',
                    headers: {
                        Authorization: `Bearer ${localStorage.getItem('token')}`,
                        Accept: 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(formData)
                });

                const data = await res.json();

                if (!res.ok) {
                    this.error = data.errors;
                    const message = data.message || 'Failed to create employee';
                    toast.error(message);
                    return;
                }

                toast.success('Employee created successfully');
                setTimeout(() => {
                    this.router.push('/employees');
                }, 3000)

            } catch (error) {
                console.error("Error creating employee:", error);
             }
        },
            
        async getEmployee(employee) {
            try {
                const res = await fetch(`/api/employee/${employee}`, {
                    headers: {
                        Authorization: `Bearer ${localStorage.getItem('token')}`,
                        Accept: 'application/json',
                        'Content-Type': 'application/json'
                    }
                });
                const data = await res.json();
                console.log("sdf",data.data.user)
                return data.data;
            } catch (error) {
                console.log(error)
            }
        },

        async editEmployee(employee, formData) {
            if (!localStorage.getItem("token")) {
                toast.error("Unauthorized: No token found");
                return;
            }

            try {
                console.log('works', employee.id, formData);

                const res = await fetch(`/api/employee/${employee.id}`, {
                    method: 'PUT',
                    headers: {
                        Authorization: `Bearer ${localStorage.getItem('token')}`,
                        Accept: 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(formData)
                });

                const data = await res.json();


                if (!res.ok) {
                    const message = data.message || 'Failed to update employee';
                    toast.error(message);
                    return;
                }
                
                toast.success('Employee updated successfully');

                setTimeout(() => {
                    this.router.push('/employees');
                }, 3000)

            } catch (error) {
                console.error('Error updating employee:', error);
                toast.error('Network error. Please try again.');
            }
        },
        
        async deleteEmployee(employee) {
            if (!localStorage.getItem("token")) {
                toast.error("Unauthorized: No token found");
                return;
            }

            try {
                const res = await fetch(`/api/employee/${employee.user_id}`, {
                    method: 'DELETE',
                    headers: {
                        Authorization: `Bearer ${localStorage.getItem('token')}`,
                        Accept: 'application/json'
                    }
                });

                const data = await res.json();

                if (!res.ok) {
                    const message = data.message || 'Failed to delete employee';
                    toast.error(message);
                    return;
                }

                toast.success('Employee deleted successfully');
                setTimeout(() => {
                    location.reload();
                }, 3000)


            } catch (error) {
                console.error("Error deleting employee:", error);
                toast.error('Network error. Please try again.');
            }
        }
            
    }
});