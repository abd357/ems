<script setup>

import { onMounted, reactive, ref } from 'vue';
import { useDepartmentStore } from '../../stores/deparment';

const {createDepartment} = useDepartmentStore();

const formData = reactive({

    'name': '',
    'code': '',
    'description': ''
})

</script>
<template>

    <div class="flex justify-center mt-4">

        <div class="w-3/4 text-center">

            <p class="text-3xl text-gray-500 dark:text-gray-300 mb-4"> Create a Department </p>

            <form @submit.prevent="createDepartment(formData)" class="space-y-2 w-1/2 mx-auto">
                <div>
                    <input type="text" name="name" v-model="formData.name" placeholder="Name">
                </div>
                <div>
                    <input type="text" name="code" v-model="formData.code" placeholder="Code">
                </div>
                <div>
                    <textarea name="description" v-model="formData.description" placeholder="Description"></textarea>
                </div>
                <div>
                    <button class="primary-btn">Create</button>
                </div>
            </form>
        </div>
    </div>


</template>