<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';;
import { Head } from '@inertiajs/inertia-vue3';
import { ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { reactive } from 'vue';
import { Link } from '@inertiajs/inertia-vue3';
import { useEditorStore } from '@/utils/EditorStore';
import Modal from '../../Components/EditorComponents/Modal.vue';
</script>

<script>
export default {

    props: ['doc_id', 'referencia'],

    components: {
        Modal
    },

    data() {
        const editorStore = useEditorStore();

        const referencia_data = {
            id: 0,
            titulo: '',
        }

        return { isModalVisible: false, editorStore, referencia_data };
    },

    methods: {
        retornarGerencia() {
            Inertia.get('/gerenciar/' + this.doc_id);
        },

        showModal(id, titulo) {
            this.isModalVisible = true;
            this.referencia_data.id = id;
            this.referencia_data.titulo = titulo;
        },
        closeModal() {
            this.isModalVisible = false;
        },

        add_referencia() {
            Inertia.get('/add_referencia/' + this.doc_id);
        },

        editar_referencia(id) {
            Inertia.get('/editar_referencia/' + id);
        },

        deletar_referencia(id) {
            Inertia.delete('/deletar_referencia/' + id);
        },

        retornar() {
            Inertia.get('/documents');
        }
    }

}
</script>

<style>
.modal-body {
    justify-content: center;
    align-items: center;
    text-align: center;
}

.modal-body p {
    margin: 0;
    font-size: x-large;
    font-weight: bolder;
    margin-top: -30px;
    margin-bottom: 60px;
}

#modal-buttons {
    display: flex;
    justify-content: space-around;
}

#modal-buttons button {
    color: white;
    font-weight: bolder;
    border-radius: 5px;
    width: 100px;
    height: 50px;
}

.referencias {
    display: flex;
    justify-content: space-between;
    margin-top: 10px;
    border-bottom: 1px solid black;
}

#botoes-gerenciar {
    display: flex;
    width: 180px;
    justify-content: space-between;
}

#botoes-gerenciar button {
    color: white;
    font-size: large;
    font-weight: bolder;
    width: 80px;
    height: 30px;
    border-radius: 5px;
}

#title {
    display: flex;
    justify-content: space-between;
}

#title button {
    color: white;
    background-color: orange;
    font-size: large;
    font-weight: bolder;
    width: 100px;
    height: 30px;
    border-radius: 5px;
}
</style>

<template>

    <Head title="Gerenciar Referências" />

    <BreezeAuthenticatedLayout>
        <template #links>
            <div class="mt-4 ml-2">
                <a class="no-underline font-bold text-slate-100 hover:text-slate-800 hover:cursor-pointer"
                    @click="retornar">Documentos</a>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="conteudo-corpo">
                            <div class="container">
                                <div id="title">
                                    <button @click="retornarGerencia()">Retornar</button>
                                    <h1 style="font-weight: bolder; margin-bottom: 50px;">Referências</h1>
                                    <button @click="add_referencia">Adicionar</button>
                                </div>
                                <div class="referencias" v-for="value in this.referencia" :key="value.id">
                                    <p>{{ value.titulo }}</p>
                                    <div id="botoes-gerenciar">
                                        <button @click="editar_referencia(value.id)" style="background-color: green;">
                                            Editar
                                        </button>
                                        <button @click="showModal(value.id, value.titulo)"
                                            style="background-color: red;">
                                            Deletar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <Modal v-show="isModalVisible" @close="closeModal">
                            <template class="modal-body" v-slot:body>
                                <div>
                                    <div>
                                        <p>Deseja Realmente Apagar a Referência "{{ this.referencia_data.titulo }}" ?</p>
                                    </div>
                                    <div id="modal-buttons">
                                        <button @click="closeModal()" style="background-color: green">
                                            Cancelar
                                        </button>
                                        <button @click="closeModal(), deletar_referencia(this.referencia_data.id)"
                                            style="background-color: red">
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

