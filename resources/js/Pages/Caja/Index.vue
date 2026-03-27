<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

//Recinirmos las cajas físicas y la sesión activa 
const props = defineProps({
    cajas_fisicas: Array,
    sesion_activa: Object
});

//Abrir caja y crear sesión
const form = useForm({
    id_caja_fisica: '',
    saldo_inicial: 0
});
const abrirCaja = () => {
    form.post(route('caja.abrir'), {
        onSuccess: () => alert('¡Caja abierta con éxito! Ya puedes ir a Ventas.')
    });
};
</script>

<template>
    <Head title="Gestión de Caja" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800">Gestión de Caja</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                
                <div v-if="!sesion_activa" class="bg-white p-6 rounded-lg shadow max-w-md mx-auto">
                    <h3 class="text-lg font-bold mb-4 text-blue-600">Apertura de Turno</h3>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Seleccionar Caja Física:</label>
                        <select v-model="form.id_caja_fisica" class="w-full mt-1 border-gray-300 rounded-md shadow-sm">
                            <option value="" disabled>Selecciona una caja...</option>
                            <option v-for="caja in cajas_fisicas" :key="caja.ID_Caja_Fisica" :value="caja.ID_Caja_Fisica">
                                {{ caja.Nombre_Caja }} ({{ caja.Ubicacion }})
                            </option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Saldo Inicial (Fondo):</label>
                        <input v-model="form.saldo_inicial" type="number" class="w-full mt-1 border-gray-300 rounded-md shadow-sm" />
                    </div>

                    <button @click="abrirCaja" :disabled="!form.id_caja_fisica" 
                            class="w-full bg-blue-600 text-white font-bold py-2 rounded hover:bg-blue-700 disabled:bg-gray-400">
                        ABRIR CAJA E INICIAR TURNO
                    </button>
                </div>

                <div v-else class="bg-green-50 border border-green-200 p-8 rounded-lg shadow text-center max-w-lg mx-auto">
                    <div class="text-5xl mb-4">🏪</div>
                    <h3 class="text-2xl font-black text-green-800 uppercase">La Caja está Abierta</h3>
                    <p class="text-green-700 mt-2">Sesión activa #{{ sesion_activa.ID_Session }}</p>
                    <p class="text-sm text-green-600">Saldo en sistema: ${{ sesion_activa.Saldo_Inicial + sesion_activa.Ingresos_Totales }}</p>
                    
                    <div class="mt-8 flex gap-4 justify-center">
                        <a :href="route('ventas.index')" class="bg-green-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-green-700">IR A VENDER</a>
                        <a :href="route('caja.corte')" class="bg-red-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-red-700">REALIZAR CORTE</a>
                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>