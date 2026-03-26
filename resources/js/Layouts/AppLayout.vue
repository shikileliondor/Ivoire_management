

<script setup>
import { ref, computed, watch, onMounted } from 'vue'
import { Link, usePage, router } from '@inertiajs/vue3'
import { useToast } from 'vue-toastification'

const toast  = useToast()
const page   = usePage()
const open   = ref(false) // mobile sidebar

// ── Flash messages Inertia ──────────────────────────────────────────────────
watch(
  () => page.props.flash,
  (flash) => {
    if (!flash) return
    if (flash.success) toast.success(flash.success)
    if (flash.error)   toast.error(flash.error)
    if (flash.warning) toast.warning(flash.warning)
    if (flash.info)    toast.info(flash.info)
  },
  { immediate: true, deep: true }
)

// ── Données partagées (HandleInertiaRequests::share) ───────────────────────
const school = computed(() => page.props.school ?? {})
const auth   = computed(() => page.props.auth  ?? {})
const can    = computed(() => page.props.can   ?? {})

// ── Déconnexion ────────────────────────────────────────────────────────────
function logout() {
  router.post(route('logout'))
}

// ── Navigation (filtrée par permission) ────────────────────────────────────
const allNavItems = [
  {
    key: 'dashboard',
    label: 'Tableau de bord',
    route: 'dashboard',
    permission: null, // toujours visible
    icon: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round"
        d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/>
    </svg>`,
  },
  {
    key: 'students',
    label: 'Élèves',
    route: 'students.index',
    permission: 'students.view',
    icon: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round"
        d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
    </svg>`,
  },
  {
    key: 'classrooms',
    label: 'Classes',
    route: 'classrooms.index',
    permission: 'classrooms.view',
    icon: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round"
        d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5"/>
    </svg>`,
  },
  {
    key: 'staff',
    label: 'Personnel',
    route: 'staff.index',
    permission: 'staff.view',
    icon: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round"
        d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/>
    </svg>`,
  },
  {
    key: 'timetable',
    label: 'Emploi du temps',
    route: 'timetable.index',
    permission: 'timetable.view',
    icon: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round"
        d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 9v7.5m-9-6h.008v.008H12v-.008zM12 15h.008v.008H12V15zm0 2.25h.008v.008H12v-.008zM9.75 15h.008v.008H9.75V15zm0 2.25h.008v.008H9.75v-.008zM7.5 15h.008v.008H7.5V15zm0 2.25h.008v.008H7.5v-.008zm6.75-4.5h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V15zm0 2.25h.008v.008h-.008v-.008zm2.25-4.5h.008v.008H16.5v-.008zm0 2.25h.008v.008H16.5V15z"/>
    </svg>`,
  },
  {
    key: 'grades',
    label: 'Notes',
    route: 'grades.index',
    permission: 'grades.view',
    icon: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round"
        d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/>
    </svg>`,
  },
  {
    key: 'absences',
    label: 'Absences',
    route: 'absences.index',
    permission: 'absences.view',
    icon: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round"
        d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
    </svg>`,
  },
  {
    key: 'payments',
    label: 'Paiements',
    route: 'payments.index',
    permission: 'payments.view',
    icon: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round"
        d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z"/>
    </svg>`,
  },
  {
    key: 'documents',
    label: 'Documents',
    route: 'documents.index',
    permission: 'documents.view',
    icon: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round"
        d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
    </svg>`,
  },
  {
    key: 'settings',
    label: 'Paramètres',
    route: 'settings.school.edit',
    permission: 'settings.manage',
    icon: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round"
        d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z"/>
      <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
    </svg>`,
  },
]

// Filtrer selon les permissions
const navItems = computed(() =>
  allNavItems.filter(item =>
    item.permission === null || can.value[item.permission]
  )
)

// Rôle lisible
const roleLabel = computed(() => {
  const roles = auth.value?.roles ?? []
  if (roles.includes('Directeur'))  return 'Directeur'
  if (roles.includes('Secrétaire')) return 'Secrétaire'
  if (roles.includes('Enseignant')) return 'Enseignant'
  return ''
})

// Initiales de l'utilisateur
const initials = computed(() => {
  const name = auth.value?.name ?? ''
  return name.split(' ').map(n => n[0]).join('').slice(0, 2).toUpperCase()
})
</script>

<template>
  <div class="edugest-shell">

    <!-- ░░ OVERLAY MOBILE ░░ -->
    <div
      v-if="open"
      class="sidebar-overlay"
      @click="open = false"
    />

    <!-- ═══════════════════════════════════════════════════════
         SIDEBAR
    ═══════════════════════════════════════════════════════ -->
    <aside :class="['sidebar', { 'sidebar--open': open }]">

      <!-- Logo + nom école -->
      <div class="sidebar-brand">
        <div class="brand-logo">
          <img
            v-if="school.logo"
            :src="school.logo"
            :alt="school.name"
            class="brand-logo-img"
          />
          <span v-else class="brand-logo-fallback">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
              <path d="M11.7 2.805a.75.75 0 01.6 0A60.65 60.65 0 0122.83 8.72a.75.75 0 01-.231 1.337 49.949 49.949 0 00-9.902 3.912l-.003.002-.34.18a.75.75 0 01-.707 0A50.009 50.009 0 007.5 12.174v-.224c0-.131.067-.248.172-.311a54.614 54.614 0 014.653-2.52.75.75 0 00-.65-1.352 56.129 56.129 0 00-4.78 2.589 1.858 1.858 0 00-.859 1.228 49.803 49.803 0 00-4.634-1.527.75.75 0 01-.231-1.337A60.653 60.653 0 0111.7 2.805z"/>
              <path d="M13.06 15.473a48.45 48.45 0 017.666-3.282c.134 1.414.22 2.843.255 4.285a.75.75 0 01-.46.71 47.878 47.878 0 00-8.105 4.342.75.75 0 01-.832 0 47.877 47.877 0 00-8.104-4.342.75.75 0 01-.461-.71c.035-1.442.121-2.87.255-4.286A48.4 48.4 0 016 13.18v1.27a1.5 1.5 0 00-.14 2.508c-.09.38-.222.753-.397 1.11.452.213.901.434 1.346.661a6.729 6.729 0 00.551-1.608 1.5 1.5 0 00.14-2.67v-.645a48.549 48.549 0 013.44 1.668 2.25 2.25 0 002.12 0z"/>
            </svg>
          </span>
        </div>
        <div class="brand-info">
          <span class="brand-name">{{ school.name || 'EduGest CI' }}</span>
          <span class="brand-sub">Gestion scolaire</span>
        </div>
      </div>

      <!-- Navigation principale -->
      <nav class="sidebar-nav">
        <ul>
          <li v-for="item in navItems" :key="item.key">
            <Link
              :href="route(item.route)"
              :class="['nav-link', { 'nav-link--active': route().current(item.route) }]"
              @click="open = false"
            >
              <span class="nav-icon" v-html="item.icon" />
              <span class="nav-label">{{ item.label }}</span>
              <span v-if="route().current(item.route)" class="nav-active-dot" />
            </Link>
          </li>
        </ul>
      </nav>

      <!-- Déconnexion (bas de sidebar) -->
      <div class="sidebar-footer">
        <div class="user-info">
          <div class="user-avatar">{{ initials }}</div>
          <div class="user-meta">
            <span class="user-name">{{ auth.name }}</span>
            <span class="user-role">{{ roleLabel }}</span>
          </div>
        </div>
        <button class="logout-btn" @click="logout" title="Se déconnecter">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75"/>
          </svg>
        </button>
      </div>
    </aside>

    <!-- ═══════════════════════════════════════════════════════
         ZONE PRINCIPALE (header + contenu)
    ═══════════════════════════════════════════════════════ -->
    <div class="main-wrapper">

      <!-- HEADER -->
      <header class="app-header">
        <!-- Burger (mobile) -->
        <button class="burger" @click="open = !open" aria-label="Menu">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
          </svg>
        </button>

        <!-- Nom de l'école (centre ou gauche) -->
        <span class="header-school">{{ school.name || 'EduGest CI' }}</span>

        <!-- Spacer -->
        <div style="flex:1" />

        <!-- Utilisateur connecté -->
        <div class="header-user">
          <div class="user-avatar user-avatar--sm">{{ initials }}</div>
          <div class="header-user-meta">
            <span class="user-name">{{ auth.name }}</span>
            <span class="user-role">{{ roleLabel }}</span>
          </div>
          <button class="logout-btn logout-btn--header" @click="logout" title="Se déconnecter">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75"/>
            </svg>
          </button>
        </div>
      </header>

      <!-- CONTENU (slot) -->
      <main class="app-content">
        <slot />
      </main>
    </div>
  </div>
</template>

<style scoped>
/* ── Variables ────────────────────────────────────────────── */
:root {
  --sidebar-w      : 260px;
  --header-h       : 64px;
  --sidebar-bg     : #1a5c38;
  --sidebar-hover  : rgba(255,255,255,.08);
  --sidebar-active : rgba(255,255,255,.15);
  --accent         : #4ade80;       /* vert clair pour l'actif */
  --text-on-dark   : rgba(255,255,255,.9);
  --text-muted     : rgba(255,255,255,.55);
  --content-bg     : #f8fafc;
  --header-bg      : #ffffff;
  --border         : #e2e8f0;
  --shadow-sm      : 0 1px 3px rgba(0,0,0,.08);
  --shadow-md      : 0 4px 12px rgba(0,0,0,.12);
  --radius         : 8px;
  --transition     : .18s ease;
}

/* ── Shell global ─────────────────────────────────────────── */
.edugest-shell {
  display       : flex;
  height        : 100vh;
  overflow      : hidden;
  background    : var(--content-bg);
  font-family   : 'Inter', 'Segoe UI', system-ui, sans-serif;
}

/* ── Overlay mobile ───────────────────────────────────────── */
.sidebar-overlay {
  display    : none;
  position   : fixed;
  inset      : 0;
  background : rgba(0,0,0,.45);
  z-index    : 40;
}

/* ── Sidebar ──────────────────────────────────────────────── */
.sidebar {
  width           : var(--sidebar-w);
  min-height      : 100vh;
  background      : var(--sidebar-bg);
  display         : flex;
  flex-direction  : column;
  flex-shrink     : 0;
  position        : relative;
  z-index         : 50;
  box-shadow      : var(--shadow-md);
  transition      : transform var(--transition);
}

/* Brand */
.sidebar-brand {
  display     : flex;
  align-items : center;
  gap         : 12px;
  padding     : 20px 18px 16px;
  border-bottom: 1px solid rgba(255,255,255,.1);
}
.brand-logo {
  width      : 44px;
  height     : 44px;
  flex-shrink: 0;
}
.brand-logo-img {
  width        : 44px;
  height       : 44px;
  object-fit   : contain;
  border-radius: 8px;
  background   : rgba(255,255,255,.15);
  padding      : 4px;
}
.brand-logo-fallback {
  width           : 44px;
  height          : 44px;
  background      : rgba(255,255,255,.15);
  border-radius   : 10px;
  display         : flex;
  align-items     : center;
  justify-content : center;
  color           : #fff;
}
.brand-logo-fallback svg { width: 26px; height: 26px; }
.brand-info { display: flex; flex-direction: column; overflow: hidden; }
.brand-name {
  font-size   : .875rem;
  font-weight : 700;
  color       : #fff;
  white-space : nowrap;
  overflow    : hidden;
  text-overflow: ellipsis;
}
.brand-sub {
  font-size : .7rem;
  color     : var(--text-muted);
  margin-top: 1px;
}

/* Nav */
.sidebar-nav { flex: 1; overflow-y: auto; padding: 10px 0; }
.sidebar-nav::-webkit-scrollbar { width: 4px; }
.sidebar-nav::-webkit-scrollbar-thumb { background: rgba(255,255,255,.15); border-radius: 4px; }
.sidebar-nav ul { list-style: none; margin: 0; padding: 0 8px; }

.nav-link {
  display        : flex;
  align-items    : center;
  gap            : 10px;
  padding        : 9px 12px;
  border-radius  : var(--radius);
  color          : var(--text-on-dark);
  text-decoration: none;
  font-size      : .875rem;
  font-weight    : 400;
  transition     : background var(--transition), color var(--transition);
  position       : relative;
  margin-bottom  : 2px;
}
.nav-link:hover { background: var(--sidebar-hover); }
.nav-link--active {
  background  : var(--sidebar-active);
  font-weight : 600;
  color       : #fff;
}
.nav-icon { width: 20px; height: 20px; flex-shrink: 0; }
.nav-icon :deep(svg) { width: 20px; height: 20px; }
.nav-label { flex: 1; }
.nav-active-dot {
  width        : 6px;
  height       : 6px;
  border-radius: 50%;
  background   : var(--accent);
  flex-shrink  : 0;
}

/* Footer sidebar */
.sidebar-footer {
  display       : flex;
  align-items   : center;
  gap           : 8px;
  padding       : 14px 14px;
  border-top    : 1px solid rgba(255,255,255,.1);
}
.user-info {
  display     : flex;
  align-items : center;
  gap         : 10px;
  flex        : 1;
  overflow    : hidden;
}
.user-avatar {
  width           : 36px;
  height          : 36px;
  border-radius   : 50%;
  background      : rgba(255,255,255,.2);
  color           : #fff;
  display         : flex;
  align-items     : center;
  justify-content : center;
  font-size       : .75rem;
  font-weight     : 700;
  flex-shrink     : 0;
}
.user-avatar--sm {
  width     : 32px;
  height    : 32px;
  font-size : .7rem;
  background: #1a5c38;
  color     : #fff;
}
.user-meta { display: flex; flex-direction: column; overflow: hidden; }
.user-name {
  font-size    : .8rem;
  font-weight  : 600;
  color        : var(--text-on-dark);
  white-space  : nowrap;
  overflow     : hidden;
  text-overflow: ellipsis;
}
.user-role {
  font-size: .68rem;
  color    : var(--text-muted);
}
.logout-btn {
  width           : 32px;
  height          : 32px;
  display         : flex;
  align-items     : center;
  justify-content : center;
  border          : none;
  background      : rgba(255,255,255,.1);
  border-radius   : var(--radius);
  color           : var(--text-on-dark);
  cursor          : pointer;
  flex-shrink     : 0;
  transition      : background var(--transition);
}
.logout-btn:hover { background: rgba(239,68,68,.35); color: #fca5a5; }
.logout-btn svg { width: 16px; height: 16px; }

/* ── Wrapper principal ────────────────────────────────────── */
.main-wrapper {
  flex           : 1;
  display        : flex;
  flex-direction : column;
  overflow       : hidden;
}

/* ── Header ───────────────────────────────────────────────── */
.app-header {
  height          : var(--header-h);
  background      : var(--header-bg);
  border-bottom   : 1px solid var(--border);
  display         : flex;
  align-items     : center;
  padding         : 0 24px;
  gap             : 16px;
  flex-shrink     : 0;
  box-shadow      : var(--shadow-sm);
}
.burger {
  display    : none;
  width      : 36px;
  height     : 36px;
  border     : none;
  background : transparent;
  cursor     : pointer;
  color      : #64748b;
  align-items: center;
  justify-content: center;
  border-radius: var(--radius);
  flex-shrink: 0;
}
.burger:hover { background: #f1f5f9; }
.burger svg { width: 22px; height: 22px; }
.header-school {
  font-size  : .9rem;
  font-weight: 600;
  color      : #1e293b;
}
.header-user {
  display    : flex;
  align-items: center;
  gap        : 10px;
}
.header-user-meta {
  display       : flex;
  flex-direction: column;
  text-align    : right;
}
.header-user-meta .user-name  { color: #1e293b; }
.header-user-meta .user-role  { color: #64748b; }
.logout-btn--header {
  background: #f1f5f9;
  color     : #64748b;
}
.logout-btn--header:hover { background: rgba(239,68,68,.12); color: #ef4444; }

/* ── Contenu ──────────────────────────────────────────────── */
.app-content {
  flex       : 1;
  overflow-y : auto;
  padding    : 28px 32px;
}

/* ── Responsive mobile ────────────────────────────────────── */
@media (max-width: 768px) {
  .sidebar {
    position  : fixed;
    top       : 0;
    left      : 0;
    transform : translateX(-100%);
  }
  .sidebar--open {
    transform: translateX(0);
  }
  .sidebar-overlay {
    display: block;
  }
  .burger {
    display        : flex;
  }
  .app-content {
    padding: 20px 16px;
  }
  .header-school { display: none; }
  .header-user-meta { display: none; }
}
</style>