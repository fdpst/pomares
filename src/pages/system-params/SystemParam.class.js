import { ParamSystemEnum } from "@/@core/types/ParamSystem.enum";
import { $api } from "@/utils/api";
import { ParamSystemTypeEnum } from "./ParamSystemType.enum";

export class SystemParam {
    static getInstance() {
        return new SystemParam();
    }

    /**
     * @param {number} user_id
     */
    async createParamsForUser(user_id) {
        try {
            return await $api("/api/system-params/update-bulk", {
                method: "POST",
                body: this.getPayloadCreateParamsForUser(user_id),
            });
        } catch (e) {
            console.log("Algo no fue bien: ", e.message);
            throw e;
        }
    }

    getDefinitions() {
        return [
            {
                param: ParamSystemEnum.TEXT_EMAIL_PAY_REMINDER,
                label: "Texto email recordatorio de pago",
                value: "",
                type: ParamSystemTypeEnum.TEXTAREA,
            },
            {
                param: ParamSystemEnum.INVOICE_FOOTER,
                label: "Pie de factura",
                value: "",
                type: ParamSystemTypeEnum.TEXT,
            },
            {
                param: ParamSystemEnum.ENABLE_BATCH,
                label: "Activar lote",
                value: false,
                type: ParamSystemTypeEnum.BOOLEAN,
            },
        ];
    }

    mapDefinitionToPayload(definition, user_id) {
        return {
            param: definition.param,
            label: definition.label,
            value: definition.value,
            business_id: user_id,
            type: definition.type,
            options: definition.options ?? null,
        };
    }

    /**
     * @param {number} user_id
     * @description se iran agregando los parametros que se creen en esta
     * funcion para que cuando ingrese un usuario al modulo automaticamente
     * se le creen los parametros del sistema
     * @default IMPORTANTE!!! seguir la connotacion del uso de ENUMs
     */
    getPayloadCreateParamsForUser(user_id) {
        return this.getDefinitions().map((definition) =>
            this.mapDefinitionToPayload(definition, user_id)
        );
    }

    getMissingParamsPayload(existingParams, user_id) {
        const existingKeys = new Set(existingParams.map((param) => param.param));
        return this.getDefinitions()
            .filter((definition) => !existingKeys.has(definition.param))
            .map((definition) =>
                this.mapDefinitionToPayload(definition, user_id)
            );
    }
}
