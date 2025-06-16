import axios from "axios";
import { defineStore } from "pinia";
import { toast } from "vue3-toastify";


export const useDepartmentStore = defineStore('departmentStore', {
    state: () => {
        return {
            // departments: {},
            errors: {}
        }
    },
    getters: {

    },
    actions: {
        

        async getAllDepartments() {
            if (localStorage.getItem('token')) {
                
                try {
                    const res = await fetch('/api/department', {
                        headers: {
                            Authorization: `Bearer ${localStorage.getItem('token')}`,
                            Accept: 'application/json'
                        }
                    });
                    
                    if (!res.ok) {
                        const errorData = await res.json();
                        throw new Error(errorData.message || 'Failed to fetch departments');
                    }
                    
                    const data = await res.json();
                    return data.data;
                } catch (error) {
                    toast.error("Error fetching departments:", error);
                    return [];
                }
            } else {
                toast.error("You are not logged in!");
            }
        },
        

        async createDepartment(formData) {
            try {
                const res = await fetch('/api/department/', {
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
                    const message = data.message || 'Failed to create department';
                    toast.error(`${message}`);
                    return;
                }

                toast.success("Department created successfully!");
                setTimeout(() => {
                    this.router.push('/departments');

                },3000)

            } catch (error) {
                console.error("Error creating department:", error);
                toast.error("Network error. Please try again.");
            }
        },
            
        async editDepartment(department, formData) {
            try {
                const res = await fetch(`/api/department/${department[0].id}`, {
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
                    const message = data.message || 'Failed to update department';
                    toast.error(message);
                    return;
                }

                toast.success('Department updated successfully');
                setTimeout(() => {
                    this.router.push('/departments');
                }, 2000);

            } catch (error) {
                console.error('Edit department error:', error);
                toast.error('Network error. Please try again.');
            }
        },
        
        async getDepartment(department) {
            try {
                
                const res = await fetch(`/api/department/${department}`, {
                    headers: {
                        Authorization: `Bearer ${localStorage.getItem('token')}`,
                        Accept: 'application/json'
                    }
                });
                const data = await res.json();

                if(!res.ok) {
                    const message = data.message || 'Failed to get the department';
                    toast.error(message);
                    return;
                }

                return data.data;

            } catch (error) {
                console.error('Fetch department error:', error);
                toast.error('Network error. Please try again.');
            }

        },

        async deleteDepartment(department) {
            if (!localStorage.getItem('token')) {
                toast.error("Unauthorized: No token found.");
                return;
            }

            try {
                const res = await fetch(`/api/department/${department.id}`, {
                    method: 'DELETE',
                    headers: {
                        Authorization: `Bearer ${localStorage.getItem('token')}`,
                        Accept: 'application/json'
                    }
                });

                const data = await res.json();

                if (!res.ok) {
                    const message = data.message || 'Failed to delete department';
                    toast.error(`${message}`);
                    return;
                }

                toast.success("Department deleted successfully!");

                setTimeout(() => {
                    location.reload();
                }, 2000);

            } catch (error) {
                console.error("Delete error:", error);
                toast.error("Network error. Please try again.");
            }
        }
    }
});