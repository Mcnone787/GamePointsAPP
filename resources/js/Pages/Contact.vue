<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';

const form = useForm({
    name: '',
    email: '',
    subject: '',
    message: '',
});

const submit = () => {
    // Aquí iría la lógica para enviar el email o guardar el mensaje
    alert('¡Mensaje enviado! (Simulado)');
    form.reset();
};
</script>

<template>
    <GuestLayout>
        <Head title="Contacto" />

        <div class="mb-8 text-center">
            <h2 class="text-2xl font-black text-slate-800">Contacto</h2>
            <p class="text-slate-400 font-medium mt-1 text-sm">¿Tienes alguna duda o sugerencia?</p>
        </div>

        <form @submit.prevent="submit" class="space-y-4">
            <div>
                <label class="text-xs font-black text-slate-400 uppercase tracking-widest ml-1 mb-2 block">Nombre</label>
                <input
                    v-model="form.name"
                    type="text"
                    class="block w-full px-4 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:border-indigo-500 focus:ring-0 text-slate-700 font-bold transition-all placeholder:text-slate-300"
                    placeholder="Tu nombre"
                    required
                />
            </div>

            <div>
                <label class="text-xs font-black text-slate-400 uppercase tracking-widest ml-1 mb-2 block">Email</label>
                <input
                    v-model="form.email"
                    type="email"
                    class="block w-full px-4 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:border-indigo-500 focus:ring-0 text-slate-700 font-bold transition-all placeholder:text-slate-300"
                    placeholder="tu@email.com"
                    required
                />
            </div>

            <div>
                <label class="text-xs font-black text-slate-400 uppercase tracking-widest ml-1 mb-2 block">Asunto</label>
                <select
                    v-model="form.subject"
                    class="block w-full px-4 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:border-indigo-500 focus:ring-0 text-slate-700 font-bold transition-all appearance-none"
                    required
                >
                    <option value="" disabled selected>Selecciona un motivo</option>
                    <option value="Sugerencia">Sugerencia</option>
                    <option value="Error">Informar de un error</option>
                    <option value="Colaboración">Colaboración</option>
                    <option value="Otro">Otro</option>
                </select>
            </div>

            <div>
                <label class="text-xs font-black text-slate-400 uppercase tracking-widest ml-1 mb-2 block">Mensaje</label>
                <textarea
                    v-model="form.message"
                    rows="4"
                    class="block w-full px-4 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:border-indigo-500 focus:ring-0 text-slate-700 font-bold transition-all placeholder:text-slate-300 resize-none"
                    placeholder="¿En qué podemos ayudarte?"
                    required
                ></textarea>
            </div>

            <div class="pt-4">
                <button
                    type="submit"
                    class="w-full py-4 bg-slate-900 text-white rounded-2xl font-black text-lg shadow-xl shadow-slate-200 hover:bg-slate-800 active:scale-95 transition-all disabled:opacity-50"
                    :disabled="form.processing"
                >
                    Enviar mensaje
                </button>
            </div>
        </form>
    </GuestLayout>
</template>

