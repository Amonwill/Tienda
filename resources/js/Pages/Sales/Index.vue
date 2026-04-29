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
    search.value = ''; 
};

// --- NUEVAS FUNCIONES DE CONTROL DE CANTIDAD ---
const increaseQuantity = (index) => {
    const item = cart.value[index];
    if (item.quantity < item.Cantidad) {
        item.quantity++;
    } else {
        alert(`Stock máximo alcanzado (${item.Cantidad} disponibles)`);
    }
};

const decreaseQuantity = (index) => {
    if (cart.value[index].quantity > 1) {
        cart.value[index].quantity--;
    } else {
        removeFromCart(index);
    }
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
            <h2 class="font-black text-xl text-gray-800 uppercase italic tracking-tighter">
                Punto de Venta (Caja Abierta: #{{ activeSession?.ID_Session || 'SIN SESIÓN' }})
            </h2>
        </template>

        <div class="py-12 bg-gray-50 min-h-screen">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex gap-6">
                
                <div class="w-2/3 bg-white p-8 rounded-[2.5rem] shadow-xl border border-gray-100">
                    <div class="relative mb-8">
                        <input 
                            v-model="search"
                            type="text" 
                            placeholder="Buscar producto por nombre..." 
                            class="w-full border-gray-200 rounded-2xl p-4 shadow-sm focus:ring-blue-500 focus:border-blue-500 font-bold"
                        />
                        <ul v-if="filteredProducts.length > 0" class="absolute z-10 w-full bg-white border mt-2 rounded-2xl shadow-2xl overflow-hidden">
                            <li 
                                v-for="p in filteredProducts" :key="p.ID_Producto"
                                @click="addToCart(p)"
                                class="p-4 hover:bg-blue-50 cursor-pointer flex justify-between border-b last:border-0 transition"
                            >
                                <span class="font-bold text-gray-700 uppercase italic">{{ p.Nombre_Producto }}</span>
                                <span class="font-black text-blue-600 font-mono">${{ p.Precio_Venta }}</span>
                            </li>
                        </ul>
                    </div>

                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-gray-400 uppercase text-[10px] font-black tracking-widest border-b border-gray-100">
                                <th class="py-4">Producto</th>
                                <th class="py-4 text-center">Cantidad</th>
                                <th class="py-4 text-right">Precio</th>
                                <th class="py-4 text-right">Subtotal</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item, index) in cart" :key="item.ID_Producto" class="border-b border-gray-50 last:border-0 group">
                                <td class="py-5">
                                    <p class="font-black text-gray-800 uppercase italic text-sm">{{ item.Nombre_Producto }}</p>
                                    <p class="text-[9px] text-gray-400 font-bold uppercase tracking-tighter">Disponible: {{ item.Cantidad }}</p>
                                </td>
                                
                                <td class="py-5">
                                    <div class="flex items-center justify-center gap-3">
                                        <button @click="decreaseQuantity(index)" 
                                            class="h-7 w-7 rounded-lg bg-gray-100 flex items-center justify-center font-black hover:bg-gray-200 transition active:scale-90">
                                            -
                                        </button>
                                        <span class="font-mono font-black text-lg w-6 text-center text-blue-600">{{ item.quantity }}</span>
                                        <button @click="increaseQuantity(index)" 
                                            class="h-7 w-7 rounded-lg bg-blue-600 text-white flex items-center justify-center font-black hover:bg-blue-700 transition active:scale-90 shadow-md">
                                            +
                                        </button>
                                    </div>
                                </td>

                                <td class="py-5 text-right font-mono font-bold text-gray-500">${{ item.Precio_Venta }}</td>
                                <td class="py-5 text-right font-mono font-black text-gray-900">${{ (item.Precio_Venta * item.quantity).toFixed(2) }}</td>
                                <td class="py-5 text-right">
                                    <button @click="removeFromCart(index)" class="text-gray-300 hover:text-red-500 transition-colors px-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="w-1/3 space-y-6">
                    <div class="bg-white p-8 rounded-[2.5rem] shadow-xl border border-gray-100 h-fit">
                        <h3 class="text-xs font-black uppercase tracking-[0.2em] text-gray-400 mb-6 italic border-b pb-4">Resumen de Cobro</h3>
                        
                        <div class="flex justify-between items-baseline mb-8">
                            <span class="text-sm font-black text-gray-400 uppercase tracking-widest">Total a Pagar</span>
                            <span class="text-5xl font-black text-gray-900 font-mono tracking-tighter">${{ totalCart.toFixed(2) }}</span>
                        </div>

                        <div class="mb-8 p-6 bg-gray-50 rounded-3xl border border-gray-100">
                            <label class="block text-[10px] font-black uppercase text-gray-400 mb-3 tracking-widest">Efectivo Recibido</label>
                            <div class="flex items-center">
                                <span class="text-2xl font-black text-gray-400 mr-2 font-mono">$</span>
                                <input 
                                    v-model="paymentAmount"
                                    type="number" 
                                    class="w-full text-4xl font-mono font-black text-right bg-transparent border-none focus:ring-0 p-0 text-green-600"
                                    placeholder="0.00"
                                />
                            </div>
                        </div>

                        <button 
                            @click="processSale"
                            :disabled="cart.length === 0 || paymentAmount < totalCart"
                            class="w-full py-5 rounded-2xl font-black text-xs uppercase tracking-[0.2em] transition-all shadow-xl flex items-center justify-center gap-2"
                            :class="cart.length > 0 && paymentAmount >= totalCart 
                                ? 'bg-green-600 text-white hover:bg-green-700 hover:-translate-y-1 active:translate-y-0' 
                                : 'bg-gray-200 text-gray-400 cursor-not-allowed'"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            Pagar y Generar Ticket
                        </button>
                    </div>
                </div>

            </div>
        </div>

        <div v-if="showTicketModal" class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm flex items-center justify-center p-4 z-50">
            <div class="bg-white p-10 rounded-[3rem] shadow-2xl w-[26rem] text-center font-mono border-t-[12px] border-green-500">
                <div class="text-4xl mb-2">🏪</div>
                <h2 class="text-2xl font-black mb-1 uppercase italic tracking-tighter">LA MODERNA</h2>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-6">Comprobante Fiscal de Venta</p>
                
                <div class="border-y border-dashed border-gray-200 py-6 my-6 space-y-2">
                    <div v-for="item in lastSaleResponse.items" :key="item.ID_Producto" class="flex justify-between text-xs font-bold uppercase italic">
                        <span class="text-left w-3/4">{{ item.quantity }}x {{ item.Nombre_Producto }}</span>
                        <span class="text-right w-1/4">${{ (item.quantity * item.Precio_Venta).toFixed(2) }}</span>
                    </div>
                </div>

                <div class="space-y-2 mb-8">
                    <div class="flex justify-between font-black text-2xl tracking-tighter">
                        <span class="text-gray-400 uppercase text-xs self-center">Total</span> 
                        <span>${{ lastSaleResponse.total.toFixed(2) }}</span>
                    </div>
                    <div class="flex justify-between text-[10px] font-bold text-gray-400 uppercase">
                        <span>Efectivo</span> <span>${{ lastSaleResponse.pago.toFixed(2) }}</span>
                    </div>
                    <div class="flex justify-between font-black text-3xl mt-4 p-4 bg-green-50 rounded-2xl text-green-700">
                        <span class="text-[10px] uppercase self-center tracking-widest">Cambio</span> 
                        <span>${{ lastSaleResponse.cambio.toFixed(2) }}</span>
                    </div>
                </div>

                <button @click="showTicketModal = false" class="bg-gray-900 text-white w-full py-4 rounded-2xl font-black uppercase text-xs tracking-widest hover:bg-black transition shadow-lg">
                    Finalizar y Nueva Venta
                </button>
            </div>
        </div>
    </AuthenticatedLayout>
</template>