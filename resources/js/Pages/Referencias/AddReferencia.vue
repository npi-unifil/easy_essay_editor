<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';;
import { Head } from '@inertiajs/inertia-vue3';
import { ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { reactive } from 'vue';
import { Link } from '@inertiajs/inertia-vue3';
import { VueSidePanel } from 'vue3-side-panel';
</script>

<script>
export default {

    props: [
        'titulo_da_pagina',
        'id',
        'doc_id',
        'nome_autor',
        'titulo',
        'subtitulo',
        'edicao',
        'local',
        'editora',
        'ano',
        'pagina',
        'site',
        'nomeDoSite',
        'acessado',
    ],

    components: {
        VueSidePanel
    },

    data() {
        const nome = ""

        const editedNome = null

        const referencia = {
            id: this.id,
            documento: this.doc_id,
            nome_autor: [],
            titulo: this.titulo,
            subtitulo: this.subtitulo,
            edicao: this.edicao,
            local: this.local,
            editora: this.editora,
            ano: this.ano,
            pagina: this.pagina,
            site: this.site,
            nomeDoSite: this.nomeDoSite,
            acessado: this.acessado,
        }
        return { isOpened: false, referencia, nome, editedNome };
    },


    methods: {
        retornarReferencias() {
            Inertia.get('/referencias/' + this.doc_id);
        },

        keypressed: function (event) {
            if (this.nome.length === 0) return;
            if (event.key == "Enter") {
                this.adicionar_novo();
            }
        },

        adicionar_novo() {
            if (this.nome.length === 0) return;
            if (this.editedNome === null) {
                this.referencia.nome_autor.push({
                    nome: this.nome
                })
            } else {
                this.referencia.nome_autor[this.editedNome].nome = this.nome;
                this.editedNome = null;
            }
            this.nome = "";
        },

        deleteAutor(index) {
            this.referencia.nome_autor.splice(index, 1);
        },

        editar_nome(index) {
            this.nome = this.referencia.nome_autor[index].nome;
            this.editedNome = index;
        },

        add_autor() {
            this.isOpened = true;
        },

        retornar() {
            Inertia.get('/documents');
        },

        salvar_referencia() {
            Inertia.post('/salvar_referencia', this.referencia);
        },
    },

    mounted() {
        if (this.nome_autor != undefined) {
            this.referencia.nome_autor = this.nome_autor;
        }
    }

}
</script>

<style>
.form-container {
    display: flex;
    justify-content: space-between;
    margin-top: 20px;
}

.form-container p {
    font-size: larger;
    font-weight: bolder;
}

.form-container input {
    text-align: center;
    width: 300px;
    border-bottom: 1px solid rgba(0, 0, 0, 0.322);
}

.autores {
    display: block;
    margin: 20px;
    width: 500px;
    border-bottom: 1px solid black;
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
    width: 350px;
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

#back-button{
    color: white;
    background-color: orange;
    font-size: large;
    font-weight: bolder;
    width: 100px;
    height: 30px;
    border-radius: 5px;
}

#save-button {
    display: block;
    justify-content: center;
}

#save-button button {
    background-color: orange;
    width: 80px;
    height: 40px;
    border-radius: 5px;
    color: white;
    font-weight: bold;
}
</style>

<template>

    <Head :title='this.titulo_da_pagina' />

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
                        <div style="display: flex; justify-content: space-between;">
                            <button id="back-button" @click="retornarReferencias()">Retornar</button>
                            <h1>{{this.titulo_da_pagina}}</h1>
                            <br>
                        </div>
                        <div class="form-container">
                            <div>
                                <p>Nome do(s) Autor(es):</p>
                                <input type="button" @click="add_autor">
                            </div>
                            <div>
                                <p>Titulo da referência:</p>
                                <input v-model=referencia.titulo />
                            </div>
                        </div>
                        <div class="form-container">
                            <div>
                                <p>Subititulo(se houver):</p>
                                <input v-model=referencia.subtitulo />
                            </div>
                            <div>
                                <p>Numero da Edição:</p>
                                <input v-model=referencia.edicao />
                            </div>
                            <div>
                                <p>Local(cidade):</p>
                                <input v-model=referencia.local />
                            </div>
                        </div>
                        <div class="form-container">
                            <div>
                                <p>Editora ou Artigo:</p>
                                <input v-model=referencia.editora />
                            </div>
                            <div>
                                <p>Ano da publicação:</p>
                                <input v-model=referencia.ano />
                            </div>
                            <div>
                                <p>Página da publicação:</p>
                                <input v-model=referencia.pagina />
                            </div>
                        </div>
                        <div class="form-container">
                            <div>
                                <p>Site:</p>
                                <input v-model=referencia.site />
                            </div>
                            <div>
                                <p>Nome do Site:</p>
                                <input v-model=referencia.nomeDoSite />
                            </div>
                            <div>
                                <p>Data de acesso:</p>
                                <input
                                    style="border-bottom: 1px solid rgba(0, 0, 0, 0.322); border-left: none; border-right: none; border-top: none;"
                                    type="date" v-model=referencia.acessado />
                            </div>
                        </div>
                        <div id="save-button" style="display:flex; justify-content: space-between;">
                            <br>
                            <button @click="salvar_referencia">Salvar</button>
                            <br>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <VueSidePanel v-model="isOpened">
            <div style="height: 100%; background-color: white; width: 600px">
                <h1 style="text-align: center; margin-top: 23px;">Autor(es)</h1>

                <div style="display: block; justify-content: center;">
                    <div class="autores">
                        <div class="add-autor">
                            <label for="nome">Nome:</label>
                            <input v-model="this.nome" v-on:keyup="keypressed" />
                            <button @click="adicionar_novo"
                                style="background-color: orange; width: 80px; height: 30px; border-radius: 5px; margin-left: 10px;">Salvar</button>
                        </div>
                        <div v-for="autor, index  in this.referencia.nome_autor" :key="index">
                            <div class="nome-autor">
                                <p>{{autor.nome}}</p>
                                <div style="display:flex; width: 170px; justify-content: space-between;">
                                    <button @click="editar_nome(index)" style="background-color: orange;">
                                        Editar
                                    </button>
                                    <button style="background-color: red;" @click="deleteAutor(index)">
                                        Deletar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </VueSidePanel>


    </BreezeAuthenticatedLayout>

</template>

