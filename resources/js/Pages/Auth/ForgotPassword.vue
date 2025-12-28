<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import { Head, useForm } from '@inertiajs/vue3';

defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <GuestLayout>
        <Head title="Recuperar Contraseña" />

        <div class="mb-10 text-center">
            <h2 class="text-3xl font-black text-slate-800">¿Olvidaste tu clave?</h2>
            <p class="text-slate-400 font-medium mt-3 text-base leading-relaxed px-6">
                No hay problema. Dinos tu email y te enviaremos un link para crear una nueva.
            </p>
        </div>

        <div v-if="status" class="mb-8 text-sm font-bold text-emerald-600 bg-emerald-50 p-5 rounded-2xl border border-emerald-100 text-center">
            {{ status }}
        </div>

        <form @submit.prevent="submit" class="space-y-8">
            <div>
                <label class="text-xs font-black text-slate-400 uppercase tracking-widest ml-1 mb-3 block">Email</label>
                <div class="relative group">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-5 text-slate-400 group-focus-within:text-indigo-500 transition-colors">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path></svg>
                    </span>
                    <input
                        id="email"
                        type="email"
                        class="block w-full pl-14 pr-6 py-5 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:border-indigo-500 focus:ring-0 text-slate-700 font-bold transition-all placeholder:text-slate-300"
                        v-model="form.email"
                        required
                        autofocus
                        autocomplete="username"
                        placeholder="tu@email.com"
                    />
                </div>
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div>
                <button
                    class="w-full py-5 bg-indigo-600 text-white rounded-2xl font-black text-xl shadow-xl shadow-indigo-100 hover:bg-indigo-700 active:scale-95 transition-all disabled:opacity-50"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Enviar link de recuperación
                </button>
            </div>
        </form>
    </GuestLayout>
</template>
