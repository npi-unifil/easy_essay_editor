<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';;
import { Head } from '@inertiajs/inertia-vue3';
import { ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { reactive } from 'vue';
import { Link } from '@inertiajs/inertia-vue3';
import SideModal from '../Components/EditorComponents/SideModal.vue';
</script>

<script>
export default {
    props: ['documents', 'templates'],

    components: {
        SideModal,
    },

    methods: {
        gerenciar(id) {
            Inertia.get('/gerenciar/' + id);
        },

        openSideModal() {
            this.isOpened = true;
        },

        closeSideModal(){
            this.isOpened = false;
        },

        newDoc(template) {
            Inertia.get('/novo_documento/'+ template);
        }

    },

    data() {
        return { isOpened: false };
    }

}
</script>

<style>
.card:hover {
    cursor: pointer;
}

.card .card-title {
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
}

#new-doc-button:hover {
    color: rgba(34, 28, 23, 0.671);
    cursor: pointer;
}

.templates{
        display: flex;
        justify-content: space-between;
        width: 100%;
        margin-top: 10px;
        border-bottom: 1px solid black;
}

#botao-selecionar button{
        color: white;
        font-size: large;
        font-weight: bolder;
        width: 100px;
        height: 30px;
        border-radius: 5px;
}
</style>

<template>

    <Head title="Documentos" />

    <BreezeAuthenticatedLayout>
        <template #links>
            <div class="mt-4 ml-2">
                <a id="new-doc-button" @click="openSideModal">Novo Documento</a>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="card-group">
                            <div class="row" style="padding: 5px" v-for="doc of documents" :key="doc.nome">

                                <div class="col-sm-6">
                                    <div class="card" style="width: 13rem;" @click="gerenciar(doc.id)">
                                        <form @submit.prevent="submit">
                                            <img class="card-img-top"
                                                src="https://img.freepik.com/free-photo/digital-cyberspace-with-particles-digital-data-network-connections_24070-1303.jpg?w=2000"
                                                alt="Card image cap">
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


        <SideModal v-show="isOpened" @close="closeSideModal">
            <template v-slot:body>
                <div style="text-align: center; height: 100%; background-color: white; width: 100%">
                    <h1 style="text-align: center; margin-top: 23px;">Selecione um Templates</h1>

                    <div style="display: block; justify-content: center;">
                        <div class="templates" v-for="template in this.templates" :key="template.id">
                                <p>{{template.nome}}</p>
                                <div id="botao-selecionar">
                                    <button style="background-color: green;" @click="newDoc(template.id)">
                                        Selecionar
                                    </button>
                                </div>
                        </div>
                    </div>
                </div>
            </template>
        </SideModal>

    </BreezeAuthenticatedLayout>

</template>

