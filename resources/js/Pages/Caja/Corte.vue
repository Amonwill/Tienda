<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref, computed } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    sesion: Object, 
    ventasResumen: Array
});

const efectivoContado = ref(0);
const mostrarResumenFinal = ref(false);

// Cálculo del saldo esperado
const saldoEsperado = computed(() => {
    return (props.sesion.Saldo_Inicial + props.sesion.Ingresos_Totales) - props.sesion.Egresos_Totales;
});
const diferencia = computed(() => {
    return efectivoContado.value - saldoEsperado.value;
});

// Cerrar caja y registrar corte
const formCierre = useForm({
    id_session: props.sesion.ID_Session,
    saldo_final_real: 0
});
const cerrarCaja = () => {
    if (confirm('¿Estás seguro de cerrar el turno? No podrás registrar más ventas en esta sesión.')) {
        formCierre.saldo_final_real = efectivoContado.value;
        formCierre.post(route('caja.cerrar'), {
            onSuccess: () => {
                mostrarResumenFinal.value = true;
            }
        });
    }
};
</script>

<template>
    <Head title="Corte de Caja - La Moderna" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Corte de Caja: Sesión #{{ sesion.ID_Session }}
            </h2>
        </template>

        <div class="py-12 bg-gray-50 min-h-screen">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                
                <div class="bg-white shadow-xl rounded-lg overflow-hidden">
                    <div class="bg-gray-800 p-6 text-white flex justify-between items-center">
                        <div>
                            <p class="text-sm uppercase tracking-widest text-gray-400">Fecha de Apertura</p>
                            <p class="text-lg font-mono">{{ new Date(sesion.Fecha_Apertura).toLocaleString() }}</p>
                        </div>
                        <div class="text-right">
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-green-500 text-white">SESIÓN ACTIVA</span>
                        </div>
                    </div>

                    <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-4">
                            <h3 class="text-lg font-bold text-gray-700 border-b pb-2 italic">Resumen del Sistema</h3>
                            
                            <div class="flex justify-between py-2 border-b border-gray-100">
                                <span>Saldo Inicial:</span>
                                <span class="font-mono font-bold text-blue-600">${{ sesion.Saldo_Inicial.toFixed(2) }}</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-100 text-green-600">
                                <span>(+) Ventas Totales:</span>
                                <span class="font-mono font-bold">${{ sesion.Ingresos_Totales.toFixed(2) }}</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-100 text-red-500">
                                <span>(-) Salidas/Egresos:</span>
                                <span class="font-mono font-bold">${{ sesion.Egresos_Totales.toFixed(2) }}</span>
                            </div>
                            <div class="flex justify-between py-4 text-xl font-black text-gray-800 bg-gray-50 px-2 rounded">
                                <span>SALDO ESPERADO:</span>
                                <span class="font-mono font-black">${{ saldoEsperado.toFixed(2) }}</span>
                            </div>
                        </div>

                        <div class="bg-blue-50 p-6 rounded-xl border border-blue-100">
                            <h3 class="text-lg font-bold text-blue-800 mb-4">Arqueo de Caja</h3>
                            
                            <label class="block text-sm font-medium text-blue-700 mb-2">Ingresa el total de dinero físico:</label>
                            <div class="relative">
                                <span class="absolute left-4 top-3 text-3xl font-bold text-blue-400">$</span>
                                <input 
                                    v-model="efectivoContado"
                                    type="number" 
                                    step="0.01"
                                    class="w-full pl-10 text-4xl font-mono text-right border-blue-200 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-white"
                                    placeholder="0.00"
                                />
                            </div>

                            <div class="mt-6 p-4 rounded-lg bg-white shadow-inner">
                                <p class="text-xs text-gray-500 uppercase font-bold mb-1">Diferencia</p>
                                <div class="flex justify-between items-center">
                                    <span :class="diferencia < 0 ? 'text-red-600' : 'text-green-600'" class="text-2xl font-bold font-mono">
                                        ${{ diferencia.toFixed(2) }}
                                    </span>
                                    <span v-if="diferencia === 0" class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded font-bold">CAJA CUADRADA</span>
                                    <span v-else-if="diferencia < 0" class="text-xs bg-red-100 text-red-800 px-2 py-1 rounded font-bold font-mono">FALTANTE</span>
                                    <span v-else class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded font-bold font-mono">SOBRANTE</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="p-6 bg-gray-100 border-t flex justify-end">
                        <button 
                            @click="cerrarCaja"
                            class="bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-8 rounded-lg shadow-lg transition transform hover:scale-105"
                        >
                            CERRAR TURNO Y FINALIZAR CORTE
                        </button>
                    </div>
                </div>

            </div>
        </div>

        <div v-if="mostrarResumenFinal" class="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-2xl p-8 max-w-sm w-full text-center shadow-2xl">
                <div class="w-20 h-20 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <h2 class="text-2xl font-black text-gray-800 mb-2 uppercase">Turno Finalizado</h2>
                <p class="text-gray-600 mb-6">El corte ha sido registrado. La sesión de caja se encuentra ahora cerrada.</p>
                <button @click="window.location.href = '/dashboard'" class="w-full bg-blue-600 text-white font-bold py-3 rounded-xl">IR AL DASHBOARD</button>
            </div>
        </div>
    </AuthenticatedLayout>
</template>