import { defineStore } from "pinia";
import { Inertia } from '@inertiajs/inertia';
import { Object } from "core-js";
import Titulo from '../Components/EditorComponents/Titulo.vue';
import Paragrafo from '../Components/EditorComponents/Paragrafo.vue';
import Imagem from '../Components/EditorComponents/Imagem.vue';
import { toRaw } from "vue";

export const useEditorStore = defineStore("EditorStore", {

    state: () => {
        return {
            editors: {},
            componentRemoved: {},
            component_order: 0
        };
    },

    actions: {
        fill(id, editor){
            this.editors[id] = editor;
            this.increaseOrder();
        },

        removeContent(id){
            this.componentRemoved[id] = this.editors[id];
            delete this.editors[id];
            this.decreaseOrder();
        },

        saveContent(id, content){
            this.editors[id].content.value = content;
        },

        saveChapter(name){
            const request = {
                name: name,
                content: this.editors
            };
            Inertia.post('/documents', request);
            // alterar aqui para os chapters
        },

        updateDocument(title, id, orientador, cidade, ano, curso, banca){
            const request = {
                doc_id: id,
                docTitle: title,
                orientador: orientador,
                cidade: cidade,
                ano: ano,
                curso: curso,
                banca: banca,
                content: this.editors,
                removed: this.componentRemoved
            }
            Inertia.put('/documents/' + id, request);
        },

        getOrder(){
            return this.component_order;
        },

        increaseOrder(){
            this.component_order++;
        },

        decreaseOrder(){
            this.component_order--;
        },

        setComponentOrder(){
            this.component_order = Object.keys(this.editors).length;
        },

        setEditor(editor){
            this.editors = editor;
        },

        setExistingContent(){
            let editorClone = structuredClone(toRaw(this.editors));
            Object.entries(this.editors).forEach(([key, value])=> {
                const editorId = key;
                const editorContent =  value.content.value;
                Object.entries(value).forEach(([content, editor])=> {
                    Object.entries(editor).forEach(([key, value])=> {
                        if(key == 'name' && value == 'titulo'){
                            editorClone[editorId].editor.component = <Titulo id={editorId} content={editorContent}/>;
                        }
                        if(key == 'name' && value == 'paragrafo'){
                            editorClone[editorId].editor.component = <Paragrafo id={editorId} content={editorContent}/>;
                        }
                        if(key == 'name' && value == 'imagem'){
                            editorClone[editorId].editor.component = <Imagem id={editorId} content={editorContent}/>;
                        }
                    })
                })
            });
            this.setEditor(editorClone);
        },

        exportPdf(id){
            Inertia.get('/export/' + id);
        }
    }

})
