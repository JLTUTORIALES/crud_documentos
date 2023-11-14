<template>
    <div class="table">
        <button type="button" class="btn btn-primary" id="createButton" @click="change('/create')">Crear documento</button>
        <table id="documents-table" class="table">
            <thead>
                <tr class="table-dark">
                    <td scope="col">ID</td>
                    <td scope="col">Código</td>
                    <td scope="col">Nombre</td>
                    <td scope="col">Tipo</td>
                    <td scope="col">Proceso</td>
                    <td scope="col"></td>
                    <td scope="col"></td>
                    <td scope="col"></td>
                </tr>
            </thead>
            <tbody>
                <tr v-for="row in table" :key="row.id">
                    <td>{{ row.id }}</td>
                    <td>{{ row.code }}</td>
                    <td>{{ row.name }}</td>
                    <td>{{ row.type.tip_name }}</td>
                    <td>{{ row.process.pro_name }}</td>

                    <td>
                        <button class="btn btn-primary" @click="change('/view', row)" data-toggle="modal" data-target="#exampleModalScrollable"><i class="fa-solid fa-file"></i></button>
                    </td>
                    <td>
                        <button class="btn btn-success" @click="change('/edit', row)"><i class="fa-solid fa-pen"></i></button>
                    </td>
                    <td>
                        <button class="btn btn-danger" @click="deleteDocument(row.id)"><i class="fa-solid fa-trash"></i></button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

</template>
<script setup>
import { inject, onMounted, ref } from 'vue';
import axios from 'axios'
import { useRouter } from 'vue-router';
import { deleteDocument as _deleteDocument, checkSession } from '@/helpers';

const router = useRouter()
const table = ref([])
const state = inject('state')
onMounted(async () => {
    // eslint-disable-next-line no-undef
    /*$(document).ready(() => {
        // eslint-disable-next-line no-undef
        $('#documents-table').DataTable()
    })*/
    const documents = await axios.get(`${state.api_base}/documents`, {
        headers: {
            Authorization: `Bearer ${localStorage.getItem('token')}`
        }
    })

    checkSession(documents, router)

    table.value = documents.data

})

const deleteDocument = async (id) => {
    const data = await _deleteDocument(id)
    
    if (data != null){
        checkSession(data, router)
        table.value = data.data
    }

}

const change = (route, document) => {
    state.current_document = document
    router.push(route)
}

</script>
<style scoped>
.table {
    width: 80%;
}

#createButton {
    margin-bottom: 10px;
}

.table table {
    width: 100%;
}
</style>
