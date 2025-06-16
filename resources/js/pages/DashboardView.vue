<script setup>

import { onMounted } from 'vue';
import Header from '../layouts/Header.vue';
import TileComponent from '../components/TileComponent.vue';
import { storeToRefs } from 'pinia';
import { useEmployeeStore } from '../stores/employee';
import { useAuthStore } from '../stores/auth';

const role = localStorage.role

</script>
<template>
    <div class="flex justify-center mt-4">

        <div class="w-3/4 text-center">
            <Header />

            <div class="flex w-full justify-center gap-x-8" v-if="role !== 'employee'">
                <div class="w-1/4">
                    <tile-component :path="'employee-count'"></tile-component>
                </div>
                <div class="w-1/4" v-if="role === 'admin'">
                    <tile-component :path="'manager-count'"></tile-component>
                </div>
                <div class="w-1/4">
                    <tile-component :path="'department-count'"></tile-component>
                </div>
            </div>
            
        </div>
    </div>

</template>