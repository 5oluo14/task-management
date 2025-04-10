<template>
    <div class="min-h-screen bg-gray-100">
        <nav class="bg-white shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="flex-shrink-0 flex items-center">
                            <h1 class="text-xl font-bold text-indigo-600">Task Manager</h1>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <div class="ml-3 relative">
                            <div>
                                <span class="text-gray-700 mr-2">{{ user?.name }}</span>
                                <button @click="logout" class="text-indigo-600 hover:text-indigo-900">
                                    Sign out
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <div class="py-10">
            <header>
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <h1 class="text-3xl font-bold leading-tight text-gray-900">Dashboard</h1>
                </div>
            </header>
            <main>
                <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
                    <TaskList />
                </div>
            </main>
        </div>
    </div>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import { useRouter } from 'vue-router';
import { useAuth } from '../composables/useAuth';
import TaskList from '../components/Tasks/TaskList.vue';

const router = useRouter();
const { logout: authLogout, getUser } = useAuth();
const user = ref(null);

onMounted(async () => {
    user.value = getUser();
    if (!user.value) {
        router.push({ name: 'login' });
    }
});

const logout = async () => {
    try {
        await authLogout();
        router.push({ name: 'login' });
    } catch (error) {
        console.error('Error logging out:', error);
    }
};
</script>
