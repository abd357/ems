import axios, { formToJSON } from "axios";
import { defineStore } from "pinia";
import { useAuthStore } from "./auth";
import { toast } from "vue3-toastify";

export const useManagerStore = defineStore('managerStore', {
    state: () => {
        return {
            // managers: {},
        }
    },
    getters: {

    },
    actions: {

        async getAllManagers() {
            if (!localStorage.getItem("token")) {
                toast.error("Must be logged in");
                return [];
            }

            try {
                const res = await fetch('/api/manager', {
                    headers: {
                        Authorization: `Bearer ${localStorage.getItem('token')}`,
                        Accept: 'application/json'
                    }
                });

                const data = await res.json();

                if (!res.ok) {
                    toast.error(data.message || 'Failed to fetch managers');
                    return [];
                }

                return data.data;

            } catch (error) {
                console.error("Error fetching managers:", error);
                toast.error("Network error. Please try again.");
                return [];
            }
        },

        async createManager(formData) {
            if (!localStorage.getItem("token")) {
                toast.error("Unauthorized: No token found");
                return;
            }

            try {

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
                    const message = data.message || 'Failed to create manager';
                    toast.error(message);
                    return;
                }

                toast.success('Manager created successfully');
                setTimeout(() => {
                    this.router.push('/managers');
                }, 3000)

            } catch (error) {
                console.error("Error creating manager:", error);
            }
        },

        async getManager(manager) {
            const res = await fetch(`/api/manager/${manager}`, {
                headers: {
                    Authorization: `Bearer ${localStorage.getItem('token')}`,
                    Accept: 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            const data = await res.json();
            return data.data
        },


        async editManager(manager, formData) {
            if (!localStorage.getItem("token")) {
                toast.error("Unauthorized: No token found");
                return;
            }

            try {

                const res = await fetch(`/api/manager/${manager.id}`, {
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
                    const message = data.message || 'Failed to update manager';
                    toast.error(message);
                    return;
                }

                toast.success('Manager updated successfully');

                setTimeout(() => {
                    this.router.push('/managers');
                }, 3000)

            } catch (error) {
                console.error('Error updating manager:', error);
                toast.error('Network error. Please try again.');
            }
        },


        async deleteManager(manager) {
            if (!localStorage.getItem("token")) {
                toast.error("Unauthorized: No token found");
                return;
            }

            try {
                const res = await fetch(`/api/manager/${manager.user_id}`, {
                    method: 'DELETE',
                    headers: {
                        Authorization: `Bearer ${localStorage.getItem('token')}`,
                        Accept: 'application/json'
                    }
                });

                const data = await res.json();

                if (!res.ok) {
                    const message = data.message || 'Failed to delete manager';
                    toast.error(message);
                    return;
                }

                toast.success('Manager deleted successfully');
                setTimeout(() => {
                    location.reload();
                }, 3000)


            } catch (error) {
                console.error("Error deleting manager:", error);
                toast.error('Network error. Please try again.');
            }
        },

    }
});