<script setup>
import { ref } from 'vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="min-h-screen bg-slate-100 relative selection:bg-indigo-500 selection:text-white font-sans">
        
        <!-- Header Flotante -->
        <header class="fixed top-0 left-0 right-0 z-50 px-4 py-4 pointer-events-none">
            <div class="max-w-7xl mx-auto flex justify-between items-center">
                
                <!-- Logo -->
                <Link :href="route('dashboard')" class="pointer-events-auto transform transition hover:scale-105 active:scale-95">
                    <div class="bg-white/90 backdrop-blur-md shadow-sm rounded-full px-4 py-2 flex items-center gap-2 border border-slate-200/50">
                        <span class="text-xl">🎲</span>
                        <span class="font-black text-slate-800 tracking-tight hidden sm:block">GamePoints</span>
                    </div>
                </Link>

                <!-- Avatar User -->
                <div class="pointer-events-auto">
                    <Dropdown align="right" width="48">
                        <template #trigger>
                            <button class="flex items-center group focus:outline-none">
                                <div class="bg-white/90 backdrop-blur-md shadow-sm rounded-full p-1 border border-slate-200/50 flex items-center gap-3 pr-2 sm:pr-4 transition-transform active:scale-95">
                                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-indigo-500 to-violet-600 flex items-center justify-center text-white font-bold text-lg shadow-inner ring-2 ring-white">
                                        {{ $page.props.auth.user.name.charAt(0).toUpperCase() }}
                                    </div>
                                    <span class="font-bold text-slate-700 hidden sm:block">{{ $page.props.auth.user.name }}</span>
                                    <svg class="h-4 w-4 text-slate-400 hidden sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </button>
                        </template>

                        <template #content>
                            <div class="py-1">
                                <div class="px-4 py-2 text-xs text-slate-400 border-b border-slate-100 mb-1">
                                    Cuenta
                                </div>
                                <DropdownLink :href="route('profile.edit')">
                                    <span class="flex items-center gap-2">⚙️ Configuración</span>
                                </DropdownLink>
                                <DropdownLink :href="route('logout')" method="post" as="button" class="text-rose-600 hover:text-rose-700">
                                    <span class="flex items-center gap-2">🚪 Salir</span>
                                </DropdownLink>
                            </div>
                        </template>
                    </Dropdown>
                </div>
            </div>
        </header>

        <!-- Contenido -->
        <main class="max-w-[100rem] mx-auto pt-24 pb-12 px-4 sm:px-6 relative z-0">
            <slot />
        </main>
    </div>
</template>
