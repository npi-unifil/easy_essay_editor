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
    props: ['documents'],

    methods: {
        edit(id){
            Inertia.get('/documents/' + id)
        },

        newDoc(){
            Inertia.get('/dashboard')
        }

    }

}
</script>

<style>

    .card:hover{
        cursor: pointer;
    }

    .card .card-title{
        text-align: center;
        font-weight: bold;
    }

    .card .btn {
        height: 40px;
        width: 100px;
        text-align: center;
    }

    #new-doc {
        padding: 40px 10px 0 0;
    }

    #new-doc-button {
        height: 30px;
        width: 180px;
        border-radius: 5px;
        color: bisque;
        font-weight: bold;
        background-color: rgb(252, 139, 34);
    }

    #new-doc-button:hover {
        background-color: rgb(252, 132, 21);
    }
</style>

<template>
    <Head title="Documentos" />

    <BreezeAuthenticatedLayout>
        <template #header>

            <div id="new-doc" @click="newDoc" class="mb-2.5">
                <button id="new-doc-button">Novo Documento</button>
            </div>

        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="card-group">
                            <div class="row" style="padding: 5px" v-for="doc of documents" :key="doc.nome">

                                    <div class="col-sm-6">
                                            <div class="card" style="width: 13rem;" @click="edit(doc.document_id)">
                                                <form @submit.prevent="submit">
                                                    <img class="card-img-top" src="https://img.freepik.com/free-photo/digital-cyberspace-with-particles-digital-data-network-connections_24070-1303.jpg?w=2000" alt="Card image cap">
                                                    <div class="card-body">
                                                            <h5 class="card-title">{{ doc.nome }}</h5>
                                                    </div>
                                                </form>
                                            </div>

                                    </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



    </BreezeAuthenticatedLayout>

</template>

