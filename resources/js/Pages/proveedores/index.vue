<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    providers: Array
});

const showModal = ref(false);
const isEditing = ref(false);

const form = useForm({
    id: null,
    nombre: '',
    descripcion: '',
    telefono: '',
    correo: '',
    direccion: '',
    ciudad: '',
});

const openModal = (prov = null) => {
    isEditing.value = !!prov;
    if (prov) {
        form.id = prov.ID_Proveedor;
        form.nombre = prov.Nombre_Proveedor;
        form.descripcion = prov.Descripcion_Proveedor;
        form.telefono = prov.Telefono_Proveedor;
        form.correo = prov.Correo_Proveedor;
        form.direccion = prov.Direccion_Completa;
        form.ciudad = prov.Ciudad_Estado;
    } else {
        form.reset();
    }
    showModal.value = true;
};

const submit = () => {
    if (isEditing.value) {
        form.put(route('proveedores.update', form.id), {
            onSuccess: () => closeModal(),
        });
    } else {
        form.post(route('proveedores.store'), {
            onSuccess: () => closeModal(),
        });
    }
};

const closeModal = () => {
    showModal.value = false;
    form.reset();
};

const deleteProv = (id) => {
    if (confirm('¿Estás seguro de que deseas desactivar este proveedor?')) {
        form.delete(route('proveedores.destroy', id));
    }
};
</script>

<template>
    <Head title="Proveedores - La Moderna" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-black text-2xl text-gray-800 uppercase italic tracking-tighter">
                    Gestión de Proveedores
                </h2>
                <button @click="openModal()" class="bg-blue-600 hover:bg-blue-700 text-white font-black py-2.5 px-6 rounded-xl shadow-lg transition transform hover:scale-105 active:scale-95">
                    + NUEVO PROVEEDOR
                </button>
            </div>
        </template>

        <div class="py-12 bg-gray-50 min-h-screen">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white shadow-2xl rounded-3xl overflow-hidden border border-gray-200">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-gray-800 text-white uppercase text-[11px] font-black tracking-widest">
                            <tr>
                                <th class="px-6 py-4">ID</th>
                                <th class="px-6 py-4">Proveedor / Descripción</th>
                                <th class="px-6 py-4">Contacto</th>
                                <th class="px-6 py-4">Ubicación</th>
                                <th class="px-6 py-4 text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 italic text-sm">
                            <tr v-for="prov in providers" :key="prov.ID_Proveedor" class="hover:bg-blue-50/50 transition duration-150">
                                <td class="px-6 py-4 font-black text-blue-600">#{{ prov.ID_Proveedor }}</td>
                                <td class="px-6 py-4">
                                    <div class="font-black text-gray-900 uppercase tracking-tighter">{{ prov.Nombre_Proveedor }}</div>
                                    <div class="text-[10px] text-gray-400 font-bold tracking-tight">{{ prov.Descripcion_Proveedor }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-700">{{ prov.Telefono_Proveedor }}</div>
                                    <div class="text-xs text-blue-500 font-semibold underline">{{ prov.Correo_Proveedor }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-xs text-gray-600 font-medium">{{ prov.Direccion_Completa }}</div>
                                    <div class="text-[10px] font-black text-gray-400 uppercase tracking-wider">{{ prov.Ciudad_Estado }}</div>
                                </td>
                                <td class="px-6 py-4 text-center space-x-3">
                                    <button @click="openModal(prov)" class="text-blue-600 font-black hover:text-blue-800 uppercase text-[10px] underline decoration-2">Editar</button>
                                    <button @click="deleteProv(prov.ID_Proveedor)" class="text-red-600 font-black hover:text-red-800 uppercase text-[10px] underline decoration-2">Eliminar</button>
                                </td>
                            </tr>
                            <tr v-if="providers.length === 0">
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500 font-bold uppercase tracking-widest bg-gray-50">
                                    No hay proveedores registrados
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div v-if="showModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden border border-gray-100 animate-in zoom-in duration-200">
                <div class="bg-gray-100 p-6 border-b flex justify-between items-center">
                    <h3 class="text-xl font-black text-gray-800 uppercase italic tracking-tighter">
                        {{ isEditing ? 'Actualizar Proveedor' : 'Nuevo Registro' }}
                    </h3>
                    <button @click="closeModal" class="text-gray-400 hover:text-red-500 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                    </button>
                </div>

                <form @submit.prevent="submit">
                    <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-black uppercase text-gray-400 mb-1 tracking-widest">Nombre Comercial</label>
                            <input v-model="form.nombre" type="text" class="w-full border-gray-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 font-bold" required />
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-black uppercase text-gray-400 mb-1 tracking-widest">Giro / Descripción</label>
                            <input v-model="form.descripcion" type="text" class="w-full border-gray-200 rounded-xl focus:ring-blue-500 focus:border-blue-500" required />
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase text-gray-400 mb-1 tracking-widest">Teléfono</label>
                            <input v-model="form.telefono" type="text" class="w-full border-gray-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 font-mono" required />
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase text-gray-400 mb-1 tracking-widest">Email</label>
                            <input v-model="form.correo" type="email" class="w-full border-gray-200 rounded-xl focus:ring-blue-500 focus:border-blue-500" required />
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-black uppercase text-gray-400 mb-1 tracking-widest">Dirección (Calle, No, Col, CP)</label>
                            <input v-model="form.direccion" type="text" class="w-full border-gray-200 rounded-xl focus:ring-blue-500 focus:border-blue-500" required />
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-black uppercase text-gray-400 mb-1 tracking-widest">Estado / Ciudad</label>
                            <input v-model="form.ciudad" type="text" class="w-full border-gray-200 rounded-xl focus:ring-blue-500 focus:border-blue-500" required />
                        </div>
                    </div>

                    <div class="p-6 bg-gray-50 border-t flex justify-end gap-3">
                        <button type="button" @click="closeModal" class="px-6 py-2 text-gray-500 font-black uppercase text-xs tracking-widest hover:text-gray-700">
                            Cancelar
                        </button>
                        <button type="submit" :disabled="form.processing" class="bg-blue-600 text-white px-10 py-2.5 rounded-xl font-black shadow-lg uppercase text-xs tracking-widest hover:bg-blue-700 disabled:opacity-50 transition">
                            {{ isEditing ? 'Guardar Cambios' : 'Registrar Proveedor' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>