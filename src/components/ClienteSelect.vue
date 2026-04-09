<template>
    <VAutocomplete
        @update:search="SearchCliente"
        @update:modelValue="change"
        v-model="id_cliente"
        :items="clientes"
        :item-title="itemTitle"
        item-value="id"
        :label="label"
        :variant="variant"
        :density="density"
        :prepend-icon="prependIcon"
        :clearable="clearable"
        no-filter
    >
        <template #item="{ props, item }">
            <VListItem v-bind="props">
                <template #title>
                    {{ item?.raw?.nro_cliente }} - {{ item?.raw?.[itemTitle] }}
                </template>
            </VListItem>
        </template>
        <template #append-item>
            <!-- ✅ Solo mostrar el trigger de paginación si hay más páginas -->
            <div v-if="hasMorePages" v-intersect="endIntersect" />
        </template>
    </VAutocomplete>
</template>

<script setup>
import debounce from "lodash/debounce";
import {
    computed,
    getCurrentInstance,
    onMounted,
    onUnmounted,
    ref,
    watch,
} from "vue";

const { proxy } = getCurrentInstance();
const store = proxy.$store;

const props = defineProps({
    modelValue: {
        type: [Number, null],
        required: false,
        default: null,
    },
    extra: {
        type: Object,
        required: false,
    },
    label: {
        type: String,
        required: true,
    },
    itemTitle: {
        type: String,
        required: true,
    },
    variant: {
        type: String,
        required: false,
    },
    prependIcon: {
        type: String,
        required: false,
    },
    clearable: {
        type: Boolean,
        required: false,
    },
    density: {
        type: String,
        required: false,
    },
});

const emit = defineEmits(["update:modelValue", "change"]);

const creado = ref(null);
const id_cliente = ref(null);
const isLoadingMore = ref(false); // ✅ Prevenir múltiples cargas simultáneas

watch(
    () => props.modelValue,
    (newVal) => {
        if (newVal != null) {
            id_cliente.value = newVal;
        } else {
            id_cliente.value = null;
        }
    }
);

watch(id_cliente, (newVal) => {
    emit("update:modelValue", newVal);
});

const change = () => {
    emit("change", id_cliente.value);
};

// ✅ Debounce mejorado que limpia búsquedas vacías
const SearchCliente = debounce((val) => {
    store.dispatch("searchCliente", {
        search: val || "", // ✅ Asegurar que siempre sea string
    });
}, 500);

// ✅ Mejorar el manejo de intersección con protección contra cargas múltiples
const endIntersect = async (entries, observer, isIntersecting) => {
    if (isIntersecting && !isLoadingMore.value && hasMorePages.value) {
        isLoadingMore.value = true;

        try {
            await store.dispatch("getClientesNext");
        } catch (error) {
            console.error("Error loading more clients:", error);
        } finally {
            // ✅ Agregar delay para prevenir múltiples triggers rápidos
            setTimeout(() => {
                isLoadingMore.value = false;
            }, 300);
        }
    }
};

// Función para recargar clientes cuando cambia el cliente seleccionado
const handleClienteChanged = () => {
    console.log(
        "ClienteSelect: Cliente cambiado, recargando clientes del store..."
    );
    // Limpiar el cliente seleccionado
    id_cliente.value = null;
    // Recargar los clientes del store
    store.dispatch("getClientes");
};

onMounted(() => {
    store.dispatch("getClientes");
    if (props.modelValue != null) {
        id_cliente.value = props.modelValue;
    }
    // Escuchar cambios en el cliente seleccionado
    window.addEventListener("cliente-selected-changed", handleClienteChanged);
});

onUnmounted(() => {
    // Limpiar el listener
    window.removeEventListener(
        "cliente-selected-changed",
        handleClienteChanged
    );
});

// ✅ Computed para obtener el estado de páginas disponibles
const hasMorePages = computed(() => {
    return store.getters.hasMorePages;
});

const clientes = computed(() => {
    const storeClientes = store.getters.getclientes || [];

    let clientes_temp = JSON.parse(JSON.stringify(storeClientes)); // Deep clone

    // ✅ Verificar si props.extra existe antes de agregarlo
    if (props.extra?.nombre != null) {
        const existsExtra = clientes_temp.some((c) => c.id === props.extra.id);
        if (!existsExtra) {
            clientes_temp.push(props.extra);
        }
    }

    // ✅ Verificar si creado.value existe antes de agregarlo
    if (creado.value) {
        const existsCreado = clientes_temp.some(
            (c) => c.id === creado.value.id
        );
        if (!existsCreado) {
            clientes_temp.push(creado.value);
        }
    }

    // ✅ Eliminar duplicados basándose en el id
    const uniqueClientes = [];
    const seenIds = new Set();

    for (const cliente of clientes_temp) {
        if (cliente && cliente.id && !seenIds.has(cliente.id)) {
            seenIds.add(cliente.id);
            uniqueClientes.push(cliente);
        }
    }

    return uniqueClientes;
});
</script>
