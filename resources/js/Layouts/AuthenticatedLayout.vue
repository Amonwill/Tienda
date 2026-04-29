<script setup>
import { ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';

const showingNavigationDropdown = ref(false);
</script>

<template>
    <div>
        <div class="min-h-screen bg-gray-100">
            <nav class="border-b border-gray-100 bg-white">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="flex h-16 justify-between">
                        <div class="flex">
                            <div class="flex shrink-0 items-center">
                                <Link :href="route('dashboard')">
                                    <img 
                                        src="/images/logo_la_moderna.png" 
                                        alt="La Moderna" 
                                        class="block h-12 w-auto transition hover:scale-105 duration-300 drop-shadow-sm" 
                                    />
                                </Link>
                            </div>

                            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                                <NavLink :href="route('dashboard')" :active="route().current('dashboard')">
                                    Dashboard
                                </NavLink>
                                <NavLink :href="route('Metricas.index')" :active="route().current('Metricas.index')">
                                    Métricas
                                </NavLink>
                                <NavLink :href="route('productos.index')" :active="route().current('productos.index')">
                                    Productos
                                </NavLink>
                                <NavLink :href="route('proveedores.index')" :active="route().current('proveedores.index')">
                                    Proveedores
                                </NavLink>
                                <NavLink :href="route('ventas.index')" :active="route().current('ventas.index')">
                                    Ventas
                                </NavLink>
                                <NavLink :href="route('caja.index')" :active="route().current('caja.index')">
                                    Caja
                                </NavLink>
                            </div>
                        </div>

                        <div class="hidden sm:ms-6 sm:flex sm:items-center space-x-4">
                            <div class="relative">
                                <Dropdown align="right" width="64">
                                    <template #trigger>
                                        <button class="relative p-2 text-gray-400 hover:text-gray-600 transition focus:outline-none">
                                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                            </svg>
                                            <span v-if="$page.props.alertas && $page.props.alertas.length > 0" 
                                                class="absolute top-0 right-0 block h-4 w-4 rounded-full bg-red-600 text-[10px] text-white font-black flex items-center justify-center border-2 border-white">
                                                {{ $page.props.alertas.length }}
                                            </span>
                                        </button>
                                    </template>

                                    <template #content>
                                        <div class="p-4 text-xs font-black uppercase text-gray-400 border-b italic tracking-widest">Bandeja de Alertas</div>
                                        
                                        <div v-if="$page.props.alertas && $page.props.alertas.length > 0" class="max-h-64 overflow-y-auto">
                                            <div v-for="alerta in $page.props.alertas" :key="alerta.ID_Alerta" 
                                                class="p-4 border-b last:border-0 hover:bg-gray-50 transition">
                                                <p class="text-[9px] font-black uppercase tracking-tighter" :class="alerta.Tipo_Alerta === 'Inventario' ? 'text-red-600' : 'text-orange-500'">
                                                    {{ alerta.Tipo_Alerta }}
                                                </p>
                                                <p class="text-xs text-gray-700 mt-1 font-medium leading-tight">{{ alerta.Mensaje }}</p>
                                            </div>
                                        </div>
                                        <div v-else class="p-8 text-center text-gray-400 italic text-xs">
                                            No hay alertas pendientes.
                                        </div>
                                    </template>
                                </Dropdown>
                            </div>

                            <div class="relative">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button type="button" class="inline-flex items-center rounded-md border border-transparent bg-white px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out hover:text-gray-700 focus:outline-none">
                                                {{ $page.props.auth.user.name }}
                                                <svg class="-me-0.5 ms-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <DropdownLink :href="route('profile.edit')"> Profile </DropdownLink>
                                        <DropdownLink :href="route('logout')" method="post" as="button"> Log Out </DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                        <div class="-me-2 flex items-center sm:hidden">
                            <button @click="showingNavigationDropdown = !showingNavigationDropdown" class="inline-flex items-center justify-center rounded-md p-2 text-gray-400 transition duration-150 ease-in-out hover:bg-gray-100 hover:text-gray-500 focus:bg-gray-100 focus:text-gray-500 focus:outline-none">
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path :class="{'hidden': showingNavigationDropdown, 'inline-flex': !showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                    <path :class="{'hidden': !showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div :class="{'block': showingNavigationDropdown, 'hidden': !showingNavigationDropdown}" class="sm:hidden">
                    <div class="space-y-1 pb-3 pt-2">
                        <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')"> Dashboard </ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('Metricas.index')" :active="route().current('Metricas.index')"> Métricas </ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('productos.index')" :active="route().current('productos.index')"> Productos </ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('proveedores.index')" :active="route().current('proveedores.index')"> Proveedores </ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('ventas.index')" :active="route().current('ventas.index')"> Ventas </ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('caja.index')" :active="route().current('caja.index')"> Caja </ResponsiveNavLink>
                    </div>

                    <div class="border-t border-gray-200 pb-1 pt-4">
                        <div class="px-4">
                            <div class="text-base font-medium text-gray-800">{{ $page.props.auth.user.name }}</div>
                            <div class="text-sm font-medium text-gray-500">{{ $page.props.auth.user.email }}</div>
                        </div>
                        <div class="mt-3 space-y-1">
                            <ResponsiveNavLink :href="route('profile.edit')"> Profile </ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('logout')" method="post" as="button"> Log Out </ResponsiveNavLink>
                        </div>
                    </div>
                </div>
            </nav>

            <header class="bg-white shadow" v-if="$slots.header">
                <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <main>
                <slot />
            </main>
        </div>
    </div>
</template>