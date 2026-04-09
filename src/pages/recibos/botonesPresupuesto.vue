<template>
    <div style="display: inline-flex; align-items: center;">
        <VBtn
            :disabled="isloading"
            rounded="pill"
            @click="savePresupuesto"
            class="me-2">
            guardar
        </VBtn>

        <VBtn
            v-if="recibo.factura_url == null && recibo.id != null && !isEmpleado"
            :disabled="isloading"
            rounded="pill"
            @click="saveFactura('factura', true)"
            color="#5142A6"
            class="me-2 text-white">
            convertir a factura
        </VBtn>

        <!--VBtn 
            v-if="recibo.nota == null && recibo.id != null" 
            :disabled="isloading" 
            rounded="pill"
            variant="tonal"
            @click="saveNota('nota', true)" 
            class="me-2"
          >
            convertir a nota
          </VBtn-->

        <VBtn
            :disabled="isloading"
            v-if="recibo.presupuesto_url"
            rounded="pill"
            color="#5142A6"
            target="_blank"
            :href="`/storage/recibos/userId_${recibo.user_id}/${recibo.presupuesto_url}`"
            class="text-white">
            ver pdf
        </VBtn>
    </div>
</template>

<script>
export default {
    props: ["recibo", "tipo", "isloading"],
    emits: ["save_presupuesto", "convertir_factura", "convertir_nota"],
    data() {
        return {
            //
        };
    },
    computed: {
        isEmpleado() {
            const role = parseInt(localStorage.getItem('role'));
            return role === 4;
        },
    },
    methods: {
        savePresupuesto() {
            this.$emit("save_presupuesto");
        },

        saveFactura() {
            this.$emit("convertir_factura");
        },

        saveNota() {
            this.$emit("convertir_nota");
        },
    },
};
</script>
