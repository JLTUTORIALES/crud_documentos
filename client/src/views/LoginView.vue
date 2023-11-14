<template>
    <h1 class="display-6">CRUD de documentos</h1>
    <div id="loginBox">
        <img alt="logo" src="logo.png" id="logo"/>
        <input type="text" v-model="user" id="username" class="form-control" placeholder="Username" required/>
        <input type="password" id="pwd" v-model="pwd" class="form-control" placeholder="Password" required/>

        <button type="button" class="btn btn-dark" @click="login">Login</button>

        <div class="alert alert-danger" role="alert" v-if="error.trim() != ''">
            {{error}}
        </div>
    </div>
</template>
<script setup>
    import axios from 'axios';
    import { inject, onMounted, ref } from 'vue'
    import { useRouter } from 'vue-router'

    onMounted(() => {
        localStorage.clear()
    })

    const state = inject('state')
    const router = useRouter()

    const user = ref('')
    const pwd = ref('')
    const error = ref('')

    const login = async () => {
        error.value = ''

        if (user.value.trim() == '' || pwd.value.trim() == ''){
            error.value = 'Por favor, ingrese usuario y contraseña'
        }else{
            const peticion = await axios.post(`${state.api_base}/auth`, {
                user: user.value,
                pwd: pwd.value
            })

            if (peticion.data.data == null){
                error.value = 'Usuario o contraseña incorrectos'
            }else{
                localStorage.setItem('token', peticion.data.data.token)
                localStorage.setItem('data', JSON.stringify(peticion.data.data))
                router.push('/documents')
            }

        }
    }
</script>
<style scoped>
h1{
    margin-bottom: 50px;
}
#loginBox{
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    background-color: #f5f5f5;
    height: 60%;
    width: 60%;
    padding: 15px;
    border-radius: 25px;
}

#loginBox *{
    margin: 10px;
}

#loginBox #logo{
    width: 20%;
}
</style>