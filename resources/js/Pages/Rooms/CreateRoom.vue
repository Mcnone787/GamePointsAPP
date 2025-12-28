<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';

const form = useForm({
    name: '',
});

const submit = () => {
    form.post(route('rooms.store'));
};
</script>

<template>
    <Head title="Crear Sala" />

    <AuthenticatedLayout>
        <div class="max-w-md mx-auto mt-10 px-4">
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="bg-indigo-600 p-6 text-center">
                    <h2 class="text-2xl font-bold text-white">Nueva Sala</h2>
                    <p class="text-indigo-100 text-sm mt-1">Crea un espacio para jugar con tus amigos</p>
                </div>
                
                <div class="p-8">
                    <form @submit.prevent="submit" class="space-y-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nombre de la Sala</label>
                            <input
                                id="name"
                                type="text"
                                class="w-full px-4 py-3 rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm text-gray-900 placeholder-gray-400 transition-colors"
                                v-model="form.name"
                                placeholder="Ej: Noche de Juegos, Catan, Poker..."
                                autocomplete="off"
                                autofocus
                            />
                            <p v-if="form.errors.name" class="mt-2 text-sm text-red-600 font-medium">{{ form.errors.name }}</p>
                            <p class="mt-2 text-xs text-gray-500">Si lo dejas vacío, se generará un nombre automáticamente.</p>
                        </div>

                        <button
                            type="submit"
                            class="w-full flex justify-center py-4 px-4 border border-transparent rounded-xl shadow-sm text-lg font-bold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all active:scale-95"
                            :class="{ 'opacity-75 cursor-not-allowed': form.processing }"
                            :disabled="form.processing"
                        >
                            <span v-if="form.processing">Creando...</span>
                            <span v-else>Crear Sala</span>
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
