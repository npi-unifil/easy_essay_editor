<template>
    <form @submit.prevent="submit">
        <input placeholder="Document Title" id="nome" v-model="form.nome">
        <QuillEditor v-model:content="form.value" id="value" contentType="html" :modules="modules" toolbar="full" theme="snow" />
        <QuillEditor v-model:content="value" contentType="html" readOnly=true theme="snow" />
        <button id="button" type="submit">Salvar</button>

    </form>

    <div>{{ nome }}{{value}}</div>
</template>

<script>
import { QuillEditor } from '@vueup/vue-quill'
import BlotFormatter from 'quill-blot-formatter'
import '@vueup/vue-quill/dist/vue-quill.snow.css'
import { Inertia } from '@inertiajs/inertia';
import { reactive } from 'vue';
import Quill from 'quill';
import { pdfExporter } from 'quill-to-pdf';
import { saveAs } from 'file-saver';

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
        background-color: blue;
    }

    #nome {
        text-align: center;
        border: 0;
        margin-bottom: 20px;
    }

</style>
