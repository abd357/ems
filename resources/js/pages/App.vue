<script setup>

import Navbar from '../layouts/Navbar.vue';
import Header from '../layouts/Header.vue';
const token = localStorage.getItem('token')

</script>
<template>
    <header v-if="token">
        <Navbar />
    </header>

    <router-view />

</template>
