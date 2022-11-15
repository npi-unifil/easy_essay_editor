<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';;
import { Head } from '@inertiajs/inertia-vue3';
import { ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { reactive } from 'vue';
import { Link } from '@inertiajs/inertia-vue3';
import { useEditorStore } from '@/utils/EditorStore';
import SideModal from '../Components/EditorComponents/SideModal.vue';
import Modal from '@/Components/EditorComponents/Modal.vue';
import rnd from '../utils/generator.js';
</script>

<script>
export default {
    props: ['id', 'nomeAutor', 'edit',
        'orientador', 'cidade',
        'ano', 'curso', 'banca',
        'dedicatoria', 'agradecimentos',
        'epigrafe', 'template',
        'document_name', 'capitulos', 'isNewDoc'],

    components: {
        SideModal,
        Modal
    },

    methods: {
        retornarGerencia(){
          Inertia.get('/gerenciar/' + this.id);
        },

        getComponentId(){
            return rnd(20, rnd.alphaLower);
        },

        showModal(index, id, nome) {
            this.isModalVisible = true;
            this.chapter_data.index = index;
            this.chapter_data.id = id;
            this.chapter_data.nome = nome;
        },
        closeModal() {
            this.isModalVisible = false;
        },

        openSideModal() {
            this.isOpened = true;
        },

        closeSideModal() {
            this.isOpened = false;
        },

        keypressed: function (event) {
            if (this.nome_banca.length === 0) return;
            if (event.key == "Enter") {
                this.adicionar_novo();
            }
        },

        adicionar_novo() {
            if (this.nome_banca.length === 0) return;
            if (this.editedTitle === null) {
                this.dados.banca.push({
                    nome: this.nome_banca
                })
            } else {
                this.dados.banca[this.editedTitle].nome = this.nome_banca;
                this.editedTitle = null;
            }
            this.nome_banca = "";
        },

        deletarBanca(index) {
            this.dados.banca.splice(index, 1);
        },

        editar_nome(index) {
            this.nome_banca = this.dados.banca[index].nome;
            this.editedTitle = index;
        },

        newChapter() {
            Inertia.get('/newchapter/' + this.id);
        },

        editChapter(id) {
            Inertia.get('/editChapter/' + id);
        },

        delete_chapter() {
            this.dados.capitulos.splice(this.chapter_data.index, 1);
            Inertia.delete('/chapter/' + this.chapter_data.id);
        },

        saveDocument() {
            if (this.isNewDoc == 'true') {
                let dados = {
                    id: this.dados.id,
                    nomeAutor: this.dados.nomeAutor,
                    nome: this.dados.nome,
                    template: this.dados.template,
                    orientador: this.dados.orientador,
                    cidade: this.dados.cidade,
                    ano: this.dados.ano,
                    curso: this.dados.curso,
                    banca: this.dados.banca,
                    dedicatoria: this.dados.dedicatoria,
                    agradecimentos: this.dados.agradecimentos,
                    epigrafe: this.dados.epigrafe,
                    capitulos: [],
                    isNewDoc: this.dados.isNewDoc
                }
                if (dados.dedicatoria == 'true') {
                    dados.capitulos.push({
                        nome: 'Dedicatória',
                        component_id: this.getComponentId()
                    })
                }
                if (dados.agradecimentos == 'true') {
                    dados.capitulos.push({
                        nome: 'Agradecimentos',
                        component_id: this.getComponentId()
                    })
                }
                if (dados.epigrafe == 'true') {
                    dados.capitulos.push({
                        nome: 'Epígrafe',
                        component_id: this.getComponentId()
                    })
                }
                dados.capitulos.push({
                    nome: 'Resumo',
                    component_id: this.getComponentId()
                })
                if (dados.template == 1) {
                    dados.capitulos.push({
                        nome: 'Lista de Abreviaturas e Siglas',
                        component_id: this.getComponentId()
                    })
                }
                dados.capitulos.push({
                    nome: 'Introdução',
                    component_id: this.getComponentId()
                })
                Inertia.post('/documents', dados);
            } else {
                Inertia.post('/documents', this.dados);
            }
            this.closeSideModal();
        }
    },

    mounted() {
        if (this.capitulos != undefined | this.capitulos != null) {
            this.dados.capitulos = this.capitulos;
        }
        if (this.banca != undefined | this.banca != null) {
            this.dados.banca = this.banca;
        }
        if (this.dedicatoria != undefined | this.dedicatoria != null) {
            this.dados.dedicatoria = this.dedicatoria
        }
        if (this.agradecimentos != undefined | this.agradecimentos != null) {
            this.dados.agradecimentos = this.agradecimentos
        }
        if (this.epigrafe != undefined | this.epigrafe != null) {
            this.dados.epigrafe = this.epigrafe
        }
    },

    data() {
        const editorStore = useEditorStore();
        const chapter_data = {
            index: 0,
            id: 0,
            nome: '',
        }

        const dados = {
            id: this.id,
            nomeAutor: this.nomeAutor,
            nome: this.document_name,
            template: this.template,
            orientador: this.orientador,
            cidade: this.cidade,
            ano: this.ano,
            curso: this.curso,
            banca: [],
            dedicatoria: 'false',
            agradecimentos: 'false',
            epigrafe: 'false',
            capitulos: [],
            isNewDoc: this.isNewDoc
        }
        return { editorStore, chapter_data, dados, nome_banca: '', editedTitle: null, isOpened: false, isModalVisible: false };
    }

}
</script>

<style>
.autores {
    display: block;
    margin: 20px;
    width: 130%;
}

.autores input {
    margin-left: 10px;
}

.add-autor {
    display: block;
    margin-bottom: 10px;
}

.add-autor input {
    border-bottom: 1px solid black;
    width: 60%;
}

.nome-autor {
    display: flex;
    justify-content: space-between;
}

.nome-autor button {
    border-radius: 5px;
    color: white;
    font-weight: bold;
    width: 80px;
    height: 30px;
}

.chapter-button {
    border-radius: 5px;
    background-color: orange;
    color: white;
    width: 200px;
    height: 50px;
}

.modal-body {
    justify-content: center;
    align-items: center;
    text-align: center;
}

.modal-body p {
    margin: 0;
    font-size: xx-large;
    font-weight: bolder;
    margin-top: -30px;
    margin-bottom: 60px;
}

#modal-buttons {
    display: flex;
    justify-content: space-around;
}

#modal-buttons button {
    color: white;
    font-weight: bolder;
    border-radius: 5px;
    width: 100px;
    height: 50px;
}
</style>

<template>

    <Head title="Documentos" />

    <BreezeAuthenticatedLayout>
        <template #links>
            <div class="mt-4 ml-2">
                <a href="/documents" class="no-underline font-bold text-slate-100 hover:text-slate-800">Documentos</a>
            </div>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div @click="openSideModal()" class="cursor-pointer text-center">
                            <h1 v-if="this.dados.nome == '' | this.dados.nome == null">
                                <p>Adicione um titulo...</p>
                            </h1>
                            <h1 v-if="this.dados.nome != ''">
                                <p>{{ this.dados.nome }}</p>
                            </h1>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <button @click="retornarGerencia()" class="chapter-button">Retornar</button>
                            <h1>Capitulos: </h1>
                            <button v-if="this.id != undefined | this.id != null" class="chapter-button"
                                @click="newChapter">Adicionar Capitulo</button>
                        </div>
                        <h3 class="mt-8" v-if="this.dados.capitulos.length == 0">Adicione um novo capitulo ao seu
                            trabalho...</h3>
                        <div v-for="item, index in this.capitulos" :key="index" class="cursor-pointer mt-4">
                            <div v-if="this.isNewDoc != 'true'" style="display: flex; justify-content: space-between;">
                                <h2 @click="editChapter(item['id'])" class="cursor-pointer mt-4">{{ index + 1 }} - {{
                                        item['name']
                                }}</h2>
                                <button
                                    style="border-radius: 5px; width: 100px; height: 40px; color: white; font-weight: bolder; background-color: red;"
                                    @click="showModal(index, item['id'], item['name'])">
                                    Deletar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <Modal v-show="isModalVisible" @close="closeModal">
            <template class="modal-body" v-slot:body>
                <div>
                    <div>
                        <p>Deseja Realmente Apagar o Capitulo {{ this.chapter_data.nome }}?</p>
                    </div>
                    <div id="modal-buttons">
                        <button @click="closeModal()" style="background-color: green">
                            Cancelar
                        </button>
                        <button @click="closeModal(), delete_chapter()" style="background-color: red">
                            Sim
                        </button>
                    </div>
                </div>
            </template>
        </Modal>

        <SideModal v-show="isOpened" @close="closeSideModal">
            <template v-slot:body>
                <div style="height: 100%; background-color: white; width: 70%">
                    <div style="text-align: center; margin-top: 23px; width: 140%">
                        <h1>Adicionar Informações: </h1>
                    </div>
                    <div>
                        <div class="autores">
                            <div class="add-autor">
                                <div style="margin-bottom: 10px;">
                                    <label for="nomeAutor">Nome: </label>
                                    <input v-model="this.dados.nomeAutor" />
                                </div>
                                <div style="margin-bottom: 10px;">
                                    <label for="titulo">Titulo: </label>
                                    <input v-model="this.dados.nome" />
                                </div>
                                <div style="margin-bottom: 10px;">
                                    <label for="orientador">Orientador: </label>
                                    <input v-model="this.dados.orientador" />
                                </div>
                                <div style="margin-bottom: 10px;">
                                    <label for="londrina">Cidade: </label>
                                    <input v-model="this.dados.cidade" />
                                </div>
                                <div style="margin-bottom: 10px;">
                                    <label for="ano">Ano: </label>
                                    <input v-model="this.dados.ano" />
                                </div>
                                <div style="margin-bottom: 10px;">
                                    <label for="curso">Curso: </label>
                                    <input v-model="this.dados.curso" />
                                </div>
                                <div style="margin-bottom: 10px;" v-if="this.template == 1">
                                    <p>Elementos opcionais:</p>
                                </div>
                                <div style="margin-bottom: 10px;" v-if="this.template == 1">
                                    <label for="dedicatoria">Dedicatoria</label>
                                    <input style="width: 30px" type="checkbox" v-model="this.dados.dedicatoria"
                                        true-value="true" false-value="false" />
                                </div>
                                <div style="margin-bottom: 10px;" v-if="this.template == 1">
                                    <label for="agradecimentos">Agradecimentos</label>
                                    <input style="width: 30px" type="checkbox" v-model="this.dados.agradecimentos"
                                        true-value="true" false-value="false" />
                                </div>
                                <div style="margin-bottom: 10px;" v-if="this.template == 1">
                                    <label for="epigrafe">Epigrafe</label>
                                    <input style="width: 30px" type="checkbox" v-model="this.dados.epigrafe"
                                        true-value="true" false-value="false" />
                                </div>
                                <label v-if="this.template == 1" for="nome">Adicionar examinador da Banca(se houver): </label>
                                <div style="display: flex;" v-if="this.template == 1">
                                    <input v-model="this.nome_banca" v-on:keyup="keypressed" />
                                    <button @click="adicionar_novo"
                                        style="background-color: orange; width: 80px; height: 30px; border-radius: 5px; margin-left: 10px;">Adicionar</button>
                                </div>
                            </div>
                            <div v-if="this.template == 1" v-for="nome, index  in this.dados.banca" :key="index">
                                <div class="nome-autor">
                                    <div style="display:flex; width: 100%; justify-content: space-between;">
                                        <p>{{ nome.nome }}</p>
                                        <div style="display:flex; width: 50%; justify-content: space-between;">
                                            <button @click="editar_nome(index)" style="background-color: orange;">
                                                Editar
                                            </button>
                                            <button style="background-color: red;" @click="deletarBanca(index)">
                                                Deletar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button class="chapter-button" @click="saveDocument">Salvar</button>
                        </div>
                    </div>
                </div>
            </template>
        </SideModal>

    </BreezeAuthenticatedLayout>

</template>

