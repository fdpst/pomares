<template>
    <VCard>
        <loader v-if="isloading"></loader>

        <VCardText>
            <VForm ref="form" v-model="formValid" class="mt-5">
                <VRow dense>
                    <VCol cols="12" md="6">
                        <VTextField
                            variant="outlined"
                            :error-messages="
                                errors.errors.nombre_comercial
                                    ? errors.errors.nombre_comercial[0]
                                    : null
                            "
                            v-model="customer.nombre_comercial"
                            label="Nombre comercial"
                            :rules="[requiredValidator]"
                        ></VTextField>
                    </VCol>

                    <VCol cols="12" md="6">
                        <VTextField
                            variant="outlined"
                            :error-messages="
                                errors.errors.nombre
                                    ? errors.errors.nombre[0]
                                    : null
                            "
                            v-model="customer.nombre"
                            label="Nombre fiscal"
                            required
                        ></VTextField>
                    </VCol>
                    <VCol cols="12" md="4">
                        <VTextField
                            variant="outlined"
                            :error-messages="
                                errors.errors.email
                                    ? errors.errors.email[0]
                                    : null
                            "
                            v-model="customer.email"
                            label="Email"
                            :rules="[emailValidator]"
                        ></VTextField>
                    </VCol>
                    <VCol cols="12" md="4">
                        <VTextField
                            variant="outlined"
                            :error-messages="
                                errors.errors.dni ? errors.errors.dni[0] : null
                            "
                            v-model="customer.dni"
                            label="CIF/DNI"
                            :rules="[requiredValidator]"
                        ></VTextField>
                    </VCol>

                    <VCol cols="12" md="4">
                        <VTextField
                            variant="outlined"
                            :error-messages="
                                errors.errors.telefono
                                    ? errors.errors.telefono[0]
                                    : null
                            "
                            v-model="customer.telefono"
                            label="Teléfono"
                            required
                            :rules="[numberValidator]"
                            counter
                            maxlength="15"
                        ></VTextField>
                    </VCol>
                    <VCol cols="12" md="8">
                        <VTextField
                            variant="outlined"
                            v-model="customer.direccion"
                            label="Dirección"
                            :rules="[requiredValidator]"
                        ></VTextField>
                    </VCol>

                    <VCol cols="12" md="4">
                        <VTextField
                            variant="outlined"
                            :error-messages="
                                errors.errors.codigo_postal
                                    ? errors.errors.codigo_postal[0]
                                    : null
                            "
                            v-model="customer.codigo_postal"
                            label="Código Postal"
                            :rules="[requiredValidator, numberValidator]"
                            counter
                            maxlength="5"
                        ></VTextField>
                    </VCol>
                    <VCol cols="12" md="4">
                        <VTextField
                            variant="outlined"
                            v-model="customer.localidad"
                            label="Localidad"
                        ></VTextField>
                    </VCol>
                    <VCol cols="12" md="4">
                        <VSelect
                            variant="outlined"
                            :error-messages="
                                errors.errors.provincia_id
                                    ? errors.errors.provincia_id[0]
                                    : null
                            "
                            v-model="customer.provincia_id"
                            label="Provincia"
                            :items="provinces"
                            item-title="nombre"
                            item-value="id"
                            :rules="[requiredValidator]"
                        >
                        </VSelect>
                    </VCol>
                    <VCol cols="12" md="4">
                        <VSelect
                            v-on:input="customer.provincia_id = null"
                            variant="outlined"
                            v-model="customer.pais_id"
                            :items="countries"
                            item-title="nombre"
                            item-value="id"
                            label="País"
                        >
                        </VSelect>
                    </VCol>
                </VRow>
            </VForm>
        </VCardText>

        <VDivider />

        <VCardText>
            <VBtn
                rounded="pill"
                depressed
                @click="submit"
                :disabled="isloading"
                class="mr-2"
                >{{ customer.id ? "Actualizar" : "Crear" }}</VBtn
            >
            <VBtn
                rounded="pill"
                depressed
                @click="resetCustomer"
                :disabled="isloading"
                class="mr-2"
                color="secondary"
                variant="outlined"
                >Limpiar</VBtn
            >
        </VCardText>
    </VCard>
</template>
<script setup>
import { emailValidator, requiredValidator } from "@/@core/utils/validators";
import { useProvincias } from "@/composables/useProvincia";
import { defineEmits, defineProps, onMounted, ref } from "vue";
import { $toast } from "../utils/toast";

const props = defineProps({
    data: {
        type: Object,
        default: () => null,
    },
});

const $emit = defineEmits(["update:customer-list"]);

const { provinces, getProvincias, countries, getPaises } = useProvincias();
const form = ref();
const user_session_id = ref(localStorage.getItem("user_id"));
const formValid = ref(false);

const customer = ref({
    id: null,
    nro_cliente: "",
    fecha_alta: new Date(),
    activo: "",
    nombre_comercial: "",
    nombre: "",
    dni: "",
    email: "",
    telefono: "",
    direccion: "",
    codigo_postal: "",
    provincia_id: "",
    localidad: "",
    observaciones: "",
    contacto_nombre: "",
    contacto_telefono: "",
    banco: "",
    forma_pago_id: "",
    historial: "",
});

const errors = ref({
    errors: {
        nombre_comercial: [],
        nombre: [],
        dni: [],
        email: [],
        telefono: [],
        codigo_postal: [],
        provincia_id: [],
    },
});

const isloading = ref(false);

/**
 * @param {boolean} val
 * @returns {void}
 */
const setLoading = (val) => {
    isloading.value = val;
};

const resetCustomer = () => {
    nextTick(() => {
        form.value?.reset();
        form.value?.resetValidation();
    });
    customer.value = {
        id: null,
        nro_cliente: "",
        fecha_alta: new Date(),
        activo: "",
        nombre_comercial: "",
        nombre: "",
        dni: "",
        email: "",
        telefono: "",
        direccion: "",
        codigo_postal: "",
        provincia_id: "",
        localidad: "",
        observaciones: "",
        contacto_nombre: "",
        contacto_telefono: "",
        banco: "",
        forma_pago_id: "",
        historial: "",
        user_id: user_session_id.value,
    };
};

/**
 * @returns {void}
 */
const submit = async () => {
    try {
        form.value?.validate();
        if (!formValid.value) return;

        setLoading(true);
        customer.value.user_id = user_session_id.value;
        customer.value.activo = true;
        customer.value.fecha_alta = new Date().toISOString().substring(0, 10);
        const resp = await axios.post("api/save-cliente", customer.value);
        $toast.sucs(
            customer.value.id
                ? "Cliente actualizado exitosamente."
                : "Cliente creado exitosamente."
        );
        $emit("update:customer-list", resp.data);
    } catch (e) {
        $toast.warn("Algo no fue bien: " + e.message);
    } finally {
        setLoading(false);
    }
};

// START cambios para cientes con Pais

onMounted(() => {
    getPaises();
    getProvincias();
});

watch(
    () => props.data,
    (newCustomer, oldCustomer) => {
        // Solo actualizar si hay un cambio real en el cliente
        if (newCustomer.id != null) {
            customer.value = JSON.parse(JSON.stringify(newCustomer));
        } else {
            // Solo resetear si realmente se está limpiando el cliente
            resetCustomer();
        }
    },
    { deep: true, immediate: true }
);
</script>
