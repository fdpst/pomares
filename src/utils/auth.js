/** Claves de sesión del backoffice (localStorage). */
export const AUTH_STORAGE_KEYS = [
  'id_token',
  'user_name',
  'user_email',
  'role',
  'user_id',
  'selected_cliente_id',
]

export function getAuthToken() {
  return localStorage.getItem('id_token')
}

export function isAuthenticated() {
  return Boolean(getAuthToken())
}

/** Borra sesión local y cookies usadas por el layout. */
export function clearAuthStorage() {
  AUTH_STORAGE_KEYS.forEach(key => localStorage.removeItem(key))

  const cookieNames = ['userData', 'accessToken', 'userAbilityRules']
  cookieNames.forEach(name => {
    document.cookie = `${name}=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;`
  })
}

export function saveAuthFromLoginResponse(data) {
  localStorage.setItem('id_token', data.token)
  localStorage.setItem('user_name', data.user.name)
  localStorage.setItem('user_email', data.user.email)
  localStorage.setItem('role', data.user.role)
  localStorage.setItem('user_id', data.user.id)

  if (data.userAbilityRules) {
    document.cookie = `userAbilityRules=${JSON.stringify(data.userAbilityRules)}`
  }
}
