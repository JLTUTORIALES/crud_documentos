<template>
    <NavBar />
    <form id="documento">
        <div class="input-group" v-if="editButtonLabel != 'Guardar'">
            <span class="input-group-text">Código</span>
            <input type="text" class="form-control" v-model="data.code" readonly>
        </div>
        <div class="input-group">
            <span class="input-group-text">Nombre</span>
            <input type="text" class="form-control" v-model="data.name" :readonly="props.readonly" maxlength="20">
        </div>
        <div class="input-group">
            <span class="input-group-text">Tipo de documento</span>
            <select type="text" class="form-select" :disabled="props.readonly" v-model="data.type.id">
                <option v-for="_type in document_types" :key="_type.id" :value="_type.id">{{ _type.tip_name }}</option>
            </select>
        </div>
        <div class="input-group">
            <span class="input-group-text">Tipo de proceso</span>
            <select type="text" class="form-select" :disabled="props.readonly" v-model="data.process.id">
                <option v-for="_process in document_process" :key="_process.id" :value="_process.id">{{ _process.pro_name }}
                </option>
            </select>
        </div>
        <div class="input-group">
            <span class="input-group-text">Contenido</span>
            <textarea type="text" class="form-control" rows="10" v-model="data.content"
                :readonly="props.readonly"></textarea>
        </div>
    </form>
    <div id="buttons-box">
        <GoBackButton />
        <button v-if="!props.readonly" class="btn btn-success" @click="editDocument">{{ editButtonLabel }}</button>
        <button v-if="!props.readonly && editButtonLabel != 'Guardar'" class="btn btn-danger" @click="deleteDocument">Eliminar</button>
    </div>
</template>
<script setup>

import NavBar from '@/components/NavBar.vue'
import GoBackButton from '@/components/GoBackButton.vue'
import { inject, defineProps, onMounted, ref } from 'vue'
import axios from 'axios';
import { deleteDocument as _deleteDocument, checkSession } from '@/helpers';
import { useRouter } from 'vue-router';

const props = defineProps(['readonly'])
const state = inject('state')
const data = state.current_document || { id: 0, code: '', name: '', type: [], process: [] }
const router = useRouter()

const editButtonLabel = data.id == 0 ? 'Guardar' : 'Editar'

const document_types = ref([])
const document_process = ref([])

const editDocument = async () => {

    let _data = null

    const payload = {
        id: data.id,
        name: data.name,
        content: data.content,
        id_type: data.type.id,
        id_process: data.process.id
    }

    if (editButtonLabel == 'Guardar'){
        _data = await axios.post(`${state.api_base}/documents`, payload,
            {
                headers: {
                    Authorization: `Bearer ${localStorage.getItem('token')}`
                },
            }
        )
    }else{
        _data = await axios.put(`${state.api_base}/documents`, payload,
            {
                headers: {
                    Authorization: `Bearer ${localStorage.getItem('token')}`
                },
            }
        )
    }

    checkSession(_data, router)

    router.back()
    return _data

}

const deleteDocument = async () => {
    const deleted_data = await _deleteDocument(data.id)
    if (deleted_data != null) {
        checkSession(deleted_data, router)
        router.back()
    }
}

onMounted(async () => {

    const types = await axios.get(`${state.api_base}/types`, {
        headers: {
            Authorization: localStorage.getItem('token')
        }
    })
    checkSession(types, router)
    document_types.value = types.data

    const process = await axios.get(`${state.api_base}/process`, {
        headers: {
            Authorization: localStorage.getItem('token')
        }
    })

    checkSession(process, router)
    document_process.value = process.data

})

</script>

<style>
#documento {
    width: 80%;
    margin-top: 80px;
}

.input-group {
    margin: 20px 0px;
}

#buttons-box {
    padding: 15px 0px;
    display: flex;
    flex-direction: row;
    justify-content: space-evenly;
    align-content: center;
    width: 80%;
}</style>