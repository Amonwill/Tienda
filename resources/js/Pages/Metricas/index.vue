<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    stats: Object,
    topProductos: Array
});
</script>

<template>
    <Head title="Métricas - La Moderna" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-black text-2xl text-gray-800 uppercase italic tracking-tighter">Panel de Rendimiento</h2>
        </template>

        <div class="py-12 bg-gray-50 min-h-screen">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-white p-8 rounded-3xl shadow-xl border-b-4 border-green-500 transform hover:scale-105 transition duration-300">
                        <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Ingresos de Hoy</div>
                        <div class="text-4xl font-black text-gray-900 font-mono mt-2">${{ stats.ingresos.toFixed(2) }}</div>
                    </div>

                    <div class="bg-white p-8 rounded-3xl shadow-xl border-b-4 border-blue-500 transform hover:scale-105 transition duration-300">
                        <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Ventas Realizadas</div>
                        <div class="text-4xl font-black text-gray-900 font-mono mt-2">{{ stats.operaciones }}</div>
                    </div>

                    <div class="bg-white p-8 rounded-3xl shadow-xl border-b-4 border-purple-500 transform hover:scale-105 transition duration-300">
                        <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Ticket Promedio</div>
                        <div class="text-4xl font-black text-gray-900 font-mono mt-2">${{ stats.promedio.toFixed(2) }}</div>
                    </div>
                </div>

                <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100">
                    <div class="bg-gray-800 p-4 text-white font-black uppercase text-xs tracking-widest italic flex justify-between items-center">
                        <span>Productos más vendidos (Histórico)</span>
                        <a :href="route('Metricas.semanal.pdf')" target="_blank"
                            class="bg-white text-gray-800 px-4 py-2 rounded-lg font-black text-[10px] uppercase hover:bg-gray-200 transition shadow-md">
                            Descargar Resumen Semanal
                        </a>
                    </div>
                    <div class="p-6">
                        <div v-if="topProductos && topProductos.length > 0">
                            <div v-for="(item, index) in topProductos" :key="index" class="flex justify-between items-center py-3 border-b last:border-0 italic">
                                <span class="font-bold text-gray-700 uppercase">{{ item.Nombre_Producto }}</span>
                                <span class="bg-blue-100 text-blue-700 px-4 py-1 rounded-full font-black text-xs">
                                    {{ item.total }} unidades
                                </span>
                            </div>
                        </div>
                        <div v-else class="text-center py-4 text-gray-400 italic">
                            Sin datos de ventas registrados.
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>