<script setup>
import { onMounted, ref } from 'vue';
import { useAuthStore } from '../../stores/auth';
import { useEmployeeStore } from '../../stores/employee';
import { storeToRefs } from 'pinia';
import { useRoute } from 'vue-router';

const { getAllEmployees, deleteEmployee, getEmployee } = useEmployeeStore();
const route = useRoute()
const employees  = ref([])
const employee = ref(null)
const role = localStorage.getItem('role')

onMounted( async() => (employees.value = await getAllEmployees()), console.log('') ,async() => (employee.value = await getEmployee(route.params.id)));
</script>

<template>

    <div class="flex justify-center mt-4">

        <div class="w-3/4 text-center">

            <div class="relative">
                <router-link v-if="role === 'admin'" :to="{name:'create-employee'}">
                    <p class="absolute top-3 right-6">
                        Add an Employee
                    </p>
                    <span class="text-3xl absolute top-1 right-0">&#43;</span>
                </router-link>
            </div>

            <p v-if="role === 'admin'" class="text-3xl text-gray-500 dark:text-gray-300 mb-4"> Admin Dashboard </p>
            <p v-if="role === 'manager'" class="text-3xl text-gray-500 dark:text-gray-300 mb-4"> Manager Dashboard </p>
            <p v-if="role === 'employee'" class="text-3xl text-gray-500 dark:text-gray-300 mb-4"> Employee Dashboard </p>
            
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
                    <tr v-for="(employee, index) in employees.data">
                        <td class="text-black text-lg">{{ index + 1 }}</td>
                        <td class="text-black text-lg">{{ employee.user.name }}</td>
                        <td class="text-black text-lg">{{ employee.user.email }}</td>
                        <td class="text-black text-lg">{{ employee.user.department?.code }}</td>
                        <td class="text-black text-lg">{{ employee.user.department?.name }}</td>
                        <td class="text-black text-lg">{{ employee.phone }}</td>
                        <td class="text-black text-lg">{{ employee.joining_date }}</td>
                        <td class="flex gap-2 text-lg">

                            <router-link class="text-blue-500 hover:underline hover:cursor-pointer"
                                :to="{name:'get-employee', params: {id:employee.id}}">
                                Edit
                            </router-link>
                            <form v-if="role === 'admin'" @submit.prevent="deleteEmployee(employee)">
                                <button class="text-red-500 hover:underline hover:cursor-pointer">Delete</button>
                            </form>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</template>