import { defineStore } from "pinia";
import { Inertia } from '@inertiajs/inertia';
import { Object } from "core-js";
import Titulo from '../Components/EditorComponents/Titulo.vue';
import Paragrafo from '../Components/EditorComponents/Paragrafo.vue';
import ParagrafoImagem from '../Components/EditorComponents/ParagrafoImagem.vue';
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

        saveDocument(title, template){
            const request = {
                docTitle: title,
                template: template,
                content: this.editors
            };
            Inertia.post('/documents', request);
        },

        updateDocument(title, id){
            const request = {
                doc_id: id,
                docTitle: title,
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
                        if(key == 'name' && value == 'paragrafo-imagem'){
                            editorClone[editorId].editor.component = <ParagrafoImagem id={editorId} content={editorContent}/>;
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
