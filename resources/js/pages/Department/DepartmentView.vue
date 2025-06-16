<script setup>
import { onMounted, ref } from 'vue';
import Header from '../../layouts/Header.vue';
import { useDepartmentStore } from '../../stores/deparment';
import { useRoute } from 'vue-router';

const { getAllDepartments, getDepartment, deleteDepartment } = useDepartmentStore();
const route = useRoute();
const role = localStorage.getItem('role');
const departments = ref(null)
const department = ref(null)



onMounted(async () => {
    const items = await getAllDepartments();
    departments.value = items;
})

</script>
<template>

    <div class="flex justify-center mt-4">

        <div class="w-3/4 text-center">

            <div class="relative">
                <router-link v-if="role === 'admin'" :to="{ name: 'create-department' }">
                    <p class="absolute top-3 right-6">
                        Add Department
                    </p>
                    <span class="text-3xl absolute top-1 right-0">&#43;</span>
                </router-link>
            </div>

            <Header/>
            <table class="w-full">
                <thead>
                    <tr>
                        <th class="text-xl">Id</th>
                        <th class="text-xl">Name</th>
                        <th class="text-xl">Code</th>
                        <th class="text-xl">Description</th>
                        <th v-if="role === 'admin'" class="text-xl">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="department in departments">
                        <td class="text-black text-lg">{{ department.id }}</td>
                        <td class="text-black text-lg">{{ department.name }}</td>
                        <td class="text-black text-lg">{{ department.description }}</td>
                        <td class="text-black text-lg">{{ department.code }}</td>
                        <td v-if="role === 'admin'" class="flex gap-2 text-lg">

                            <router-link class="text-blue-500 hover:underline hover:cursor-pointer"
                                :to="{ name: 'edit-department', params: { id: department.id } }">
                                Edit
                            </router-link>
                            <form @submit.prevent="deleteDepartment(department)">
                                <button class="text-red-500 hover:underline hover:cursor-pointer">Delete</button>
                            </form>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>