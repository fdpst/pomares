<template>
    <loader v-if="isloading"></loader>

    <VCard class="pb-10" title="Configuración del sistema">
        <VCardText>
            <VRow
                v-for="param of params"
                :key="param.id"
                style="
                    display: grid;
                    grid-template-columns: 1fr 80%;
                    align-items: center;
                    gap: 0.5em;
                    margin: 0.5em;
                "
            >
                <label for="">
                    <b>{{ param.label }}</b>
                </label>
                <VTextField
                    v-if="allowedByInput.includes(param.type)"
                    v-model="values[param.param]"
                    class="mt-4"
                    :type="paramSystemTypeEnum.getTypeForHTML(param.type)"
                    :label="param.label"
                >
                </VTextField>

                <VAutocomplete
                    :clearable="true"
                    :label="param.label"
                    v-model="values[param.param]"
                    :items="param.options"
                    item-title="label"
                    item-value="value"
                    v-else-if="paramSystemTypeEnum.SELECTABLE == param.type"
                >
                </VAutocomplete>
                <div v-else-if="paramSystemTypeEnum.TEXTAREA == param.type">
                    <RichTextComponentVue
                        :label="param.label"
                        v-model="values[param.param]"
                    >
                    </RichTextComponentVue>
                </div>

                <VCheckbox
                    v-else-if="paramSystemTypeEnum.BOOLEAN == param.type"
                    v-model="values[param.param]"
                />

                <div v-else>
                    NOT SUPPORTED PLEASE ADD SUPPORT FOR THE NEW TYPE.
                </div>
            </VRow>
        </VCardText>

        <VCardActions>
            <VBtn
                rounded="pill"
                depressed
                class="btn-guardar"
                :disabled="isloading"
                @click="submit"
            >
                Aceptar
            </VBtn>
        </VCardActions>
    </VCard>
</template>
<script>
import { $api } from "@/utils/api";
import { nextTick } from "vue";
import RichTextComponentVue from "../recibos/RichTextComponent.vue";
import { ParamSystemTypeEnum } from "./ParamSystemType.enum";
import { SystemParam } from "./SystemParam.class";
export default {
    mounted() {
        const vm = this;
        vm.findAllParamters();
    },
    components: {
        RichTextComponentVue,
    },
    methods: {
        async findAllParamters() {
            try {
                const res = await $api(
                    "/api/system-params?business_id=" + this.selectedEmpresa,
                    {
                        method: "GET",
                    }
                );

                const missingPayload =
                    this.systemParamClass.getMissingParamsPayload(
                        res,
                        this.selectedEmpresa
                    );

                if (missingPayload.length > 0) {
                    await $api("/api/system-params/update-bulk", {
                        method: "POST",
                        body: missingPayload,
                    });
                    return this.findAllParamters();
                }

                if (res.length <= 0) {
                    await this.systemParamClass.createParamsForUser(
                        this.selectedEmpresa
                    );
                    $toast.sucs(
                        "Por favor recarga la pagina, hemos creado tus parámetros del sistema."
                    );
                }

                nextTick(() => {
                    if (res.length > 0) {
                        for (let param of res) {
                            this.values = Object.assign(this.values, {
                                [param.param]:
                                    this.parseToLoadValue(param) || "",
                            });
                        }
                    }
                });

                this.params = res;
            } catch (error) {
                console.log("Error obteniendo parametros del sistema: ", error);
                $toast.error(
                    "Algo no fue bien obteniendo tus parametros del sistema."
                );
            }
        },
        parseToLoadValue(param) {
            switch (param.type) {
                case ParamSystemTypeEnum.TEXT:
                    return String(param.value);
                case ParamSystemTypeEnum.NUMBER:
                    return Number.parseInt(param.value);
                case ParamSystemTypeEnum.BOOLEAN:
                    return Boolean(
                        param.value == "false" || param.value == "0"
                            ? false
                            : param.value
                    );
                case ParamSystemTypeEnum.SELECTABLE:
                    return String(param.value);
                case ParamSystemTypeEnum.TEXTAREA:
                    return param.value;
                default:
                    throw new Error("element doesn't supported.");
            }
        },
        async submit() {
            try {
                const keysModified = Object.keys(this.values).filter(
                    (key) =>
                        this.values[key] !=
                        this.params.find((param) => param.param == key).value
                );

                const payload = keysModified.map((key) => {
                    const param = this.params.find(
                        (param) => param.param == key
                    );
                    return {
                        id: param.id,
                        param: param.param,
                        value: this.values[key],
                        business_id: this.selectedEmpresa,
                    };
                });

                const res = await $api("/api/system-params/update-bulk", {
                    method: "POST",
                    body: payload,
                });

                if (res.success) {
                    $toast.sucs(
                        "Parámetros del sistema actualizados exitosamente."
                    );
                    return;
                }
                console.error("Algo no fue bien ", res);
            } catch (error) {
                console.error(
                    "Error al guardar parametros del sistema ",
                    error
                );

                $toast.error("Algo no fue bien: " + error.message);
            }
        },
        back() {
            window.history.back();
        },
    },
    data() {
        return {
            params: [],
            values: {},
            allowedByInput: [
                ParamSystemTypeEnum.TEXT,
                ParamSystemTypeEnum.NUMBER,
            ],
            paramSystemTypeEnum: ParamSystemTypeEnum,
            systemParamClass: SystemParam.getInstance(),
        };
    },
    computed: {
        selectedEmpresa() {
            // Recordar que el cliente es la empresa
            return localStorage.getItem("selected_cliente_id");
        },
    },
};
</script>
