<template>
    <div>
        <QuillEditor :options="editorOption" v-model:content='content' contentType="html" style="height: 130px;" theme="bubble"></QuillEditor>
    </div>
</template>

<script>
import { QuillEditor } from '@vueup/vue-quill';
import '@vueup/vue-quill/dist/vue-quill.snow.css'
import '@vueup/vue-quill/dist/vue-quill.bubble.css'


export default {

    name: 'Titulo',

    props: ['id', 'content'],

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
    }
}
</script>
