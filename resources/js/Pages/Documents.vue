<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';;
import { Head } from '@inertiajs/inertia-vue3';
import { ref } from 'vue';

</script>

<script>
    export default {
        props: ['documents'],
        components: {

        },
}
</script>

<template>
    <Head title="Documentos" />

    <BreezeAuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Documentos
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="card-group">
                            <div class="row" style="padding: 5px" v-for="doc of documents" :key="doc.nome">
                                <div class="col-sm-6">
                                        <div class="card" style="width: 15rem;">
                                            <img class="card-img-top" src="https://img.freepik.com/free-photo/digital-cyberspace-with-particles-digital-data-network-connections_24070-1303.jpg?w=2000" alt="Card image cap">
                                            <div class="card-body">
                                                <h5 class="card-title">{{ doc.nome }}</h5>
                                                <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                                <a href="#" class="btn btn-primary">Go somewhere</a>
                                            </div>
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

<style>


</style>
