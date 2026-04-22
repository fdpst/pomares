<template>
    <section>
        <loader v-if="isloading"></loader>

        <VCard>
            <VCardText>
                <VRow>
                    <VCol
                        cols="12"
                        md="6">
                        <VCard
                            variant="outlined"
                            title="Enviar / Descargar ZIP Facturas">
                            <VCardText>
                                <VRow>
                                    <VCol cols="12">
                                        <VSelect
                                            label="Seleccione el tipo de Factura"
                                            :items="facturas"
                                            item-title="tipo"
                                            v-model="
                                                modelFactura.tipo
                                            "></VSelect>
                                    </VCol>
                                    <VCol cols="12">
                                        <rango-fechas
                                            :url="url"
                                            :modelFactura="modelFactura"
                                            :user-id="effectiveUserId"
                                            v-on:success_query="
                                                setIngresoBruto
                                            "></rango-fechas>
                                    </VCol>
                                    
                                    <!-- Tabla de facturas encontradas -->
                                    <VCol cols="12" v-if="facturasEncontradas.length > 0">
                                        <VCard variant="outlined">
                                            <VCardTitle>
                                                Facturas encontradas ({{ facturasEncontradas.length }})
                                            </VCardTitle>
                                            <VCardText style="padding: 16px;">
                                                <div class="facturas-table-container" style="width: 100%;">
                                                    <VTable style="width: 100%; table-layout: fixed;">
                                                        <thead>
                                                            <tr>
                                                                <th style="width: 15%; padding: 8px;">Nro. Factura</th>
                                                                <th style="width: 15%; padding: 8px;">Fecha</th>
                                                                <th v-if="modelFactura.tipo === 'Facturas Recibidas' || modelFactura.tipo === 'Todas'" style="width: 35%; padding: 8px;">Proveedor/Cliente</th>
                                                                <th v-else style="width: 45%; padding: 8px;">Cliente</th>
                                                                <th v-if="modelFactura.tipo === 'Todas'" style="width: 15%; padding: 8px;">Tipo</th>
                                                                <th style="width: 20%; padding: 8px;">Total</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr v-for="(factura, index) in facturasEncontradas" :key="index">
                                                                <td style="padding: 8px; word-break: break-word;">{{ factura.nro_factura || factura.numero || '-' }}</td>
                                                                <td style="padding: 8px; word-break: break-word;">{{ formatearFecha(factura.fecha) }}</td>
                                                                <td style="padding: 8px; word-break: break-word; overflow: hidden; text-overflow: ellipsis;">
                                                                    <template v-if="modelFactura.tipo === 'Facturas Recibidas' || factura.tipo === 'Recibida'">
                                                                        {{ factura.proveedor?.nombre || '-' }}
                                                                    </template>
                                                                    <template v-else>
                                                                        {{ factura.cliente?.nombre || factura.recibo?.cliente?.nombre || '-' }}
                                                                    </template>
                                                                </td>
                                                                <td v-if="modelFactura.tipo === 'Todas'" style="padding: 8px; word-break: break-word;">
                                                                    <VChip size="small" :color="factura.tipo === 'Recibida' ? 'error' : 'success'">
                                                                        {{ factura.tipo }}
                                                                    </VChip>
                                                                </td>
                                                                <td style="padding: 8px; word-break: break-word;">{{ formatearMoneda(factura.total) }}</td>
                                                            </tr>
                                                        </tbody>
                                                    </VTable>
                                                </div>
                                            </VCardText>
                                        </VCard>
                                    </VCol>
                                    
                                    <VCol cols="12" v-else-if="archivo != null && facturasEncontradas.length === 0">
                                        <VAlert type="warning" variant="tonal">
                                            No se encontraron facturas en el rango de fechas seleccionado.
                                        </VAlert>
                                    </VCol>

                                    <VCol
                                        cols="12"
                                        class="d-flex">
                                        <VBtn
                                            :disabled="isloading || !archivo || facturasEncontradas.length === 0"
                                            v-if="
                                                archivo != null &&
                                                modelFactura.tipo ==
                                                    'Facturas Enviadas'
                                            "
                                            rounded="pill"
                                            depressed
                                            @click="descargarZip('enviadas')"
                                            class="mt-4 mb-2 mr-1"
                                            >descargar Enviadas
                                        </VBtn>

                                        <VBtn
                                            :disabled="isloading || facturasEncontradas.length === 0 || !downloadZipsUrls.facturas_recibidas || downloadZipsUrls.facturas_recibidas.trim() === ''"
                                            v-else-if="
                                                (archivo != null || facturasEncontradas.length > 0) &&
                                                modelFactura.tipo ==
                                                    'Facturas Recibidas'
                                            "
                                            rounded="pill"
                                            depressed
                                            @click="descargarZip('recibidas')"
                                            class="mt-4 mb-2 mr-1"
                                            :title="(!downloadZipsUrls.facturas_recibidas || downloadZipsUrls.facturas_recibidas.trim() === '') ? 'No hay archivo ZIP disponible. Las facturas no tienen archivos asociados.' : ''"
                                            >descargar Recibidas
                                        </VBtn>

                                        <VBtn
                                            :disabled="isloading || !archivo || facturasEncontradas.length === 0"
                                            v-else
                                            rounded="pill"
                                            depressed
                                            @click="callDown()"
                                            class="mt-4 mb-2 mr-1"
                                            >descargar todas
                                        </VBtn>

                                        <VBtn
                                            :disabled="isloading || !archivo || facturasEncontradas.length === 0"
                                            @click="email_dialog = true"
                                            rounded="pill"
                                            color="#5142A6"
                                            class="mt-4 mb-2 text-white"
                                            >enviar por email</VBtn
                                        >
                                    </VCol>
                                </VRow>
                            </VCardText>
                        </VCard>
                    </VCol>

                    <VCol
                        cols="12"
                        md="6">
                        <VCard
                            variant="outlined"
                            title="Exportar a Excel Facturas Periodo">
                            <VCardText>
                                <VRow>
                                    <VCol cols="12">
                                        <VAutocomplete
                                            label="Facturas"
                                            :items="facturas2"
                                            item-title="tipo"
                                            item-value="tipo"
                                            v-model="modelFactura2" />
                                    </VCol>
                                    <VCol
                                        cols="12"
                                        md="6">
                                        <AppDateTimePicker
                                            v-model="filter.desde"
                                            label="Desde"
                                            prepend-icon="ri-calendar-fill" />
                                    </VCol>
                                    <VCol
                                        cols="12"
                                        md="6">
                                        <AppDateTimePicker
                                            v-model="filter.hasta"
                                            label="Hasta"
                                            prepend-icon="ri-calendar-fill" />
                                    </VCol>
                                    <VCol cols="12">
                                        <VBtn
                                            rounded="pill"
                                            class="mt-4 mb-2"
                                            @click="downloadExcel()">
                                            EXPORTAR DATOS A EXCEL
                                            <VIcon
                                                end
                                                icon="ri-file-excel-2-line" />
                                        </VBtn>
                                    </VCol>
                                </VRow>
                            </VCardText>
                        </VCard>
                    </VCol>
                </VRow>
            </VCardText>
        </VCard>

        <lote-content-dialog
            :modelFactura="modelFactura"
            :isloading="isloading"
            v-on:close_dialog="cerrar"
            :email_dialog="email_dialog">
        </lote-content-dialog>
    </section>
</template>
<script>
import loteContentDialog from "./loteContentDialog.vue";
import rangoFechas from "./rangoFechas.vue";
import { effectiveBusinessUserId } from "@/utils/tenantContext";

export default {
    components: {
        rangoFechas,
        loteContentDialog,
    },

    data() {
        return {
            downloadZipsUrls: {
                facturas_recibidas: "",
                facturas_enviadas: "",
            },
            tipo: null,
            facturas_rango: null,
            rango: {
                desde: moment().startOf("year").format("YYYY-MM-DD"),
                hasta: moment().endOf("year").format("YYYY-MM-DD"),
            },
            filter: {
                desde: moment().startOf("year").format("YYYY-MM-DD"),
                hasta: moment().endOf("year").format("YYYY-MM-DD"),
            },
            desde: false,
            hasta: false,
            email_dialog: false,
            url: `/api/get-lote-facturas`,
            archivo: null,
            archivoTodos: {enviadas: false, reciidas: false},
            facturas: [
                {id: 1, tipo: "Facturas Recibidas"},
                {id: 2, tipo: "Facturas Enviadas"},
                {id: 3, tipo: "Todas"},
            ],
            facturas2: [
                {id: 1, tipo: "Facturas Recibidas"},
                {id: 2, tipo: "Facturas Enviadas"},
            ],
            modelFactura: {
                tipo: "Todas",
            },
            modelFactura2: "Facturas Enviadas",
            docs: [],
            facturasEncontradas: [],
        };
    },
    mounted() {
        // No cargar URLs al montar porque los ZIPs aún no existen
        // Se cargarán después de buscar facturas
    },
    methods: {
        downloadExcel() {
            let $data = {};
            $data.user_id = this.effectiveUserId;
            
            // Formatear fechas correctamente
            let desde = this.filter.desde;
            let hasta = this.filter.hasta;
            
            if (desde instanceof Date) {
                desde = moment(desde).format("YYYY-MM-DD");
            } else if (typeof desde === 'string') {
                desde = moment(desde).format("YYYY-MM-DD");
            }
            
            if (hasta instanceof Date) {
                hasta = moment(hasta).format("YYYY-MM-DD");
            } else if (typeof hasta === 'string') {
                hasta = moment(hasta).format("YYYY-MM-DD");
            }
            
            $data.desde = desde;
            $data.hasta = hasta;

            axios
                .post("/api/export-excel?type=" + encodeURIComponent(this.modelFactura2), $data, {
                    responseType: "blob",
                })
                .then((response) => {
                    let blob = new Blob([response.data], {type: "xlsx"}),
                        downloadUrl = window.URL.createObjectURL(blob),
                        filename = "",
                        disposition = response.headers["content-disposition"];

                    if (
                        disposition &&
                        disposition.indexOf("attachment") !== -1
                    ) {
                        let filenameRegex =
                                /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/,
                            matches = filenameRegex.exec(disposition);

                        if (matches != null && matches[1]) {
                            filename = matches[1].replace(/['"]/g, "");
                        }
                    }

                    let a = document.createElement("a");
                    if (typeof a.download === "undefined") {
                        window.location.href = downloadUrl;
                    } else {
                        a.href = downloadUrl;
                        a.download = filename;
                        document.body.appendChild(a);
                        a.click();
                    }
                })
                .catch(async (error) => {
                    let mensaje = "Error al exportar el Excel.";
                    const responseData = error?.response?.data;

                    // Cuando responseType es blob, el mensaje del backend llega como Blob
                    if (responseData instanceof Blob) {
                        try {
                            const text = await responseData.text();
                            const parsed = JSON.parse(text);
                            mensaje = parsed?.message || mensaje;
                        } catch (_) {
                            // Si no se puede parsear el blob, usamos mensaje genérico
                        }
                    } else {
                        mensaje = error?.response?.data?.message || mensaje;
                    }

                    $toast.warn(mensaje);
                });
        },
        cerrar() {
            this.email_dialog = false;
        },
        callDown() {
            const vm = this;
            let descargasRealizadas = 0;
            
            // Intentar descargar facturas enviadas
            if (vm.downloadZipsUrls.facturas_enviadas && vm.downloadZipsUrls.facturas_enviadas.trim() !== '') {
                vm.downloadFiles(vm.downloadZipsUrls.facturas_enviadas, 'facturas_enviadas.zip')
                    .then(() => {
                        descargasRealizadas++;
                    })
                    .catch((error) => {
                        console.error('Error descargando facturas enviadas:', error);
                        $toast.error('Error al descargar facturas enviadas');
                    });
            } else {
                $toast.warn('No hay archivo ZIP disponible para facturas enviadas');
            }
            
            // Intentar descargar autofacturas
            if (vm.downloadZipsUrls.facturas_recibidas && vm.downloadZipsUrls.facturas_recibidas.trim() !== '') {
                vm.downloadFiles(vm.downloadZipsUrls.facturas_recibidas, 'facturas_recibidas.zip')
                    .then(() => {
                        descargasRealizadas++;
                    })
                    .catch((error) => {
                        console.error('Error descargando autofacturas:', error);
                        $toast.error('Error al descargar autofacturas');
                    });
            } else {
                $toast.warn('No hay archivo ZIP disponible para autofacturas');
            }
        },
        downloadFiles(url, filename) {
            return new Promise((resolve, reject) => {
                if (!url || url.trim() === '') {
                    reject(new Error('URL vacía'));
                    return;
                }
                
                // Obtener el token de autenticación si está disponible
                const token = localStorage.getItem('id_token');
                const headers = {
                    'Accept': 'application/zip',
                };
                
                if (token) {
                    headers['Authorization'] = `Bearer ${token}`;
                }
                
                // Obtener el CSRF token si está disponible
                const csrfToken = document.querySelector('meta[name="csrf-token"]');
                if (csrfToken) {
                    headers['X-CSRF-TOKEN'] = csrfToken.getAttribute('content');
                }
                
                // Usar fetch en lugar de axios para evitar problemas con interceptores
                fetch(url, {
                    method: 'GET',
                    headers: headers,
                    credentials: 'include', // Incluir cookies si es necesario
                })
                .then(function (response) {
                    // Verificar si la respuesta es exitosa o si tiene un blob válido
                    if (response.ok || response.status === 200 || (response.status >= 200 && response.status < 300)) {
                        return response.blob();
                    } else {
                        // Si no es exitosa pero tiene contenido, intentar leerlo como blob
                        return response.blob().then(blob => {
                            // Verificar si el blob contiene un error JSON
                            if (blob.size > 0 && blob.type.includes('json')) {
                                return blob.text().then(text => {
                                    try {
                                        const json = JSON.parse(text);
                                        throw new Error(json.message || json.error || 'Error al descargar el archivo');
                                    } catch (e) {
                                        if (e instanceof Error && e.message !== text) {
                                            throw e;
                                        }
                                        throw new Error(`Error ${response.status}: ${response.statusText}`);
                                    }
                                });
                            }
                            return blob;
                        });
                    }
                })
                .then(function (blob) {
                    if (blob && blob instanceof Blob && blob.size > 0) {
                        const a = document.createElement("a");
                        a.href = URL.createObjectURL(blob);
                        a.download = filename;
                        document.body.appendChild(a);
                        a.click();
                        // Esperar un poco antes de remover el elemento para asegurar que el navegador inicie la descarga
                        setTimeout(() => {
                            document.body.removeChild(a);
                            URL.revokeObjectURL(a.href);
                        }, 100);
                        resolve();
                    } else {
                        reject(new Error('El archivo está vacío o no es válido'));
                    }
                })
                .catch(function (error) {
                    console.error('Error en downloadFiles:', error);
                    // Si el error es un Error con mensaje, rechazarlo directamente
                    if (error instanceof Error) {
                        reject(error);
                    } else {
                        reject(new Error('Error al descargar el archivo'));
                    }
                });
            });
        },
        setIngresoBruto(data) {
            this.archivo = data;
            
            // Limpiar facturas encontradas
            this.facturasEncontradas = [];
            
            // Actualizar lista de facturas encontradas según el tipo
            if (this.modelFactura.tipo === "Facturas Enviadas") {
                // La respuesta viene en data.enviadas
                const enviadas = data.enviadas || data;
                if (enviadas && enviadas.facturasEnviadasGet && Array.isArray(enviadas.facturasEnviadasGet)) {
                    this.facturasEncontradas = enviadas.facturasEnviadasGet.map(f => ({
                        nro_factura: f.recibo?.nro_factura?.nro_factura || f.nro_factura?.nro_factura || '-',
                        fecha: f.recibo?.fecha || f.fecha,
                        cliente: f.recibo?.cliente || f.cliente,
                        recibo: f.recibo,
                        total: f.recibo?.total || f.total || 0,
                    }));
                }
                
                // Mostrar mensaje si no hay facturas
                if (this.facturasEncontradas.length === 0) {
                    if (enviadas && enviadas.message) {
                        $toast.warn(enviadas.message);
                    } else {
                        $toast.warn("No se encontraron facturas enviadas en el rango de fechas especificado.");
                    }
                }
                
            } else if (this.modelFactura.tipo === "Facturas Recibidas") {
                // La respuesta viene en data.recibidas
                const recibidas = data.recibidas || data;
                if (recibidas && recibidas.facturasRecibidasGet && Array.isArray(recibidas.facturasRecibidasGet)) {
                    if (recibidas.facturasRecibidasGet.length > 0) {
                        // Verificar si es array de objetos o array de strings (nombres de archivo)
                        if (typeof recibidas.facturasRecibidasGet[0] === 'string') {
                            // Si son strings, buscar las facturas completas
                            this.cargarFacturasRecibidasDetalle();
                        } else {
                            // Si son objetos, usar directamente
                            this.facturasEncontradas = recibidas.facturasRecibidasGet.map(f => ({
                                nro_factura: f.nro_factura || '-',
                                fecha: f.fecha,
                                proveedor: f.proveedor || null,
                                total: f.total || 0,
                            }));
                        }
                    }
                }
                
                // No mostrar mensaje si es normal que no haya archivos (hasArchivos: false)
                // Solo mostrar si realmente hubo un error
                if (recibidas && recibidas.hasArchivos === false && recibidas.archivo === null && this.facturasEncontradas.length > 0) {
                    // Es normal que no haya archivos, no mostrar mensaje molesto
                    // Solo lo mostraremos si el usuario intenta descargar
                }
                
                // Mostrar mensaje si no hay facturas
                if (this.facturasEncontradas.length === 0) {
                    if (recibidas && recibidas.message) {
                        $toast.warn(recibidas.message);
                    } else {
                        $toast.warn("No se encontraron autofacturas en el rango de fechas especificado.");
                    }
                }
                
            } else if (this.modelFactura.tipo === "Todas") {
                this.archivoTodos = {
                    enviadas: null,
                    recibidas: null,
                };
                
                // Cuando el tipo es "Todas", los datos vienen en data.todas
                const todas = data.todas || data;
                
                // Procesar facturas enviadas
                if (todas.enviadas && todas.enviadas.facturasEnviadasGet && Array.isArray(todas.enviadas.facturasEnviadasGet)) {
                    this.facturasEncontradas.push(...todas.enviadas.facturasEnviadasGet.map(f => ({
                        nro_factura: f.recibo?.nro_factura?.nro_factura || f.nro_factura?.nro_factura || '-',
                        fecha: f.recibo?.fecha || f.fecha,
                        cliente: f.recibo?.cliente || f.cliente,
                        total: f.recibo?.total || f.total || 0,
                        tipo: 'Enviada'
                    })));
                }
                
                // Procesar autofacturas
                if (todas.recibidas && todas.recibidas.facturasRecibidasGet && Array.isArray(todas.recibidas.facturasRecibidasGet)) {
                    if (todas.recibidas.facturasRecibidasGet.length > 0) {
                        // Verificar si es array de objetos o array de strings
                        if (typeof todas.recibidas.facturasRecibidasGet[0] !== 'string') {
                            // Si son objetos, usar directamente
                            this.facturasEncontradas.push(...todas.recibidas.facturasRecibidasGet.map(f => ({
                                nro_factura: f.nro_factura && f.nro_factura !== 'null' ? f.nro_factura : '-',
                                fecha: f.fecha,
                                proveedor: f.proveedor || null,
                                total: f.total || 0,
                                tipo: 'Recibida'
                            })));
                        }
                    }
                }
                
                // No mostrar mensaje automático si es normal que no haya archivos
                // El mensaje solo aparecerá si el usuario intenta descargar
                
                // Mostrar mensaje si no hay facturas
                if (this.facturasEncontradas.length === 0) {
                    $toast.warn("No se encontraron facturas en el rango de fechas especificado.");
                }
            } else {
                this.facturasEncontradas = [];
            }
            
            // Actualizar URLs de descarga después de procesar los datos
            // Esperar un momento para que los ZIPs se hayan creado en el servidor
            // Aumentar el delay a 1000ms para asegurar que el ZIP esté completamente creado
            setTimeout(() => {
                this.getZipsUrl();
            }, 1000);
        },
        async cargarFacturasRecibidasDetalle() {
            try {
                const response = await axios.get(`/api/facturas-recibidas`);
                if (response.data && response.data.facturaRecibidas) {
                    this.facturasEncontradas = response.data.facturaRecibidas.map(f => ({
                        nro_factura: f.nro_factura,
                        fecha: f.fecha,
                        proveedor: f.proveedor,
                        total: f.total,
                    }));
                }
            } catch (error) {
                console.error('Error cargando detalles de autofacturas:', error);
            }
        },
        descargarZip(tipo) {
            const vm = this;
            const url = tipo === 'recibidas' 
                ? vm.downloadZipsUrls.facturas_recibidas 
                : vm.downloadZipsUrls.facturas_enviadas;
            
            if (!url || url.trim() === '') {
                // Obtener mensaje del archivo si está disponible
                let mensaje = "No hay archivo disponible para descargar.";
                if (tipo === 'recibidas' && vm.archivo) {
                    const recibidas = vm.archivo.recibidas || (vm.archivo.todas && vm.archivo.todas.recibidas);
                    if (recibidas && recibidas.message) {
                        mensaje = recibidas.message;
                    }
                }
                $toast.warn(mensaje);
                return;
            }
            
            vm.downloadFiles(url, `facturas_${tipo}.zip`)
                .then(() => {
                    $toast.sucs(`Archivo ${tipo} descargado correctamente`);
                })
                .catch((error) => {
                    console.error('Error descargando ZIP:', error);
                    $toast.error(`Error al descargar el archivo ${tipo}`);
                });
        },
        async getZipsUrl() {
            const vm = this;
            try {
                const response = await axios.get(
                    `/api/lote-facturas-get-url-download-zips`
                );
                vm.downloadZipsUrls = {
                    facturas_recibidas: response.data?.facturas_recibidas ?? "",
                    facturas_enviadas: response.data?.facturas_enviadas ?? "",
                    url_base: response.data?.url_base ?? "",
                };
                // Solo loggear si hay URLs para evitar spam en consola
                if (vm.downloadZipsUrls.facturas_enviadas || vm.downloadZipsUrls.facturas_recibidas) {
                    console.log('URLs de descarga actualizadas:', vm.downloadZipsUrls);
                }
            } catch (error) {
                console.error('Error obteniendo URLs de ZIP:', error);
            }
        },
        formatearFecha(fecha) {
            if (!fecha) return '-';
            return moment(fecha).format('DD/MM/YYYY');
        },
        formatearMoneda(valor) {
            if (!valor) return '0.00 €';
            return parseFloat(valor).toFixed(2) + ' €';
        },
    },
    filters: {
        format_currency(value) {
            return `${parseFloat(value).toFixed(2)}`;
        },
    },
    computed: {
        isloading: function () {
            return this.$store.getters.getloading;
        },
        effectiveUserId() {
            return effectiveBusinessUserId();
        },
    },
};
</script>

<style scoped>
.v-table {
    width: 100%;
}

.v-table th,
.v-table td {
    word-wrap: break-word;
    overflow-wrap: break-word;
}

.v-card-text {
    padding: 16px;
    overflow-x: hidden;
}

/* Evitar scroll horizontal en la tabla */
.v-table-wrapper {
    width: 100%;
    overflow-x: hidden;
}

/* Scroll vertical para tablas con muchas filas */
.facturas-table-container {
    max-height: 400px;
    overflow-y: auto;
    overflow-x: hidden;
}

/* Estilos para el scrollbar */
.facturas-table-container::-webkit-scrollbar {
    width: 8px;
}

.facturas-table-container::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

.facturas-table-container::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 4px;
}

.facturas-table-container::-webkit-scrollbar-thumb:hover {
    background: #555;
}
</style>
