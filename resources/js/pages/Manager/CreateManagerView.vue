<script setup>
import { onMounted, reactive, ref } from 'vue';
import { useManagerStore } from '../../stores/manager';
import { useDepartmentStore } from '../../stores/deparment';
import { storeToRefs } from 'pinia';

const { createManager } = useManagerStore();
const { getAllDepartments } = useDepartmentStore();
const  departments  = ref(null);
// const {}

const formData = reactive({
    'name' : "",
    'email' : "",
    'department_id': "0",
    'password' : "",
    'password_confirmation' : "",
    'role' : "manager",
    'phone' : "",
    'joining_date' : "",
});

onMounted(
    async () => {
        const data = await getAllDepartments();
        departments.value = data
    }
)


</script>
<template>
    <div class="flex justify-center mt-4">

        <div class="w-3/4 text-center">

            <p class="text-3xl text-gray-500 dark:text-gray-300 mb-4"> Create a Manager </p>

            <form @submit.prevent="createManager(formData)" class="space-y-2 w-1/2 mx-auto">
                <div>
                    <input type="text" name="name" v-model="formData.name" placeholder="Name">
                </div>
                <div>
                    <input type="email" name="email" v-model="formData.email" placeholder="Email">
                    <!-- <p v-if="errors.email" class="error">{{ errors.email[0] }}</p> -->
                </div>
                <div>
                    <select name="role" id="role" v-model="formData.role">
                        <option value='employee'>Employee</option>
                        <option value='manager'>Manager</option>
                    </select>
                    <!-- <input type="text" value="employee" name="role" v-model="formData.role" placeholder="Role"> -->
                </div>
                <div>
                    <select name="department" id="department" v-model="formData.department_id">
                        <option value=0 disabled>Select Deparment</option>
                        <option v-for="department in departments" :value=department.id>{{ department.name }} </option>
                    </select>
                    <!-- <input type="text" value="IT" name="department" v-model="formData.department_id" placeholder="Department"> -->
                </div>
                <div>
                    <input type="tel" name="phone" v-model="formData.phone" placeholder="Phone">
                </div>
                <div>
                    <input type="password" name="password" v-model="formData.password" placeholder="Password">
                    <!-- <p v-if="errors.password" class="error">{{ errors.password[0] }}</p> -->
                </div>
                <div>
                    <input type="password" name="password_confirmation" v-model="formData.password_confirmation"
                        placeholder="Confirm Password">
                </div>
                <div class="relative">
                    <label class="absolute top-[15%] pl-2 text-slate-500 font-medium z-10"
                        for=" joining_date">Joining Date</label>
                    <input class="pl-28 focus:pl-2 focus:relative focus:z-10" type="date" name="joining_date"
                        v-model="formData.joining_date">
                </div>
                <div>
                    <button class="primary-btn">Create</button>
                </div>
            </form>
        </div>
    </div>
</template>