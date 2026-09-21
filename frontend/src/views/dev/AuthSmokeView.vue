<script setup>
// Temporary smoke-test view for the Sanctum SPA authentication foundation
// (card 01.0). Not final UI/UX; remove once a real login page exists.
import { ref } from 'vue'
import { login, logout, fetchCurrentUser } from '../../services/auth'

const email = ref('')
const password = ref('')
const result = ref(null)
const error = ref(null)

async function run(action) {
  error.value = null
  result.value = null
  try {
    result.value = await action()
  } catch (err) {
    error.value = err.response ? `${err.response.status}: ${JSON.stringify(err.response.data)}` : err.message
  }
}

const doLogin = () => run(() => login({ email: email.value, password: password.value }))
const doLogout = () => run(() => logout())
const doMe = () => run(() => fetchCurrentUser())
</script>

<template>
  <main aria-label="Auth smoke test (temporary)">
    <h1>Auth smoke test (temporary)</h1>
    <p>Manual verification only. Not part of the product UI.</p>

    <div>
      <label>
        Email
        <input v-model="email" type="email" />
      </label>
      <label>
        Password
        <input v-model="password" type="password" />
      </label>
    </div>

    <button type="button" @click="doLogin">Login</button>
    <button type="button" @click="doMe">Fetch /auth/me</button>
    <button type="button" @click="doLogout">Logout</button>

    <pre v-if="result">{{ JSON.stringify(result, null, 2) }}</pre>
    <pre v-if="error">{{ error }}</pre>
  </main>
</template>
