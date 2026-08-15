<template>
  <div class="gateway-root">
    <form class="gateway-card card" @submit.prevent="handleSubmit">
      <div class="gateway-brand">
        <svg viewBox="0 0 100 100" fill="none" width="34" height="34">
          <ellipse cx="50" cy="62" rx="26" ry="22" stroke="#E02020" stroke-width="6" stroke-linecap="round"/>
          <ellipse cx="32" cy="36" rx="9" ry="12" stroke="#E02020" stroke-width="5.5" stroke-linecap="round"/>
          <ellipse cx="50" cy="29" rx="9" ry="12" stroke="#E02020" stroke-width="5.5" stroke-linecap="round"/>
          <ellipse cx="68" cy="36" rx="9" ry="12" stroke="#E02020" stroke-width="5.5" stroke-linecap="round"/>
          <ellipse cx="19" cy="50" rx="8" ry="11" stroke="#E02020" stroke-width="5" stroke-linecap="round"/>
          <ellipse cx="81" cy="50" rx="8" ry="11" stroke="#E02020" stroke-width="5" stroke-linecap="round"/>
          <path d="M50 72 C46 67, 38 63, 38 57 C38 52, 50 52, 50 59 C50 52, 62 52, 62 57 C62 63, 54 67, 50 72Z" stroke="#E02020" stroke-width="5" stroke-linejoin="round"/>
        </svg>
        <span>poseidonvet</span>
      </div>

      <!-- Stage 1: identify -->
      <template v-if="stage === 'identify'">
        <h1>Sign In</h1>
        <p class="text-light">Pet owner or staff — enter your phone or email to continue.</p>

        <div class="form-group" style="margin-top:22px">
          <label class="form-label">Phone number or email</label>
          <input class="form-control" v-model.trim="identifier" placeholder="0712 345 678 or you@example.com" required autofocus />
        </div>

        <div v-if="error" class="gateway-error">{{ error }}</div>

        <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;margin-top:18px" :disabled="loading">
          {{ loading ? 'Checking…' : 'Continue' }}
        </button>
      </template>

      <!-- Stage 2a: staff password -->
      <template v-else-if="stage === 'staff-password'">
        <h1>Staff Sign In</h1>
        <p class="text-light">{{ identifier }}</p>

        <div class="form-group" style="margin-top:22px">
          <label class="form-label">Password</label>
          <input type="password" class="form-control" v-model="password" placeholder="••••••••" required autofocus />
        </div>

        <div v-if="error" class="gateway-error">{{ error }}</div>

        <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;margin-top:18px" :disabled="loading">
          {{ loading ? 'Signing in…' : 'Sign In' }}
        </button>
        <button type="button" class="link-btn" style="margin-top:14px" @click="reset">← Not you? Start over</button>
      </template>

      <!-- Stage 2b: client password -->
      <template v-else-if="stage === 'client-password'">
        <h1>Sign In</h1>
        <p class="text-light">{{ identifier }}</p>

        <div class="form-group" style="margin-top:22px">
          <label class="form-label">Password</label>
          <input type="password" class="form-control" v-model="password" placeholder="••••••••" required autofocus />
        </div>

        <div v-if="error" class="gateway-error">{{ error }}</div>

        <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;margin-top:18px" :disabled="loading">
          {{ loading ? 'Signing in…' : 'Sign In' }}
        </button>
        <div class="gateway-actions">
          <button type="button" class="link-btn" @click="reset">← Not you? Start over</button>
          <button type="button" class="link-btn" :disabled="loading" @click="requestClientOtp(identifier)">Forgot password? Use a code instead</button>
        </div>
      </template>

      <!-- Stage 2c: client OTP (first-time setup or password recovery) -->
      <template v-else-if="stage === 'client-otp'">
        <h1>Enter your code</h1>
        <p class="text-light">We sent a 6-digit code to <strong>{{ maskedDestination }}</strong>.</p>

        <div class="form-group" style="margin-top:22px">
          <label class="form-label">6-digit code</label>
          <input class="form-control otp-input" v-model.trim="code" inputmode="numeric" maxlength="6" placeholder="••••••" required autofocus />
        </div>

        <div v-if="error" class="gateway-error">{{ error }}</div>

        <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;margin-top:18px" :disabled="loading">
          {{ loading ? 'Verifying…' : 'Verify' }}
        </button>
        <div class="gateway-actions">
          <button type="button" class="link-btn" @click="reset">← Start over</button>
          <button type="button" class="link-btn" :disabled="loading" @click="requestClientOtp(identifier)">Resend code</button>
        </div>
      </template>

      <!-- Stage 2d: client set/reset password -->
      <template v-else-if="stage === 'client-set-password'">
        <h1>{{ hasPassword ? 'Reset your password' : 'Set your password' }}</h1>
        <p class="text-light">{{ hasPassword ? 'Choose a new password for next time.' : 'Create a password so you can skip the code next time.' }}</p>

        <div class="form-group" style="margin-top:22px">
          <label class="form-label">New password</label>
          <input type="password" class="form-control" v-model="password" placeholder="At least 8 characters" required autofocus minlength="8" />
        </div>
        <div class="form-group" style="margin-top:14px">
          <label class="form-label">Confirm password</label>
          <input type="password" class="form-control" v-model="passwordConfirmation" placeholder="Repeat password" required minlength="8" />
        </div>

        <div v-if="error" class="gateway-error">{{ error }}</div>

        <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;margin-top:18px" :disabled="loading">
          {{ loading ? 'Saving…' : 'Save & Continue' }}
        </button>
      </template>

      <p class="gateway-footer-note">
        Booking without an account? <RouterLink to="/appointments">Book an appointment →</RouterLink>
      </p>
    </form>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { api }           from '../lib/api.js'
import { useAdminAuth }  from '../composables/useAdminAuth.js'
import { usePortalAuth } from '../composables/usePortalAuth.js'

const router = useRouter()
const { login: staffLogin }                          = useAdminAuth()
const { requestOtp, verifyOtp, login: clientLogin, setPassword } = usePortalAuth()

const stage                 = ref('identify')
const identifier            = ref('')
const password               = ref('')
const passwordConfirmation   = ref('')
const code                    = ref('')
const maskedDestination       = ref('')
const hasPassword             = ref(false)
const loading                  = ref(false)
const error                    = ref('')

function reset() {
  stage.value = 'identify'
  password.value = ''
  passwordConfirmation.value = ''
  code.value = ''
  error.value = ''
}

async function requestClientOtp(id) {
  loading.value = true
  error.value = ''
  try {
    const res = await requestOtp(id)
    maskedDestination.value = res.masked_destination || 'your registered contact'
    stage.value = 'client-otp'
  } catch (e) {
    error.value = e.message
  } finally {
    loading.value = false
  }
}

async function handleIdentify() {
  loading.value = true
  error.value = ''
  try {
    if (identifier.value.includes('@')) {
      const { is_staff } = await api.post('/auth/check-identity.php', { identifier: identifier.value })
      if (is_staff) {
        stage.value = 'staff-password'
        loading.value = false
        return
      }
    }
    stage.value = 'client-password'
    loading.value = false
  } catch (e) {
    error.value = e.message
    loading.value = false
  }
}

async function handleStaffLogin() {
  loading.value = true
  error.value = ''
  try {
    await staffLogin(identifier.value, password.value)
    router.push('/admin')
  } catch (e) {
    error.value = e.message
    loading.value = false
  }
}

async function handleClientLogin() {
  loading.value = true
  error.value = ''
  try {
    await clientLogin(identifier.value, password.value)
    router.push('/portal')
  } catch (e) {
    if (e.data?.needs_setup) {
      error.value = ''
      password.value = ''
      await requestClientOtp(identifier.value)
      return
    }
    error.value = e.message
    loading.value = false
  }
}

async function handleClientVerify() {
  loading.value = true
  error.value = ''
  try {
    const { hasPassword: existing } = await verifyOtp(identifier.value, code.value)
    hasPassword.value = existing
    code.value = ''
    stage.value = 'client-set-password'
  } catch (e) {
    error.value = e.message
  } finally {
    loading.value = false
  }
}

async function handleSetPassword() {
  loading.value = true
  error.value = ''
  if (password.value !== passwordConfirmation.value) {
    error.value = 'Passwords do not match.'
    loading.value = false
    return
  }
  try {
    await setPassword(password.value, passwordConfirmation.value)
    router.push('/portal')
  } catch (e) {
    error.value = e.message
    loading.value = false
  }
}

function handleSubmit() {
  if (stage.value === 'identify')            return handleIdentify()
  if (stage.value === 'staff-password')      return handleStaffLogin()
  if (stage.value === 'client-password')     return handleClientLogin()
  if (stage.value === 'client-otp')          return handleClientVerify()
  if (stage.value === 'client-set-password') return handleSetPassword()
}
</script>

<style scoped>
.gateway-root {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 24px;
  background: var(--gray-50);
}
.gateway-card { width: 100%; max-width: 400px; padding: 40px; }
.gateway-brand { display: flex; align-items: center; gap: 10px; margin-bottom: 28px; }
.gateway-brand span { font-size: 1.05rem; font-weight: 800; color: var(--navy); }
.gateway-card h1 { font-size: 1.4rem; margin-bottom: 6px; }
.otp-input { font-size: 1.3rem; font-weight: 700; letter-spacing: 0.4em; text-align: center; }
.gateway-error {
  background: #FEE2E2; color: var(--red); font-size: 0.82rem;
  padding: 10px 14px; border-radius: var(--radius-sm);
  border-left: 3px solid var(--red); margin-top: 14px;
}
.link-btn { font-size: 0.82rem; color: var(--teal); font-weight: 600; }
.link-btn:hover { text-decoration: underline; }
.link-btn:disabled { opacity: 0.5; cursor: not-allowed; }
.gateway-actions { display: flex; justify-content: space-between; margin-top: 14px; flex-wrap: wrap; gap: 8px; }
.gateway-footer-note {
  margin-top: 28px; padding-top: 20px;
  border-top: 1px solid var(--border);
  font-size: 0.8rem; color: var(--text-light); text-align: center;
}
.gateway-footer-note a { color: var(--teal); font-weight: 600; }
</style>
