<template>
    <div>
        <div style="display:flex; justify-content:flex-end;">
            <button type="button" class="btn-close-editor" @click="removeContent()">x</button>
        </div>
        <QuillEditor @editorChange="saveContent()" @textChange="saveContent()" :options="editorOption"
            v-model:content='content' contentType="html" theme="snow"></QuillEditor>
    </div>
</template>

<script>
import { Quill } from '@vueup/vue-quill';
import { QuillEditor } from '@vueup/vue-quill';
import '@vueup/vue-quill/dist/vue-quill.snow.css';
import '@vueup/vue-quill/dist/vue-quill.bubble.css';
import { useEditorStore } from '@/utils/EditorStore';
import BlotFormatter, { AlignAction, ResizeAction, DeleteAction, ImageSpec } from 'quill-blot-formatter';
Quill.register('modules/blotFormatter', BlotFormatter);

class CustomImageSpec extends ImageSpec {
    getActions() {
        return [AlignAction, ResizeAction, DeleteAction];
    }
}

export default {

    name: 'ParagrafoImagem',

    props: {
        id: String,
        content: {
            type: String,
            default: ''
        }
    },

    components: {
        QuillEditor
    },

    data() {
        return {
            editorOption: {
                modules: {
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
                    toolbar: [
                        ['bold', 'italic', 'underline', 'strike'],
                        ['link'],
                        ['image', 'video'],
                        [{ 'align': [] }],
                        [{ 'color': [] }],
                    ],
                }
            }
        }
    },

    methods: {
        saveContent() {
            this.editorStore.saveContent(this.id, this.content);
        },
        removeContent() {
            this.editorStore.removeContent(this.id);
        }
    },

    setup: () => {
        const editorStore = useEditorStore();

        return { editorStore }
    }

}
</script>
<style>
.btn-close-editor {
    top: 0;
    right: 0;
    border: none;
    font-size: 20px;
    cursor: pointer;
    font-weight: bold;
    color: #4AAE9B;
    background: transparent;
}
</style>
