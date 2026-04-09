export class ParamSystemTypeEnum {
    static TEXT = "TEXT";
    static NUMBER = "NUMBER";
    static BOOLEAN = "BOOLEAN";
    static SELECTABLE = "SELECTABLE";
    static TEXTAREA = "TEXTAREA";
    static IMAGE = "IMAGE";

    static getTypeForHTML(type) {
        switch (type) {
            case ParamSystemTypeEnum.TEXT:
                return "text";
            case ParamSystemTypeEnum.NUMBER:
                return "number";
            case ParamSystemTypeEnum.BOOLEAN:
                return "checkbox";
            case ParamSystemTypeEnum.SELECTABLE:
                return "select";
            case ParamSystemTypeEnum.TEXTAREA:
                return "textarea";
            case ParamSystemTypeEnum.IMAGE:
                return "file";
            default:
                throw new Error("element doesn't supported.");
        }
    }
}
