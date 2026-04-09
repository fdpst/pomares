<template>
    <VCol
        class="pa-4"
        cols="12"
        md="3">
        <VRow>
            <b>Subtotal:</b>
            <VSpacer></VSpacer>
            {{ formatPrice(recibo?.sub_total) }}€
        </VRow>

        <VRow
            class="mt-4"
            v-if="tipo != 'parte-trabajo'">
            <b>Descuento:</b>
            <VSpacer></VSpacer>
            {{ formatPrice(recibo?.total_descuento) }}€
        </VRow>

        <!-- Importe total de cada IVA (solo cuando hay artículos con ese IVA) -->
        <template
            v-if="
                (tipo === 'factura' ||
                    tipo === 'facturaproforma' ||
                    tipo === 'presupuesto' ||
                    tipo === 'facturarectificativa') &&
                ivasConArticulos.length > 0
            ">
            <VRow
                v-for="(iva, index) in ivasConArticulos"
                :key="iva.percent_raw"
                :class="index === 0 ? 'mt-5' : 'mt-2'">
                <b>Iva {{ iva.percent }}:</b>
                <VSpacer></VSpacer>
                {{ formatPrice(iva.value) }}€
            </VRow>
            <VRow
                class="mt-4"
                v-if="
                    tipo == 'factura' ||
                    tipo == 'facturaproforma' ||
                    tipo == 'presupuesto'
                ">
                <b>IVA:</b><VSpacer></VSpacer>{{ formatPrice(recibo.iva) }}€
            </VRow>
        </template>

        <VRow
            class="mt-4"
            v-if="
                tipo == 'presupuesto' ||
                tipo == 'factura' ||
                tipo == 'facturarectificativa' ||
                tipo == 'facturaproforma' ||
                tipo == 'parte-trabajo'
            ">
            <b>Total:</b>
            <VSpacer></VSpacer>
            {{ formatPrice(recibo.total) }}€
        </VRow>

        <VRow
            class="mt-4"
            v-else>
            <b>Total:</b>
            <VSpacer></VSpacer>
            {{ formatPrice(total) }}€
        </VRow>
    </VCol>
</template>

<script>
export default {
    props: {
        value: {
            type: Object,
            required: true,
        },
        tipo: String,
        has_iva: Boolean,
        total: Number,
        IVAs: {
            type: Array,
            default: () => [],
        },
    },
    data() {
        return {
            recibo: this.value || {
                sub_total: 0,
                total_descuento: 0,
                iva: 0,
                total: 0,
            },
        };
    },
    computed: {
        /** IVAs con artículos (array); solo tipos con al menos una línea */
        ivasConArticulos() {
            const ivas = this.IVAs
            if (Array.isArray(ivas) && ivas.length > 0) return ivas
            return []
        },
    },
    created() {
        //
    },
    watch: {
        value: {
            handler(newVal) {
                this.recibo = newVal;
            },
            deep: true,
        },
        recibo(newVal) {
            this.$emit("input", newVal);
        },
    },
};
</script>
