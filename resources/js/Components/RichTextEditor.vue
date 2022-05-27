<template>
    <QuillEditor v-model="content" :modules="modules" toolbar="full" theme="snow" />
    <QuillEditor :content="content" readOnly=true theme="snow" />
</template>

<script>
import { QuillEditor } from '@vueup/vue-quill'
import BlotFormatter from 'quill-blot-formatter'
import '@vueup/vue-quill/dist/vue-quill.snow.css';

export default {

    components: {
      QuillEditor
    },
    setup: () => {
        const modules = {
            name: 'blotFormatter',
            module: BlotFormatter,

        }
        return { modules }
    },

}

</script>
