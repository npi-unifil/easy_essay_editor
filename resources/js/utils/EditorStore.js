import { defineStore } from "pinia";

export const useEditorStore = defineStore("EditorStore", {

    state: () => {
        return {
            editors: {}
        };
    },

    actions: {
        fill(id, editor){
            this.editors[id] = editor;
        }
    }

})
