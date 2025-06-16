import { defineStore } from "pinia";
import { toast } from "vue3-toastify";

export const useAuthStore = defineStore('authStore', {
    state: () => {
        return {
            user: {},
            errors: {}
        }
    },
    getters: {

    },
    actions: {

        async getUser() {
            if (localStorage.getItem("token")) {
                const res = await fetch(`/api/user/`, {
                    headers: {
                        authorization: `Bearer ${localStorage.getItem("token")}`
                    }
                });
                const data = await res.json()
                if (res.ok) {
                    this.user = data
                }
            }
        },

        async authenticate(apiRoute, formData) {
            try {
                const res = await fetch(`/api/auth/${apiRoute}`, {
                    method: "POST",
                    headers: {
                        Accept: 'application/json',
                        "Content-Type": 'application/json'
                    },
                    body: JSON.stringify(formData)
                })

                const data = await res.json()

                if (!res.ok) {
                    // this.errors = data.errors || { general: ['Login failed'] }
                    toast.error("Login failed!")
                    return
                }

                localStorage.setItem('token', data.token.plainTextToken)
                localStorage.setItem('role', data.role)
                localStorage.setItem('user', data.user.id)
                // console.log(data.user);
                this.user = data.user

                toast.success(`${data.user.name} Login successful!`);

                setTimeout(() => {
                    this.router.push('/dashboard')
                    location.reload();

                }, 3000)
            } catch (err) {
                console.error(err)
                toast.error("Network error")
            }
          },

        async logout() {
            if (localStorage.getItem('token')) {
                try {
                    const res = await fetch('/api/auth/logout', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            authorization: `Bearer ${localStorage.getItem('token')}`
                        }
                    });
                    
                    const data = await res.json();
                    
                    if (res.ok) {
                        this.user = null
                        this.errors = {}
                        localStorage.removeItem("token")
                        localStorage.removeItem("role")
                        localStorage.removeItem("user")
                        toast.success("Logged out successfully!")
                        setTimeout(() => {
                            this.router.push('/auth/login')
                            location.reload();

                        }, 3000)
                    } else {
                        toast.error(data.message || "Logout failed.")
                    }
                } catch (error) {
                    toast.error("Network error. Please try again.")
                    console.error(error)
                }
            }
        }
        
    }
});
