<script setup>
import GameLayout from '@/Layouts/GameLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import { ref, computed, onMounted, onUnmounted, watch } from 'vue';

const props = defineProps({
    room: Object,
    players: Array,
    currentUserPlayerId: Number,
});

const localPlayers = ref([...props.players]);

    // Sincronizar con props solo para jugadores que NO soy yo (para evitar saltos)
    watch(() => props.players, (newPlayers) => {
        newPlayers.forEach(remotePlayer => {
            const localPlayer = localPlayers.value.find(p => p.id === remotePlayer.id);
            if (localPlayer) {
                // Si es mi propio jugador, solo actualizamos si no estamos en medio de una edición
                if (remotePlayer.id !== props.currentUserPlayerId || !isSyncing.value) {
                    localPlayer.points = remotePlayer.points;
                    localPlayer.name = remotePlayer.name;
                }
            } else {
                localPlayers.value.push(remotePlayer);
            }
        });
    }, { deep: true });

const sortedPlayers = computed(() => {
    return [...localPlayers.value].sort((a, b) => b.points - a.points);
});

const maxPoints = computed(() => {
    if (localPlayers.value.length === 0) return 0;
    return Math.max(...localPlayers.value.map(p => p.points));
});

// --- LÓGICA DE ACTUALIZACIÓN MEJORADA ---
const isSyncing = ref(false);
let debounceTimeout = null;

const adjustPoints = (player, amount) => {
    // 1. Actualización visual inmediata
    player.points += amount;

    // 2. Si soy yo el que edita, debouncemos la subida al servidor
    if (player.id === props.currentUserPlayerId || props.room.user_id === props.$page.props.auth.user?.id) {
        clearTimeout(debounceTimeout);
        isSyncing.value = true;

        debounceTimeout = setTimeout(() => {
            sendPointsToServer(player);
        }, 500); // Espera 500ms tras el último clic
    }
};

const sendPointsToServer = (player) => {
    const form = useForm({ points: player.points });
    form.patch(route('players.updatePoints', player.id), {
        preserveScroll: true,
        onFinish: () => {
            isSyncing.value = false;
        }
    });
};

// --- RESTO DE LÓGICA ---
const editingPlayerId = ref(null);
const editForm = useForm({ points: 0 });

const startEditing = (player) => {
    editingPlayerId.value = player.id;
    editForm.points = player.points;
};

const saveManualEdit = (player) => {
    player.points = editForm.points;
    editingPlayerId.value = null;
    sendPointsToServer(player);
};

const joinForm = useForm({
    code: props.room.code,
    name: '',
});

const joinRoom = () => {
    joinForm.post(route('rooms.join.post'), {
        preserveScroll: true,
    });
};

const getLifeColor = (points, initialPoints) => {
    if (points <= 0) return 'text-slate-400';
    const percentage = (points / initialPoints) * 100;
    if (percentage <= 25) return 'text-rose-600';
    if (percentage <= 50) return 'text-orange-600';
    if (percentage <= 75) return 'text-amber-600';
    return 'text-emerald-600';
};

const getLifeBgColor = (points, initialPoints) => {
    if (points <= 0) return 'bg-slate-100';
    const percentage = (points / initialPoints) * 100;
    if (percentage <= 25) return 'bg-rose-50';
    if (percentage <= 50) return 'bg-orange-50';
    if (percentage <= 75) return 'bg-amber-50';
    return 'bg-emerald-50';
};

const getPlayerColor = (id) => {
    const colors = [
        'bg-orange-100 text-orange-600', 'bg-amber-100 text-amber-600', 
        'bg-emerald-100 text-emerald-600', 'bg-teal-100 text-teal-600', 
        'bg-cyan-100 text-cyan-600', 'bg-indigo-100 text-indigo-600', 
        'bg-violet-100 text-violet-600', 'bg-fuchsia-100 text-fuchsia-600', 
        'bg-rose-100 text-rose-600'
    ];
    return colors[id % colors.length];
};

const initialPoints = computed(() => props.room.initial_points || 20);
const showInfo = ref(false);

onMounted(() => {
    window.Echo.private(`room.${props.room.code}`)
        .listen('.points.updated', (e) => {
            // Solo actualizamos si no soy yo el que está enviando puntos actualmente
            if (e.player.id !== props.currentUserPlayerId || !isSyncing.value) {
                const playerIndex = localPlayers.value.findIndex(p => p.id === e.player.id);
                if (playerIndex !== -1) {
                    localPlayers.value[playerIndex].points = e.player.points;
                }
            }
        })
        .listen('.player.joined', (e) => {
            if (!localPlayers.value.find(p => p.id === e.player.id)) {
                localPlayers.value.push(e.player);
            }
        });
});

onUnmounted(() => {
    window.Echo.leave(`room.${props.room.code}`);
});
</script>

<template>
    <Head :title="room.name || 'Juego'" />

    <GameLayout>
        <template #header>
            <div class="flex items-center gap-2">
                <span class="text-xl">🎮</span>
                <h1 class="text-base font-black text-slate-800 truncate max-w-[150px] sm:max-w-none">
                    {{ room.name || 'Partida' }}
                </h1>
            </div>
        </template>

        <!-- OVERLAY PARA UNIRSE -->
        <div v-if="!currentUserPlayerId" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
            <div class="bg-white rounded-[2.5rem] w-full max-w-md p-8 shadow-2xl border border-white animate-in zoom-in duration-300">
                <div class="text-center mb-8">
                    <div class="h-20 w-20 bg-indigo-100 rounded-3xl flex items-center justify-center text-4xl mx-auto mb-4 shadow-inner">👋</div>
                    <h2 class="text-2xl font-black text-slate-800 mb-2">¡Hola, Invitado!</h2>
                    <p class="text-slate-500 font-medium">Estás a punto de entrar en <span class="text-indigo-600 font-bold">{{ room.name || 'esta sala' }}</span>. ¿Cómo quieres que te llamen?</p>
                </div>
                <form @submit.prevent="joinRoom" class="space-y-4">
                    <input v-model="joinForm.name" type="text" placeholder="Tu nombre o apodo" class="w-full px-6 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:border-indigo-500 focus:ring-0 text-lg font-bold text-slate-700" required>
                    <button type="submit" :disabled="joinForm.processing" class="w-full py-4 bg-indigo-600 text-white rounded-2xl font-black text-lg shadow-xl shadow-indigo-200 hover:bg-indigo-700 active:scale-95 transition-all">
                        {{ joinForm.processing ? 'Entrando...' : '¡A jugar!' }}
                    </button>
                </form>
            </div>
        </div>

        <div class="max-w-[1600px] mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 lg:gap-8">
                
                <!-- COLUMNA IZQUIERDA -->
                <div class="lg:col-span-3 xl:col-span-2 space-y-4">
                    <button @click="showInfo = !showInfo" class="lg:hidden w-full flex items-center justify-between bg-white p-4 rounded-2xl shadow-sm border border-slate-100">
                        <span class="font-bold text-slate-700 flex items-center gap-2">ℹ️ Info</span>
                        <svg class="h-5 w-5 text-slate-400" :class="{'rotate-180': showInfo}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div :class="{'hidden lg:block': !showInfo}" class="space-y-4">
                        <div class="bg-white rounded-3xl p-5 shadow-sm border border-slate-100">
                            <div class="bg-indigo-50 rounded-2xl p-4 text-center border-2 border-indigo-100 mb-4">
                                <span class="text-[10px] font-bold text-indigo-400 uppercase block mb-1">Código</span>
                                <span class="text-2xl font-mono font-black text-indigo-600 tracking-wider">{{ room.code }}</span>
                            </div>
                            <div class="grid grid-cols-2 gap-2 text-center">
                                <div class="bg-slate-50 p-2 rounded-xl border border-slate-100">
                                    <span class="text-[9px] font-bold text-slate-400 uppercase block">Players</span>
                                    <p class="text-lg font-black text-slate-800">👥 {{ localPlayers.length }}</p>
                                </div>
                                <div class="bg-slate-50 p-2 rounded-xl border border-slate-100">
                                    <span class="text-[9px] font-bold text-slate-400 uppercase block">Vida</span>
                                    <p class="text-lg font-black text-slate-800">❤️ {{ initialPoints }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- COLUMNA CENTRAL -->
                <div class="lg:col-span-6 xl:col-span-7 space-y-4">
                    <div class="flex items-center justify-between px-2 mb-2">
                        <h2 class="text-lg font-black text-slate-800 flex items-center gap-2">
                            Tablero <span class="bg-slate-200 text-slate-600 text-[10px] px-2 py-0.5 rounded-md font-bold">{{ localPlayers.length }}</span>
                        </h2>
                        <!-- Indicador de guardado -->
                        <div v-if="isSyncing" class="flex items-center gap-2 bg-indigo-50 px-3 py-1 rounded-full border border-indigo-100 animate-pulse">
                            <span class="h-2 w-2 bg-indigo-500 rounded-full"></span>
                            <span class="text-[10px] font-bold text-indigo-600 uppercase">Sincronizando...</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4 lg:gap-6">
                        <div v-for="(player, index) in sortedPlayers" :key="player.id" class="relative transition-all duration-300" :class="{'z-10': player.id === currentUserPlayerId}">
                            
                            <div v-if="player.points === maxPoints && player.points > 0" class="absolute -top-2 -right-1 z-20"><span class="text-xl">👑</span></div>
                            <div v-if="player.points <= 0" class="absolute -top-2 -right-1 z-20"><span class="text-xl">💀</span></div>

                            <div class="bg-white rounded-[2rem] shadow-sm border-2 overflow-hidden flex flex-col h-full transition-all"
                                :class="{
                                    'border-indigo-500 ring-4 ring-indigo-500/10': player.id === currentUserPlayerId && player.points > 0,
                                    'border-slate-200 bg-slate-50 opacity-60 scale-[0.98]': player.points <= 0,
                                    'border-slate-100 hover:border-slate-200': player.id !== currentUserPlayerId && player.points > 0
                                }">
                                
                                <div class="px-4 py-3 flex items-center gap-3 border-b border-slate-50" :class="getLifeBgColor(player.points, initialPoints)">
                                    <div class="h-10 w-10 rounded-xl flex items-center justify-center font-black text-xs shadow-inner flex-shrink-0" :class="player.points <= 0 ? 'bg-slate-200 text-slate-400' : getPlayerColor(player.id)">
                                        {{ player.name.charAt(0).toUpperCase() }}
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="font-black text-sm text-slate-800 truncate leading-tight">{{ player.name }}</p>
                                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter">{{ player.points <= 0 ? 'Eliminado' : `#${index + 1} Posición` }}</p>
                                    </div>
                                    <div v-if="player.id === currentUserPlayerId && player.points > 0" class="text-[8px] font-black bg-indigo-600 text-white px-1.5 py-0.5 rounded uppercase">Tú</div>
                                </div>

                                <div class="flex-1 py-6 lg:py-8 flex flex-col items-center justify-center bg-white relative">
                                    <span class="text-6xl lg:text-7xl font-black tracking-tighter tabular-nums leading-none" :class="getLifeColor(player.points, initialPoints)">
                                        {{ player.points <= 0 ? '0' : player.points }}
                                    </span>
                                    <div class="w-24 h-1.5 bg-slate-100 rounded-full mt-3 overflow-hidden shadow-inner">
                                        <div class="h-full transition-all duration-500" :class="getLifeColor(player.points, initialPoints).replace('text', 'bg')" :style="{ width: `${Math.max(0, Math.min(100, (player.points / initialPoints) * 100))}%` }"></div>
                                    </div>
                                </div>

                                <div v-if="(player.id === currentUserPlayerId || room.user_id === $page.props.auth.user?.id) && player.points > 0" class="p-3 bg-slate-50/50 border-t border-slate-50">
                                    <div v-if="editingPlayerId !== player.id" class="flex gap-2">
                                        <button @click="adjustPoints(player, -1)" class="h-12 flex-1 rounded-xl bg-white border border-slate-200 text-rose-500 hover:bg-rose-50 active:scale-90 transition-all flex items-center justify-center shadow-sm">
                                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M20 12H4"></path></svg>
                                        </button>
                                        <button @click="startEditing(player)" class="h-12 w-10 rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-indigo-500 active:scale-90 transition-all flex items-center justify-center"><svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg></button>
                                        <button @click="adjustPoints(player, 1)" class="h-12 flex-1 rounded-xl bg-white border border-slate-200 text-emerald-500 hover:bg-emerald-50 active:scale-90 transition-all flex items-center justify-center shadow-sm">
                                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
                                        </button>
                                    </div>
                                    <div v-else class="flex gap-2">
                                        <input type="number" v-model="editForm.points" class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-0 text-center text-xl font-black py-2" @keyup.enter="saveManualEdit(player)">
                                        <button @click="saveManualEdit(player)" class="h-12 w-12 rounded-xl bg-indigo-600 text-white flex items-center justify-center shadow-md shadow-indigo-100 hover:bg-indigo-700 active:scale-90 transition-all"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"></path></svg></button>
                                    </div>
                                </div>
                                <div v-else class="py-3 text-center bg-slate-50/30">
                                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">{{ player.points <= 0 ? 'ELIMINADO' : 'OBSERVANDO' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- COLUMNA DERECHA -->
                <div class="lg:col-span-3 xl:col-span-3 space-y-4">
                    <div class="bg-white rounded-3xl p-5 shadow-sm border border-slate-100">
                        <h3 class="text-xs font-black text-slate-400 uppercase tracking-wider mb-4">🏆 Clasificación</h3>
                        <div class="space-y-2">
                            <div v-for="(player, idx) in sortedPlayers" :key="'rank-' + player.id" class="flex items-center justify-between p-3 rounded-2xl transition-all" :class="{'bg-indigo-50 ring-1 ring-indigo-100': player.id === currentUserPlayerId, 'bg-slate-50/50': player.id !== currentUserPlayerId}">
                                <div class="flex items-center gap-3 min-w-0">
                                    <span class="text-[10px] font-black text-slate-400 w-5">{{ idx + 1 }}º</span>
                                    <div class="h-7 w-7 rounded-lg flex items-center justify-center text-[10px] font-bold flex-shrink-0" :class="getPlayerColor(player.id)">{{ player.name.charAt(0).toUpperCase() }}</div>
                                    <span class="text-xs font-bold text-slate-700 truncate" :class="{'line-through opacity-50': player.points <= 0}">{{ player.name }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span v-if="idx === 0 && player.points > 0" class="text-xs">👑</span>
                                    <span class="text-xs font-black tabular-nums" :class="getLifeColor(player.points, initialPoints)">{{ player.points }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </GameLayout>
</template>
