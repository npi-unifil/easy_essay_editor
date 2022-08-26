<template>
    <div>
        <QuillEditor @editorChange="saveContent()" @textChange="saveContent()" :options="editorOption" v-model:content='content' contentType="html" style="height: 130px;" theme="snow"></QuillEditor>
    </div>
</template>

<script>
import { QuillEditor } from '@vueup/vue-quill';
import '@vueup/vue-quill/dist/vue-quill.snow.css'
import '@vueup/vue-quill/dist/vue-quill.bubble.css'
import { useEditorStore } from '@/utils/EditorStore';

export default {

    name: 'Titulo',

    props: {
        id: String,
        content: {
            type: String,
            default: 'Escreva seu título'
        }
    },

    components: {
        QuillEditor
    },

    data(){
        return{
            editorOption: {
                modules:{
                    toolbar:[
                        [{'header':[1,2,3,false]}],
                        [{'size':[]}],
                        [{'font':[]}],
                        [{'align':[]}],
                        [{'color':[]}],
                    ]
                }
            }
        }
    },

    methods: {
        saveContent(){
           this.editorStore.saveContent(this.id, this.content);
        }
    },

    setup: () => {
        const editorStore = useEditorStore();

        return{editorStore}
    }
}
</script>
