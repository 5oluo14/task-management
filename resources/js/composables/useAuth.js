
import { useApi } from './useApi';

export function useAuth() {
    const api = useApi();

    const register = async (userData) => {
        const response = await api.post('auth/register', userData);
        if (response.data.access_token) {
            localStorage.setItem('token', response.data.access_token);
            let user = {
                id: response.data.id,
                name: response.data.name,
                email: response.data.email

            };
            localStorage.setItem('user', user);
        }
        return response;
    };

    const login = async (credentials) => {
        const response = await api.post('auth/login', credentials);
        if (response.data.access_token) {
            localStorage.setItem('token', response.data.access_token);
            let user = {
                id: response.data.id,
                name: response.data.name,
                email: response.data.email
            };
            localStorage.setItem('user', user);
        }
        return response;
    };

    const logout = async () => {
        try {
            await api.post('/logout');
        } finally {
            localStorage.removeItem('token');
            localStorage.removeItem('user');
        }
    };

    const getUser = () => {
        const userJson = localStorage.getItem('user');
        return userJson ? JSON.parse(userJson) : null;
    };

    const isAuthenticated = () => {
        return !!localStorage.getItem('token');
    };

    return {
        register,
        login,
        logout,
        getUser,
        isAuthenticated
    };
}
