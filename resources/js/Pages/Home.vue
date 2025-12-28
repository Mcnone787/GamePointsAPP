<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});
</script>

<template>
    <Head title="GamePoints - Gestiona tus puntos de juego" />

    <div class="min-h-screen bg-slate-50 font-sans selection:bg-indigo-500 selection:text-white">
        
        <!-- Header / Nav -->
        <nav class="fixed top-0 left-0 right-0 z-50 px-6 py-4">
            <div class="max-w-7xl mx-auto flex justify-between items-center bg-white/80 backdrop-blur-md rounded-3xl px-6 py-3 border border-slate-200/50 shadow-sm">
                <Link href="/" class="flex items-center gap-3 group">
                    <span class="text-2xl group-hover:scale-110 transition-transform">🎲</span>
                    <span class="font-black text-xl text-slate-800 tracking-tighter">GamePoints</span>
                </Link>

                <div class="flex items-center gap-6">
                    <Link :href="route('login')" class="hidden sm:block text-sm font-bold text-slate-600 hover:text-indigo-600 transition-colors">Iniciar Sesión</Link>
                    <Link :href="route('register')" class="bg-indigo-600 text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-lg shadow-indigo-100 hover:bg-indigo-700 active:scale-95 transition-all">
                        Empezar gratis
                    </Link>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <header class="pt-32 pb-20 px-6">
            <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="text-center lg:text-left space-y-8">
                    <div class="inline-flex items-center gap-2 bg-indigo-50 text-indigo-600 px-4 py-2 rounded-full text-xs font-black uppercase tracking-widest border border-indigo-100">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-500"></span>
                        </span>
                        Nuevo: Tiempo real activado
                    </div>
                    <h1 class="text-5xl sm:text-7xl font-black text-slate-800 tracking-tighter leading-[0.9]">
                        Tus partidas, <br>
                        <span class="text-indigo-600 italic">bajo control.</span>
                    </h1>
                    <p class="text-xl text-slate-500 font-medium max-w-xl mx-auto lg:mx-0 leading-relaxed">
                        Olvida el papel y lápiz. Gestiona los puntos de tus juegos de mesa en tiempo real con tus amigos desde cualquier dispositivo.
                    </p>
                    <div class="flex flex-col sm:flex-row items-center gap-4 justify-center lg:justify-start">
                        <Link :href="route('register')" class="w-full sm:w-auto px-10 py-5 bg-slate-900 text-white rounded-[2rem] font-black text-lg shadow-2xl shadow-slate-200 hover:bg-slate-800 active:scale-95 transition-all">
                            Crear mi primera sala
                        </Link>
                        <Link :href="route('login')" class="w-full sm:w-auto px-10 py-5 bg-white text-slate-600 border-2 border-slate-100 rounded-[2rem] font-black text-lg hover:bg-slate-50 active:scale-95 transition-all">
                            Entrar a mi cuenta
                        </Link>
                    </div>
                    <div class="flex items-center justify-center lg:justify-start gap-4 text-slate-400 font-bold text-sm">
                        <div class="flex -space-x-2">
                            <div v-for="i in 3" :key="i" class="h-8 w-8 rounded-full border-2 border-white bg-slate-200 flex items-center justify-center text-[10px] text-slate-500 font-black">
                                {{ ['JD', 'AL', 'MS'][i-1] }}
                            </div>
                        </div>
                        +1,000 jugadores ya lo usan
                    </div>
                </div>

                <!-- Visual Mockup (Simple representation) -->
                <div class="relative">
                    <div class="bg-indigo-600 rounded-[3rem] p-4 shadow-3xl transform rotate-2 hover:rotate-0 transition-transform duration-700 aspect-square sm:aspect-video flex items-center justify-center overflow-hidden group">
                        <div class="absolute inset-0 bg-gradient-to-tr from-indigo-900/50 to-transparent"></div>
                        <div class="relative z-10 grid grid-cols-2 gap-4 w-full p-4">
                            <div v-for="i in 4" :key="i" class="bg-white/10 backdrop-blur-md rounded-2xl p-4 border border-white/20">
                                <div class="h-8 w-8 rounded-lg bg-white/20 mb-3"></div>
                                <div class="h-2 w-12 bg-white/20 rounded-full mb-2"></div>
                                <div class="h-6 w-8 bg-white/40 rounded-lg"></div>
                            </div>
                        </div>
                        <!-- Emoji flotante -->
                        <div class="absolute -top-10 -right-10 text-9xl filter drop-shadow-2xl animate-bounce">👑</div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Features Section -->
        <section class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-6">
                <div class="text-center mb-16">
                    <h2 class="text-xs font-black text-indigo-500 uppercase tracking-[0.3em] mb-4 italic">Características</h2>
                    <h3 class="text-4xl font-black text-slate-800 tracking-tight">Diseñado para jugar, no para complicar.</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div v-for="(feat, i) in [
                        {title: 'Tiempo Real', desc: 'Los puntos se actualizan al instante en todos los dispositivos usando tecnología Reverb.', icon: '⚡'},
                        {title: 'Acceso Invitado', desc: 'Tus amigos pueden unirse sin registrarse simplemente usando un código o link.', icon: '👥'},
                        {title: 'Multi-dispositivo', desc: 'Funciona perfecto en móviles, tablets y ordenadores. Ideal para la mesa de juego.', icon: '📱'}
                    ]" :key="i" class="p-8 bg-slate-50 rounded-[2.5rem] border border-slate-100 hover:shadow-xl hover:shadow-slate-100 transition-all group">
                        <div class="text-4xl mb-6 group-hover:scale-110 transition-transform inline-block">{{ feat.icon }}</div>
                        <h4 class="text-xl font-black text-slate-800 mb-3">{{ feat.title }}</h4>
                        <p class="text-slate-500 font-medium leading-relaxed">{{ feat.desc }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-20 px-6">
            <div class="max-w-5xl mx-auto bg-indigo-600 rounded-[3rem] p-12 text-center text-white relative overflow-hidden shadow-2xl shadow-indigo-200">
                <div class="absolute top-0 left-0 w-full h-full bg-[radial-gradient(circle_at_top_right,_var(--tw-gradient-stops))] from-white/20 via-transparent to-transparent"></div>
                
                <h2 class="text-4xl sm:text-5xl font-black mb-6 relative z-10 leading-tight">¿Listo para tu próxima partida?</h2>
                <p class="text-indigo-100 text-lg mb-10 max-w-xl mx-auto font-medium relative z-10 leading-relaxed">
                    Únete a miles de jugadores y deja de preocuparte por quién lleva la cuenta. Es gratis y siempre lo será.
                </p>
                <div class="flex flex-col sm:flex-row items-center gap-4 justify-center relative z-10">
                    <Link :href="route('register')" class="w-full sm:w-auto px-10 py-5 bg-white text-indigo-600 rounded-2xl font-black text-lg hover:bg-indigo-50 active:scale-95 transition-all">
                        Crear cuenta ahora
                    </Link>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="py-12 border-t border-slate-200 px-6">
            <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-8">
                <div class="flex items-center gap-3">
                    <span class="text-2xl">🎲</span>
                    <span class="font-black text-slate-800">GamePoints</span>
                </div>
                
                <div class="flex gap-8 text-sm font-bold text-slate-400">
                    <Link href="#" class="hover:text-indigo-600 transition-colors">Términos</Link>
                    <Link href="#" class="hover:text-indigo-600 transition-colors">Privacidad</Link>
                    <Link :href="route('contact')" class="hover:text-indigo-600 transition-colors">Contacto</Link>
                </div>

                <div class="text-sm font-bold text-slate-400 italic">
                    Hecho con ❤️ para jugadores de mesa.
                </div>
            </div>
        </footer>
    </div>
</template>
