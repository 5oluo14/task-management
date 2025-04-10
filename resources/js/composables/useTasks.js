import { useApi } from './useApi';

export function useTasks() {
    const api = useApi();

    const getTasks = async (status = '') => {
        const params = status ? { status } : {};
        const response = await api.get('/tasks', { params });
        return response.data || [];
    };

    const getTask = async (id) => {
        const response = await api.get(`/tasks/${id}`);
        return response.data;
    };

    const createTask = async (taskData) => {
        const response = await api.post('/tasks', taskData);
        return response.data;
    };

    const updateTask = async (id, taskData) => {
        const response = await api.put(`/tasks/${id}`, taskData);
        return response.data;
    };

    const deleteTask = async (id) => {
        await api.delete(`/tasks/${id}`);
        return true;
    };

    return {
        getTasks,
        getTask,
        createTask,
        updateTask,
        deleteTask
    };
}
