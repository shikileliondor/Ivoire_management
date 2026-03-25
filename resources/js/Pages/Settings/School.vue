<script setup>
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    settings: {
        type: Object,
        required: true,
    },
    showWelcomeMessage: {
        type: Boolean,
        default: false,
    },
});

const form = useForm({
    name: props.settings.name ?? '',
    slogan: props.settings.slogan ?? '',
    address: props.settings.address ?? '',
    phone: props.settings.phone ?? '',
    email: props.settings.email ?? '',
    director_name: props.settings.director_name ?? '',
    logo: null,
});

const submit = () => {
    form.post('/settings/school');
};
</script>

<template>
    <main class="mx-auto max-w-3xl space-y-6 px-4 py-10">
        <h1 class="text-2xl font-semibold">Configuration de l'école</h1>

        <p
            v-if="showWelcomeMessage"
            class="rounded-md border border-blue-200 bg-blue-50 px-4 py-3 text-blue-700"
        >
            Configurez votre école pour commencer
        </p>

        <form class="space-y-4" @submit.prevent="submit">
            <input v-model="form.name" type="text" placeholder="Nom de l'école" class="w-full rounded border px-3 py-2" />
            <input v-model="form.slogan" type="text" placeholder="Slogan" class="w-full rounded border px-3 py-2" />
            <input v-model="form.address" type="text" placeholder="Adresse" class="w-full rounded border px-3 py-2" />
            <input v-model="form.phone" type="text" placeholder="Téléphone" class="w-full rounded border px-3 py-2" />
            <input v-model="form.email" type="email" placeholder="Email" class="w-full rounded border px-3 py-2" />
            <input v-model="form.director_name" type="text" placeholder="Nom du directeur" class="w-full rounded border px-3 py-2" />
            <input type="file" accept="image/*" @input="form.logo = $event.target.files[0]" class="w-full rounded border px-3 py-2" />

            <button type="submit" :disabled="form.processing" class="rounded bg-black px-4 py-2 text-white disabled:opacity-60">
                Enregistrer
            </button>
        </form>
    </main>
</template>
