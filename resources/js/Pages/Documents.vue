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

        closeSideModal() {
            this.isOpened = false;
        },

        newDoc(template) {
            Inertia.get('/novo_documento/' + template);
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

.templates {
    display: flex;
    justify-content: space-between;
    width: 100%;
    margin-top: 10px;
    border-bottom: 1px solid black;
}

#botao-selecionar button {
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
                            <div class="grid grid-cols-4 gap-4">
                                <div v-if="documents == null | documents == undefined">
                                    <h2>Você não possui documentos, <br> selecione novo documento acima e crie um
                                        novo...</h2>
                                </div>

                                <div class="card" @click="gerenciar(doc.id)"
                                    v-for="doc of documents" :key="doc.nome">
                                    <span
                                        class="ml-2 mt-2 font-medium text-xs leading-5 rounded-full text-sky-600 bg-sky-400/10 px-2 py-0.5 dark:text-sky-400 z-20 absolute">{{doc.template.nome}}</span>
                                    <form @submit.prevent="submit">
                                        <div class="card-body mt-6">
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


        <SideModal class="absolute" v-show="isOpened" @close="closeSideModal">
            <template v-slot:body>
                <div style="text-align: center; height: 100%; background-color: white; width: 100%">
                    <h1 style="text-align: center; margin-top: 23px;">Selecione um Templates</h1>

                    <div style="display: block; justify-content: center;">
                        <div class="templates" v-for="template in this.templates" :key="template.id">
                            <p>{{ template.nome }}</p>
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

