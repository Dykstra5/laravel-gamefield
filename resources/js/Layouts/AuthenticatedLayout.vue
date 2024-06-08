<script setup>
import { ref, computed } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import TextInput from '@/Components/TextInput.vue';

const showingNavigationDropdown = ref(false);
const keyword = ref('');

const authUser = usePage().props.auth.user;

const isAdmin = computed(() => authUser && authUser.role_id === 1);

function search() {
    if (keyword.value != '') {
        router.get(route('search', keyword.value));
    }
}

</script>

<template>
    <div class="h-full overflow-hidden flex flex-col" :class="[
        isAdmin ? 'dark:bg-emerald-700' : 'dark:bg-red-950'
    ]">
        <nav class="bg-white border-b dark:border-gray-700" :class="[
            isAdmin ? 'dark:bg-emerald-500' : 'dark:bg-rose-950'
        ]">
            <!-- Primary Navigation Menu -->
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <!-- Logo -->
                        <div class="shrink-0 flex items-center">
                            <Link :href="route('dashboard')">
                            <ApplicationLogo
                                class="block size-12 w-auto fill-current text-gray-800 dark:text-gray-200" />
                            </Link>
                        </div>
                    </div>

                    <div class="flex items-center w-full ml-6 sm:ml-16">
                        <TextInput @keyup.enter="search" placeholder="Buscar..." class="w-full" v-model="keyword" />
                    </div>

                    <div class="hidden sm:flex sm:items-center sm:ms-6">
                        <!-- Settings Dropdown -->
                        <div class="ms-3 relative">
                            <Dropdown v-if="authUser" align="right" width="48">
                                <template #trigger>
                                    <span class="inline-flex rounded-md">
                                        <button type="button"
                                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150"
                                            :class="[
                                                isAdmin ? 'bg-white dark:bg-emerald-950' : 'bg-white dark:bg-rose-950'
                                            ]">
                                            {{ authUser.name }}{{ isAdmin ? ' - Admin' : '' }}

                                            <svg class="ms-2 -me-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </span>
                                </template>

                                <template #content>
                                    <DropdownLink :href="route('profile', { username: authUser.username })" :class="[
                                        isAdmin ? 'dark:bg-emerald-700 text-gray-900 hover:dark:bg-emerald-500' : ''
                                    ]"> Profile
                                    </DropdownLink>
                                    <DropdownLink :href="route('logout')" method="post" as="button" :class="[
                                        isAdmin ? 'dark:bg-emerald-700 text-gray-900 hover:dark:bg-emerald-500' : ''
                                    ]">
                                        Log Out
                                    </DropdownLink>
                                </template>
                            </Dropdown>
                            <div v-else>
                                <Link :href="route('dashboard')">
                                Iniciar Sesion
                                </Link>
                            </div>
                        </div>
                    </div>

                    <!-- Hamburger -->
                    <div class="-me-2 flex items-center sm:hidden">
                        <button @click="showingNavigationDropdown = !showingNavigationDropdown"
                            class="inline-flex items-center justify-center p-2 rounded-md text-gray-800 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out" 
                            :class="[
                                isAdmin ? 'dark:hover:bg-emerald-600 dark:focus:bg-emerald-600' : 'dark:hover:bg-gray-900 dark:focus:bg-gray-900'
                            ]">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path :class="{
                                    hidden: showingNavigationDropdown,
                                    'inline-flex': !showingNavigationDropdown,
                                }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16" />
                                <path :class="{
                                    hidden: !showingNavigationDropdown,
                                    'inline-flex': showingNavigationDropdown,
                                }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Responsive Navigation Menu -->
            <div :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }" class="sm:hidden">
                <!-- Responsive Settings Options -->
                <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
                    <template v-if="authUser">
                        <div class="px-4">
                            <div class="font-medium text-base text-gray-800 dark:text-gray-200">
                                {{ authUser.name }}
                            </div>
                            <div class="font-medium text-sm text-gray-500">{{ authUser.email }}</div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <ResponsiveNavLink :href="route('profile', { username: authUser.username })" :class="[
                                isAdmin ? 'dark:bg-emerald-500 hover:dark:bg-emerald-700' : ''
                            ]">
                                <span :class="[
                                    isAdmin ? 'text-gray-900' : ''
                                ]">Perfil</span>
                            </ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('logout')" method="post" as="button" :class="[
                                isAdmin ? 'dark:bg-emerald-500 hover:dark:bg-emerald-700' : ''
                            ]">
                                <span :class="[
                                    isAdmin ? 'text-gray-900' : ''
                                ]">Log out</span>
                            </ResponsiveNavLink>
                        </div>
                    </template>
                    <template v-else>
                        Iniciar Sesión
                    </template>
                </div>
            </div>
        </nav>

        <!-- Page Heading -->
        <header class="bg-white dark:bg-gray-800 shadow" v-if="$slots.header">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <slot name="header" />
            </div>
        </header>

        <!-- Page Content -->
        <main class="flex-1 overflow-auto">
            <slot />
        </main>
    </div>
</template>
