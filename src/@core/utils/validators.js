import { isEmpty, isEmptyArray, isNullOrUndefined } from "./helpers";

// 👉 Required Validator
export const requiredValidator = (value) => {
    if (isNullOrUndefined(value) || isEmptyArray(value) || value === false)
        return "Este campo es requerido";

    return !!String(value).trim().length || "Este campo es requerido";
};

// 👉 Email Validator
export const emailValidator = (value) => {
    if (isEmpty(value)) return true;
    const re =
        /^(?:[^<>()[\]\\.,;:\s@"]+(?:\.[^<>()[\]\\.,;:\s@"]+)*|".+")@(?:\[\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\]|(?:[a-z\-\d]+\.)+[a-z]{2,})$/i;
    if (Array.isArray(value))
        return (
            value.every((val) => re.test(String(val))) ||
            "El campo de correo electrónico debe ser un correo electrónico válido."
        );

    return (
        re.test(String(value)) ||
        "El campo de correo electrónico debe ser un correo electrónico válido."
    );
};

// 👉 Password Validator
export const passwordValidator = (password) => {
    const regExp = /(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%&*()]).{8,}/;
    const validPassword = regExp.test(password);

    return (
        validPassword ||
        "El campo debe contener al menos una letra mayúscula, una minúscula, un carácter especial y un dígito con un mínimo de 8 caracteres."
    );
};

// 👉 Confirm Password Validator
export const confirmedValidator = (value, target) =>
    value === target ||
    "La confirmación del campo Confirmar contraseña no coincide";

// 👉 Between Validator
export const betweenValidator = (value, min, max) => {
    const valueAsNumber = Number(value);

    return (
        (Number(min) <= valueAsNumber && Number(max) >= valueAsNumber) ||
        `Introduzca un número entre ${min} y ${max}`
    );
};

// 👉 Integer Validator
export const integerValidator = (value) => {
    if (isEmpty(value)) return true;
    if (Array.isArray(value))
        return (
            value.every((val) => /^-?\d+$/.test(String(val))) ||
            "Este campo debe ser un número entero"
        );

    return (
        /^-?\d+$/.test(String(value)) || "Este campo debe ser un número entero"
    );
};

// 👉 Regex Validator
export const regexValidator = (value, regex) => {
    if (isEmpty(value)) return true;
    let regeX = regex;
    if (typeof regeX === "string") regeX = new RegExp(regeX);
    if (Array.isArray(value))
        return value.every((val) => regexValidator(val, regeX));

    return (
        regeX.test(String(value)) || "El formato del campo Regex no es válido"
    );
};

// 👉 Alpha Validator
export const alphaValidator = (value) => {
    if (isEmpty(value)) return true;

    return (
        /^[A-Z]*$/i.test(String(value)) ||
        "El campo Alfa solo puede contener caracteres alfabéticos"
    );
};

// 👉 URL Validator
export const urlValidator = (value) => {
    if (isEmpty(value)) return true;
    const re = /^https?:\/\/[^\s$.?#].\S*$/;

    return re.test(String(value)) || "La URL no es válida";
};

// 👉 Length Validator
export const lengthValidator = (value, length) => {
    if (isEmpty(value)) return true;

    return (
        String(value).length === length ||
        `El campo debe tener al menos ${length} caracteres`
    );
};

// 👉 Alpha-dash Validator
export const alphaDashValidator = (value) => {
    if (isEmpty(value)) return true;
    const valueAsString = String(value);

    return (
        /^[\w-]*$/.test(valueAsString) || "No todos los caracteres son válidos"
    );
};

export const numberValidator = (modelValue) =>
    /^\d+$/.test(modelValue) || "Campo numérico";
