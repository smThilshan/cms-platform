import api from './axios';
import type { Page, PaginatedResponse } from '../types';

export const getPages = (page = 1) =>
  api.get<PaginatedResponse<Page>>('/admin/pages', { params: { page } });

export const getPage = (id: number) =>
  api.get<{ data: Page }>(`/admin/pages/${id}`);

export const createPage = (data: FormData) =>
  api.post<{ data: Page }>('/admin/pages', data);

export const updatePage = (id: number, data: FormData) =>
  api.post<{ data: Page }>(`/admin/pages/${id}`, data);

export const deletePage = (id: number) =>
  api.delete(`/admin/pages/${id}`);

export const getPublicPage = (slug: string) =>
  api.get<{ data: Page }>(`/pages/${slug}`);
