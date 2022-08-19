<template>
    <div>
        <QuillEditor :options="editorOption" v-model:content='content' contentType="html" theme="snow"></QuillEditor>
    </div>
</template>

<script>
import { QuillEditor } from '@vueup/vue-quill';
import '@vueup/vue-quill/dist/vue-quill.snow.css'
import '@vueup/vue-quill/dist/vue-quill.bubble.css'


export default {

    name: 'Paragrafo',

    props: ['id', 'content'],

    components: {
        QuillEditor
    },

    data(){
        return{
            editorOption: {
                modules:{
                    toolbar:[
                        ['bold', 'italic', 'underline', 'strike'],
                        ['link'],
                        [{'list':'ordered'}, {'list':'bullet'}],
                        [{'direction':'rtl'}],
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
