<script setup>
import { useRoute } from 'vue-router';
import { useManagerStore } from '../../stores/manager';
import { useDepartmentStore } from '../../stores/deparment';
import { ref, reactive, onMounted } from 'vue';

const role = localStorage.getItem('role');
const route = useRoute();
const { editManager, getManager } = useManagerStore();
const { getAllDepartments } = useDepartmentStore();
const departments = ref(null)
const manager = ref(null)

const formData = reactive({
    name: "",
    email: "",
    department_id: "",
    password: "",
    password_confirmation: "",
    role: "manager",
    phone: "",
    joining_date: "",
});

onMounted(async () => {
    const item = await getAllDepartments();
    departments.value = item;


    const data = await getManager(route.params.id);
    manager.value = data;
    formData.name = data.user.name,
    formData.email = data.user.email,
    formData.phone = data.phone,
    formData.department_id = data.user.department_id,
    formData.joining_date = data.joining_date
})
</script>
<template>

    <div class="flex justify-center mt-4">

        <div class="w-3/4 text-center">

            <p class="text-3xl text-gray-500 dark:text-gray-300 mb-4"> Edit Manager </p>

            <form @submit.prevent="editManager(manager, formData)" class="space-y-2 w-1/2 mx-auto">
                <div>
                    <input type="text" name="name" v-model="formData.name" placeholder="Name">
                </div>
                <div>
                    <input type="email" name="email" v-model="formData.email" placeholder="Email">
                    <!-- <p v-if="errors.email" class="error">{{ errors.email[0] }}</p> -->
                </div>
                <div>
                    <select v-if="role === 'admin'" name="role" id="role" v-model="formData.role">
                        <option value='employee'>Employee</option>
                        <option value='manager'>Manager</option>
                    </select>
                </div>
                <div>
                    <select v-if="role === 'admin'" name="department" id="department" v-model="formData.department_id">
                        <option :value=item.id v-for="item in departments">{{ item.name }} </option>
                    </select>

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
                    <label class="absolute top-[15%] pl-2 text-slate-500 font-medium" for=" joining_date">Joining
                        Date</label>
                    <input class="pl-28 focus:pl-2 focus:relative focus:z-10" type="date" name="joining_date"
                        v-model="formData.joining_date">
                </div>
                <div>
                    <button class="primary-btn">Update</button>
                </div>
            </form>
        </div>
    </div>

</template>