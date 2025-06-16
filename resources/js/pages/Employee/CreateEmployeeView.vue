<script setup>
import { onMounted, reactive, ref } from 'vue';
import { useEmployeeStore } from '../../stores/employee';
import { useDepartmentStore } from '../../stores/deparment';
import { storeToRefs } from 'pinia';

const { createEmployee } = useEmployeeStore();
const { getAllDepartments } = useDepartmentStore();
const { errors } = storeToRefs(useEmployeeStore());
const departments = ref(null)

const formData = reactive({
    'name' : "",
    'email' : "",
    'department_id': "0",
    'password' : "",
    'password_confirmation' : "",
    'role' : "employee",
    'phone' : "",
    'joining_date' : "",
});

onMounted(async() => {
    const data = await getAllDepartments();
    departments.value = data
})


</script>
<template>
    <div class="flex justify-center mt-4">

        <div class="w-3/4 text-center">

            <p class="text-3xl text-gray-500 dark:text-gray-300 mb-4"> Create an Employee </p>

            <form @submit.prevent="createEmployee(formData)" class="space-y-2 w-1/2 mx-auto">
                <div>
                    <input type="text" name="name" v-model="formData.name" placeholder="Name">
                </div>
                <div>
                    <input type="email" name="email" v-model="formData.email" placeholder="Email">
                    <p v-if="errors.email" class="error">{{ errors }}</p>
                </div>
                <div>
                    <select name="role" id="role" v-model="formData.role">
                        <option value='employee'>Employee</option>
                    </select>
                </div>
                <div>
                    <select name="department" id="department" v-model="formData.department_id">
                        <option value=0 disabled>Select Deparment</option>
                        <option v-for="department in departments" :value=department.id>{{ department.name }} </option>
                    </select>
                </div>
                <div>
                    <input type="tel" name="phone" v-model="formData.phone" placeholder="Phone">
                </div>
                <div>
                    <input type="password" name="password" v-model="formData.password" placeholder="Password">
                </div>
                <div>
                    <input type="password" name="password_confirmation" v-model="formData.password_confirmation"
                    placeholder="Confirm Password">
                </div>
                <div class="relative">
                    <label class="absolute top-[15%] pl-2 text-slate-500 font-medium z-10" for=" joining_date">Joining Date</label>
                    <input class="pl-28 focus:pl-2 focus:relative focus:z-10" type="date" name="joining_date" v-model="formData.joining_date">
                </div>
                <div>
                    <button class="primary-btn">Create</button>
                </div>
            </form>
        </div>
    </div>
</template>