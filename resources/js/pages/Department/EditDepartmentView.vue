<script setup>
import { computed, onMounted, reactive, ref } from 'vue';
import { useEmployeeStore } from '../../stores/employee';
import { useDepartmentStore } from '../../stores/deparment';
import { storeToRefs } from 'pinia';
import { useAuthStore } from '../../stores/auth'
import { useRoute } from 'vue-router';

const route = useRoute();
const { getDepartment, editDepartment } = useDepartmentStore();
const department = ref(null)

const formData = reactive({
    name: "",
    code: "",
    description: "",
});


onMounted(async () => {
    const deparmentData = await getDepartment(route.params.id);
    department.value = deparmentData

    formData.name = department.value[0].name 
    formData.code = department.value[0].code 
    formData.description = department.value[0].description 
})


</script>
<template>
    <div class="flex justify-center mt-4">

        <div class="w-3/4 text-center">

            <p class="text-3xl text-gray-500 dark:text-gray-300 mb-4"> Edit Department </p>

            <form @submit.prevent="editDepartment(department, formData)" class="space-y-2 w-1/2 mx-auto">
                <div>
                    <input type="text" name="name" v-model="formData.name" placeholder="Name">
                </div>
                <div>
                    <input type="text" name="code" v-model="formData.code" placeholder="Code">
                    <!-- <p v-if="errors.email" class="error">{{ errors.email[0] }}</p> -->
                </div>
                <div>
                    <textarea type="text" name="description" v-model="formData.description" placeholder="Description"></textarea>
                    <!-- <p v-if="errors.email" class="error">{{ errors.email[0] }}</p> -->
                </div>
                <div>
                    <button class="primary-btn">Update</button>
                </div>
            </form>
        </div>
    </div>
</template>