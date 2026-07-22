import api from './axios';
import type { Privilege } from '../types';

export const getPrivileges = () =>
  api.get<{ data: Privilege[] }>('/admin/privileges');
