<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import { Head } from '@inertiajs/inertia-vue3';
import { ref } from 'vue';
import { QuillEditor, Quill } from '@vueup/vue-quill';
import '@vueup/vue-quill/dist/vue-quill.snow.css'
import { Inertia } from '@inertiajs/inertia';
import { reactive } from 'vue';
import { useEditorStore } from '@/utils/EditorStore';
import Modal from '../Components/EditorComponents/Modal.vue';
import Titulo from '../Components/EditorComponents/Titulo.vue';
import Paragrafo from '../Components/EditorComponents/Paragrafo.vue';
import Imagem from '../Components/EditorComponents/Imagem.vue';
import rnd from '../utils/generator.js';
import SideModal from '../Components/EditorComponents/SideModal.vue';
</script>

<script>

export default {

    props: ['document', 'chapter_id', 'edit', 'chapter_name'],

    data() {
        const editorStore = useEditorStore();
        const dados = {
            id: this.chapter_id,
            nome: this.chapter_name,
            value: this.edit.conteudo,
        }
        return { disabled: 0, isModalVisible: false, nome_banca: '', editedTitle: null, dados, editorStore }
    },

    components: {
        QuillEditor,
        Modal,
        SideModal
    },

    mounted() {
        if (
            this.chapter_name == 'Dedicatória' |
            this.chapter_name == 'Agradecimentos' |
            this.chapter_name == 'Epígrafe' |
            this.chapter_name == 'Resumo' |
            this.chapter_name == 'Lista de Abreviaturas e Siglas' |
            this.chapter_name == 'Introdução'
        ) {
            this.disabled = 1;
        }
        this.editorStore.setEditor(this.edit);
        this.editorStore.setExistingContent();
        this.editorStore.setComponentOrder();
    },

    methods: {

        retornarChapters(){
            Inertia.get('/documents/' + this.document);
        },

        showModal() {
            this.isModalVisible = true;
        },
        closeModal() {
            this.isModalVisible = false;
        },
        keypressed: function (event) {
            if (this.nome_banca.length === 0) return;
            if (event.key == "Enter") {
                this.adicionar_novo();
            }
        },

        adicionar_novo() {
            if (this.nome_banca.length === 0) return;
            if (this.editedTitle === null) {
                this.banca.push({
                    nome: this.nome_banca
                })
            } else {
                this.banca[this.editedTitle].nome = this.nome_banca;
                this.editedTitle = null;
            }
            this.nome_banca = "";
        },

        deletarBanca(index) {
            this.banca.splice(index, 1);
        },

        editar_nome(index) {
            this.nome_banca = this.banca[index].nome;
            this.editedTitle = index;
        },

        openSideModal() {
            this.isOpened = true;
        },

        closeSideModal() {
            this.isOpened = false;
        },

        createEditor(editor) {
            const id = rnd(20, rnd.alphaLower);
            if (editor === "paragrafo") {
                const paragrafo = {
                    editor: {
                        name: 'paragrafo',
                        component: <Paragrafo id={id} />,
                        component_order: this.editorStore.getOrder()
                    },
                    content: {
                        value: ''
                    }
                }

                this.editorStore.fill(id, paragrafo)
            }

            if (editor === "titulo") {
                const titulo = {
                    editor: {
                        name: 'titulo',
                        component: <Titulo id={id} />,
                        component_order: this.editorStore.getOrder()
                    },
                    content: {
                        value: ''
                    }

                }
                this.editorStore.fill(id, titulo)
            }
            if (editor === "imagem") {
                const imagem = {
                    editor: {
                        name: 'imagem',
                        component: <Imagem id={id} />,
                        component_order: this.editorStore.getOrder()
                    },
                    content: {
                        value: ''
                    }
                }
                this.editorStore.fill(id, imagem)
            }

        },

        saveDocument() {
            this.editorStore.updateChapter(
                this.dados.id,
                this.dados.nome,
            );
        },

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
                {{ this.edit.nome }}
            </h2>
        </div>

        <div class="py-12" style="text-align:center">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">

                        <div>
                            <div style="display: flex; justify-content: space-between;">
                                <button id="back-button" @click="retornarChapters()">Retornar</button>
                                <textarea :disabled="disabled == 1"
                                    style="text-align: center; border: none; margin-bottom: 10px; font-weight: bolder;"
                                    name="title" cols="30" rows="2" v-model="this.dados.nome"
                                    placeholder="Dê um título ao seu capitulo..."></textarea>
                                <br>
                            </div>

                            <table class="table table-bordered">
                                <tbody>
                                    <tr v-for="{ editor, content } in this.editorStore.editors" :key="content">
                                        <td>
                                            <component :is="editor.component"></component>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <div>
                                <button type="button" class="btn-add" @click="showModal">
                                    Adicionar Campo
                                </button>
                                <button type="button" class="btn-save" @click="saveDocument">
                                    Salvar
                                </button>
                            </div>
                            <Modal v-show="isModalVisible" @close="closeModal">
                                <template v-slot:body>
                                    <div class="container">
                                        <div class="editor-options">
                                            <p>Selecione o editor:</p>
                                            <select v-model="editorOption">
                                                <option disabled value="">Selecione uma opção</option>
                                                <option value="titulo">Titulo</option>
                                                <option value="paragrafo">Paragrafo</option>
                                                <option value="imagem">Imagem</option>
                                            </select>
                                        </div>
                                        <div class="modal-buttons">
                                            <button @click="closeModal()">
                                                Cancelar
                                            </button>
                                            <button @click="closeModal(), createEditor(editorOption)">
                                                Adicionar
                                            </button>
                                        </div>
                                    </div>
                                </template>
                            </Modal>

                        </div>
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

#back-button {
    color: white;
    background-color: orange;
    font-size: large;
    font-weight: bolder;
    width: 100px;
    height: 30px;
    border-radius: 10px;
}

#back-button:hover{
    width: 110px;
    height: 40px;
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

.btn-add {
    color: white;
    width: 150px;
    background: #4AAE9B;
    border: 1px solid #4AAE9B;
    border-radius: 20px;
    margin: 5px;
}

.btn-add:hover{
    background: #337a6d;
}

.btn-save {
    color: white;
    width: 150px;
    background: #514aae;
    border: 1px solid #514aae;
    border-radius: 20px;
    margin: 5px;
}

.btn-save:hover{
    background: #433e8b;
}

#title {
    border: none;
    text-align: center;
    margin-bottom: 20px;
}

.editor-options {
    margin-left: 20px;
    text-align: left;
}

.editor-options select {
    width: 100%;
    border: 1px solid rgb(190, 190, 190);
    border-radius: 20px;
}


.modal-buttons {
    margin-top: 20px;
    text-align: right;
}


.modal-buttons button {
    width: 100px;
    border: solid 1px rgb(199, 182, 182);
    border-radius: 20px;
    margin: 0 5px 0 5px;
}

.modal-buttons button:nth-child(1):hover {
    border: solid 1px blue;
    color: blue;
}

.modal-buttons button:nth-child(2) {
    background-color: rgb(83, 83, 226);
    color: white;
    border: none;
}

.modal-buttons button:nth-child(2):hover {
    background-color: rgb(112, 112, 221);
}

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

.autores {
    display: block;
    margin: 20px;
    width: 500px;
    border-bottom: 1px solid black;
}

.autores input {
    margin-left: 10px;
}

.add-autor {
    display: block;
    margin-bottom: 10px;
}

.add-autor input {
    border-bottom: 1px solid black;
    width: 350px;
}

.nome-autor {
    display: flex;
    justify-content: space-between;
}

.nome-autor button {
    border-radius: 5px;
    color: white;
    font-weight: bold;
    width: 80px;
    height: 30px;
}
</style>
