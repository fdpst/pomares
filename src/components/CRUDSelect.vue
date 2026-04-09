<script setup>
const props = defineProps({
    modelValue: {
        type: Number,
        required: true,
        default: null,
    },
    title: {
        type: String,
        required: true,
    },
    items: {
        type: Array,
        required: true,
    },
    itemTitle: {
        type: String,
        required: true,
    },
    itemValue: {
        type: String,
        required: true,
    },
    label: {
        type: String,
        required: true,
    },
    placeholder: {
        type: String,
    },
    rules: {
        type: Array,
    },
    clearable: {
        type: Boolean,
        default: false,
    },
    density: {
        type: String,
    },
});

const emit = defineEmits([
    "update:modelValue",
    "update:selectedItem",
    "submit",
    "delete",
]);

const id = ref(null);
const selectedItem = ref({
    id: null,
    serie: "",
});

const formValid = ref({});
const refForm = ref(true);
const isDialogOpen = ref(false);
const isConfirmDialogVisible = ref(false);

const updateModelValue = (newValue) => {
    emit("update:modelValue", newValue);
};

const onFormSubmit = () => {
    refForm.value?.validate().then(({ valid }) => {
        if (valid) {
            console.log("selectedItem.value", selectedItem.value);
            emit("update:selectedItem", selectedItem.value);
            emit("submit", selectedItem.value);
            isDialogOpen.value = false;
            nextTick(() => {
                refForm.value?.reset();
                refForm.value?.resetValidation();
            });
        }
    });
};

const openDialog = (item) => {
    if (item != null) {
        selectedItem.value = item;
    } else {
        id.value = null;
        resetSelectedItem();
    }
    emit("update:selectedItem", selectedItem.value);
    isDialogOpen.value = true;
};

const closeDialog = () => {
    isDialogOpen.value = false;
};

const openConfirmDialog = (item) => {
    if (item != null) {
        selectedItem.value = item;
    }
    isConfirmDialogVisible.value = true;
};

const deleteItem = () => {
    console.log("selectedItem.value", selectedItem.value);
    emit("delete", selectedItem.value.id);
    id.value = null;
    resetSelectedItem();
    isConfirmDialogVisible.value = false;
};

const resetSelectedItem = () => {
    selectedItem.value = {
        id: null,
        serie: "",
    };
};

watch(() => {
    if (props.modelValue != null) {
        id.value = props.modelValue;
    }
});

watch(id, (newVal) => {
    if (newVal != null) {
        selectedItem.value = props.items.find((item) => item.id === newVal);
    }
});
</script>

<template>
    <div class="d-flex align-center">
        <VAutocomplete
            v-model="id"
            :density="density"
            :items="props.items"
            :item-title="props.itemTitle"
            :item-value="props.itemValue"
            :label="props.label"
            :placeholder="props.placeholder"
            :rules="props.rules"
            :clearable="props.clearable"
            @update:model-value="updateModelValue"
        >
            <template #item="{ props, item }">
                <VListItem v-bind="props">
                    <VListItemTitle>{{
                        item.raw[props.itemTitle]
                    }}</VListItemTitle>
                    <template #append>
                        <VBtn
                            icon="ri-edit-box-line"
                            variant="text"
                            size="small"
                            @click="openDialog(item.raw)"
                        />
                        <VBtn
                            icon="ri-delete-bin-7-line"
                            variant="text"
                            color="error"
                            size="small"
                            @click="openConfirmDialog(item.raw)"
                        />
                    </template>
                </VListItem>
            </template>
        </VAutocomplete>

        <VBtn
            icon="ri-add-line"
            size="small"
            class="ml-2"
            color="#5142A6"
            @click="openDialog(null)"
        />
    </div>

    <VDialog v-model="isDialogOpen" width="500">
        <VCard>
            <DialogCloseBtn
                variant="text"
                size="default"
                @click="closeDialog"
            />

            <VCardTitle>
                <div class="text-center pb-6">
                    <h4 class="text-h4 mb-2">
                        {{
                            id == null
                                ? `Nuevo ${props.title}`
                                : `Editar ${props.title}`
                        }}
                    </h4>
                    <div class="text-body-1">
                        Ingrese los datos del {{ props.title }}
                    </div>
                </div>
            </VCardTitle>
            <VCardText>
                <VForm
                    ref="refForm"
                    v-model="formValid"
                    @submit.prevent="onFormSubmit"
                >
                    <VRow>
                        <slot name="form" />
                        <VCol
                            cols="12"
                            class="d-flex flex-wrap justify-center gap-4"
                        >
                            <VBtn type="submit" color="primary">
                                Confirmar
                            </VBtn>
                            <VBtn
                                type="button"
                                color="secondary"
                                variant="outlined"
                                @click="closeDialog"
                            >
                                Cancelar
                            </VBtn>
                        </VCol>
                    </VRow>
                </VForm>
            </VCardText>
        </VCard>
    </VDialog>

    <ConfirmDialog
        v-model="isConfirmDialogVisible"
        :text="`¿Estás seguro de eliminar ${props.title}?`"
        :title="`Eliminar ${props.title}`"
        @confirm="deleteItem"
        @cancel="isConfirmDialogVisible = false"
    />
</template>
