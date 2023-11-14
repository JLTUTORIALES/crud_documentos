<template>
<button class="btn btn-primary" @click="goBack">Volver</button>
</template>
<script setup>
    import { useRouter } from 'vue-router';
    const router = useRouter()

    const goBack = () => {
        router.back()
    }
    
</script>