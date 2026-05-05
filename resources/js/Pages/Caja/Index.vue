<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, router } from '@inertiajs/vue3';

const props = defineProps({
    cajas_fisicas: Array,
    sesion_activa: Object
});

const form = useForm({
    id_caja_fisica: '',
    saldo_inicial: 0
});

const abrirCaja = () => {
    if (!form.id_caja_fisica) return alert('Selecciona una caja física');
    form.post(route('caja.abrir'), {
        onSuccess: () => alert('¡Caja abierta!')
    });
};

const finalizarTurnoCompleto = () => {
    if (confirm('¿Cerrar turno y descargar reporte?')) {
        const urlDescarga = route('caja.descargar.pdf') + `?id_session=${props.sesion_activa.ID_Session}`;

        window.open(urlDescarga, '_blank');

        router.post(route('caja.cerrar.automatico'), {}, {
            onFinish: () => {
                console.log("Turno finalizado y sesión cerrada");
            }
        });
    }
};
</script>

<template>
    <Head title="Gestión de Caja" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-black text-xl text-gray-800 uppercase italic">Gestión de Caja</h2>
        </template>

        <div class="py-12 bg-gray-50 min-h-screen">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                
                <div v-if="!sesion_activa" class="bg-white p-8 rounded-3xl shadow-xl max-w-md mx-auto border border-gray-100">
                    <h3 class="text-lg font-black mb-6 text-blue-600 uppercase italic text-center border-b pb-4">Apertura de Turno</h3>
                    <div class="mb-4 text-left">
                        <label class="block text-[10px] font-black uppercase text-gray-400 mb-1">Caja Física</label>
                        <select v-model="form.id_caja_fisica" class="w-full border-gray-200 rounded-xl">
                            <option value="" disabled>Selecciona...</option>
                            <option v-for="caja in cajas_fisicas" :key="caja.ID_Caja_Fisica" :value="caja.ID_Caja_Fisica">
                                {{ caja.Nombre_Caja }}
                            </option>
                        </select>
                    </div>
                    <div class="mb-6 text-left">
                        <label class="block text-[10px] font-black uppercase text-gray-400 mb-1">Fondo Inicial</label>
                        <input v-model="form.saldo_inicial" type="number" class="w-full border-gray-200 rounded-xl" />
                    </div>
                    <button @click="abrirCaja" class="w-full bg-blue-600 text-white font-black py-4 rounded-2xl uppercase text-xs tracking-widest">
                        INICIAR TURNO
                    </button>
                </div>

                <div v-else class="bg-white border border-green-100 p-10 rounded-[40px] shadow-2xl text-center max-w-lg mx-auto">
                    <div class="text-6xl mb-6">🏪</div>
                    <h3 class="text-3xl font-black text-green-800 uppercase italic">La Caja está Abierta</h3>
                    <div class="mt-4 p-6 bg-green-50 rounded-3xl border border-green-100">
                        <p class="text-green-700 font-bold uppercase text-[10px]">Sesión activa #{{ sesion_activa.ID_Session }}</p>
                        <p class="text-3xl font-black text-green-900 font-mono mt-1">
                            ${{ (parseFloat(sesion_activa.Saldo_Inicial) + parseFloat(sesion_activa.Ingresos_Totales)).toFixed(2) }}
                        </p>
                    </div>
                    <div class="mt-10 flex gap-4 justify-center">
                        <a :href="route('ventas.index')" class="bg-green-600 text-white px-8 py-4 rounded-2xl font-black uppercase text-[10px] tracking-widest">
                            IR A VENDER
                        </a>
                        <button @click="finalizarTurnoCompleto" class="bg-red-600 text-white px-8 py-4 rounded-2xl font-black uppercase text-[10px] tracking-widest shadow-lg">
                            REALIZAR CORTE (PDF)
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>