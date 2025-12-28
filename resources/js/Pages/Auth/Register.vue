<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Crear Cuenta" />

        <div class="mb-10 text-center">
            <h2 class="text-3xl font-black text-slate-800 tracking-tight">Crea tu cuenta</h2>
            <p class="text-slate-400 font-medium mt-2 text-base">Únete a la comunidad de GamePoints</p>
        </div>

        <form @submit.prevent="submit" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nombre de Usuario -->
                <div class="space-y-2">
                    <label class="text-xs font-black text-slate-400 uppercase tracking-widest ml-1 block">Nombre de usuario</label>
                    <div class="relative group">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-5 text-slate-400 group-focus-within:text-indigo-500 transition-colors">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </span>
                        <input
                            id="name"
                            type="text"
                            class="block w-full pl-14 pr-6 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:border-indigo-500 focus:ring-0 text-slate-700 font-bold transition-all placeholder:text-slate-300"
                            v-model="form.name"
                            required
                            autofocus
                            autocomplete="name"
                            placeholder="Ej: JugadorPro"
                        />
                    </div>
                    <InputError class="mt-2" :message="form.errors.name" />
                </div>

                <!-- Email -->
                <div class="space-y-2">
                    <label class="text-xs font-black text-slate-400 uppercase tracking-widest ml-1 block">Email</label>
                    <div class="relative group">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-5 text-slate-400 group-focus-within:text-indigo-500 transition-colors">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path></svg>
                        </span>
                        <input
                            id="email"
                            type="email"
                            class="block w-full pl-14 pr-6 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:border-indigo-500 focus:ring-0 text-slate-700 font-bold transition-all placeholder:text-slate-300"
                            v-model="form.email"
                            required
                            autocomplete="username"
                            placeholder="tu@email.com"
                        />
                    </div>
                    <InputError class="mt-2" :message="form.errors.email" />
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Contraseña -->
                <div class="space-y-2">
                    <label class="text-xs font-black text-slate-400 uppercase tracking-widest ml-1 block">Contraseña</label>
                    <div class="relative group">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-5 text-slate-400 group-focus-within:text-indigo-500 transition-colors">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 00-2 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </span>
                        <input
                            id="password"
                            type="password"
                            class="block w-full pl-14 pr-6 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:border-indigo-500 focus:ring-0 text-slate-700 font-bold transition-all placeholder:text-slate-300"
                            v-model="form.password"
                            required
                            autocomplete="new-password"
                            placeholder="••••••••"
                        />
                    </div>
                    <InputError class="mt-2" :message="form.errors.password" />
                </div>

                <!-- Confirmar Contraseña -->
                <div class="space-y-2">
                    <label class="text-xs font-black text-slate-400 uppercase tracking-widest ml-1 block">Confirmar</label>
                    <div class="relative group">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-5 text-slate-400 group-focus-within:text-indigo-500 transition-colors">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        </span>
                        <input
                            id="password_confirmation"
                            type="password"
                            class="block w-full pl-14 pr-6 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:border-indigo-500 focus:ring-0 text-slate-700 font-bold transition-all placeholder:text-slate-300"
                            v-model="form.password_confirmation"
                            required
                            autocomplete="new-password"
                            placeholder="••••••••"
                        />
                    </div>
                    <InputError class="mt-2" :message="form.errors.password_confirmation" />
                </div>
            </div>

            <div class="pt-6">
                <button
                    class="w-full py-5 bg-indigo-600 text-white rounded-[2rem] font-black text-xl shadow-xl shadow-indigo-100 hover:bg-indigo-700 active:scale-95 transition-all disabled:opacity-50"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Registrarme ahora
                </button>
            </div>

            <div class="text-center pt-2">
                <p class="text-sm font-bold text-slate-400">
                    ¿Ya tienes cuenta?
                    <Link :href="route('login')" class="text-indigo-600 hover:text-indigo-800 transition-colors underline underline-offset-4">
                        Inicia sesión
                    </Link>
                </p>
            </div>
        </form>
    </GuestLayout>
</template>
