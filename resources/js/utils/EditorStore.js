import { defineStore } from "pinia";
import { Inertia } from '@inertiajs/inertia';

export const useEditorStore = defineStore("EditorStore", {

    state: () => {
        return {
            editors: {},
            component_order: 0
        };
    },

    actions: {
        fill(id, editor){
            this.editors[id] = editor;
            this.increaseOrder();
        },

        removeContent(id){
            delete this.editors[id];
            this.decreaseOrder();
        },

        saveContent(id, content){
            this.editors[id].content.value = content;
        },

        saveDocument(title){
            const request = {
                docTitle: title,
                content: this.editors
            };
            Inertia.post('/documento', request);
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
    }

})
