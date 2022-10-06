<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';;
import { Head } from '@inertiajs/inertia-vue3';
import { ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { reactive } from 'vue';
import { Link } from '@inertiajs/inertia-vue3'
</script>

<script>
export default {

    methods: {

        retornar() {
            Inertia.get('/documents');
        }

    }

}
</script>

<style>
    .admin-body{
        display: block;
        text-align: center;
    }

    .admin-button{
        display: block;
        text-align: center;
        justify-content: space-around;
    }

    .admin-button button{
        background-color: palevioletred;
        width: 90%;
        border-radius: 5px;
        height: 60px;
        margin: 30px;
    }
</style>

<template>

    <Head title="Admin" />

    <BreezeAuthenticatedLayout>
        <template #links>
            <div class="mt-4 ml-2">
                <a class="no-underline font-bold text-slate-100 hover:text-slate-800 hover:cursor-pointer"
                    @click="retornar">Documentos</a>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="admin-body">
                            <h1>Administrador</h1>
                            <div class="admin-button">
                                <button>Gerenciar templates</button>
                                <button>Ir para documentos</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



    </BreezeAuthenticatedLayout>

</template>

