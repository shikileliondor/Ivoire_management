<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { usePage, Link } from '@inertiajs/vue3'
import { computed } from 'vue'

defineOptions({ layout: AppLayout })

const props = defineProps({
  academicYear   : Object,
  stats          : Object,
  alerts         : Array,
  recentPayments : Array,
  recentStudents : Array,
})

const can  = computed(() => usePage().props.can ?? {})

// Formatage FCFA
function fcfa(val) {
  return new Intl.NumberFormat('fr-CI', {
    style: 'currency', currency: 'XOF', maximumFractionDigits: 0,
  }).format(val ?? 0)
}

// Libellés paiement
const paymentLabels = {
  inscription : 'Inscription',
  tranche_1   : '1ère tranche',
  tranche_2   : '2ème tranche',
  tranche_3   : '3ème tranche',
}
</script>

<template>
  <div class="dashboard">

    <!-- ── En-tête ──────────────────────────────────────────────────────── -->
    <div class="dash-header">
      <div>
        <h1>Tableau de bord</h1>
        <p v-if="academicYear">Année scolaire : <strong>{{ academicYear.label }}</strong></p>
        <p v-else class="no-year">Aucune année scolaire active</p>
      </div>
      <span class="today">{{ new Date().toLocaleDateString('fr-CI', { weekday:'long', day:'2-digit', month:'long', year:'numeric' }) }}</span>
    </div>

    <!-- ── Alertes ──────────────────────────────────────────────────────── -->
    <div class="alerts-row">
      <component
        :is="alert.link ? 'a' : 'div'"
        v-for="(alert, i) in alerts"
        :key="i"
        :href="alert.link ?? undefined"
        :class="['alert-chip', `alert-chip--${alert.type}`]"
      >
        <!-- Icône selon type -->
        <svg v-if="alert.type === 'error'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd"/>
        </svg>
        <svg v-else-if="alert.type === 'warning'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
        </svg>
        <svg v-else-if="alert.type === 'success'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/>
        </svg>
        <svg v-else xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a.75.75 0 000 1.5h.253a.25.25 0 01.244.304l-.459 2.066A1.75 1.75 0 0010.747 15H11a.75.75 0 000-1.5h-.253a.25.25 0 01-.244-.304l.459-2.066A1.75 1.75 0 009.253 9H9z" clip-rule="evenodd"/>
        </svg>
        <span>{{ alert.message }}</span>
        <svg v-if="alert.link" class="alert-arrow" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M3 10a.75.75 0 01.75-.75h10.638L10.23 5.29a.75.75 0 111.04-1.08l5.5 5.25a.75.75 0 010 1.08l-5.5 5.25a.75.75 0 11-1.04-1.08l4.158-3.96H3.75A.75.75 0 013 10z" clip-rule="evenodd"/>
        </svg>
      </component>
    </div>

    <!-- ── Cartes de stats ───────────────────────────────────────────────── -->
    <div class="stats-grid">

      <!-- Élèves -->
      <div class="stat-card stat-card--green">
        <div class="stat-icon">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
            <path d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
          </svg>
        </div>
        <div class="stat-body">
          <span class="stat-value">{{ stats.students }}</span>
          <span class="stat-label">Élèves inscrits</span>
        </div>
      </div>

      <!-- Classes -->
      <div class="stat-card stat-card--blue">
        <div class="stat-icon">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
            <path d="M11.25 4.533A9.707 9.707 0 006 3a9.735 9.735 0 00-3.25.555.75.75 0 00-.5.707v14.25a.75.75 0 001 .707A8.237 8.237 0 016 18.75c1.995 0 3.823.707 5.25 1.886V4.533zM12.75 20.636A8.214 8.214 0 0118 18.75c.966 0 1.89.166 2.75.47a.75.75 0 001-.708V4.262a.75.75 0 00-.5-.707A9.735 9.735 0 0018 3a9.707 9.707 0 00-5.25 1.533v16.103z"/>
          </svg>
        </div>
        <div class="stat-body">
          <span class="stat-value">{{ stats.classrooms }}</span>
          <span class="stat-label">Classes actives</span>
        </div>
      </div>

      <!-- Enseignants -->
      <div class="stat-card stat-card--purple">
        <div class="stat-icon">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
            <path fill-rule="evenodd" d="M8.25 6.75a3.75 3.75 0 117.5 0 3.75 3.75 0 01-7.5 0zM15.75 9.75a3 3 0 116 0 3 3 0 01-6 0zM2.25 9.75a3 3 0 116 0 3 3 0 01-6 0zM6.31 15.117A6.745 6.745 0 0112 12a6.745 6.745 0 016.709 7.498.75.75 0 01-.372.568A12.696 12.696 0 0112 21.75c-2.305 0-4.47-.612-6.337-1.684a.75.75 0 01-.372-.568 6.787 6.787 0 011.019-4.38z" clip-rule="evenodd"/>
            <path d="M5.082 14.254a8.287 8.287 0 00-1.308 5.135 9.687 9.687 0 01-1.764-.44l-.115-.04a.563.563 0 01-.373-.487l-.01-.121a3.75 3.75 0 013.57-4.047zM20.226 19.389a8.287 8.287 0 00-1.308-5.135 3.75 3.75 0 013.57 4.047l-.01.121a.563.563 0 01-.373.486l-.115.04c-.567.2-1.156.349-1.764.441z"/>
          </svg>
        </div>
        <div class="stat-body">
          <span class="stat-value">{{ stats.staff }}</span>
          <span class="stat-label">Enseignants</span>
        </div>
      </div>

      <!-- Paiements du jour -->
      <div class="stat-card stat-card--amber" v-if="can['payments.view']">
        <div class="stat-icon">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
            <path d="M12 7.5a2.25 2.25 0 100 4.5 2.25 2.25 0 000-4.5z"/>
            <path fill-rule="evenodd" d="M1.5 4.875C1.5 3.839 2.34 3 3.375 3h17.25c1.035 0 1.875.84 1.875 1.875v9.75c0 1.036-.84 1.875-1.875 1.875H3.375A1.875 1.875 0 011.5 14.625v-9.75zM8.25 9.75a3.75 3.75 0 117.5 0 3.75 3.75 0 01-7.5 0zM18.75 9a.75.75 0 00-.75.75v.008c0 .414.336.75.75.75h.008a.75.75 0 00.75-.75V9.75a.75.75 0 00-.75-.75h-.008zM4.5 9.75A.75.75 0 015.25 9h.008a.75.75 0 01.75.75v.008a.75.75 0 01-.75.75H5.25a.75.75 0 01-.75-.75V9.75z" clip-rule="evenodd"/>
            <path d="M2.25 18a.75.75 0 000 1.5c5.4 0 10.63.722 15.6 2.075 1.19.324 2.4-.558 2.4-1.82V18.75a.75.75 0 00-.75-.75H2.25z"/>
          </svg>
        </div>
        <div class="stat-body">
          <span class="stat-value stat-value--sm">{{ fcfa(stats.paid_today) }}</span>
          <span class="stat-label">Encaissé aujourd'hui</span>
        </div>
      </div>

      <!-- Impayés -->
      <div class="stat-card stat-card--red" v-if="can['payments.view']">
        <div class="stat-icon">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
            <path fill-rule="evenodd" d="M9.401 3.003c1.155-2 4.043-2 5.197 0l7.355 12.748c1.154 2-.29 4.5-2.599 4.5H4.645c-2.309 0-3.752-2.5-2.598-4.5L9.4 3.003zM12 8.25a.75.75 0 01.75.75v3.75a.75.75 0 01-1.5 0V9a.75.75 0 01.75-.75zm0 8.25a.75.75 0 100-1.5.75.75 0 000 1.5z" clip-rule="evenodd"/>
          </svg>
        </div>
        <div class="stat-body">
          <span class="stat-value">{{ stats.unpaid }}</span>
          <span class="stat-label">Inscriptions impayées</span>
        </div>
      </div>

      <!-- Absences du mois -->
      <div class="stat-card stat-card--orange">
        <div class="stat-icon">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
            <path fill-rule="evenodd" d="M6.75 2.25A.75.75 0 017.5 3v1.5h9V3A.75.75 0 0118 3v1.5h.75a3 3 0 013 3v11.25a3 3 0 01-3 3H5.25a3 3 0 01-3-3V7.5a3 3 0 013-3H6V3a.75.75 0 01.75-.75zm13.5 9a1.5 1.5 0 00-1.5-1.5H5.25a1.5 1.5 0 00-1.5 1.5v7.5a1.5 1.5 0 001.5 1.5h13.5a1.5 1.5 0 001.5-1.5v-7.5z" clip-rule="evenodd"/>
          </svg>
        </div>
        <div class="stat-body">
          <span class="stat-value">{{ stats.absences_month }}</span>
          <span class="stat-label">Absences non justifiées (mois)</span>
        </div>
      </div>

    </div>

    <!-- ── Tableaux récents ───────────────────────────────────────────────── -->
    <div class="tables-row">

      <!-- Élèves récents -->
      <div class="table-card" v-if="can['students.view'] && recentStudents.length">
        <div class="table-card-header">
          <h2>Dernières inscriptions</h2>
          <Link :href="route('students.index')" class="see-all">Voir tout →</Link>
        </div>
        <table>
          <thead>
            <tr>
              <th>Élève</th>
              <th>Matricule</th>
              <th>Classe</th>
              <th>Date</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="s in recentStudents" :key="s.id">
              <td class="td-name">{{ s.full_name }}</td>
              <td><span class="badge-mono">{{ s.matricule }}</span></td>
              <td>{{ s.classroom_name }}</td>
              <td class="td-date">{{ s.enrolled_at }}</td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Paiements récents -->
      <div class="table-card" v-if="can['payments.view'] && recentPayments.length">
        <div class="table-card-header">
          <h2>Derniers paiements</h2>
          <Link :href="route('payments.index')" class="see-all">Voir tout →</Link>
        </div>
        <table>
          <thead>
            <tr>
              <th>Élève</th>
              <th>Type</th>
              <th>Montant</th>
              <th>Date</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="p in recentPayments" :key="p.id">
              <td class="td-name">{{ p.student_name }}</td>
              <td><span class="badge-type">{{ paymentLabels[p.payment_type] ?? p.payment_type }}</span></td>
              <td class="td-amount">{{ fcfa(p.amount) }}</td>
              <td class="td-date">{{ p.payment_date }}</td>
            </tr>
          </tbody>
        </table>
      </div>

    </div>

  </div>
</template>

<style scoped>
.dashboard { max-width: 1100px; }

/* ── En-tête ────────────────────────────────────────────── */
.dash-header {
  display        : flex;
  align-items    : flex-start;
  justify-content: space-between;
  margin-bottom  : 24px;
  flex-wrap      : wrap;
  gap            : 8px;
}
.dash-header h1 {
  font-size  : 1.375rem;
  font-weight: 700;
  color      : #1e293b;
  margin     : 0 0 4px;
}
.dash-header p { font-size: .875rem; color: #64748b; margin: 0; }
.dash-header .no-year { color: #ef4444; }
.today {
  font-size  : .8rem;
  color      : #94a3b8;
  white-space: nowrap;
  margin-top : 4px;
}

/* ── Alertes ────────────────────────────────────────────── */
.alerts-row {
  display       : flex;
  flex-direction: column;
  gap           : 8px;
  margin-bottom : 24px;
}
.alert-chip {
  display       : flex;
  align-items   : center;
  gap           : 10px;
  padding       : 12px 16px;
  border-radius : 10px;
  font-size     : .85rem;
  font-weight   : 500;
  text-decoration: none;
  transition    : opacity .15s;
}
.alert-chip svg:first-child { width: 18px; height: 18px; flex-shrink: 0; }
.alert-chip span { flex: 1; }
.alert-arrow { width: 16px; height: 16px; opacity: .6; }
.alert-chip--error   { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }
.alert-chip--warning { background: #fffbeb; color: #92400e; border: 1px solid #fde68a; }
.alert-chip--info    { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
.alert-chip--success { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
a.alert-chip:hover   { opacity: .85; }

/* ── Stats ──────────────────────────────────────────────── */
.stats-grid {
  display              : grid;
  grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
  gap                  : 16px;
  margin-bottom        : 28px;
}
.stat-card {
  display      : flex;
  align-items  : center;
  gap          : 16px;
  padding      : 20px;
  border-radius: 12px;
  border       : 1px solid transparent;
}
.stat-icon {
  width           : 48px;
  height          : 48px;
  border-radius   : 12px;
  display         : flex;
  align-items     : center;
  justify-content : center;
  flex-shrink     : 0;
}
.stat-icon svg { width: 24px; height: 24px; }
.stat-body    { display: flex; flex-direction: column; }
.stat-value   { font-size: 1.75rem; font-weight: 800; line-height: 1; }
.stat-value--sm { font-size: 1.1rem; }
.stat-label   { font-size: .75rem; margin-top: 4px; opacity: .75; }

/* Couleurs cartes */
.stat-card--green  { background: #f0fdf4; border-color: #bbf7d0; color: #166534; }
.stat-card--green  .stat-icon { background: #dcfce7; }
.stat-card--blue   { background: #eff6ff; border-color: #bfdbfe; color: #1e40af; }
.stat-card--blue   .stat-icon { background: #dbeafe; }
.stat-card--purple { background: #faf5ff; border-color: #e9d5ff; color: #6b21a8; }
.stat-card--purple .stat-icon { background: #f3e8ff; }
.stat-card--amber  { background: #fffbeb; border-color: #fde68a; color: #92400e; }
.stat-card--amber  .stat-icon { background: #fef3c7; }
.stat-card--red    { background: #fef2f2; border-color: #fecaca; color: #991b1b; }
.stat-card--red    .stat-icon { background: #fee2e2; }
.stat-card--orange { background: #fff7ed; border-color: #fed7aa; color: #9a3412; }
.stat-card--orange .stat-icon { background: #ffedd5; }

/* ── Tableaux ───────────────────────────────────────────── */
.tables-row {
  display              : grid;
  grid-template-columns: 1fr 1fr;
  gap                  : 20px;
}
.table-card {
  background   : #fff;
  border       : 1px solid #e2e8f0;
  border-radius: 12px;
  overflow     : hidden;
}
.table-card-header {
  display        : flex;
  align-items    : center;
  justify-content: space-between;
  padding        : 16px 20px;
  border-bottom  : 1px solid #f1f5f9;
}
.table-card-header h2 {
  font-size  : .9rem;
  font-weight: 600;
  color      : #1e293b;
  margin     : 0;
}
.see-all {
  font-size      : .78rem;
  color          : #1a5c38;
  text-decoration: none;
  font-weight    : 500;
}
.see-all:hover { text-decoration: underline; }
table { width: 100%; border-collapse: collapse; }
thead th {
  padding    : 10px 16px;
  text-align : left;
  font-size  : .72rem;
  font-weight: 600;
  color      : #94a3b8;
  text-transform: uppercase;
  letter-spacing: .05em;
  background : #f8fafc;
}
tbody tr { border-top: 1px solid #f1f5f9; transition: background .1s; }
tbody tr:hover { background: #f8fafc; }
tbody td {
  padding  : 11px 16px;
  font-size: .825rem;
  color    : #334155;
}
.td-name   { font-weight: 500; color: #1e293b; }
.td-date   { color: #94a3b8; font-size: .78rem; }
.td-amount { font-weight: 600; color: #166534; }
.badge-mono {
  font-family  : monospace;
  font-size    : .75rem;
  background   : #f1f5f9;
  padding      : 2px 7px;
  border-radius: 4px;
  color        : #475569;
}
.badge-type {
  font-size    : .72rem;
  background   : #eff6ff;
  color        : #1d4ed8;
  padding      : 2px 8px;
  border-radius: 20px;
  font-weight  : 500;
}

/* ── Responsive ─────────────────────────────────────────── */
@media (max-width: 900px) {
  .tables-row { grid-template-columns: 1fr; }
}
@media (max-width: 600px) {
  .stats-grid { grid-template-columns: 1fr 1fr; }
}
</style>