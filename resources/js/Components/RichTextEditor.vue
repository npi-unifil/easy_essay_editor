<template>
    <form @submit.prevent="submit">
        <textarea placeholder="Document Title" id="nome" v-model="form.nome"></textarea>
        <QuillEditor v-model:content="form.value" id="value" contentType="html" :modules="modules" toolbar="full" theme="snow"  style="height: 800px;"/>
        <button id="button" type="submit" class="bg-orange-400">Salvar</button>
    </form>
    <button id="button" @click="exportPdf()" class="bg-orange-400">Exportar PDF</button>

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

        function exportPdf(){
            Inertia.post('/export/', form)
        }

        function submit() {
            console.log(form)
            Inertia.post('/documento', form)
        }
        return { form, exportPdf, submit, modules }
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
        margin-top: 20px;
    }

    #nome {
        text-align: center;
        border: 0;
        margin-bottom: 20px;
    }

</style>
