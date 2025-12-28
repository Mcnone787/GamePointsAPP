<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Iniciar Sesión" />

        <div class="mb-10 text-center">
            <h2 class="text-3xl font-black text-slate-800 tracking-tight">¡Hola de nuevo!</h2>
            <p class="text-slate-400 font-medium mt-2 text-base">Entra para seguir tus partidas</p>
        </div>

        <div v-if="status" class="mb-8 text-sm font-bold text-emerald-600 bg-emerald-50 p-5 rounded-2xl border border-emerald-100 text-center">
            {{ status }}
        </div>

        <form @submit.prevent="submit" class="space-y-8 max-w-md mx-auto">
            <div>
                <label class="text-xs font-black text-slate-400 uppercase tracking-widest ml-1 mb-3 block">Email o Usuario</label>
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
                <div class="flex items-center justify-between mb-3 px-1">
                    <label class="text-xs font-black text-slate-400 uppercase tracking-widest block">Contraseña</label>
                    <Link
                        v-if="canResetPassword"
                        :href="route('password.request')"
                        class="text-xs font-bold text-indigo-500 hover:text-indigo-700 transition-colors"
                    >
                        ¿Olvidaste tu clave?
                    </Link>
                </div>
                <div class="relative group">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-5 text-slate-400 group-focus-within:text-indigo-500 transition-colors">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 00-2 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </span>
                    <input
                        id="password"
                        type="password"
                        class="block w-full pl-14 pr-6 py-5 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:border-indigo-500 focus:ring-0 text-slate-700 font-bold transition-all placeholder:text-slate-300"
                        v-model="form.password"
                        required
                        autocomplete="current-password"
                        placeholder="••••••••"
                    />
                </div>
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="flex items-center justify-start px-1">
                <label class="flex items-center cursor-pointer group">
                    <Checkbox name="remember" v-model:checked="form.remember" class="h-6 w-6 rounded-lg text-indigo-600 border-slate-200 focus:ring-0 focus:ring-offset-0 transition-all cursor-pointer" />
                    <span class="ms-3 text-sm font-bold text-slate-500 group-hover:text-slate-700 transition-colors">Mantener sesión iniciada</span>
                </label>
            </div>

            <div class="pt-2">
                <button
                    class="w-full py-5 bg-indigo-600 text-white rounded-[2rem] font-black text-xl shadow-xl shadow-indigo-100 hover:bg-indigo-700 active:scale-95 transition-all disabled:opacity-50"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Entrar a mi cuenta
                </button>
            </div>

            <div class="text-center pt-4">
                <p class="text-sm font-bold text-slate-400">
                    ¿Nuevo por aquí?
                    <Link :href="route('register')" class="text-indigo-600 hover:text-indigo-800 transition-colors underline underline-offset-4">
                        Regístrate gratis
                    </Link>
                </p>
            </div>
        </form>
    </GuestLayout>
</template>
