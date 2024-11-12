// resources/js/stores/auth.js
import { defineStore } from 'pinia';
import axios from 'axios';

export const useAuthStore = defineStore('auth', {
    state: () => ({
        user: null,
        token: localStorage.getItem('token'),
    }),

    getters: {
        isAuthenticated: (state) => !!state.token,
        isAdmin: (state) => state.user?.role === 'admin',
    },

    actions: {
        async login(credentials) {
            try {
                const response = await axios.post('/api/v1/login', credentials);
                this.token = response.data.token;
                this.user = response.data.user;
                localStorage.setItem('token', this.token);
                axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
                return response;
            } catch (error) {
                throw error;
            }
        },

        async register(userData) {
            try {
                const response = await axios.post('/api/v1/register', userData);
                this.token = response.data.token;
                this.user = response.data.user;
                localStorage.setItem('token', this.token);
                axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
                return response;
            } catch (error) {
                throw error;
            }
        },

        async logout() {
            try {
                await axios.post('/api/v1/logout');
                this.token = null;
                this.user = null;
                localStorage.removeItem('token');
                delete axios.defaults.headers.common['Authorization'];
            } catch (error) {
                throw error;
            }
        },

        async fetchUser() {
            try {
                const response = await axios.get('/api/v1/user');
                this.user = response.data;
                return response;
            } catch (error) {
                this.token = null;
                this.user = null;
                localStorage.removeItem('token');
                throw error;
            }
        },
    },
});