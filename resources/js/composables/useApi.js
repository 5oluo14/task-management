import axios from 'axios';

export function useApi() {
    const api = axios.create({
        baseURL: '/api/v1/',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    });

    api.interceptors.request.use(config => {
        const token = localStorage.getItem('token');
        if (token) {
            config.headers.Authorization = `Bearer ${token}`;
        }
        return config;
    });

    api.interceptors.response.use(
        response => response,
        error => {
            const { status } = error.response || {};

            if (status === 401) {
                localStorage.removeItem('token');
                localStorage.removeItem('user');
                if (!['/login', '/register'].includes(window.location.pathname)) {
                    window.location.href = '/login';
                }
            }

            return Promise.reject(error);
        }
    );

    const request = async (method, url, data = null, config = {}) => {
        try {
            const response = await api({
                method,
                url,
                data,
                ...config
            });
            return response.data;
        } catch (error) {

            let errorMessage = 'An unexpected error occurred';

            if (error.response) {

                const { data, status } = error.response;

                if (status === 422 && data.errors) {
                    const messages = Object.values(data.errors).flat();
                    errorMessage = messages.join(', ');
                } else if (data.message) {
                    errorMessage = data.message;
                }
            } else if (error.message) {
                errorMessage = error.message;
            }

            throw new Error(errorMessage);
        }
    };

    return {
        get: (url, config) => request('get', url, null, config),
        post: (url, data, config) => request('post', url, data, config),
        put: (url, data, config) => request('put', url, data, config),
        patch: (url, data, config) => request('patch', url, data, config),
        delete: (url, config) => request('delete', url, null, config)
    };
}
