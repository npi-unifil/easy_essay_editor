<template>
    <form @submit.prevent="submit">
        <textarea placeholder="Document Title" id="nome" v-model="form.nome"></textarea>
        <QuillEditor v-model:content="form.value" id="value" contentType="html" :modules="modules" toolbar="full"
            theme="snow" style="height: 100px;" />
        <button id="button" type="submit" class="bg-orange-400">Salvar</button>
    </form>
    <button id="button" @click="exportPdf()" class="bg-orange-400">Exportar PDF</button>


    <table class="table table-bordered">
        <thead>
            <tr>
                <th scope="col">Editor</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="{ editor, content } in editors" :key="content">
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
            <option value="completo">Completo</option>
        </select>
        <button @click="createEditor(editorOption)" class="btn btn-warning rounded-0">SUBMIT</button>
    </div>

</template>

<script>
import { QuillEditor } from '@vueup/vue-quill'
import BlotFormatter from 'quill-blot-formatter'
import '@vueup/vue-quill/dist/vue-quill.snow.css'
import '@vueup/vue-quill/dist/vue-quill.bubble.css'
import { Inertia } from '@inertiajs/inertia';
import { reactive } from 'vue';
import rnd from '../utils/generator.js';

export default {

    mounted() {
        this.initialEditor()
    },

    data() {
        return {
            editorOption: '',
            nome: '',
            value: 'Digite um título',
            dynamicId: 0,
            dinamicData: [],
            dataContent: [],
            editors: {}
        }
    },

    components: {
        QuillEditor,
    },

    setup: () => {

        const form = reactive({
            nome: null,
            value: null
        })

        const modules = {
            name: 'blotFormatter',
            module: BlotFormatter,

        }

        function exportPdf() {
            Inertia.post('/export/', form)
        }

        function submit() {
            console.log(form)
            Inertia.post('/documento', form)
        }
        return { form, exportPdf, submit, modules }
    },

    methods: {

        removeEditor(id) {
            console.log(id);
            console.log(this.editors);
            console.log(this.dataContent);
        },

        initialEditor() {
            this.editors['abcdefg'] = {
                editor: {
                    type: 'titulo',
                    value:
                        <>
                            <div id='abcdefg'>
                                <button class="ql-bold"></button>
                                <button class="ql-italic"></button>
                                <button class="ql-underline"></button>
                                <button class="ql-strike"></button>
                            </div>
                            <QuillEditor toolbar='#abcdefg' content-type="html" v-model:content={this.value} theme="bubble" />
                        </>
                },
                content: {
                    value: '<h1>Escreva seu título</h1>'
                }
            }
        },


        createEditor(editor) {
            const id = rnd(20, rnd.alphaLower);
            const toolbarId = '#' + id
            if (editor === "paragrafo") {
                this.editors[id] = {
                    editor: {
                        type: 'paragrafo',
                        value:
                            <>
                                <div id={id}>
                                    <button class="ql-bold"></button>
                                    <button class="ql-italic"></button>
                                    <button class="ql-underline"></button>
                                    <button class="ql-strike"></button>
                                </div>
                                <QuillEditor toolbar={toolbarId} contentType="html" v-model:content={this.editors[id].content}></QuillEditor>
                            </>
                    },
                    content: {
                        value: ''
                    }
                }
            }

            if (editor === "titulo") {
                this.editors[id] = {
                    editor: {
                        type: 'titulo',
                        value:
                            <>
                                <div id={id}>
                                    <select class="ql-header" aria-controls="ql-picker-options-1">
                                        <option value="1"></option>
                                        <option value="2"></option>
                                        <option value="3"></option>
                                        <option value="4"></option>
                                        <option value="5"></option>
                                        <option value="6"></option>
                                    </select>
                                </div>
                                <QuillEditor toolbar={toolbarId} contentType="html" style="height: 130px;"></QuillEditor>
                            </>
                    },
                    content: {
                        value: ''
                    }

                }
            }
            if (editor === "paragrafo-imagem") {
                this.editors[id] = {
                    editor: {
                        type: 'paragrafo-imagem',
                        value:
                            <>
                                <div id={id}>
                                    <button class="ql-bold"></button>
                                    <button class="ql-italic"></button>
                                    <select class="ql-size">
                                        <option value="small"></option>
                                        <option selected></option>
                                        <option value="large"></option>
                                        <option value="huge"></option>
                                    </select>
                                    <button class="ql-header" type="button" value="1"></button>
                                    <button class="ql-header" type="button" value="2"></button>
                                </div>
                                <QuillEditor contentType="html" toolbar={toolbarId}></QuillEditor>
                            </>
                    },
                    content: {
                        value: ''
                    }
                }
            }
            if (editor === "completo") {
                this.editors[id] = {
                    editor: {
                        type: 'completo',
                        value: <QuillEditor v-model:content={this.value} id="value" contentType="html" toolbar="full" theme="snow" />
                    },
                    content: {
                        value: ''
                    }
                };
            }

            console.log(this.editors);
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
