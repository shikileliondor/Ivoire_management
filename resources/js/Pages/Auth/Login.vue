<script setup>
import { useForm, usePage } from '@inertiajs/vue3'
import { computed } from 'vue'

const school = computed(() => usePage().props.school ?? {})

const form = useForm({
  email: '',
  password: '',
  remember: false,
})

const submit = () => {
  form.post(route('login'), {
    onFinish: () => form.reset('password'),
  })
}
</script>

<template>
  <div class="login-shell">

    <!-- Panneau gauche — branding -->
    <div class="login-brand">
      <div class="brand-content">
        <div class="brand-icon">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
            <path d="M11.7 2.805a.75.75 0 01.6 0A60.65 60.65 0 0122.83 8.72a.75.75 0 01-.231 1.337 49.949 49.949 0 00-9.902 3.912l-.003.002-.34.18a.75.75 0 01-.707 0A50.009 50.009 0 007.5 12.174v-.224c0-.131.067-.248.172-.311a54.614 54.614 0 014.653-2.52.75.75 0 00-.65-1.352 56.129 56.129 0 00-4.78 2.589 1.858 1.858 0 00-.859 1.228 49.803 49.803 0 00-4.634-1.527.75.75 0 01-.231-1.337A60.653 60.653 0 0111.7 2.805z"/>
            <path d="M13.06 15.473a48.45 48.45 0 017.666-3.282c.134 1.414.22 2.843.255 4.285a.75.75 0 01-.46.71 47.878 47.878 0 00-8.105 4.342.75.75 0 01-.832 0 47.877 47.877 0 00-8.104-4.342.75.75 0 01-.461-.71c.035-1.442.121-2.87.255-4.286A48.4 48.4 0 016 13.18v1.27a1.5 1.5 0 00-.14 2.508c-.09.38-.222.753-.397 1.11.452.213.901.434 1.346.661a6.729 6.729 0 00.551-1.608 1.5 1.5 0 00.14-2.67v-.645a48.549 48.549 0 013.44 1.668 2.25 2.25 0 002.12 0z"/>
          </svg>
        </div>
        <h1 class="brand-title">EduGest CI</h1>
        <p class="brand-sub">Système de gestion scolaire</p>
        <p class="brand-desc">
          Gérez votre établissement simplement — élèves, notes, paiements et emplois du temps en un seul endroit.
        </p>
        <div class="brand-badges">
          <span class="badge">CP1 → CM2</span>
          <span class="badge">3 trimestres</span>
          <span class="badge">MENA CI</span>
        </div>
      </div>
    </div>

    <!-- Panneau droit — formulaire -->
    <div class="login-form-panel">
      <div class="login-card">

        <!-- En-tête -->
        <div class="login-header">
          <h2>Connexion</h2>
          <p>{{ school.name || 'Votre établissement' }}</p>
        </div>

        <!-- Formulaire -->
        <form @submit.prevent="submit" novalidate>

          <!-- Email -->
          <div class="field" :class="{ 'field--error': form.errors.email }">
            <label for="email">Adresse email</label>
            <div class="input-wrap">
              <span class="input-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                  <path d="M3 4a2 2 0 00-2 2v1.161l8.441 4.221a1.25 1.25 0 001.118 0L19 7.162V6a2 2 0 00-2-2H3z"/>
                  <path d="M19 8.839l-7.77 3.885a2.75 2.75 0 01-2.46 0L1 8.839V14a2 2 0 002 2h14a2 2 0 002-2V8.839z"/>
                </svg>
              </span>
              <input
                id="email"
                v-model="form.email"
                type="email"
                autocomplete="email"
                placeholder="exemple@ecole.ci"
                autofocus
              />
            </div>
            <span v-if="form.errors.email" class="field-error">{{ form.errors.email }}</span>
          </div>

          <!-- Mot de passe -->
          <div class="field" :class="{ 'field--error': form.errors.password }">
            <label for="password">Mot de passe</label>
            <div class="input-wrap">
              <span class="input-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10 1a4.5 4.5 0 00-4.5 4.5V9H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 8V5.5a3 3 0 10-6 0V9h6z" clip-rule="evenodd"/>
                </svg>
              </span>
              <input
                id="password"
                v-model="form.password"
                type="password"
                autocomplete="current-password"
                placeholder="••••••••"
              />
            </div>
            <span v-if="form.errors.password" class="field-error">{{ form.errors.password }}</span>
          </div>

          <!-- Se souvenir -->
          <div class="remember">
            <label>
              <input v-model="form.remember" type="checkbox" />
              <span>Se souvenir de moi</span>
            </label>
          </div>

          <!-- Bouton -->
          <button
            type="submit"
            class="btn-submit"
            :disabled="form.processing"
          >
            <svg v-if="form.processing" class="spinner" viewBox="0 0 24 24" fill="none">
              <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" stroke-dasharray="31.4" stroke-dashoffset="10"/>
            </svg>
            <span>{{ form.processing ? 'Connexion...' : 'Se connecter' }}</span>
          </button>

        </form>

        <!-- Footer -->
        <p class="login-footer">EduGest CI &copy; {{ new Date().getFullYear() }}</p>

      </div>
    </div>

  </div>
</template>

<style scoped>
/* ── Variables ───────────────────────────────────────────── */
:root {
  --green      : #1a5c38;
  --green-dark : #144830;
  --green-light: #e8f5ee;
  --accent     : #4ade80;
  --error      : #ef4444;
  --text       : #1e293b;
  --muted      : #64748b;
  --border     : #e2e8f0;
  --radius     : 10px;
}

/* ── Shell ───────────────────────────────────────────────── */
.login-shell {
  min-height    : 100vh;
  display       : flex;
  font-family   : 'Inter', 'Segoe UI', system-ui, sans-serif;
}

/* ── Panneau gauche (branding) ───────────────────────────── */
.login-brand {
  width      : 45%;
  background : linear-gradient(145deg, #1a5c38 0%, #0f3d25 100%);
  display    : flex;
  align-items: center;
  justify-content: center;
  padding    : 48px;
  position   : relative;
  overflow   : hidden;
}
.login-brand::before {
  content   : '';
  position  : absolute;
  width     : 400px;
  height    : 400px;
  border-radius: 50%;
  background: rgba(255,255,255,.04);
  top: -100px; right: -100px;
}
.login-brand::after {
  content   : '';
  position  : absolute;
  width     : 300px;
  height    : 300px;
  border-radius: 50%;
  background: rgba(255,255,255,.03);
  bottom: -80px; left: -80px;
}
.brand-content { position: relative; z-index: 1; max-width: 340px; }
.brand-icon {
  width           : 72px;
  height          : 72px;
  background      : rgba(255,255,255,.15);
  border-radius   : 20px;
  display         : flex;
  align-items     : center;
  justify-content : center;
  margin-bottom   : 24px;
  color           : #fff;
}
.brand-icon svg { width: 40px; height: 40px; }
.brand-title {
  font-size  : 2rem;
  font-weight: 800;
  color      : #fff;
  margin     : 0 0 6px;
}
.brand-sub {
  font-size : .95rem;
  color     : rgba(255,255,255,.7);
  margin    : 0 0 20px;
}
.brand-desc {
  font-size  : .875rem;
  color      : rgba(255,255,255,.6);
  line-height: 1.6;
  margin     : 0 0 28px;
}
.brand-badges { display: flex; gap: 8px; flex-wrap: wrap; }
.badge {
  padding      : 4px 12px;
  border-radius: 20px;
  border       : 1px solid rgba(255,255,255,.25);
  color        : rgba(255,255,255,.8);
  font-size    : .75rem;
  font-weight  : 500;
}

/* ── Panneau droit (formulaire) ──────────────────────────── */
.login-form-panel {
  flex            : 1;
  display         : flex;
  align-items     : center;
  justify-content : center;
  background      : #f8fafc;
  padding         : 32px 24px;
}
.login-card {
  width     : 100%;
  max-width : 400px;
  background: #fff;
  border    : 1px solid var(--border);
  border-radius: 16px;
  padding   : 40px 36px;
  box-shadow: 0 4px 24px rgba(0,0,0,.07);
}

/* En-tête */
.login-header { margin-bottom: 28px; }
.login-header h2 {
  font-size  : 1.5rem;
  font-weight: 700;
  color      : var(--text);
  margin     : 0 0 4px;
}
.login-header p {
  font-size: .875rem;
  color    : var(--muted);
  margin   : 0;
}

/* Champs */
.field { margin-bottom: 18px; }
.field label {
  display    : block;
  font-size  : .825rem;
  font-weight: 600;
  color      : var(--text);
  margin-bottom: 6px;
}
.input-wrap { position: relative; }
.input-icon {
  position        : absolute;
  left            : 12px;
  top             : 50%;
  transform       : translateY(-50%);
  color           : var(--muted);
  display         : flex;
  align-items     : center;
  pointer-events  : none;
}
.input-icon svg { width: 16px; height: 16px; }
.input-wrap input {
  width         : 100%;
  padding       : 10px 14px 10px 38px;
  border        : 1.5px solid var(--border);
  border-radius : var(--radius);
  font-size     : .875rem;
  color         : var(--text);
  background    : #fff;
  transition    : border-color .15s, box-shadow .15s;
  box-sizing    : border-box;
  outline       : none;
}
.input-wrap input:focus {
  border-color: #1a5c38;
  box-shadow  : 0 0 0 3px rgba(26,92,56,.12);
}
.field--error .input-wrap input {
  border-color: var(--error);
}
.field-error {
  display   : block;
  font-size : .75rem;
  color     : var(--error);
  margin-top: 5px;
}

/* Se souvenir */
.remember {
  margin-bottom: 22px;
}
.remember label {
  display    : flex;
  align-items: center;
  gap        : 8px;
  font-size  : .825rem;
  color      : var(--muted);
  cursor     : pointer;
}
.remember input[type="checkbox"] {
  width : 15px;
  height: 15px;
  accent-color: #1a5c38;
  cursor: pointer;
}

/* Bouton */
.btn-submit {
  width           : 100%;
  padding         : 12px;
  background      : #1a5c38;
  color           : #fff;
  border          : none;
  border-radius   : var(--radius);
  font-size       : .9rem;
  font-weight     : 600;
  cursor          : pointer;
  display         : flex;
  align-items     : center;
  justify-content : center;
  gap             : 8px;
  transition      : background .15s, transform .1s;
}
.btn-submit:hover:not(:disabled)  { background: #144830; }
.btn-submit:active:not(:disabled) { transform: scale(.98); }
.btn-submit:disabled { opacity: .65; cursor: not-allowed; }

/* Spinner */
.spinner {
  width : 18px;
  height: 18px;
  animation: spin .7s linear infinite;
}
@keyframes spin { to { transform: rotate(360deg); } }

/* Footer */
.login-footer {
  text-align : center;
  font-size  : .75rem;
  color      : var(--muted);
  margin     : 24px 0 0;
}

/* ── Responsive mobile ───────────────────────────────────── */
@media (max-width: 768px) {
  .login-shell  { flex-direction: column; }
  .login-brand  { width: 100%; padding: 36px 24px; min-height: 220px; }
  .brand-desc   { display: none; }
  .login-card   { padding: 28px 20px; }
}
</style>
