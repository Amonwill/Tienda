<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref, computed } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    products: Array, 
    activeSession: Object 
});

// ESTADOS DE LA VENTA
const search = ref('');
const cart = ref([]);
const paymentAmount = ref(0);
const showTicketModal = ref(false);
const lastSaleResponse = ref(null);

// LOGICA DE FILTRADO DE PRODUCTOS
const filteredProducts = computed(() => {
    if (!search.value) return [];
    return props.products.filter(p => 
        p.Nombre_Producto.toLowerCase().includes(search.value.toLowerCase()) && p.Cantidad > 0
    );
});

// CARRITO DE COMPRAS Y PROCESO DE VENTA
const addToCart = (product) => {
    const item = cart.value.find(i => i.ID_Producto === product.ID_Producto);
    if (item) {
        if (item.quantity < product.Cantidad) item.quantity++;
    } else {
        cart.value.push({ ...product, quantity: 1 });
    }
    search.value = ''; // Limpiar buscador
};

const removeFromCart = (index) => cart.value.splice(index, 1);

const totalCart = computed(() => 
    cart.value.reduce((acc, item) => acc + (item.Precio_Venta * item.quantity), 0)
);

// VENTA: ENVÍO DE DATOS AL BACKEND
const form = useForm({
    id_cliente: 1, 
    pago_con: 0,
    id_session: props.activeSession?.ID_Session,
    items: [], 
});

const processSale = () => {
    if (paymentAmount.value < totalCart.value) {
        alert("El pago es insuficiente");
        return;
    }
    form.pago_con = paymentAmount.value;
    form.items = cart.value; 
    form.id_session = props.activeSession?.ID_Session;

    form.post(route('ventas.store'), {
        onSuccess: () => {
            lastSaleResponse.value = {
                total: totalCart.value,
                pago: paymentAmount.value,
                cambio: paymentAmount.value - totalCart.value,
                items: [...cart.value]
            };
            showTicketModal.value = true;
            cart.value = [];
            paymentAmount.value = 0;
        },
    });
};
</script>

<template>
    <Head title="Ventas - Tienda" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Punto de Venta (Caja Abierta: #{{ activeSession?.ID_Session }})</h2>
        </template>

        <div class="py-12 bg-gray-100 min-h-screen">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex gap-6">
                
                <div class="w-2/3 bg-white p-6 rounded-lg shadow-sm">
                    <div class="relative mb-6">
                        <input 
                            v-model="search"
                            type="text" 
                            placeholder="Buscar producto por nombre..." 
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500"
                        />
                        <ul v-if="filteredProducts.length > 0" class="absolute z-10 w-full bg-white border mt-1 rounded-md shadow-lg">
                            <li 
                                v-for="p in filteredProducts" :key="p.ID_Producto"
                                @click="addToCart(p)"
                                class="p-3 hover:bg-blue-50 cursor-pointer flex justify-between border-b"
                            >
                                <span>{{ p.Nombre_Producto }}</span>
                                <span class="font-bold text-blue-600">${{ p.Precio_Venta }}</span>
                            </li>
                        </ul>
                    </div>

                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b text-gray-600 uppercase text-sm">
                                <th class="py-2">Producto</th>
                                <th class="py-2 text-center">Cant.</th>
                                <th class="py-2 text-right">Precio</th>
                                <th class="py-2 text-right">Subtotal</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item, index) in cart" :key="item.ID_Producto" class="border-b">
                                <td class="py-4">{{ item.Nombre_Producto }}</td>
                                <td class="py-4 text-center">{{ item.quantity }}</td>
                                <td class="py-4 text-right">${{ item.Precio_Venta }}</td>
                                <td class="py-4 text-right">${{ (item.Precio_Venta * item.quantity).toFixed(2) }}</td>
                                <td class="text-right">
                                    <button @click="removeFromCart(index)" class="text-red-500 hover:text-red-700">✕</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="w-1/3 bg-white p-6 rounded-lg shadow-sm h-fit">
                    <h3 class="text-lg font-bold mb-4 border-b pb-2 text-gray-700">Resumen de Cobro</h3>
                    <div class="flex justify-between text-2xl font-black text-gray-800 mb-6">
                        <span>TOTAL:</span>
                        <span>${{ totalCart.toFixed(2) }}</span>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Efectivo Recibido:</label>
                        <input 
                            v-model="paymentAmount"
                            type="number" 
                            class="w-full text-3xl font-mono text-right border-gray-300 rounded-md focus:ring-green-500"
                        />
                    </div>

                    <button 
                        @click="processSale"
                        :disabled="cart.length === 0 || paymentAmount < totalCart"
                        class="w-full py-4 rounded-lg font-bold text-white transition shadow-md"
                        :class="cart.length > 0 && paymentAmount >= totalCart ? 'bg-green-600 hover:bg-green-700' : 'bg-gray-300 cursor-not-allowed'"
                    >
                        PAGAR Y GENERAR TICKET
                    </button>
                </div>

            </div>
        </div>

        <div v-if="showTicketModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4">
            <div class="bg-white p-8 rounded-lg shadow-2xl w-96 text-center font-mono">
                <h2 class="text-xl font-bold mb-2 uppercase">LA MODERNA</h2>
                <p class="text-xs mb-4">Comprobante de Venta</p>
                <hr class="border-dashed mb-4">
                <div v-for="item in lastSaleResponse.items" :key="item.ID_Producto" class="flex justify-between text-sm mb-1">
                    <span>{{ item.quantity }}x {{ item.Nombre_Producto }}</span>
                    <span>${{ (item.quantity * item.Precio_Venta).toFixed(2) }}</span>
                </div>
                <hr class="border-dashed my-4">
                <div class="flex justify-between font-bold text-lg">
                    <span>TOTAL:</span> <span>${{ lastSaleResponse.total.toFixed(2) }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span>PAGO CON:</span> <span>${{ lastSaleResponse.pago.toFixed(2) }}</span>
                </div>
                <div class="flex justify-between font-bold text-xl mt-2 bg-yellow-100">
                    <span>CAMBIO:</span> <span>${{ lastSaleResponse.cambio.toFixed(2) }}</span>
                </div>
                <button @click="showTicketModal = false" class="mt-8 bg-black text-white w-full py-2 rounded">CERRAR</button>
            </div>
        </div>
    </AuthenticatedLayout>
</template>