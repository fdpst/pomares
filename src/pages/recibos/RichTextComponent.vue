<template>
    <div
        id="editor"
        ref="quillContainer"></div>
</template>
<script setup>
import Quill from "quill";
import ImageResize from "quill-image-resize-vue";
import QuillImageDropAndPaste from "quill-image-drop-and-paste";
import {ref, defineProps, defineEmits, onMounted, watch, toRefs, nextTick} from "vue";

Quill.register("modules/imageDropAndPaste", QuillImageDropAndPaste);
Quill.register("modules/imageResize", ImageResize);

const props = defineProps({
    modelValue: {
        type: String,
        default: "",
    },
});

const emit = defineEmits(["update:modelValue"]);
const quillContainer = ref(null);
const quillInstance = ref(null);
const {modelValue} = toRefs(props);
let isInternalUpdate = false;
const toolbarOptions = ref([
    [{header: [1, 2, 3, 4, 5, 6, false]}],
    ["bold", "italic", "underline", "strike"],
    [{align: []}],
    ["blockquote", "code-block"],
    [{list: "ordered"}, {list: "bullet"}, {list: "check"}],
    [{indent: "-1"}, {indent: "+1"}],
    [{color: []}, {background: []}],
    ["link", "image", "video"],
    ["clean"],
]);

watch(
    () => props.modelValue,
    (newVal) => {
        nextTick(() => {
            if (!quillInstance.value) return;

            if (isInternalUpdate) {
                isInternalUpdate = false;
                return;
            }

            const currentHtml = quillInstance.value.root.innerHTML;
            const incomingHtml = newVal || "";

            if (currentHtml === incomingHtml) return;

            const selection = quillInstance.value.getSelection();
            quillInstance.value.root.innerHTML = incomingHtml;

            if (selection) {
                const length = quillInstance.value.getLength();
                const index = Math.min(selection.index, Math.max(length - 1, 0));
                quillInstance.value.setSelection(index, selection.length, "silent");
            }
        });
    },
    {
        immediate: true,
    }
);

const initQuillEditor = () => {
    quillInstance.value = new Quill(quillContainer.value, {
        modules: {
            toolbar: toolbarOptions.value,
            imageDropAndPaste: {
                autoConvert: false,
            },
            imageResize: {},
        },
        theme: "snow",
    });

    if (props.modelValue) {
        quillInstance.value.root.innerHTML = props.modelValue;
    }

    quillInstance.value.getModule("toolbar").addHandler("image", function () {
        const input = document.createElement("input");
        input.setAttribute("type", "file");
        input.setAttribute("accept", "image/*");
        input.click();

        input.onchange = () => {
            const file = input.files[0];
            const formData = new FormData();
            formData.append("image", file);

            axios({
                url: "api/upload-image-editor",
                method: "POST",
                data: formData,
                headers: {
                    "Content-Type": "multipart/form-data",
                },
            })
                .then((result) => {
                    const url = result.data.url;
                    const range = quillInstance.value.getSelection();
                    quillInstance.value.insertEmbed(
                        range.index,
                        "image",
                        result.data.url
                    );
                })
                .catch((err) => {
                    console.error("Error al subir la imagen:", err);
                });
        };
    });

    quillInstance.value.on("text-change", (delta, oldDelta, source) => {
        if (source === "user") {
            const html = quillInstance.value.root.innerHTML;
            const contentToEmit = html === "<p><br></p>" ? "" : html;
            isInternalUpdate = true;
            emit("update:modelValue", contentToEmit);
        }
    });
};

onMounted(initQuillEditor);
</script>

<style>
#editor {
    min-height: 200px;
}

.ql-toolbar {
    margin-top: 25px;
}
</style>
