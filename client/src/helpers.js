import  axios from 'axios'

export const deleteDocument = async (id) => {

    if (confirm('¿Desea eliminar el documento?')){

        const endpoint = 'https://api.luisjdev.com/cruce_documentos/documents'
        const result = await axios.delete(endpoint, {
            data:{
                'id' : id,
            },
            headers: {
                Authorization: `Bearer ${localStorage.getItem('token')}`
            }
        })
        return result
    }
}

export const checkSession = (response, router) => {
    if (response.status == 403){
        localStorage.clear()
        router.push('/')
    }
}