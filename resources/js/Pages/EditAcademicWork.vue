<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import { Head } from '@inertiajs/inertia-vue3';
import { ref } from 'vue';
import { QuillEditor, Quill } from '@vueup/vue-quill';
import '@vueup/vue-quill/dist/vue-quill.snow.css'
import { Inertia } from '@inertiajs/inertia';
import { reactive } from 'vue';
import BlotFormatter from 'quill-blot-formatter';
import { useEditorStore } from '@/utils/EditorStore';
import Modal from '../Components/EditorComponents/Modal.vue';
import Titulo from '../Components/EditorComponents/Titulo.vue';
import Paragrafo from '../Components/EditorComponents/Paragrafo.vue';
import ParagrafoImagem from '../Components/EditorComponents/ParagrafoImagem.vue';
import rnd from '../utils/generator.js';
import SideModal from '../Components/EditorComponents/SideModal.vue';
import { VueSidePanel } from 'vue3-side-panel';
</script>

<script>

export default {

    props: ['id', 'edit', 'orientador', 'cidade', 'ano', 'curso', 'banca', 'template', 'document_name'],

    data() {
        const editorStore = useEditorStore();
        const dados = {
            nome: this.document_name,
            value: this.edit.conteudo,
            orientador: this.orientador,
            cidade: this.cidade,
            ano: this.ano,
            curso: this.curso,
            banca: this.banca,
        }
        const modules = {
            name: 'blotFormatter',
            module: BlotFormatter
        }
        return { isModalVisible: false, nome_banca: '', isOpened: false, dados, editorStore, modules }
    },

    components: {
        QuillEditor,
        Modal,
        SideModal
    },

    mounted() {
        this.editorStore.setEditor(this.edit);
        this.editorStore.setExistingContent();
        this.editorStore.setComponentOrder();
    },

    methods: {
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

        closeSideModal(){
            this.isOpened = false;
        },

        showModal() {
            this.isModalVisible = true;
        },
        closeModal() {
            this.isModalVisible = false;
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
            if (editor === "paragrafo-imagem") {
                const paragrafoImagem = {
                    editor: {
                        name: 'paragrafo-imagem',
                        component: <ParagrafoImagem id={id} />,
                        component_order: this.editorStore.getOrder()
                    },
                    content: {
                        value: ''
                    }
                }
                this.editorStore.fill(id, paragrafoImagem)
            }

        },

        saveDocument() {
            this.editorStore.updateDocument(
                this.dados.nome,
                this.id,
                this.dados.orientador,
                this.dados.cidade,
                this.dados.ano,
                this.dados.curso,
                this.dados.banca
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
                {{this.edit.nome}}
            </h2>
            <div>
                <button id="button" @click="teste" class="bg-orange-400">Salvar</button>
            </div>
        </div>

        <div class="py-12" style="text-align:center">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">

                        <div>
                            <div class="cursor-pointer" @click="openSideModal()">
                                <h1 v-if="this.dados.nome == ''">
                                    Adicione um titulo...
                                </h1>
                                <h1 v-if="this.dados.nome != ''">
                                    {{this.dados.nome}}
                                </h1>
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
                                                <option value="paragrafo-imagem">Paragrafo-imagem</option>
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


        <SideModal v-show="isOpened" @close="closeSideModal">
            <template v-slot:body>
                <div style="height: 100%; background-color: white; width: 600px">
                    <h1 style="text-align: center; margin-top: 23px;">Adicionar Informações: </h1>

                    <div style="display: block; justify-content: center;">

                    </div>


                    <div style="display: block; justify-content: center;">
                        <div class="autores">
                            <div class="add-autor">
                                <div style="margin-bottom: 10px;">
                                    <label for="titulo">Titulo: </label>
                                    <input v-model="this.dados.nome" />
                                </div>
                                <div style="margin-bottom: 10px;">
                                    <label for="orientador">Orientador: </label>
                                    <input v-model="this.dados.orientador" />
                                </div>
                                <div style="margin-bottom: 10px;">
                                    <label for="londrina">Cidade: </label>
                                    <input v-model="this.dados.cidade" />
                                </div>
                                <div style="margin-bottom: 10px;">
                                    <label for="ano">Ano: </label>
                                    <input v-model="this.dados.ano" />
                                </div>
                                <div style="margin-bottom: 10px;">
                                    <label for="curso">Curso: </label>
                                    <input v-model="this.dados.curso" />
                                </div>
                                <label for="nome">Adicionar examinador da Banca(se houver): </label>
                                <input v-model="this.nome_banca" v-on:keyup="keypressed" />
                                <button @click="adicionar_novo"
                                    style="background-color: orange; width: 80px; height: 30px; border-radius: 5px; margin-left: 10px;">Salvar</button>
                            </div>
                            <div v-for="nome, index  in this.dados.banca" :key="index">
                                <div class="nome-autor">
                                    <p>{{nome.nome}}</p>
                                    <div style="display:flex; width: 170px; justify-content: space-between;">
                                        <button @click="editar_nome(index)" style="background-color: orange;">
                                            Editar
                                        </button>
                                        <button style="background-color: red;" @click="deletarBanca(index)">
                                            Deletar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </SideModal>

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

.btn-add {
    color: white;
    width: 150px;
    background: #4AAE9B;
    border: 1px solid #4AAE9B;
    border-radius: 20px;
    margin: 5px;
}

.btn-save {
    color: white;
    width: 150px;
    background: #514aae;
    border: 1px solid #514aae;
    border-radius: 20px;
    margin: 5px;
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
