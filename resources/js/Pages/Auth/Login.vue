<script setup>
import { useForm } from '@inertiajs/vue3'

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
    <main class="flex min-h-screen items-center justify-center bg-[#e5e7eb] p-6">
        <section class="w-full max-w-[460px] rounded-3xl bg-[#f3f4f6] px-10 py-14 shadow-sm">
            <h1 class="text-5xl font-bold leading-none text-black">Connexion</h1>
            <p class="mt-4 text-[34px] leading-none text-black">Votre établissement</p>

            <form class="mt-12" @submit.prevent="submit">
                <div>
                    <label class="block text-[36px] font-semibold leading-none text-black">Adresse email</label>
                    <input
                        v-model="form.email"
                        type="email"
                        autocomplete="email"
                        class="mt-5 block w-full border-0 bg-transparent p-0 text-[34px] leading-none text-black placeholder:text-black/50 focus:ring-0"
                    />
                    <span v-if="form.errors.email" class="mt-2 block text-xl text-red-600">{{ form.errors.email }}</span>
                </div>

                <div class="mt-10">
                    <label class="block text-[36px] font-semibold leading-none text-black">Mot de passe</label>
                    <input
                        v-model="form.password"
                        type="password"
                        autocomplete="current-password"
                        class="mt-5 block w-full border-0 bg-transparent p-0 text-[34px] leading-none text-black placeholder:text-black/50 focus:ring-0"
                    />
                    <span v-if="form.errors.password" class="mt-2 block text-xl text-red-600">{{ form.errors.password }}</span>
                </div>

                <label class="mt-10 inline-flex items-center gap-4 text-[34px] leading-none text-black">
                    <input v-model="form.remember" type="checkbox" class="h-7 w-7 rounded border border-black/40" />
                    Se souvenir de moi
                </label>

                <button
                    type="submit"
                    :disabled="form.processing"
                    class="mt-12 flex h-20 w-full items-center justify-center bg-[#1c6b3f] text-[40px] font-semibold leading-none text-white disabled:opacity-70"
                >
                    <span v-if="form.processing">Connexion...</span>
                    <span v-else>Se connecter</span>
                </button>
            </form>

            <p class="mt-12 text-center text-[30px] leading-none text-black">EduGest CI © 2026</p>
        </section>
    </main>
</template>
