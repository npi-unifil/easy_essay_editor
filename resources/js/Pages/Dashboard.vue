<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';;
import { Head } from '@inertiajs/inertia-vue3';
import { ref } from 'vue';
import Editor from '../Components/EditorComponents/RichTextEditor.vue';

</script>

<script>
export default {

    props: ['template'],

}
</script>

<template>
    <Head title="Novo Documento" />

    <BreezeAuthenticatedLayout>
        <template #links>
            <div class="mt-4 ml-2">
                <a href="/documents" class="no-underline font-bold text-slate-100 hover:text-slate-800">Documentos</a>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-9xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="max-w-7xl text-center mx-auto p-6 bg-white border-b border-gray-200">
                        <Editor :template=this.template />
                    </div>
                </div>
            </div>
        </div>



    </BreezeAuthenticatedLayout>

</template>



