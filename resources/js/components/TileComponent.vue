<script setup>
import { ref, onMounted } from 'vue'
import { VueSpinner } from 'vue3-spinners'
import axios from 'axios'

const props = defineProps({
  path: String,
  auth_user: Object
})

const data = ref({})
const loading = ref(false)

const getData = async () => {
  loading.value = true
  try {
    const response = await axios.post(`http://127.0.0.1:8000/api/${props.path}`)
    data.value = response.data
  } catch (error) {
    console.error(error)
  } finally {
    loading.value = false
  }
}

onMounted(getData)
</script>

<template>
    <VueSpinner size="20" color="blue" :class="'absolute top-50 left-50 hourgalss z-50'" v-show="loading" />

    <div class="card rounded-lg bg-gray-300 dark:bg-gray-300 flex items-center justify-center" style="min-height: 7rem">
        <div class="card-body">
            <h5 class="card-title text-2xl text-gray-700">{{ data.message }}</h5>
            <h2 class="card-text text-2xl text-gray-700">
                {{ data.data }}
            </h2>
        </div>
    </div>

</template>