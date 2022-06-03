<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import { Head } from '@inertiajs/inertia-vue3';
import { ref } from 'vue';
import { QuillEditor, Quill } from '@vueup/vue-quill';
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
            module: BlotFormatter
        }
        return { modules, update }
    },

    methods: {
        submit(){
            console.log(value);
            const update = {
                id: this.edit.document_id,
                nome: nome.value,
                conteudo: value.firstElementChild.innerHTML
            }
            Inertia.post('/doc/' + this.edit.document_id, update);
        },
        deleteDoc(){
            const id = this.edit.document_id;
            Inertia.delete('/documento/' + this.edit.document_id, id);
        }
    }
}
</script>

<template>
    <Head title="Dashboard" />

    <BreezeAuthenticatedLayout>
        <template #header>
            <div id="head-buttons">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{this.edit.nome}}
                </h2>
                <div>
                    <button id="button" @click="submit">Salvar</button>
                    <button id="delete-button" @click="deleteDoc">Deletar</button>
                </div>
            </div>
        </template>

        <div class="py-12" style="text-align:center">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <textarea placeholder="Document Title" id="nome" v-model="nome"></textarea>
                        <QuillEditor v-model:content="value" id="value" contentType="html" :modules="modules" style="height: 800px;" toolbar="full" theme="snow" />
                        <button id="button" @click="submit">Salvar</button>
                    </div>
                </div>
            </div>
        </div>



    </BreezeAuthenticatedLayout>

</template>
<style>
    #head-buttons {
        display: flex;
        justify-content: space-between;
    }

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

    #delete-button {
        width: 100px;
        height: 30px;
        margin-left: 5px;
        font-weight: bold;
        color: white;
        border: 0;
        border-radius: 5px;
        background-color: red;
        margin-top: 20px;
    }

    #nome {
        text-align: center;
        border: 0;
        margin-bottom: 20px;
    }

</style>


