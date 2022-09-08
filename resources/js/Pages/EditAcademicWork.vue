<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import { Head } from '@inertiajs/inertia-vue3';
import { ref } from 'vue';
import { QuillEditor, Quill } from '@vueup/vue-quill';
import '@vueup/vue-quill/dist/vue-quill.snow.css'
import { Inertia } from '@inertiajs/inertia';
import { reactive } from 'vue';
import BlotFormatter from 'quill-blot-formatter';
</script>

<script>

    export default {

        props: ['edit', 'document_name'],

        data() {
            const dados = {
                nome: this.document_name,
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
            return { modules }
        },

        methods: {
            teste(){
                console.log(this.edit);
            }

        }
    }
</script>

<template>
    <Head title="Editar Documento" />

    <BreezeAuthenticatedLayout>
        <template #links>
            <div class="mt-4 ml-2">
                <a href="/documents" class="no-underline font-bold text-slate-100 hover:text-slate-800">Documentos</a>
            </div>
        </template>
        <div id="head-buttons" class="mr-9">
            <h2 class="font-semibold text-xl text-gray-50 mt-2.5 leading-tight">
                {{this.edit.nome}}
            </h2>
            <div>
                <button id="button" @click="teste" class="bg-orange-400">Salvar</button>
                <button @click="exportPdf" class="bg-orange-400 ml-1.5 rounded w-28 h-8 font-bold text-slate-100">Exportar PDF</button>
                <button id="delete-button" @click="deleteDoc">Deletar</button>
            </div>
        </div>

        <div class="py-12" style="text-align:center">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <textarea placeholder="Document Title" id="nome" v-model="nome"></textarea>
                        <QuillEditor v-model:content="value" id="value" contentType="html" :modules="modules" style="height: 800px;" toolbar="full" theme="snow" />
                        <button id="button" @click="submit" class="bg-orange-400">Salvar</button>
                        <button @click="exportPdf" class="bg-orange-400 ml-1.5 rounded w-28 h-8 font-bold text-slate-100">Exportar PDF</button>
                        <button id="delete-button" @click="deleteDoc">Deletar</button>
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
        height: 32px;
        font-weight: bold;
        color: white;
        border: 0;
        border-radius: 5px;
        margin-top: 20px;
    }

    #delete-button {
        width: 100px;
        height: 32px;
        margin-left: 5px;
        font-weight: bold;
        color: white;
        border: 0;
        border-radius: 5px;
        background-color: rgb(252, 66, 66);
        margin-top: 20px;
    }

    #nome {
        text-align: center;
        border: 0;
        margin-bottom: 20px;
    }

</style>
