<template>
    <nav class="dropdown">
        <span id="nav-title" class="fs-4">CRUD Documentos</span>
        <div class="bg-info rounded-circle" id="avatar" title="Click para cerrar sesión" @click="cerrarSesion">
            {{ nombre }}
        </div>
    </nav>
</template>
<script setup>
    import { ref } from 'vue'
    import { useRouter } from 'vue-router';

    const datos = JSON.parse(localStorage.getItem('data') || '{}')
    const router = useRouter()

    const iniciales = `${datos.name.split(' ')[0][0]}${datos.name.split(' ')[1][0]}`
    const nombre = ref(iniciales)

    const cerrarSesion = () => {
        localStorage.clear()
        router.push('/')
    }


</script>
<style scoped>
    nav{
        z-index: 100;
        margin-bottom: 25px;
        background-color: #f5f5f5;
        width: 100%;
        position: absolute;
        top: 0;
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
    }
    #nav-title{
        font-size: 18px;
        margin-left: 15px;
    }
    #avatar{
        padding: 20px;
        margin: 10px;
        width: 10px;
        height: 10px;
        display: flex;
        justify-content: center;
        align-items: center;
        cursor: pointer;
        font-weight: bold;
    }
</style>