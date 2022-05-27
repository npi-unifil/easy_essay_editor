<template>
    <div>

        <div class="flex flex-wrap">
            <button @click="applyBold" class="button">
                <font-awesome-icon icon="bold"></font-awesome-icon>
            </button>
            <button @click="applyItalic" class="button">
                <font-awesome-icon icon="italic"></font-awesome-icon>
            </button>
            <button @click="applyHeading" class="button">
                <font-awesome-icon icon="heading"></font-awesome-icon>
            </button>
            <button @click="applyHeadingH2" class="button">
                <i style="font-weight: bold;">H2</i>
            </button>
            <button @click="applyHeadingH3" class="button">
                <i style="font-weight: bold;">H3</i>
            </button>
            <button @click="applyUl" class="button">
                <font-awesome-icon icon="list-ul"></font-awesome-icon>
            </button>
            <button @click="applyOl" class="button">
                <font-awesome-icon icon="list-ol"></font-awesome-icon>
            </button>
            <button @click="undo" class="button">
                <font-awesome-icon icon="undo"></font-awesome-icon>
            </button>
            <button @click="redo" class="button">
                <font-awesome-icon icon="redo"></font-awesome-icon>
            </button>
            <button @click="insertImage" class="button">
                <i><img src="https://static.thenounproject.com/png/2106484-200.png" style="height: 20px; width: 20px"></i>
            </button>
        </div>

        <div
            @input="onInput"
            v-html="innerValue"
            contenteditable="true"
            class="wysiwyg-output outline-none border-2 p-4 rounded-lg border-gray-300 focus:border-green-300"
        />



    </div>
</template>

<script>

function createElement(element){
    const strongElement = document.createElement(element);
    const userSelection = window.getSelection();
    const selectedTextRange = userSelection.getRangeAt(0);
    selectedTextRange.surroundContents(strongElement);
}

import TurndownService from 'turndown'

export default {
    name: 'WysiwygEditor',

    props: ['value'],

    data() {
        return {
            innerValue: this.value || '<p><br></p>'
        }
    },


    methods: {
        onInput(event){
            const turndown = new TurndownService({
                emDelimiter: '_',
                linkStyle: 'inlined',
                headingStyle: 'atx'
            })
            this.$emit('input', turndown.turndown(event.target.innerHTML))
        },

        applyBold(){
            createElement("strong")
            //document.execCommand('bold')
        },

        applyItalic(){
            createElement("i")
            //document.execCommand('italic')
        },

        applyHeading(){
            createElement("h1")
            //document.execCommand('formatBlock', false, '<h1>')
        },

        applyHeadingH2(){
            document.execCommand('formatBlock', false, '<h2>')
        },

        applyHeadingH3(){
            document.execCommand('formatBlock', false, '<h3>')
        },

        applyUl(){
            document.execCommand('insertUnorderedList')
        },

        applyOl(){
            document.execCommand('insertOrderedList')
        },

        undo(){
            document.execCommand('undo')
        },

        redo(){
            document.execCommand('redo')
        },

        insertImage(){
            var url = prompt('Enter the link here: ','');
            if(!url == ''){
                document.execCommand('insertimage', 0, url)
            }
        }
    }
}
</script>
