<template>
    <form @submit.prevent="submit">
        <textarea placeholder="Document Title" id="nome" v-model="form.nome"></textarea>
        <QuillEditor v-model:content="form.value" id="value" contentType="html" :modules="modules" toolbar="full" theme="snow"  style="height: 800px;"/>
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
        <tr v-for="(editor, index) in editors" :key="index">
          <td>
            <component :is="editor.content"></component>
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
import { Inertia } from '@inertiajs/inertia';
import { reactive } from 'vue';

export default {

    data() {
        return {
            editors: [],
            editorOption: '',
            nome: '',
            value: '',
            dynamicId: 0
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

        function exportPdf(){
            Inertia.post('/export/', form)
        }

        function submit() {
            console.log(form)
            Inertia.post('/documento', form)
        }
        return { form, exportPdf, submit, modules }
    },

    methods: {

        createEditor(editor){
            this.dynamicId++
            const id = 'my-toolbar' + this.dynamicId
            const toolbarId = '#' + id
            if(editor === "paragrafo"){
              this.editors.push({
                content:
                <>
                  <div id={id}>
                    <button class="ql-bold"></button>
                    <button class="ql-italic"></button>
                  </div>
                  <QuillEditor toolbar={toolbarId}></QuillEditor>
                </>
              })
            }
            if(editor === "titulo"){
              this.editors.push({content:
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
                  </div>
                  <QuillEditor toolbar={toolbarId}></QuillEditor>
                </>
              })
            }
            if(editor === "paragrafo-imagem"){
              this.editors.push({content: <QuillEditor/>})
            }
            if(editor === "completo"){
              this.editors.push({content: <QuillEditor v-model:content={this.form.value} id="value" contentType="html" toolbar="full" theme="snow"/>});
            }
            //
        }
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

</style>
