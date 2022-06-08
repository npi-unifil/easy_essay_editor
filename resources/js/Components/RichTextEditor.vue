<template>
    <form @submit.prevent="submit">
        <textarea placeholder="Document Title" id="nome" v-model="form.nome"></textarea>
        <QuillEditor v-model:content="form.value" id="value" contentType="html" :modules="modules" toolbar="full" theme="snow"  style="height: 800px;"/>
        <button id="button" type="submit">Salvar</button>
        <button id="button" type="export">Exportar PDF</button>
    </form>

</template>

<script>
import { QuillEditor } from '@vueup/vue-quill'
import BlotFormatter from 'quill-blot-formatter'
import '@vueup/vue-quill/dist/vue-quill.snow.css'
import { Inertia } from '@inertiajs/inertia';
import { reactive } from 'vue';

export default {

    data() {
        return {
            nome: '',
            value: ''
        }
    },

    components: {
      QuillEditor,
    },

    setup: () => {
        const form = reactive({
            nome: null,
            value: null
        })

        const modules = {
            name: 'blotFormatter',
            module: BlotFormatter,

        }

        function submit() {
            console.log(form)
            Inertia.post('/documento', form)
        }
        return { form, submit, modules }
    },


}

</script>

<style>

    #button {
        width: 100px;
        height: 30px;
        font-weight: bold;
        color: white;
        border: 0;
        border-radius: 5px;
        background-color: blue;
        margin-top: 20px;
    }

    #nome {
        text-align: center;
        border: 0;
        margin-bottom: 20px;
    }

</style>
