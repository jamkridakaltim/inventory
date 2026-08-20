<template>
    <div class="container mt-4">
        <h1 class="text-center mb-4">Locations</h1>
        <div v-if="locations.length === 0" class="text-center text-muted">No locations found.</div>
        <div v-else class="row">
            <div v-for="location in locations" :key="location.id" class="col-md-4 mb-3">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">{{ location.name }}</h5>
                        <p class="card-text">{{ location.description }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

const API_BASE_URL = import.meta.env.VITE_API_BASE_URL || 'http://localhost:8000'

const locations = ref([])

const getLocations = async () => {
    try {
        const response = await axios.get(`${API_BASE_URL}/api/locations`)
        locations.value = response.data.data || []
        console.log('Locations API Response:', response.data)
    } catch (error) {
        console.error('Locations API Error:', error)
    }
}

onMounted(() => {
    getLocations()
})
</script>