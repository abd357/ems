<script setup>
import { useRoute } from 'vue-router';
import { useAuthStore } from '../stores/auth';
import { computed, onMounted, reactive, ref, watch } from 'vue'
// const { logout } = useAuthStore();
const route = useRoute();
const authStore = useAuthStore();
const token = localStorage.getItem('token')
const role = localStorage.getItem('role')

</script>

<template>

    <nav v-if="(route.name) !== 'login'" class="nav">
        <router-link :to="{ name: 'dashboard' }" class="nav-link">Home</router-link>
        <div class="flex flex-row">

            <router-link :to="{ name: 'departments' }"
                class="nav-link text-gray-500 dark:text-gray-300">Departments</router-link>
            <router-link :to="{ name: 'employees' }"
                class="nav-link text-gray-500 dark:text-gray-300">Employees</router-link>
            <router-link :to="{ name: 'managers' }" v-if="role !== 'employee'"
                class="nav-link text-gray-500 dark:text-gray-300">Managers</router-link>
            <div class="flex">
                <form @submit.prevent="authStore.logout()">
                    <button class="nav-link">Logout</button>
                </form>
            </div>
        </div>
    </nav>
    <!-- <button @click="count++">Increment</button> -->
</template>