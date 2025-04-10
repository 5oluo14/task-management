<template>
    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <div class="flex justify-between items-center bg-gray-50 px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Your Tasks</h3>
            <div class="flex space-x-2">
                <select v-model="filter" @change="fetchTasks"
                    class="mt-1 block pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                    <option value="">All Tasks</option>
                    <option value="pending">Pending</option>
                    <option value="completed">Completed</option>
                </select>
                <button @click="showCreateModal = true"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Add Task
                </button>
            </div>
        </div>
        <ul class="divide-y divide-gray-200">
            <li v-if="loading" class="px-4 py-4 sm:px-6 text-center">
                <svg class="animate-spin h-6 w-6 text-indigo-500 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                    </path>
                </svg>
            </li>
            <li v-else-if="tasks.length === 0" class="px-4 py-4 sm:px-6 text-center text-gray-500">
                No tasks found. Create a new task to get started.
            </li>
            <li v-for="task in tasks" :key="task.id" class="px-4 py-4 sm:px-6 hover:bg-gray-50">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input :id="`task-${task.id}`" :checked="task.status === 'completed'"
                            @change="toggleTaskStatus(task)" type="checkbox"
                            class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <label :for="`task-${task.id}`"
                            :class="{ 'line-through text-gray-500': task.status === 'completed' }"
                            class="ml-3 block text-sm font-medium">
                            {{ task.title }}
                        </label>
                    </div>
                    <div class="flex space-x-2">
                        <button @click="editTask(task)" class="text-indigo-600 hover:text-indigo-900">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </button>
                        <button @click="deleteTask(task.id)" class="text-red-600 hover:text-red-900">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                </div>
                <div v-if="task.description" class="mt-2 text-sm text-gray-500">
                    {{ task.description }}
                </div>
            </li>
        </ul>

        <!-- Create/Edit Task Modal -->
        <div v-if="showCreateModal || showEditModal"
            class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-10">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                    {{ showEditModal ? 'Edit Task' : 'Create New Task' }}
                </h3>
                <form @submit.prevent="showEditModal ? updateTask() : createTask()">
                    <div class="mb-4">
                        <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                        <input v-model="taskForm.title" type="text" name="title" id="title" required
                            class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    </div>
                    <div class="mb-4">
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea v-model="taskForm.description" name="description" id="description" rows="3"
                            class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>
                    </div>
                    <div v-if="showEditModal" class="mb-4">
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select v-model="taskForm.status" id="status" name="status"
                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            <option value="pending">Pending</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" @click="closeModal"
                            class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Cancel
                        </button>
                        <button type="submit"
                            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ showEditModal ? 'Update' : 'Create' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useTasks } from '../../composables/useTasks';

const { getTasks, createTask: apiCreateTask, updateTask: apiUpdateTask, deleteTask: apiDeleteTask } = useTasks();

const tasks = ref([]);
const loading = ref(true);
const filter = ref('');
const showCreateModal = ref(false);
const showEditModal = ref(false);
const taskForm = ref({
    title: '',
    description: '',
    status: 'pending'
});
const currentTaskId = ref(null);

onMounted(() => {
    fetchTasks();
});

const fetchTasks = async () => {
    loading.value = true;
    try {
        tasks.value = await getTasks(filter.value);
    } catch (error) {
        console.error('Error fetching tasks:', error);
    } finally {
        loading.value = false;
    }
};

const createTask = async () => {
    try {
        await apiCreateTask(taskForm.value);
        closeModal();
        fetchTasks();
    } catch (error) {
        console.error('Error creating task:', error);
    }
};

const editTask = (task) => {
    currentTaskId.value = task.id;
    taskForm.value = {
        title: task.title,
        description: task.description || '',
        status: task.status
    };
    showEditModal.value = true;
};

const updateTask = async () => {
    try {
        await apiUpdateTask(currentTaskId.value, taskForm.value);
        closeModal();
        fetchTasks();
    } catch (error) {
        console.error('Error updating task:', error);
    }
};

const deleteTask = async (taskId) => {
    if (confirm('Are you sure you want to delete this task?')) {
        try {
            await apiDeleteTask(taskId);
            fetchTasks();
        } catch (error) {
            console.error('Error deleting task:', error);
        }
    }
};

const toggleTaskStatus = async (task) => {
    try {
        await apiUpdateTask(task.id, {
            status: task.status === 'completed' ? 'pending' : 'completed'
        });
        fetchTasks();
    } catch (error) {
        console.error('Error toggling task status:', error);
    }
};

const closeModal = () => {
    showCreateModal.value = false;
    showEditModal.value = false;
    taskForm.value = {
        title: '',
        description: '',
        status: 'pending'
    };
    currentTaskId.value = null;
};
</script>
