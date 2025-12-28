<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="min-h-screen flex flex-col items-center justify-center bg-slate-50 relative overflow-hidden selection:bg-indigo-500 selection:text-white font-sans">
        
        <!-- Decoración de fondo -->
        <div class="absolute top-0 left-0 w-full h-full pointer-events-none overflow-hidden z-0">
            <div class="absolute -top-[10%] -left-[10%] w-[40%] h-[40%] bg-indigo-100/50 rounded-full blur-3xl opacity-60"></div>
            <div class="absolute -bottom-[10%] -right-[10%] w-[40%] h-[40%] bg-violet-100/50 rounded-full blur-3xl opacity-60"></div>
        </div>

        <div class="z-10 w-full max-w-2xl px-4 py-8">
            <div class="mb-10 text-center">
                <Link href="/" class="inline-flex flex-col items-center gap-4 group">
                    <div class="h-20 w-20 bg-white shadow-xl shadow-indigo-100 rounded-[2rem] flex items-center justify-center text-4xl group-hover:scale-110 transition-transform duration-500 border border-slate-50">
                        🎲
                    </div>
                    <div>
                        <h1 class="text-4xl font-black text-slate-800 tracking-tighter">GamePoints</h1>
                        <p class="text-slate-400 font-bold text-sm uppercase tracking-widest mt-1">Tu tablero digital</p>
                    </div>
                </Link>
            </div>

            <div class="w-full bg-white/90 backdrop-blur-xl px-6 sm:px-12 py-10 sm:py-12 shadow-2xl shadow-slate-200/50 rounded-[3rem] border border-white relative overflow-hidden">
                <!-- Decoración interna sutil -->
                <div class="absolute top-0 right-0 p-10 opacity-[0.03] pointer-events-none">
                    <span class="text-[10rem]">♟️</span>
                </div>
                
                <div class="relative z-10">
                    <slot />
                </div>
            </div>

            <div class="mt-8 text-center">
                <Link href="/" class="text-sm font-bold text-slate-400 hover:text-indigo-600 transition-colors flex items-center justify-center gap-2">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Volver al inicio
                </Link>
            </div>
        </div>
    </div>
</template>
