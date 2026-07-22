import api from './axios';
import type { User } from '../types';

export const login = (email: string, password: string) =>
  api.post<{ token: string; user: User }>('/login', { email, password });

export const logout = () => api.post('/logout');

export const me = () => api.get<{ data: User }>('/me');
