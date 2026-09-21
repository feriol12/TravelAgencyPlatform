import api from './api'

function isUnauthenticated(error) {
  const status = error.response?.status
  return status === 401 || status === 419
}

export function fetchCsrfCookie() {
  return api.get('/sanctum/csrf-cookie')
}

export async function login(credentials) {
  await fetchCsrfCookie()
  const { data } = await api.post('/api/v1/auth/login', credentials)
  return data.data
}

export async function logout() {
  await api.post('/api/v1/auth/logout')
}

export async function fetchCurrentUser() {
  try {
    const { data } = await api.get('/api/v1/auth/me')
    return data.data
  } catch (error) {
    if (isUnauthenticated(error)) {
      return null
    }
    throw error
  }
}
