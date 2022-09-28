<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';;
import { Head } from '@inertiajs/inertia-vue3';
import { ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { reactive } from 'vue';
import { Link } from '@inertiajs/inertia-vue3';
import { useEditorStore } from '@/utils/EditorStore';
import Modal from '../Components/EditorComponents/Modal.vue';
</script>

<script>
export default {

    props: ['id', 'nome'],

    components: {
        Modal
    },

    data(){
        const editorStore = useEditorStore();

        return {isModalVisible: false, editorStore};
    },

    methods: {
        gerenciar_doc(){
            Inertia.get('/documents/' + this.id);
        },

        gerenciar_referencias(){
            Inertia.get('/referencias');
        },

        showModal() {
            this.isModalVisible = true;
        },
        closeModal() {
            this.isModalVisible = false;
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

.modal-body{
    justify-content: center;
    align-items: center;
    text-align: center;
}

.modal-body p{
    margin: 0;
    font-size: xx-large;
    font-weight: bolder;
    margin-top: -30px;
    margin-bottom: 60px;
}

#modal-buttons{
    display: flex;
    justify-content: space-around;
}

#modal-buttons button{
    color: white;
    font-weight: bolder;
    border-radius: 5px;
    width: 100px;
    height: 50px;
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
                                    <button style="background-color: red;" @click="showModal">
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

                        <Modal v-show="isModalVisible" @close="closeModal">
                                <template class="modal-body" v-slot:body>
                                    <div>
                                        <div>
                                            <p>Deseja Realmente Apagar o Documento ?</p>
                                        </div>
                                        <div id="modal-buttons">
                                            <button @click="closeModal()" style="background-color: green">
                                                Cancelar
                                            </button>
                                            <button @click="closeModal(), deletar_documento()" style="background-color: red">
                                                Sim
                                            </button>
                                        </div>
                                    </div>
                                </template>
                        </Modal>
                    </div>
                </div>
            </div>
        </div>



    </BreezeAuthenticatedLayout>

</template>

