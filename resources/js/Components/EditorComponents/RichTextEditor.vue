<template>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th scope="col">Editor</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="{ editor, content } in editorStore.editors" :key="content">
                <td>
                    <component :is="editor.value"></component>
                </td>
            </tr>
        </tbody>
    </table>

    <div class="d-flex">
        <select class="sort-filter" v-model="editorOption">
            <option value="paragrafo">Paragrafo</option>
            <option value="titulo">Titulo</option>
            <option value="paragrafo-imagem">Paragrafo-imagem</option>
        </select>
        <button @click="createEditor(editorOption)" class="btn btn-warning rounded-0">SUBMIT</button>
    </div>

</template>

<script>
import { QuillEditor } from '@vueup/vue-quill';
import Titulo from './Titulo.vue';
import Paragrafo from './Paragrafo.vue';
import ParagrafoImagem from './ParagrafoImagem.vue';
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
                    type: 'titulo',
                    value: <Titulo id={id}/>
                },
                content: {
                    value: ''
                }

            }
        this.editorStore.fill(id, initialEditor);
    },

    data() {
        return {
            editorOption: '',
            nome: '',
            value: 'Digite um título',
        }
    },

    components: {
        QuillEditor,
    },

    setup: () => {

        const editorStore = useEditorStore();

        const form = reactive({
            nome: null,
            value: null
        })

        function exportPdf() {
            Inertia.post('/export/', form)
        }

        function submit() {
            console.log(form)
            Inertia.post('/documento', form)
        }
        return { form, exportPdf, submit, editorStore }
    },

    methods: {

        removeEditor(id) {
            console.log(id);
            console.log(this.editors);
            console.log(this.dataContent);
        },

        createEditor(editor) {
            const id = rnd(20, rnd.alphaLower);
            if (editor === "paragrafo") {
                const paragrafo = {
                    editor: {
                        type: 'paragrafo',
                        value:  <Paragrafo id={id}/>,
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
                        type: 'titulo',
                        value: <Titulo id={id}/>
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
                        type: 'paragrafo-imagem',
                        value: <ParagrafoImagem id={id}/>
                    },
                    content: {
                        value: ''
                    }
                }
                this.editorStore.fill(id, paragrafoImagem)
            }

        },
    }

}

</script>

<style>
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
