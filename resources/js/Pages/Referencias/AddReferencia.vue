<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';;
import { Head } from '@inertiajs/inertia-vue3';
import { ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { reactive } from 'vue';
import { Link } from '@inertiajs/inertia-vue3';
</script>

<script>
export default {

    props: [
        'doc_id',
        'nome',
        'sobrenome',
        'titulo',
        'subtitulo',
        'edicao',
        'local',
        'editora',
        'ano',
        'pagina',
        'site',
        'acessado',
    ],

    data() {
        const referencia = {
            documento: this.doc_id,
            nome: this.nome,
            sobrenome: this.sobrenome,
            titulo: this.titulo,
            subtitulo: this.subtitulo,
            edicao: this.edicao,
            local: this.local,
            editora: this.editora,
            ano: this.ano,
            pagina: this.pagina,
            site: this.site,
            acessado: this.acessado,
        }
        return { referencia };
    },

    methods: {

        retornar() {
            Inertia.get('/documents');
        },

        salvar_referencia(){
            Inertia.post('/salvar_referencia', this.referencia);
        }
    }

}
</script>

<style>
.form-container {
    display:flex;
    justify-content: space-between;
    margin-top: 20px;
}

.form-container p{
    font-size: larger;
    font-weight: bolder;
}

.form-container input{
    text-align: center;
    width: 300px;
    border-bottom: 1px solid rgba(0, 0, 0, 0.322);
}

#save-button{
    display: flex;
    justify-content: center;
}

#save-button button{
    background-color: orange;
    width: 100px;
    height: 50px;
    border-radius: 5px;
}

</style>

<template>

    <Head title="Adicionar Referência" />

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

                        <h1>Adicionar Referência</h1>
                        <div class="form-container">
                            <div>
                                <p>Nome do Autor:</p>
                                <input v-model=referencia.nome />
                            </div>
                            <div>
                                <p>Sobrenome do Autor:</p>
                                <input v-model=referencia.sobrenome />
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
                                <p>Editora:</p>
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
                                <p>Data de acesso:</p>
                                <input style="border-bottom: 1px solid rgba(0, 0, 0, 0.322); border-left: none; border-right: none; border-top: none;" type="date" v-model=referencia.acessado />
                            </div>
                        </div>
                        <div id="save-button">
                            <button @click="salvar_referencia">Salvar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>



    </BreezeAuthenticatedLayout>

</template>

