<template>
    <div style="display: inline-flex; align-items: center;">
        <VBtn
            v-if="tipo != 'facturaproforma'"
            :disabled="isloading"
            rounded="pill"
            @click="saveRecibo"
            class="me-2"
        >
            Guardar
        </VBtn>

        <VBtn
            v-if="tipo == 'facturaproforma'"
            :disabled="isloading"
            rounded="pill"
            @click="saveRecibo"
            class="me-2"
        >
            Guardar Proforma
        </VBtn>

        <VBtn
            :disabled="isloading || regenerandoPdf"
            v-if="recibo.factura_url"
            rounded="pill"
            color="#5142A6"
            class="me-2 text-white"
            :loading="regenerandoPdf"
            @click="verPdf"
        >
            ver pdf
        </VBtn>

        <VBtn
            :disabled="isloading"
            v-if="recibo.orden_url && recibo.metodo_pago == 'pago_dos'"
            rounded="pill"
            variant="tonal"
            target="_blank"
            :href="`/storage/recibos/userId_${recibo.user_id}/${recibo.orden_url}`"
        >
            Orden Sepa
        </VBtn>
    </div>
</template>

<script>
import axios from "axios";

export default {
    props: ["recibo", "tipo", "isloading"],
    emits: ["save_factura", "factura_url_actualizada"],

    data() {
        return {
            regenerandoPdf: false,
        };
    },

    methods: {
        saveRecibo() {
            this.$emit("save_factura");
        },

        async verPdf() {
            if (this.regenerandoPdf) return;
            this.regenerandoPdf = true;
            try {
                const { data } = await axios.get(
                    `api/recibos/${this.recibo.id}/regenerar-factura-pdf`
                );
                if (data.url) {
                    window.open(data.url, "_blank");
                    if (data.factura_url) {
                        this.$emit("factura_url_actualizada", data.factura_url);
                    }
                }
            } catch (err) {
                const msg =
                    err.response?.data?.error ||
                    "Error al regenerar el PDF.";
                if (this.$toast) this.$toast.error(msg);
                else console.error(msg);
            } finally {
                this.regenerandoPdf = false;
            }
        },
    },
};
</script>
