<script setup>

import { onMounted, ref } from 'vue';
import Header from '../../layouts/Header.vue';
import {useManagerStore} from '../../stores/manager';
import { useRoute } from 'vue-router';
import { useAuthStore } from '../../stores/auth';
import { storeToRefs } from 'pinia';

const { getUser } = useAuthStore();
const {getAllManagers, deleteManager, getManager} = useManagerStore();

const {user} = storeToRefs(useAuthStore());
const managers = ref(null);
const manager = ref(null);
const route = useRoute();
const id = localStorage.getItem('user');
const role = localStorage.getItem('role');

async() => {
    const data = await getManager(route.params.id);
    manager.value = data
}

onMounted(
    async () => {
        const items = await getAllManagers();
        managers.value = items;
})
</script>

<template>

    <div class="flex justify-center mt-4">

        <div class="w-3/4 text-center">

            <div class="relative">
                <router-link v-if="role === 'admin'" :to="{name:'create-manager'}">
                    <p class="absolute top-3 right-6">
                        Add a Manager
                    </p>
                    <span class="text-3xl absolute top-1 right-0">&#43;</span>
                </router-link>
            </div>

            <Header />

            <table class="w-full">
                <thead>
                    <tr>
                        <th class="text-xl">Id</th>
                        <th class="text-xl">Name</th>
                        <th class="text-xl">Email</th>
                        <th class="text-xl">Dept. Code</th>
                        <th class="text-xl">Dept. Name</th>
                        <th class="text-xl">Phone</th>
                        <th class="text-xl">Joining_date</th>
                        <th class="text-xl">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="manager in managers">
                        <td class="text-black text-lg">{{ manager.id }}</td>
                        <td class="text-black text-lg">{{ manager.user.name }}</td>
                        <td class="text-black text-lg">{{ manager.user.email }}</td>
                        <td class="text-black text-lg">{{ manager.user.department.code }}</td>
                        <td class="text-black text-lg">{{ manager.user.department.name }}</td>
                        <td class="text-black text-lg">{{ manager.phone }}</td>
                        <td class="text-black text-lg">{{ manager.joining_date }}</td>
                        <td class="flex gap-2 text-lg">
                            <router-link class="text-blue-500 hover:underline hover:cursor-pointer"
                                :to="{ name: 'get-manager', params: { id: manager.id } }">
                                Edit
                            </router-link>
                            <form v-if="role=== 'admin'" @submit.prevent="deleteManager(manager)">
                                <button class="text-red-500 hover:underline hover:cursor-pointer">Delete</button>
                            </form>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</template>