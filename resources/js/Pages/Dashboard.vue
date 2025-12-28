<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import Modal from '@/Components/Modal.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

const props = defineProps({
    userRooms: Object, // Objeto paginado de Laravel
    joinedRooms: Array,
});

// Estado de filtros
const filterType = ref('all'); // 'all', 'creator', 'player'
const searchQuery = ref('');

// Combinar, filtrar y ordenar salas
const filteredRooms = computed(() => {
    // Extraer datos de la paginación si existe, o usar array directo
    const userRoomsData = props.userRooms?.data || props.userRooms || [];
    const joinedRoomsData = props.joinedRooms || [];
    
    let rooms = [
        ...userRoomsData.map(room => ({ ...room, role: 'creator' })),
        ...joinedRoomsData.map(room => ({ ...room, role: 'player' }))
    ];

    // Filtrar por Rol
    if (filterType.value !== 'all') {
        rooms = rooms.filter(room => room.role === filterType.value);
    }

    // Filtrar por Búsqueda
    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        rooms = rooms.filter(room => 
            (room.name && room.name.toLowerCase().includes(query)) || 
            room.code.toLowerCase().includes(query)
        );
    }
    
    // Ordenar por fecha (más reciente primero)
    return rooms.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
});

// Utilidades visuales
const getRoomColor = (id) => {
    const colors = [
        'bg-orange-100 text-orange-600', 'bg-amber-100 text-amber-600', 
        'bg-emerald-100 text-emerald-600', 'bg-teal-100 text-teal-600', 
        'bg-cyan-100 text-cyan-600', 'bg-indigo-100 text-indigo-600', 
        'bg-violet-100 text-violet-600', 'bg-fuchsia-100 text-fuchsia-600', 
        'bg-rose-100 text-rose-600'
    ];
    return colors[id % colors.length];
};

const formatDate = (dateString) => {
    if (!dateString) return '';
    const date = new Date(dateString);
    return new Intl.DateTimeFormat('es-ES', { month: 'short', day: 'numeric' }).format(date);
};

// --- Lógica Crear/Unir ---
const creatingRoom = ref(false);
const joiningRoom = ref(false);
const createForm = useForm({ name: '', initial_points: 20 });
const joinForm = useForm({ code: '' });

const createRoom = () => createForm.post(route('rooms.store'), { onSuccess: closeModal, onFinish: () => createForm.reset() });
const joinRoom = () => joinForm.post(route('rooms.join.post'), { onSuccess: closeModal, onFinish: () => joinForm.reset() });

const closeModal = () => {
    creatingRoom.value = false;
    joiningRoom.value = false;
    createForm.reset();
    joinForm.reset();
    createForm.clearErrors();
    joinForm.clearErrors();
};
</script>

<template>
    <Head title="Inicio" />

    <AuthenticatedLayout>
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 items-start">
            
            <!-- COLUMNA IZQUIERDA (Acciones) -->
            <div class="lg:col-span-3 space-y-4 order-2 lg:order-1 sticky top-24">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider ml-1 mb-2 hidden lg:block">Menú</h3>
                
                <button @click="creatingRoom = true" class="w-full bg-white hover:bg-indigo-50 text-slate-700 hover:text-indigo-700 rounded-2xl p-4 shadow-sm border-2 border-slate-100 hover:border-indigo-200 transition-all active:scale-95 group flex items-center gap-3">
                    <div class="bg-indigo-100 text-indigo-600 p-2.5 rounded-xl group-hover:scale-110 transition-transform">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                    </div>
                    <span class="font-bold text-lg">Crear Sala</span>
                </button>

                <button @click="joiningRoom = true" class="w-full bg-white hover:bg-emerald-50 text-slate-700 hover:text-emerald-700 rounded-2xl p-4 shadow-sm border-2 border-slate-100 hover:border-emerald-200 transition-all active:scale-95 group flex items-center gap-3">
                    <div class="bg-emerald-100 text-emerald-600 p-2.5 rounded-xl group-hover:scale-110 transition-transform">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                    </div>
                    <span class="font-bold text-lg">Unirse</span>
                </button>
            </div>

            <!-- COLUMNA CENTRAL (Contenido Principal) -->
            <div class="lg:col-span-6 space-y-6 order-1 lg:order-2">
                
                <!-- Header Bienvenida -->
                <div class="bg-gradient-to-r from-violet-600 to-indigo-600 rounded-3xl p-8 text-white shadow-xl shadow-indigo-200 relative overflow-hidden">
                    <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
                    <div class="relative z-10">
                        <h1 class="text-3xl font-black mb-1 tracking-tight">¡Hola, {{ $page.props.auth.user.name }}! 👋</h1>
                        <p class="text-indigo-100 font-medium text-sm">Gestiona tus puntos y gana partidas.</p>
                    </div>
                </div>

                <!-- Barra de Herramientas (Búsqueda y Filtros) -->
                <div class="bg-white p-2 rounded-2xl shadow-sm border border-slate-100 flex flex-col sm:flex-row gap-2">
                    <div class="relative flex-1">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        <input v-model="searchQuery" type="text" placeholder="Buscar sala..." class="w-full pl-10 pr-4 py-2 bg-slate-50 border-transparent focus:bg-white focus:border-indigo-300 focus:ring-0 rounded-xl text-sm font-medium transition-all placeholder-slate-400">
                    </div>
                    <div class="flex bg-slate-100 p-1 rounded-xl">
                        <button @click="filterType = 'all'" :class="{'bg-white text-slate-800 shadow-sm': filterType === 'all', 'text-slate-500 hover:text-slate-700': filterType !== 'all'}" class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all">Todas</button>
                        <button @click="filterType = 'creator'" :class="{'bg-white text-indigo-600 shadow-sm': filterType === 'creator', 'text-slate-500 hover:text-slate-700': filterType !== 'creator'}" class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all">Creadas</button>
                        <button @click="filterType = 'player'" :class="{'bg-white text-emerald-600 shadow-sm': filterType === 'player', 'text-slate-500 hover:text-slate-700': filterType !== 'player'}" class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all">Unidas</button>
                    </div>
                </div>

                <!-- Listado de Partidas Mejorado -->
                <div class="space-y-3">
                    <div v-if="filteredRooms.length === 0" class="text-center py-12 bg-white rounded-3xl border-2 border-dashed border-slate-200">
                        <div class="text-4xl mb-3 opacity-50">🔍</div>
                        <p class="text-slate-400 font-medium">No se encontraron partidas.</p>
                    </div>

                    <Link v-for="room in filteredRooms" :key="room.id" :href="route('rooms.show', room.code)" 
                        class="block bg-white p-4 rounded-2xl border border-slate-100 hover:border-indigo-300 hover:shadow-md transition-all group relative overflow-hidden">
                        
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <!-- Avatar de Sala -->
                                <div class="h-14 w-14 rounded-2xl flex flex-col items-center justify-center font-black text-xl shadow-sm transition-transform group-hover:scale-105"
                                    :class="getRoomColor(room.id)">
                                    <span>{{ room.name ? room.name.charAt(0).toUpperCase() : '#' }}</span>
                                </div>
                                
                                <div>
                                    <h3 class="font-bold text-lg text-slate-800 leading-tight group-hover:text-indigo-700 transition-colors">{{ room.name || 'Sala sin nombre' }}</h3>
                                    <div class="flex items-center gap-2 mt-1.5">
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-md uppercase tracking-wider bg-slate-100 text-slate-500 flex items-center gap-1">
                                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                            {{ room.code }}
                                        </span>
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-md uppercase tracking-wider flex items-center gap-1"
                                            :class="room.role === 'creator' ? 'bg-indigo-50 text-indigo-600' : 'bg-emerald-50 text-emerald-600'">
                                            {{ room.role === 'creator' ? '👑 Creador' : '👤 Jugador' }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex flex-col items-end gap-2">
                                <span class="text-xs font-medium text-slate-400">{{ formatDate(room.created_at) }}</span>
                                <div class="bg-slate-50 p-2 rounded-full text-slate-300 group-hover:bg-indigo-600 group-hover:text-white transition-all">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
                                </div>
                            </div>
                        </div>
                    </Link>
                </div>

                <!-- Paginación -->
                <div v-if="userRooms?.links && userRooms.links.length > 3" class="mt-6 flex justify-center">
                    <nav class="flex gap-2">
                        <Link v-for="(link, index) in userRooms.links" :key="index" 
                            :href="link.url || '#'" 
                            v-html="link.label"
                            class="px-4 py-2 rounded-xl border border-slate-200 text-sm font-bold transition-all"
                            :class="{
                                'bg-indigo-600 text-white border-indigo-600': link.active,
                                'bg-white text-slate-600 hover:bg-slate-50': !link.active && link.url,
                                'bg-slate-100 text-slate-400 cursor-not-allowed': !link.url
                            }"
                        />
                    </nav>
                </div>
            </div>

            <!-- COLUMNA DERECHA (Perfil) -->
            <div class="lg:col-span-3 space-y-6 order-3 sticky top-24">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider ml-1 mb-2 hidden lg:block">Perfil</h3>
                
                <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 text-center">
                    <div class="h-20 w-20 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-3xl shadow-inner mb-4 ring-4 ring-white mx-auto">
                        {{ $page.props.auth.user.name.charAt(0).toUpperCase() }}
                    </div>
                    <h2 class="text-lg font-black text-slate-800 truncate">{{ $page.props.auth.user.name }}</h2>
                    <p class="text-xs text-slate-500 mb-6 truncate">{{ $page.props.auth.user.email }}</p>
                    
                    <div class="grid grid-cols-2 gap-2 mb-6">
                        <div class="bg-slate-50 p-3 rounded-2xl">
                            <span class="block text-xl font-black text-indigo-600 leading-none">{{ userRooms?.total || (Array.isArray(userRooms) ? userRooms.length : 0) }}</span>
                            <span class="text-[10px] font-bold text-slate-400 uppercase mt-1 block">Creadas</span>
                        </div>
                        <div class="bg-slate-50 p-3 rounded-2xl">
                            <span class="block text-xl font-black text-emerald-600 leading-none">{{ joinedRooms?.length || 0 }}</span>
                            <span class="text-[10px] font-bold text-slate-400 uppercase mt-1 block">Unidas</span>
                        </div>
                    </div>

                    <div class="space-y-2">
                         <Link :href="route('profile.edit')" class="w-full flex items-center justify-center gap-2 py-2.5 px-4 rounded-xl border border-slate-200 text-slate-600 text-sm font-bold hover:bg-slate-50 transition-colors">
                            Configuración
                        </Link>
                        <Link :href="route('logout')" method="post" as="button" class="w-full flex items-center justify-center gap-2 py-2.5 px-4 rounded-xl bg-rose-50 text-rose-600 text-sm font-bold hover:bg-rose-100 transition-colors">
                            Cerrar Sesión
                        </Link>
                    </div>
                </div>
            </div>

        </div>

        <!-- Modales Crear/Unir -->
        <Modal :show="creatingRoom" @close="closeModal">
            <div class="p-6 text-center">
                <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4 text-3xl">🎲</div>
                <h2 class="text-2xl font-black text-slate-800">Nueva Sala</h2>
                <p class="mt-2 text-sm text-slate-500 mb-6">Configura tu partida</p>
                
                <div class="mt-6 space-y-4 text-left">
                    <div>
                        <InputLabel for="name" value="Nombre de la Sala (Opcional)" class="ml-1 mb-1" />
                        <TextInput id="name" v-model="createForm.name" type="text" class="mt-1 block w-full rounded-xl border-slate-300 py-3" placeholder="Ej: Póker Noche" @keyup.enter="createRoom" />
                        <InputError :message="createForm.errors.name" class="mt-2" />
                    </div>
                    
                    <div>
                        <InputLabel for="initial_points" value="Puntos Iniciales (Vida)" class="ml-1 mb-1" />
                        <TextInput id="initial_points" v-model.number="createForm.initial_points" type="number" min="1" max="1000" class="mt-1 block w-full rounded-xl border-slate-300 py-3 text-center text-lg font-bold" @keyup.enter="createRoom" />
                        <p class="mt-1 text-xs text-slate-400">Los jugadores empezarán con esta cantidad de vida</p>
                        <InputError :message="createForm.errors.initial_points" class="mt-2" />
                    </div>
                </div>
                
                <div class="mt-8 flex gap-3">
                    <button @click="closeModal" class="flex-1 py-3 rounded-xl border-2 font-bold text-slate-600 hover:bg-slate-50 transition-colors">Cancelar</button>
                    <button @click="createRoom" class="flex-1 py-3 rounded-xl bg-indigo-600 text-white font-bold hover:bg-indigo-700 shadow-lg shadow-indigo-200 transition-colors" :class="{ 'opacity-75': createForm.processing }" :disabled="createForm.processing">Crear</button>
                </div>
            </div>
        </Modal>

        <Modal :show="joiningRoom" @close="closeModal">
            <div class="p-6 text-center">
                <div class="w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4 text-3xl">👋</div>
                <h2 class="text-2xl font-black text-slate-800">Unirse</h2>
                <div class="mt-6 text-left">
                    <InputLabel for="code" value="Código" class="ml-1 mb-1 text-center w-full" />
                    <TextInput id="code" v-model="joinForm.code" type="text" class="mt-1 block w-full text-center font-mono text-2xl tracking-widest uppercase rounded-xl border-slate-300 py-3" placeholder="ABCDE" @keyup.enter="joinRoom" />
                    <InputError :message="joinForm.errors.code" class="mt-2 text-center" />
                </div>
                <div class="mt-8 flex gap-3">
                    <button @click="closeModal" class="flex-1 py-3 rounded-xl border-2 font-bold text-slate-600">Cancelar</button>
                    <button @click="joinRoom" class="flex-1 py-3 rounded-xl bg-emerald-600 text-white font-bold hover:bg-emerald-700 shadow-lg shadow-emerald-200">Entrar</button>
                </div>
            </div>
        </Modal>

    </AuthenticatedLayout>
</template>

