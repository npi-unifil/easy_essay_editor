<template>
    <div>
        <textarea name="title" id="title" v-model="title" placeholder="Titulo do documento"></textarea>

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
            <template v-slot:header>
                Selecione o editor:
            </template>
            <template v-slot:body>
                <div class="container">
                    <div class="editor-options">
                        <p>Tipo</p>
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


export default {

    mounted() {
        const id = 'abcdefg';
        const initialEditor = {
            editor: {
                name: 'titulo',
                component: <Titulo id={id} />
            },
            content: {
                value: ''
            }

        }
        this.editorStore.fill(id, initialEditor);
    },

    data() {
        return {
            isModalVisible: false,
            title: '',
            editorOption: '',
            value: 'Digite um título',
        }
    },

    components: {
        QuillEditor,
        Modal
    },

    setup: () => {

        const editorStore = useEditorStore();

        function exportPdf() {
            Inertia.post('/export/', form)
        }

        return { exportPdf, editorStore }
    },

    methods: {

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
                        component: <Titulo id={id} />
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
                        component: <ParagrafoImagem id={id} />
                    },
                    content: {
                        value: ''
                    }
                }
                this.editorStore.fill(id, paragrafoImagem)
            }

        },

        saveDocument(){
            this.editorStore.saveDocument(this.title);
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

.modal-buttons button:nth-child(2)  {
    background-color: rgb(83, 83, 226);
    color: white;
    border: none;
}

.modal-buttons button:nth-child(2):hover  {
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
</style>
