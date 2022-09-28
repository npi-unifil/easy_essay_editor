<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';;
import { Head } from '@inertiajs/inertia-vue3';
import { ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { reactive } from 'vue';
import { Link } from '@inertiajs/inertia-vue3';
import { useEditorStore } from '@/utils/EditorStore';
</script>

<script>
export default {

    props: ['id', 'nome'],

    data(){
        const editorStore = useEditorStore();

        return {editorStore};
    },

    methods: {
        gerenciar_doc(){
            Inertia.get('/documents/' + this.id);
        },

        deletar_documento(){
            Inertia.delete('/documents/' + this.id);
        },

        retornar(){
            Inertia.get('/documents');
        }
    }

}
</script>

<style>
.conteudo-corpo{
    display: flex;
    justify-content: space-between;
}

.documento{
    border: 1px solid black;
    width: 700px;
    height: 500px;
    text-align:center;
}

.gerenciar-botoes {
    width: 30%;
    display: block;
}

.gerenciar-botoes button {
    width: 200px;
    height: 50px;
    margin-bottom: 20px;
    border-radius: 10px;
    font-size: large;
    font-weight: bolder;
    color: white;
    background-color: blue;
}
</style>

<template>

    <Head title="Gerenciar Trabalho" />

    <BreezeAuthenticatedLayout>
        <template #links>
            <div class="mt-4 ml-2">
                <a
                    class="no-underline font-bold text-slate-100 hover:text-slate-800 hover:cursor-pointer" @click="retornar">Documentos</a>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="conteudo-corpo">
                                <div class="gerenciar-botoes">
                                    <button style="background-color: green;" @click="gerenciar_doc">
                                        Editar Documento
                                    </button>
                                    <button style="background-color: blue;" @click="gerenciar_referencias">
                                        Gerenciar Referencias
                                    </button>
                                    <button style="background-color: red;" @click="deletar_documento">
                                        Deletar Documento
                                    </button>
                                    <button style="background-color: yellow;" @click="retornar">
                                        Retornar
                                    </button>
                                </div>

                            <div class="documento">
                                <h1 style="margin-top: 50px;">{{this.nome}}</h1>
                                <p>----------------------------------------------------------------------------</p>
                                <p>----------------------------------------------------------------------------</p>
                                <p>----------------------------------------------------------------------------</p>
                                <p>----------------------------------------------------------------------------</p>
                                <p>----------------------------------------------------------------------------</p>
                                <p>----------------------------------------------------------------------------</p>
                                <p>----------------------------------------------------------------------------</p>
                                <p>----------------------------------------------------------------------------</p>
                                <p>----------------------------------------------------------------------------</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



    </BreezeAuthenticatedLayout>

</template>

