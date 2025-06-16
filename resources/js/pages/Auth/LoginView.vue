<script setup>
import { useAuthStore } from '../../stores/auth';
import { storeToRefs } from 'pinia';
import { onMounted, reactive } from 'vue';

const { errors } = storeToRefs(useAuthStore());
const { authenticate } = useAuthStore();
const formData = reactive({
    'email': "",
    'password': "",
})

onMounted(() => { errors.value = {} })


</script>

<template>
    <main class="flex flex-col min-h-screen justify-center">
        <h1 class="title dark:text-gray-200">Login Here!</h1>

        <form @submit.prevent="authenticate('login', formData)" class="space-y-2 w-1/2 mx-auto">
            <div>
                <input type="email" name="email" placeholder="Email" v-model="formData.email">
                <p v-if="errors.email" class="error">{{ errors.email[0] }}</p>
            </div>
            <div>
                <input type="password" name="password" placeholder="Password" v-model="formData.password">
                <p v-if="errors.password" class="error">{{ errors.password[0] }}</p>
            </div>
            <div>
                <button class="primary-btn">Login</button>
            </div>
        </form>
    </main>
</template>
