<script setup>
import { ref } from 'vue';
import NavLink from '@/Components/NavLink.vue';





</script>

<template>
    <nav class="bg-indigo-800
                py-4 text-white fixed
                w-full top-0 left-0 px-4 font-bold shadow-md">
        <div class="flex justify-between">
            <p>TASK MONITORING SYSTEM</p>

        </div>



    </nav>
    <aside
        class="sidebar fixed top-14 h-full pt-8 w-64 -translate-x-full transform bg-white p-4 transition-transform duration-150 ease-in md:translate-x-0 md:shadow-md">

        <img src="/img/tesda-animation.gif" class="h-28 mx-auto" alt="TESDA Lingap">

        <div class="my-2 w-full text-center">
            <p class="font-bold text-blue-800">{{ $page.props.auth.user.office }}</p>
            <p class="font-normal text-gray-800 text-sm">{{ $page.props.auth.user.name }}</p>
        </div>

        <hr>

        <!-- Navigation Links -->

        <div class="px-2 py-4 flex flex-col text-center space-y-4">


            <NavLink :href="route('dashboard')" :active="route().current('dashboard')">
                <font-awesome-icon icon="fa-solid fa-handshake-angle" class="mr-2" />
                Dashboard
            </NavLink>

            <NavLink :href="route('profile.edit')" :active="route().current('profile.edit')">
                <font-awesome-icon icon="fa-solid fa-user" class="mr-2" />
                Profile
            </NavLink>


            

        </div>
        <hr>
        <div class="bottom-40 p-4 w-full px-2 py-4">
            <NavLink :href="route('logout')" method="post" as="button">
                <font-awesome-icon icon="fa-solid fa-right-from-bracket" class="mr-2" />
                Logout
            </NavLink>


        </div>
        <div class="absolute bottom-20 flex space-x-2">
            <img src="/img/tesda-lingap.png" class="h-20" alt="TESDA Lingap">
            <img src="/img/bagong-pilipinas.png" class="h-20" alt="Bagong Pilipinas">
        </div>
    </aside>



    <main class="ml-64 mt-8 bg-gray-100 min-h-screen">
        <slot />
    </main>

    <footer class="p-4 text-center bg-indigo-100 text-indigo-500 text-xs">
    <p>Information and Communication Technology Office (ICTO)</p>
    <p>Technical Education and Skills Development Authority (TESDA)</p>
</footer>


</template>
