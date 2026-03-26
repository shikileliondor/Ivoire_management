<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { useForm } from '@inertiajs/vue3'
import { ref } from 'vue'

defineOptions({ layout: AppLayout })

const props = defineProps({ settings: Object })

const form = useForm({
  name          : props.settings.name          ?? '',
  address       : props.settings.address       ?? '',
  phone         : props.settings.phone         ?? '',
  email         : props.settings.email         ?? '',
  slogan        : props.settings.slogan        ?? '',
  director_name : props.settings.director_name ?? '',
  logo          : null,
})

// Prévisualisation logo
const logoPreview = ref(props.settings.logo ?? null)

function onLogoChange(e) {
  const file = e.target.files[0]
  if (!file) return
  form.logo = file
  const reader = new FileReader()
  reader.onload = (ev) => { logoPreview.value = ev.target.result }
  reader.readAsDataURL(file)
}

function submit() {
  form.patch(route('settings.school.update'), {
    forceFormData: true,
    onSuccess: () => form.reset('logo'),
  })
}
</script>

<template>
  <div class="settings-page">

    <!-- Titre de section -->
    <div class="page-header">
      <div>
        <h1>Paramètres de l'école</h1>
        <p>Informations générales affichées sur les documents et bulletins.</p>
      </div>
    </div>

    <form @submit.prevent="submit" enctype="multipart/form-data">
      <div class="settings-grid">

        <!-- ── Colonne logo ───────────────────────────────────── -->
        <div class="card card--logo">
          <h2 class="card-title">Logo de l'établissement</h2>
          <div class="logo-upload">
            <div class="logo-preview">
              <img v-if="logoPreview" :src="logoPreview" alt="Logo" />
              <span v-else class="logo-placeholder">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M11.7 2.805a.75.75 0 01.6 0A60.65 60.65 0 0122.83 8.72a.75.75 0 01-.231 1.337 49.949 49.949 0 00-9.902 3.912l-.003.002-.34.18a.75.75 0 01-.707 0A50.009 50.009 0 007.5 12.174v-.224c0-.131.067-.248.172-.311a54.614 54.614 0 014.653-2.52.75.75 0 00-.65-1.352 56.129 56.129 0 00-4.78 2.589 1.858 1.858 0 00-.859 1.228 49.803 49.803 0 00-4.634-1.527.75.75 0 01-.231-1.337A60.653 60.653 0 0111.7 2.805z"/>
                  <path d="M13.06 15.473a48.45 48.45 0 017.666-3.282c.134 1.414.22 2.843.255 4.285a.75.75 0 01-.46.71 47.878 47.878 0 00-8.105 4.342.75.75 0 01-.832 0 47.877 47.877 0 00-8.104-4.342.75.75 0 01-.461-.71c.035-1.442.121-2.87.255-4.286A48.4 48.4 0 016 13.18v1.27a1.5 1.5 0 00-.14 2.508c-.09.38-.222.753-.397 1.11.452.213.901.434 1.346.661a6.729 6.729 0 00.551-1.608 1.5 1.5 0 00.14-2.67v-.645a48.549 48.549 0 013.44 1.668 2.25 2.25 0 002.12 0z"/>
                </svg>
              </span>
            </div>
            <label class="btn-upload">
              <input type="file" accept="image/*" @change="onLogoChange" hidden />
              Choisir un logo
            </label>
            <p class="logo-hint">JPG, PNG ou WEBP — max 2 Mo</p>
            <span v-if="form.errors.logo" class="field-error">{{ form.errors.logo }}</span>
          </div>
        </div>

        <!-- ── Colonne infos ──────────────────────────────────── -->
        <div class="card card--info">
          <h2 class="card-title">Informations générales</h2>
          <div class="form-grid">

            <!-- Nom -->
            <div class="field field--full" :class="{ 'field--error': form.errors.name }">
              <label>Nom de l'établissement <span class="required">*</span></label>
              <input v-model="form.name" type="text" placeholder="École Primaire Privée …" />
              <span v-if="form.errors.name" class="field-error">{{ form.errors.name }}</span>
            </div>

            <!-- Slogan -->
            <div class="field field--full" :class="{ 'field--error': form.errors.slogan }">
              <label>Slogan / Devise</label>
              <input v-model="form.slogan" type="text" placeholder="Ex : L'excellence au service de l'avenir" />
              <span v-if="form.errors.slogan" class="field-error">{{ form.errors.slogan }}</span>
            </div>

            <!-- Directeur -->
            <div class="field field--full" :class="{ 'field--error': form.errors.director_name }">
              <label>Nom du directeur</label>
              <input v-model="form.director_name" type="text" placeholder="M. / Mme …" />
              <span v-if="form.errors.director_name" class="field-error">{{ form.errors.director_name }}</span>
            </div>

            <!-- Téléphone -->
            <div class="field" :class="{ 'field--error': form.errors.phone }">
              <label>Téléphone</label>
              <input v-model="form.phone" type="tel" placeholder="+225 07 XX XX XX XX" />
              <span v-if="form.errors.phone" class="field-error">{{ form.errors.phone }}</span>
            </div>

            <!-- Email -->
            <div class="field" :class="{ 'field--error': form.errors.email }">
              <label>Email</label>
              <input v-model="form.email" type="email" placeholder="contact@ecole.ci" />
              <span v-if="form.errors.email" class="field-error">{{ form.errors.email }}</span>
            </div>

            <!-- Adresse -->
            <div class="field field--full" :class="{ 'field--error': form.errors.address }">
              <label>Adresse</label>
              <textarea v-model="form.address" rows="3" placeholder="Quartier, commune, ville …" />
              <span v-if="form.errors.address" class="field-error">{{ form.errors.address }}</span>
            </div>

          </div>
        </div>

      </div>

      <!-- Barre d'actions -->
      <div class="form-actions">
        <button type="submit" class="btn-save" :disabled="form.processing">
          <svg v-if="form.processing" class="spinner" viewBox="0 0 24 24" fill="none">
            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" stroke-dasharray="31.4" stroke-dashoffset="10"/>
          </svg>
          <svg v-else xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path d="M10.75 2.75a.75.75 0 00-1.5 0v8.614L6.295 8.235a.75.75 0 10-1.09 1.03l4.25 4.5a.75.75 0 001.09 0l4.25-4.5a.75.75 0 00-1.09-1.03l-2.955 3.129V2.75z"/>
            <path d="M3.5 12.75a.75.75 0 00-1.5 0v2.5A2.75 2.75 0 004.75 18h10.5A2.75 2.75 0 0018 15.25v-2.5a.75.75 0 00-1.5 0v2.5c0 .69-.56 1.25-1.25 1.25H4.75c-.69 0-1.25-.56-1.25-1.25v-2.5z"/>
          </svg>
          {{ form.processing ? 'Enregistrement...' : 'Enregistrer' }}
        </button>
      </div>

    </form>
  </div>
</template>

<style scoped>
.settings-page { max-width: 900px; }

/* En-tête */
.page-header {
  display        : flex;
  align-items    : flex-start;
  justify-content: space-between;
  margin-bottom  : 28px;
}
.page-header h1 {
  font-size  : 1.375rem;
  font-weight: 700;
  color      : #1e293b;
  margin     : 0 0 4px;
}
.page-header p {
  font-size: .875rem;
  color    : #64748b;
  margin   : 0;
}

/* Grille 2 colonnes */
.settings-grid {
  display              : grid;
  grid-template-columns: 260px 1fr;
  gap                  : 20px;
  margin-bottom        : 20px;
}

/* Cards */
.card {
  background   : #fff;
  border       : 1px solid #e2e8f0;
  border-radius: 12px;
  padding      : 24px;
}
.card-title {
  font-size    : .875rem;
  font-weight  : 600;
  color        : #1e293b;
  margin       : 0 0 20px;
  padding-bottom: 12px;
  border-bottom: 1px solid #f1f5f9;
}

/* Logo */
.logo-upload { display: flex; flex-direction: column; align-items: center; gap: 12px; }
.logo-preview {
  width        : 140px;
  height       : 140px;
  border-radius: 12px;
  border       : 2px dashed #cbd5e1;
  overflow     : hidden;
  display      : flex;
  align-items  : center;
  justify-content: center;
  background   : #f8fafc;
}
.logo-preview img { width: 100%; height: 100%; object-fit: contain; }
.logo-placeholder { color: #94a3b8; }
.logo-placeholder svg { width: 52px; height: 52px; }
.btn-upload {
  padding      : 8px 18px;
  background   : #f1f5f9;
  border       : 1px solid #e2e8f0;
  border-radius: 8px;
  font-size    : .825rem;
  font-weight  : 500;
  color        : #334155;
  cursor       : pointer;
  transition   : background .15s;
}
.btn-upload:hover { background: #e2e8f0; }
.logo-hint { font-size: .72rem; color: #94a3b8; margin: 0; }

/* Formulaire */
.form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
.field { display: flex; flex-direction: column; gap: 5px; }
.field--full { grid-column: 1 / -1; }
.field label {
  font-size  : .8rem;
  font-weight: 600;
  color      : #374151;
}
.required { color: #ef4444; }
.field input,
.field textarea {
  padding      : 9px 12px;
  border       : 1.5px solid #e2e8f0;
  border-radius: 8px;
  font-size    : .875rem;
  color        : #1e293b;
  font-family  : inherit;
  transition   : border-color .15s, box-shadow .15s;
  outline      : none;
  resize       : vertical;
}
.field input:focus,
.field textarea:focus {
  border-color: #1a5c38;
  box-shadow  : 0 0 0 3px rgba(26,92,56,.1);
}
.field--error input,
.field--error textarea { border-color: #ef4444; }
.field-error { font-size: .75rem; color: #ef4444; }

/* Actions */
.form-actions {
  display        : flex;
  justify-content: flex-end;
}
.btn-save {
  display        : flex;
  align-items    : center;
  gap            : 8px;
  padding        : 10px 24px;
  background     : #1a5c38;
  color          : #fff;
  border         : none;
  border-radius  : 8px;
  font-size      : .875rem;
  font-weight    : 600;
  cursor         : pointer;
  transition     : background .15s;
}
.btn-save:hover:not(:disabled) { background: #144830; }
.btn-save:disabled { opacity: .65; cursor: not-allowed; }
.btn-save svg { width: 17px; height: 17px; }
.spinner { animation: spin .7s linear infinite; }
@keyframes spin { to { transform: rotate(360deg); } }

/* Responsive */
@media (max-width: 700px) {
  .settings-grid { grid-template-columns: 1fr; }
  .form-grid { grid-template-columns: 1fr; }
}
</style>