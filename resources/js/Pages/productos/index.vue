<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    products: Array,
    proveedores: Array 
});

const showModal = ref(false);
const showProvModal = ref(false); 
const isEditing = ref(false);

// Formulario de Producto
const form = useForm({
    id_prod: null,
    nombre: '',
    descripcion: '',
    precio_compra: 0,
    precio_venta: 0,
    cantidad: 0,
    num_lote: '',
    fecha_caducidad: '',
    nombre_proveedor: '',
});

// Formulario de Proveedor (Con todos los campos NOT NULL de tu DB)
const provForm = useForm({
    nombre: '',
    descripcion: '',
    telefono: '',
    correo: '',
    direccion: '',
    ciudad: '',
});

const openModal = (product = null) => {
    isEditing.value = !!product;
    if (product) {
        form.id_prod = product.ID_Producto;
        form.nombre = product.Nombre_Producto;
        form.descripcion = product.Descripcion_Producto;
        form.precio_compra = product.Precio_Compra;
        form.precio_venta = product.Precio_Venta;
        form.cantidad = product.Cantidad;
        form.num_lote = product.Num_Lote || '';
        form.fecha_caducidad = product.Fecha_Caducidad || '';
        form.nombre_proveedor = product.Nombre_Proveedor || '';
    } else {
        form.reset();
        form.id_prod = null;
    }
    showModal.value = true;
};

// Validación de existencia de proveedor
const checkAndSubmit = () => {
    const existe = props.proveedores.some(
        p => p.Nombre_Proveedor.toLowerCase() === form.nombre_proveedor.toLowerCase()
    );

    if (!existe && form.nombre_proveedor !== '') {
        provForm.nombre = form.nombre_proveedor;
        showProvModal.value = true; 
    } else {
        submitProducto();
    }
};

const submitProducto = () => {
    form.post(route('productos.store'), {
        onSuccess: () => {
            showModal.value = false;
            form.reset();
        }
    });
};

const submitProveedor = () => {
    // Sincronizamos el nombre antes de enviar
    provForm.nombre = form.nombre_proveedor; 

    provForm.post(route('proveedores.store'), {
        onSuccess: () => {
            showProvModal.value = false;
            // Una vez creado con éxito, procedemos a guardar el producto
            submitProducto(); 
        },
        onError: (err) => {
            console.error(err);
            alert("Error: Verifica que todos los campos del proveedor estén llenos.");
        }
    });
};

const deleteProduct = (id) => {
    if (confirm('¿Eliminar producto?')) form.delete(route('productos.destroy', id));
};
</script>

<template>
    <Head title="Inventario - La Moderna" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-bold text-xl text-gray-800 uppercase tracking-widest">Inventario</h2>
                <button @click="openModal()" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-black shadow-md">+ NUEVO PRODUCTO</button>
            </div>
        </template>

        <div class="py-12 bg-gray-50 min-h-screen">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-200">
                    <table class="w-full text-left">
                        <thead class="bg-gray-800 text-white uppercase text-xs font-black">
                            <tr>
                                <th class="px-6 py-4">Producto</th>
                                <th class="px-6 py-4">Proveedor</th>
                                <th class="px-6 py-4 text-center">Stock</th>
                                <th class="px-6 py-4 text-right">Venta</th>
                                <th class="px-6 py-4 text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 italic font-medium">
                            <tr v-for="p in products" :key="p.ID_Producto" class="hover:bg-blue-50/50 transition">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-900">{{ p.Nombre_Producto }}</div>
                                    <div class="text-[10px] text-gray-400">{{ p.Descripcion_Producto }}</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 uppercase">{{ p.Nombre_Proveedor }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span :class="p.Cantidad < 10 ? 'bg-red-100 text-red-600' : 'bg-green-100 text-green-600'" class="px-3 py-1 rounded-full text-xs font-black">
                                        {{ p.Cantidad }} pz
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right font-mono font-bold">${{ parseFloat(p.Precio_Venta).toFixed(2) }}</td>
                                <td class="px-6 py-4 text-center space-x-2">
                                    <button @click="openModal(p)" class="text-blue-500 font-black hover:underline text-xs uppercase">Editar</button>
                                    <button @click="deleteProduct(p.ID_Producto)" class="text-red-500 font-black hover:underline text-xs uppercase">Borrar</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div v-if="showModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-3xl shadow-2xl w-full max-w-2xl overflow-hidden animate-in fade-in zoom-in duration-200">
                <div class="bg-gray-100 p-6 border-b flex justify-between font-black uppercase italic tracking-tighter">
                    <h3>{{ isEditing ? 'Actualizar Producto' : 'Nuevo Producto' }}</h3>
                    <button @click="showModal = false">✕</button>
                </div>
                <div class="p-8 grid grid-cols-2 gap-6">
                    <div class="col-span-2">
                        <label class="block text-[10px] font-black uppercase text-gray-400 mb-1">Nombre</label>
                        <input v-model="form.nombre" type="text" class="w-full border-gray-200 rounded-xl focus:ring-blue-500" required />
                    </div>
                    <div class="col-span-2">
                        <label class="block text-[10px] font-black uppercase text-gray-400 mb-1">Nombre del Proveedor</label>
                        <input v-model="form.nombre_proveedor" type="text" class="w-full border-gray-200 rounded-xl focus:ring-blue-500" placeholder="Ej: Bimbo" required />
                    </div>
                    <div class="col-span-2">
                        <label class="block text-[10px] font-black uppercase text-gray-400 mb-1">Descripción</label>
                        <textarea v-model="form.descripcion" class="w-full border-gray-200 rounded-xl focus:ring-blue-500" rows="2"></textarea>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black uppercase text-gray-400 mb-1">P. Compra</label>
                        <input v-model="form.precio_compra" type="number" step="0.01" class="w-full border-gray-200 rounded-xl focus:ring-blue-500" required />
                    </div>
                    <div>
                        <label class="block text-[10px] font-black uppercase text-gray-400 mb-1">P. Venta</label>
                        <input v-model="form.precio_venta" type="number" step="0.01" class="w-full border-gray-200 rounded-xl focus:ring-blue-500" required />
                    </div>
                    <div>
                        <label class="block text-[10px] font-black uppercase text-gray-400 mb-1">Stock</label>
                        <input v-model="form.cantidad" type="number" class="w-full border-gray-200 rounded-xl focus:ring-blue-500" required />
                    </div>
                    <div>
                        <label class="block text-[10px] font-black uppercase text-gray-400 mb-1">Lote</label>
                        <input v-model="form.num_lote" type="text" class="w-full border-gray-200 rounded-xl focus:ring-blue-500" required />
                    </div>
                    <div class="col-span-2">
                        <label class="block text-[10px] font-black uppercase text-gray-400 mb-1">Caducidad</label>
                        <input v-model="form.fecha_caducidad" type="date" class="w-full border-gray-200 rounded-xl focus:ring-blue-500" required />
                    </div>
                </div>
                <div class="p-6 bg-gray-50 flex justify-end gap-3 border-t">
                    <button @click="showModal = false" class="font-bold text-gray-500 uppercase text-xs">CANCELAR</button>
                    <button @click="checkAndSubmit" class="bg-blue-600 text-white px-8 py-2 rounded-xl font-black shadow-lg uppercase text-xs tracking-widest">GUARDAR</button>
                </div>
            </div>
        </div>

        <div v-if="showProvModal" class="fixed inset-0 bg-red-900/40 backdrop-blur-md flex items-center justify-center z-[60] p-4">
            <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden border-4 border-red-500 animate-in zoom-in duration-300">
                <div class="bg-red-500 p-4 text-white font-black text-center uppercase italic tracking-tighter">¡Proveedor No Registrado!</div>
                <div class="p-8 space-y-4 max-h-[65vh] overflow-y-auto">
                    <p class="text-sm text-gray-600 text-center">Completa los datos de <strong>{{ form.nombre_proveedor }}</strong>:</p>
                    
                    <div>
                        <label class="block text-[10px] font-black uppercase text-gray-400 mb-1">Descripción</label>
                        <input v-model="provForm.descripcion" type="text" class="w-full border-gray-300 rounded-xl focus:ring-red-500" required />
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-black uppercase text-gray-400 mb-1">Teléfono</label>
                            <input v-model="provForm.telefono" type="text" class="w-full border-gray-300 rounded-xl focus:ring-red-500" required />
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase text-gray-400 mb-1">Correo</label>
                            <input v-model="provForm.correo" type="email" class="w-full border-gray-300 rounded-xl focus:ring-red-500" required />
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black uppercase text-gray-400 mb-1">Dirección Completa</label>
                        <input v-model="provForm.direccion" type="text" class="w-full border-gray-300 rounded-xl focus:ring-red-500" placeholder="Calle, Nro, Col, CP" required />
                    </div>
                    <div>
                        <label class="block text-[10px] font-black uppercase text-gray-400 mb-1">Ciudad / Estado</label>
                        <input v-model="provForm.ciudad" type="text" class="w-full border-gray-300 rounded-xl focus:ring-red-500" placeholder="Ej: CDMX" required />
                    </div>
                </div>
                <div class="p-6 bg-gray-50 flex justify-end gap-3 border-t">
                    <button @click="showProvModal = false" class="font-bold text-gray-500 text-[10px] uppercase">CANCELAR</button>
                    <button @click="submitProveedor" class="bg-red-600 text-white px-6 py-3 rounded-xl font-black shadow-lg text-[10px] uppercase tracking-tighter">REGISTRAR Y GUARDAR PRODUCTO</button>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template> 