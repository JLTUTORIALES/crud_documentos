import { createApp } from 'vue'
import App from './App.vue'
import { createRouter, createWebHistory } from 'vue-router'
import LoginView from './views/LoginView.vue'
import DocumentsManager from './views/DocumentsManager.vue'
import DocumentsViewer from './views/DocumentsViewer.vue'
import { state } from './state'
import axios from 'axios'


const router = createRouter({
    history: createWebHistory(),
    routes: [
        { path: '/', component: LoginView, meta: { title: 'Login' } },
        { path: '/login', component: LoginView, meta: { title: 'Login' } },
        { path: '/documents', component: DocumentsManager, meta: { title: 'Documentos' } },
        { path: '/view', component: DocumentsViewer, props: { readonly : true }, meta: { title: 'Documentos' } },
        { path: '/edit', component: DocumentsViewer, props: { readonly : false }, meta: { title: 'Editar documento' } },
        { path: '/create', component: DocumentsViewer, props: { readonly : false }, meta: { title: 'Crear documento' } },
        //{ path: '*', component: ''}
    ]
})


axios.interceptors.response.use(undefined, (error) => {
    if (error.response.status == 403){
        localStorage.clear()
        location.href = '/'
    }
})

router.beforeEach((to, from, next) => {
    document.title = `${to.meta.title} - CRUD`;
    next();
});



const app = createApp(App)
app.provide('state', state)
app.use(router)
app.mount('#app')
