import api from './axios';
import type { Role } from '../types';

export const getRoles = () =>
  api.get<{ data: Role[] }>('/admin/roles');

export const getRole = (id: number) =>
  api.get<{ data: Role }>(`/admin/roles/${id}`);

export const createRole = (data: { name: string; slug: string; privilege_ids?: number[] }) =>
  api.post<{ data: Role }>('/admin/roles', data);

export const updateRole = (id: number, data: { name?: string; slug?: string; privilege_ids?: number[] }) =>
  api.post<{ data: Role }>(`/admin/roles/${id}`, data);

export const deleteRole = (id: number) =>
  api.delete(`/admin/roles/${id}`);
