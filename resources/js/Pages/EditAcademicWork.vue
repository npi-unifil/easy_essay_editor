<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import { Head } from '@inertiajs/inertia-vue3';
import { ref } from 'vue';
import { QuillEditor } from '@vueup/vue-quill';
import BlotFormatter from 'quill-blot-formatter';
import '@vueup/vue-quill/dist/vue-quill.snow.css'
import { Inertia } from '@inertiajs/inertia';
import { reactive } from 'vue';

</script>

<script>
    export default {

    props: {
        edit: Object
    },

    data() {
        const dados = {
            nome: this.edit.nome,
            value: this.edit.conteudo
        }
        return dados
    },

    components: {
      QuillEditor,
    },
    setup: () => {

        const modules = {
            name: 'blotFormatter',
            module: BlotFormatter,

        }
        return { modules, update }
    },

    methods: {
        submit(){
            console.log(nome.value);
            console.log(value.firstElementChild.innerHTML);
            const update = {
                id: this.edit.document_id,
                nome: nome.value,
                conteudo: value.firstElementChild.innerHTML
            }
            Inertia.post('/documento/' + this.edit.document_id, update);
            console.log(this.edit.document_id);
        }
    }
}
</script>

<template>
    <Head title="Dashboard" />

    <BreezeAuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>

        <div class="py-12" style="text-align:center">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <input placeholder="Document Title" id="nome" v-model="nome" :key="edit.nome">
                        <QuillEditor v-model:content="value" id="value" contentType="html" :modules="modules" toolbar="full" theme="snow" />
                        <QuillEditor v-model:content="value" contentType="html" theme="snow" />
                        <button id="button" @onclick="submit">Salvar</button>
                    </div>
                </div>
            </div>
        </div>



    </BreezeAuthenticatedLayout>

</template>
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


