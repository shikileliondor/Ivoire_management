<script setup>
import { computed, ref, watch } from 'vue'
import { Link, router, usePage } from '@inertiajs/vue3'
import { useToast } from 'vue-toastification'

const props = defineProps({
    pageTitle: {
        type: String,
        default: '',
    },
})

const page = usePage()
const toast = useToast()
const mobileSidebarOpen = ref(false)

const app = computed(() => page.props.app ?? page.props.school ?? {})
const user = computed(() => page.props.auth?.user ?? {})
const authRoles = computed(() => page.props.auth?.roles ?? user.value.roles ?? [])
const canMap = computed(() => page.props.can ?? {})

const permissionSet = computed(() => {
    const permissions = user.value.permissions ?? []
    return new Set(Array.isArray(permissions) ? permissions : [])
})

const unreadNotifications = computed(() => {
    return (
        user.value.unread_notifications_count
        ?? page.props.notifications?.unread_count
        ?? page.props.unreadNotificationsCount
        ?? 0
    )
})

const currentPageTitle = computed(() => {
    if (props.pageTitle) return props.pageTitle
    return page.props.title ?? 'Tableau de bord'
})

const initials = computed(() => {
    const name = user.value.name ?? ''
    return name
        .split(' ')
        .filter(Boolean)
        .slice(0, 2)
        .map((part) => part[0])
        .join('')
        .toUpperCase()
})

const navItems = [
    { key: 'dashboard', label: 'Tableau de bord', route: 'dashboard', permission: null },
    { key: 'students', label: 'Élèves', route: 'students.index', permission: 'students.view' },
    { key: 'classrooms', label: 'Classes', route: 'classrooms.index', permission: 'classrooms.view' },
    { key: 'staff', label: 'Personnel', route: 'staff.index', permission: 'staff.view' },
    { key: 'timetable', label: 'Emploi du temps', route: 'timetable.index', permission: 'timetable.view' },
    { key: 'grades', label: 'Notes', route: 'grades.index', permission: 'grades.view' },
    { key: 'payments', label: 'Paiements', route: 'payments.index', permission: 'payments.view' },
    { key: 'absences', label: 'Absences', route: 'absences.index', permission: 'absences.view' },
    { key: 'documents', label: 'Documents', route: 'documents.index', permission: 'documents.view' },
]

const settingsItem = { key: 'settings', label: 'Paramètres', route: 'settings.school.edit', permission: 'settings.manage' }

function hasPermission(permission) {
    if (!permission) return true
    return permissionSet.value.has(permission) || Boolean(canMap.value[permission])
}

const visibleNavItems = computed(() => navItems.filter((item) => hasPermission(item.permission)))
const showSettings = computed(() => hasPermission(settingsItem.permission))

function isActive(routeName) {
    return route().current(routeName)
}

function logout() {
    router.post(route('logout'))
}

watch(
    () => page.props.flash,
    (flash) => {
        if (!flash) return

        if (flash.success) toast.success(flash.success)
        if (flash.error) toast.error(flash.error)
        if (flash.warning) toast.warning(flash.warning)
    },
    { deep: true, immediate: true },
)
</script>

<template>
    <div class="h-screen overflow-hidden bg-slate-100">
        <div
            v-if="mobileSidebarOpen"
            class="fixed inset-0 z-30 bg-black/50 lg:hidden"
            @click="mobileSidebarOpen = false"
        />

        <aside
            class="fixed inset-y-0 left-0 z-40 flex w-[260px] flex-col bg-[#1a5c38] text-white transition-transform duration-200"
            :class="mobileSidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
        >
            <div class="border-b border-white/20 px-5 py-4">
                <div class="flex items-center gap-3">
                    <div class="flex h-11 w-11 items-center justify-center overflow-hidden rounded-lg bg-white/20">
                        <img
                            v-if="app.logo"
                            :src="app.logo"
                            :alt="app.name || 'Logo école'"
                            class="h-full w-full object-cover"
                        >
                        <svg
                            v-else
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24"
                            fill="currentColor"
                            class="h-6 w-6"
                        >
                            <path d="M11.7 2.805a.75.75 0 01.6 0A60.65 60.65 0 0122.83 8.72a.75.75 0 01-.231 1.337 49.949 49.949 0 00-9.902 3.912l-.003.002-.34.18a.75.75 0 01-.707 0A50.009 50.009 0 007.5 12.174v-.224c0-.131.067-.248.172-.311a54.614 54.614 0 014.653-2.52.75.75 0 00-.65-1.352 56.129 56.129 0 00-4.78 2.589 1.858 1.858 0 00-.859 1.228 49.803 49.803 0 00-4.634-1.527.75.75 0 01-.231-1.337A60.653 60.653 0 0111.7 2.805z" />
                            <path d="M13.06 15.473a48.45 48.45 0 017.666-3.282c.134 1.414.22 2.843.255 4.285a.75.75 0 01-.46.71 47.878 47.878 0 00-8.105 4.342.75.75 0 01-.832 0 47.877 47.877 0 00-8.104-4.342.75.75 0 01-.461-.71c.035-1.442.121-2.87.255-4.286A48.4 48.4 0 016 13.18v1.27a1.5 1.5 0 00-.14 2.508c-.09.38-.222.753-.397 1.11.452.213.901.434 1.346.661a6.729 6.729 0 00.551-1.608 1.5 1.5 0 00.14-2.67v-.645a48.549 48.549 0 013.44 1.668 2.25 2.25 0 002.12 0z" />
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <p class="truncate text-sm font-semibold">{{ app.name || 'EduGest CI' }}</p>
                        <p class="text-xs text-white/80">{{ app.slogan || 'Gestion scolaire' }}</p>
                    </div>
                </div>
            </div>

            <nav class="flex-1 overflow-y-auto px-2 py-4">
                <Link
                    v-for="item in visibleNavItems"
                    :key="item.key"
                    :href="route(item.route)"
                    class="mb-1 flex items-center gap-3 rounded-r-md border-l-[3px] border-l-transparent px-3 py-2.5 text-sm text-white/95 transition hover:bg-white/10"
                    :class="isActive(item.route) ? 'border-l-white bg-white/15 font-bold text-white' : ''"
                    @click="mobileSidebarOpen = false"
                >
                    <svg v-if="item.key === 'dashboard'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-5 w-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zm0 9.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zm9.75-9.75a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zm0 9.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25" />
                    </svg>
                    <svg v-else-if="item.key === 'students'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0" /></svg>
                    <svg v-else-if="item.key === 'classrooms'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0A50.57 50.57 0 0012 13.489a50.702 50.702 0 017.74-3.342m-12.99 4.57a.75.75 0 100-1.5.75.75 0 000 1.5z" /></svg>
                    <svg v-else-if="item.key === 'staff'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031a11.944 11.944 0 01-5.999 2.25 11.944 11.944 0 01-6-2.25m12 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m8.058-8.772a3 3 0 11-6 0 3 3 0 016 0" /></svg>
                    <svg v-else-if="item.key === 'timetable'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75M7.5 12h9" /></svg>
                    <svg v-else-if="item.key === 'grades'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zM18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" /></svg>
                    <svg v-else-if="item.key === 'payments'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M3.75 5.25h16.5A1.5 1.5 0 0121.75 6.75v10.5a1.5 1.5 0 01-1.5 1.5H3.75a1.5 1.5 0 01-1.5-1.5V6.75a1.5 1.5 0 011.5-1.5zM12 15a2.25 2.25 0 100-4.5 2.25 2.25 0 000 4.5z" /></svg>
                    <svg v-else-if="item.key === 'absences'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0 3h.008v.008H12v-.008zM2.697 16.126c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.95 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126z" /></svg>
                    <svg v-else xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625A1.125 1.125 0 004.5 3.375v17.25c0 .621.504 1.125 1.125 1.125h12.75A1.125 1.125 0 0019.5 20.625V11.25a9 9 0 00-9-9z" /></svg>
                    <span>{{ item.label }}</span>
                </Link>

                <div v-if="showSettings" class="mt-4 border-t border-white/20 pt-4">
                    <Link
                        :href="route(settingsItem.route)"
                        class="flex items-center gap-3 rounded-r-md border-l-[3px] border-l-transparent px-3 py-2.5 text-sm text-white/95 transition hover:bg-white/10"
                        :class="isActive(settingsItem.route) ? 'border-l-white bg-white/15 font-bold text-white' : ''"
                        @click="mobileSidebarOpen = false"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-5 w-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span>{{ settingsItem.label }}</span>
                    </Link>
                </div>
            </nav>

            <div class="border-t border-white/20 p-4">
                <div class="mb-3 flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-green-300 text-sm font-bold text-[#1a5c38]">
                        {{ initials || 'U' }}
                    </div>
                    <div class="min-w-0">
                        <p class="truncate text-sm font-semibold">{{ user.name }}</p>
                        <p class="truncate text-xs text-white/80">{{ authRoles.join(', ') }}</p>
                    </div>
                </div>

                <button
                    type="button"
                    class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-white/10 px-3 py-2 text-sm font-medium text-white transition hover:bg-white/20"
                    @click="logout"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                    </svg>
                    <span>Déconnexion</span>
                </button>
            </div>
        </aside>

        <div class="flex h-full flex-col lg:ml-[260px]">
            <header class="flex h-16 items-center justify-between border-b border-slate-200 bg-white px-4 shadow-sm md:px-6">
                <div class="flex items-center gap-3">
                    <button
                        type="button"
                        class="inline-flex h-10 w-10 items-center justify-center rounded-lg border border-slate-200 text-slate-600 lg:hidden"
                        @click="mobileSidebarOpen = true"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-5 w-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>
                    <h1 class="text-lg font-semibold text-slate-800">{{ currentPageTitle }}</h1>
                </div>

                <div class="flex items-center gap-4">
                    <button type="button" class="relative rounded-lg p-2 text-slate-600 hover:bg-slate-100">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-5 w-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.083 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                        </svg>
                        <span v-if="unreadNotifications > 0" class="absolute right-1 top-1 inline-flex h-2.5 w-2.5 rounded-full bg-red-500" />
                    </button>

                    <div class="hidden text-right md:block">
                        <p class="text-sm font-medium text-slate-800">{{ user.name }}</p>
                    </div>

                    <button
                        type="button"
                        class="inline-flex items-center gap-2 rounded-lg border border-slate-200 px-3 py-2 text-sm text-slate-700 hover:bg-slate-50"
                        @click="logout"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                        </svg>
                        <span class="hidden sm:inline">Déconnexion</span>
                    </button>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-6">
                <slot />
            </main>
        </div>
    </div>
</template>
