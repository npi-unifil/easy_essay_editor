<template>
    <div>
        <div @click="openSideModal" class="cursor-pointer">
            <h1 v-if="this.title == ''">
                <p>Adicione um titulo...</p>
            </h1>
            <h1 v-if="this.title != ''">
                <p>{{this.title}}</p>
            </h1>
        </div>

        <table class="table table-bordered">
            <tbody>
                <tr v-for="{ editor, content } in editorStore.editors" :key="content">
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

    <SideModal v-show="isOpened" @close="closeSideModal">
        <template v-slot:body>
            <div style="height: 100%; background-color: white; width: 600px">
                <h1 style="text-align: center; margin-top: 23px;">Adicionar Informações: </h1>
                <div style="display: block;">
                    <div class="autores">
                        <div class="add-autor">
                            <div style="margin-bottom: 10px;">
                                <label for="titulo">Titulo: </label>
                                <input v-model="this.title" />
                            </div>
                            <div style="margin-bottom: 10px;">
                                <label for="orientador">Orientador: </label>
                                <input v-model="this.orientador" />
                            </div>
                            <div style="margin-bottom: 10px;">
                                <label for="londrina">Cidade: </label>
                                <input v-model="this.cidade" />
                            </div>
                            <div style="margin-bottom: 10px;">
                                <label for="ano">Ano: </label>
                                <input v-model="this.ano" />
                            </div>
                            <div style="margin-bottom: 10px;">
                                <label for="curso">Curso: </label>
                                <input v-model="this.curso" />
                            </div>
                            <label for="nome">Adicionar examinador da Banca(se houver): </label>
                            <input v-model="this.nome_banca" v-on:keyup="keypressed" />
                            <button @click="adicionar_novo"
                                style="background-color: orange; width: 80px; height: 30px; border-radius: 5px; margin-left: 10px;">Salvar</button>
                        </div>
                        <div v-for="nome, index  in this.banca" :key="index">
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

</template>

<script>
import { QuillEditor } from '@vueup/vue-quill';
import Titulo from './Titulo.vue';
import Paragrafo from './Paragrafo.vue';
import ParagrafoImagem from './ParagrafoImagem.vue';
import Modal from './Modal.vue';
import '@vueup/vue-quill/dist/vue-quill.snow.css';
import '@vueup/vue-quill/dist/vue-quill.bubble.css';
import { Inertia } from '@inertiajs/inertia';
import { reactive } from 'vue';
import rnd from '../../utils/generator.js';
import { useEditorStore } from '@/utils/EditorStore';
import SideModal from '../EditorComponents/SideModal.vue';

export default {

    props: ['template'],

    mounted() {
        const id = 'abcdefg';
        const initialEditor = {
            editor: {
                name: 'titulo',
                component: <Titulo id={id} />,
                component_order: 0
            },
            content: {
                value: '<p>Escreva seu titulo<p>'
            }

        }
        this.editorStore.fill(id, initialEditor);
    },

    data() {
        return {
            isModalVisible: false,
            title: '',
            editedTitle: null,
            orientador: '',
            cidade: '',
            ano: null,
            curso: '',
            nome_banca: '',
            banca: [],
            editorOption: '',
            value: 'Digite um título',
            isOpened: false
        }
    },

    components: {
        QuillEditor,
        Modal,
        SideModal
    },

    setup: () => {

        const editorStore = useEditorStore();

        function exportPdf() {
            Inertia.post('/export/', form)
        }

        return { exportPdf, editorStore }
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
            this.editorStore.saveDocument(
                this.title,
                this.template,
                this.orientador,
                this.cidade,
                this.ano,
                this.curso,
                this.banca
            );
        }
    }

}

</script>

<style>
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

#remove-editor {
    background-color: red;
    height: 30px;
    width: 80px;
    border-radius: 5px;
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
