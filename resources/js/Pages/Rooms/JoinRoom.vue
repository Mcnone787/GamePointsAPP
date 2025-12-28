<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';

const form = useForm({
    code: '',
});

const submit = () => {
    form.post(route('rooms.join.post'));
};
</script>

<template>
    <Head title="Unirse a Sala" />

    <AuthenticatedLayout>
        <div class="max-w-md mx-auto mt-10 px-4">
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="bg-emerald-600 p-6 text-center">
                    <h2 class="text-2xl font-bold text-white">Unirse a una Sala</h2>
                    <p class="text-emerald-100 text-sm mt-1">Introduce el código que te han compartido</p>
                </div>
                
                <div class="p-8">
                    <form @submit.prevent="submit" class="space-y-6">
                        <div>
                            <label for="code" class="block text-sm font-medium text-gray-700 mb-2">Código de la Sala</label>
                            <input
                                id="code"
                                type="text"
                                class="w-full px-4 py-3 rounded-xl border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 shadow-sm text-gray-900 placeholder-gray-400 text-center font-mono text-xl tracking-widest uppercase transition-colors"
                                v-model="form.code"
                                placeholder="ABCDE"
                                required
                                autofocus
                                autocomplete="off"
                            />
                            <p v-if="form.errors.code" class="mt-2 text-sm text-red-600 font-medium">{{ form.errors.code }}</p>
                        </div>

                        <button
                            type="submit"
                            class="w-full flex justify-center py-4 px-4 border border-transparent rounded-xl shadow-sm text-lg font-bold text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all active:scale-95"
                            :class="{ 'opacity-75 cursor-not-allowed': form.processing }"
                            :disabled="form.processing"
                        >
                            <span v-if="form.processing">Entrando...</span>
                            <span v-else>Entrar a la Sala</span>
                        </button>
                    </form>

                    <div class="mt-6 text-center">
                        <Link :href="route('dashboard')" class="text-sm font-medium text-gray-500 hover:text-gray-900 transition-colors">
                            Cancelar y volver
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
