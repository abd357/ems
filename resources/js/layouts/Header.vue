<script setup>
import { useRoute } from 'vue-router';
import { useAuthStore } from '../stores/auth';
import { computed, onMounted, reactive, ref, watch } from 'vue'
// const { logout } = useAuthStore();
const route = useRoute();
const authStore = useAuthStore();
const role = localStorage.role;
const token = localStorage.getItem('token')

</script>

<template>

    <p v-if="role === 'admin'" class="text-3xl text-gray-500 dark:text-gray-300 mb-4"> Admin Dashboard </p>
    <p v-if="role === 'manager'" class="text-3xl text-gray-500 dark:text-gray-300 mb-4"> Manager Dashboard </p>
    <p v-if="role === 'employee'" class="text-3xl text-gray-500 dark:text-gray-300 mb-4"> Employee Dashboard </p>
    <!-- <button @click="count++">Increment</button> -->
</template>