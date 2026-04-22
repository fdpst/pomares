/**
 * Identificador de la cuenta de negocio en sesión (mismo valor que guarda el login en localStorage).
 * Centraliza el acceso para que vistas y listados no repitan lógica ni acoplen URLs a ese detalle.
 */
const LS_ACCOUNT_KEY = "user_id";

export function getSessionAccountId() {
    const raw = localStorage.getItem(LS_ACCOUNT_KEY);
    const n = Number(raw);
    return Number.isFinite(n) && n > 0 ? n : null;
}

/**
 * Id de negocio para listados y formularios: gestor con cliente seleccionado, o cuenta de sesión.
 */
export function effectiveBusinessUserId() {
    const role = parseInt(localStorage.getItem("role") || "0", 10);
    const selectedCliente = localStorage.getItem("selected_cliente_id");
    if (role === 3 && selectedCliente) {
        return selectedCliente;
    }
    return getSessionAccountId();
}

/** Prefijo de carpeta en storage público (`userId_123`). */
export function storageAccountDirName(accountId) {
    return `userId_${accountId}`;
}
