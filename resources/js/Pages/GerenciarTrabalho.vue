<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';;
import { Head } from '@inertiajs/inertia-vue3';
import { ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { reactive } from 'vue';
import { Link } from '@inertiajs/inertia-vue3';
import { useEditorStore } from '@/utils/EditorStore';
import Modal from '../Components/EditorComponents/Modal.vue';
import SideModal from '../Components/EditorComponents/SideModal.vue'
import 'vue3-side-panel/dist/vue3-side-panel.css';
</script>

<script>
export default {

    props: ['id', 'nome', 'templates', 'pdfFormatado'],

    components: {
        SideModal,
        Modal
    },

    mounted() {
        if(this.pdfFormatado == 1){
            this.openPdfModal();
        }
    },

    data() {
        const editorStore = useEditorStore();

        return { isModalVisible: false,  pdfGerado: false,successModal: false, templateName: '', isOpened: false, editorStore };
    },

    methods: {
        gerenciar_doc() {
            Inertia.get('/documents/' + this.id);
        },

        gerenciar_referencias() {
            Inertia.get('/referencias/' + this.id);
        },

        mudarTemplate(template, name){
            this.templateName = name;
            Inertia.put('/mudarTemplate/' + this.id + '/' + template);
            this.closeSideModal();
            this.openSuccessModal();
        },

        openPdfModal() {
            this.pdfGerado = true;
        },

        closePdfModal(){
            this.pdfGerado = false;
            Inertia.get('/gerenciar/' + this.id);
        },

        openSuccessModal() {
            this.successModal = true;
        },

        closeSuccessModal(){
            this.successModal = false;
        },

        openSideModal() {
            this.isOpened = true;
        },

        closeSideModal(){
            this.isOpened = false;
        },

        exportPdf() {
            this.editorStore.exportPdf(this.id);
        },

        showModal() {
            this.isModalVisible = true;
        },
        closeModal() {
            this.isModalVisible = false;
        },

        deletar_documento() {
            Inertia.delete('/documents/' + this.id);
        },

        retornar() {
            Inertia.get('/documents');
        }
    }

}
</script>

<style>
.conteudo-corpo {
    display: flex;
    justify-content: space-between;
}

.documento {
    border: 1px solid black;
    width: 700px;
    height: 500px;
    text-align: center;
}

.gerenciar-botoes {
    width: 30%;
    display: block;
}

.gerenciar-botoes button {
    width: 200px;
    height: 60px;
    margin-bottom: 20px;
    border-radius: 10px;
    font-size: large;
    font-weight: bolder;
    color: white;
    background-color: blue;
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

    <Head title="Gerenciar Trabalho" />

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
                        <div class="conteudo-corpo">
                            <div class="gerenciar-botoes">
                                <button style="background-color: pink;" @click="openSideModal">
                                    Mudar Template
                                </button>
                                <button style="background-color: green;" @click="gerenciar_doc">
                                    Editar Documento
                                </button>
                                <button style="background-color: blue;" @click="gerenciar_referencias">
                                    Gerenciar Referencias
                                </button>
                                <button style="background-color: orange;" @click="exportPdf">
                                    Gerar Trabalho Formatado
                                </button>
                                <button style="background-color: red;" @click="showModal">
                                    Deletar Documento
                                </button>
                                <button style="background-color: yellow;" @click="retornar">
                                    Retornar
                                </button>
                            </div>

                            <div class="documento">
                                <h1 style="margin-top: 50px;">{{this.nome}}</h1>
                                <p>----------------------------------------------------------------------------</p>
                                <p>----------------------------------------------------------------------------</p>
                                <p>----------------------------------------------------------------------------</p>
                                <p>----------------------------------------------------------------------------</p>
                                <p>----------------------------------------------------------------------------</p>
                                <p>----------------------------------------------------------------------------</p>
                                <p>----------------------------------------------------------------------------</p>
                                <p>----------------------------------------------------------------------------</p>
                                <p>----------------------------------------------------------------------------</p>
                            </div>
                        </div>

                        <Modal v-show="isModalVisible" @close="closeModal">
                            <template class="modal-body" v-slot:body>
                                <div>
                                    <div>
                                        <p>Deseja Realmente Apagar o Documento ?</p>
                                    </div>
                                    <div id="modal-buttons">
                                        <button @click="closeModal()" style="background-color: green">
                                            Cancelar
                                        </button>
                                        <button @click="closeModal(), deletar_documento()"
                                            style="background-color: red">
                                            Sim
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </Modal>

                        <Modal v-show="successModal" @close="closeSuccessModal">
                            <template class="modal-body" v-slot:body>
                                <div>
                                    <div>
                                        <p>Template alterado para {{this.templateName}} com sucesso!</p>
                                    </div>
                                    <div id="modal-buttons">
                                        <button @click="closeSuccessModal()"
                                            style="background-color: orange">
                                            Fechar
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </Modal>

                        <Modal v-show="pdfGerado" @close="closePdfModal">
                            <template class="modal-body" v-slot:body>
                                <div>
                                    <div style="margin-bottom: -45px;">
                                        <p style="font-size: 18px; margin-bottom: 40px; color: orangered">ESTAMOS FORMATANDO O SEU <br/>DOCUMENTO</p>
                                        <p style="font-size: 15px; text-align: center;">Nosso sistema está formatando seu
                                            documento e gerando um arquivo PDF. Assim
                                            que a formatação estiver pronta,
                                            será enviado no seu e-mail cadastrado.
                                        </p>
                                    </div>
                                    <div id="modal-buttons">
                                        <button @click="closePdfModal()"
                                            style="border: 1px solid orangered; color: orangered; width: 300px;">
                                            Fechar
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </Modal>

                        <SideModal v-show="isOpened" @close="closeSideModal">
                            <template v-slot:body>
                                <div style="height: 100%; background-color: white; width: 100%">
                                    <h1 style="text-align: center; margin-top: 23px;">Templates</h1>

                                    <div style="display: block; justify-content: center;">
                                        <div class="templates" v-for="template in this.templates" :key="template.id">
                                            <p>{{template.nome}}</p>
                                            <div id="botao-selecionar">
                                                <button style="background-color: green;" @click="mudarTemplate(template.id, template.nome)">
                                                    Selecionar
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </SideModal>

                    </div>
                </div>
            </div>
        </div>

    </BreezeAuthenticatedLayout>

</template>

