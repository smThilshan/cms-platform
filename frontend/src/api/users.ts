import api from './axios';
import type { User, PaginatedResponse } from '../types';

export const getUsers = (page = 1) =>
  api.get<PaginatedResponse<User>>('/admin/users', { params: { page } });

export const getUser = (id: number) =>
  api.get<{ data: User }>(`/admin/users/${id}`);

export const createUser = (data: { name: string; email: string; password: string; role_id: number }) =>
  api.post<{ data: User }>('/admin/users', data);

export const updateUser = (id: number, data: { name?: string; email?: string; password?: string; role_id?: number }) =>
  api.post<{ data: User }>(`/admin/users/${id}`, data);

export const deleteUser = (id: number) =>
  api.delete(`/admin/users/${id}`);
