<template>
    <div>
        <QuillEditor :options="editorOption" v-model:content='content' contentType="html" theme="snow"></QuillEditor>
    </div>
</template>

<script>
import { Quill } from '@vueup/vue-quill';
import { QuillEditor } from '@vueup/vue-quill';
import '@vueup/vue-quill/dist/vue-quill.snow.css';
import '@vueup/vue-quill/dist/vue-quill.bubble.css';
import BlotFormatter, { AlignAction, ResizeAction, DeleteAction, ImageSpec } from 'quill-blot-formatter';
Quill.register('modules/blotFormatter', BlotFormatter);

class CustomImageSpec extends ImageSpec {
    getActions(){
        return [AlignAction, ResizeAction, DeleteAction];
    }
}

export default {

    name: 'ParagrafoImagem',

    props: ['id', 'content'],

    components: {
        QuillEditor
    },

    data(){
        return{
            editorOption: {
                modules:{
                    blotFormatter: {
                        specs: [
                            CustomImageSpec,
                        ],
                        overlay: {
                            style: {
                                border: '2px solid red',
                            }
                        }
                    },
                    toolbar:[
                        ['bold', 'italic', 'underline', 'strike'],
                        ['link', 'image', 'video'],
                        [{'list':'ordered'}, {'list':'bullet'}],
                        [{'direction':'rtl'}],
                        [{'size':[]}],
                        [{'font':[]}],
                        [{'align':[]}],
                        [{'color':[]}],
                    ],
                }
            }
        }
    },

}
</script>
