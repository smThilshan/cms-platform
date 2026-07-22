import api from './axios';
import type { MenuItem } from '../types';

export const getPublicMenu = () =>
  api.get<{ data: MenuItem[] }>('/menu');

export const getAdminMenu = () =>
  api.get<{ data: MenuItem[] }>('/admin/menu-items');

export const createMenuItem = (data: { title: string; parent_id?: number | null; order?: number }) =>
  api.post<{ data: MenuItem }>('/admin/menu-items', data);

export const updateMenuItem = (id: number, data: { title?: string; parent_id?: number | null; order?: number }) =>
  api.post<{ data: MenuItem }>(`/admin/menu-items/${id}`, data);

export const deleteMenuItem = (id: number) =>
  api.delete(`/admin/menu-items/${id}`);

export const reorderMenuItems = (items: { id: number; order: number; parent_id: number | null }[]) =>
  api.post('/admin/menu-items/reorder', { items });
