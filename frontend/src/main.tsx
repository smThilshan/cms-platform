import { createRoot } from 'react-dom/client';
import { BrowserRouter, Routes, Route, Navigate } from 'react-router-dom';
import { QueryClient, QueryClientProvider } from '@tanstack/react-query';
import { ReactQueryDevtools } from '@tanstack/react-query-devtools';
import { AuthProvider } from './contexts/AuthContext';
import ProtectedRoute from './components/ProtectedRoute';
import './index.css';

import LoginPage from './pages/auth/LoginPage';
import AdminLayout from './pages/admin/AdminLayout';
import PageList from './pages/admin/pages/PageList';
import PageForm from './pages/admin/pages/PageForm';
import MenuManager from './pages/admin/menu/MenuManager';
import RoleList from './pages/admin/roles/RoleList';
import RoleForm from './pages/admin/roles/RoleForm';
import PrivilegeList from './pages/admin/privileges/PrivilegeList';
import PublicLayout from './pages/public/PublicLayout';
import PublicPage from './pages/public/PublicPage';
import HomePage from './pages/public/HomePage';

const queryClient = new QueryClient({
  defaultOptions: {
    queries: { retry: 1, staleTime: 30_000 },
  },
});

createRoot(document.getElementById('root')!).render(
  <QueryClientProvider client={queryClient}>
    <AuthProvider>
      <BrowserRouter>
        <Routes>
          <Route path="/login" element={<LoginPage />} />

          <Route
            path="/admin"
            element={
              <ProtectedRoute>
                <AdminLayout />
              </ProtectedRoute>
            }
          >
            <Route index element={<Navigate to="pages" replace />} />
            <Route path="pages" element={<PageList />} />
            <Route path="pages/create" element={<PageForm />} />
            <Route path="pages/:id/edit" element={<PageForm />} />
            <Route path="menu" element={<MenuManager />} />
            <Route path="roles" element={<RoleList />} />
            <Route path="roles/create" element={<RoleForm />} />
            <Route path="roles/:id/edit" element={<RoleForm />} />
            <Route path="privileges" element={<PrivilegeList />} />
          </Route>

          <Route path="/" element={<PublicLayout />}>
            <Route index element={<HomePage />} />
            <Route path=":slug" element={<PublicPage />} />
          </Route>
        </Routes>
      </BrowserRouter>
    </AuthProvider>
    <ReactQueryDevtools initialIsOpen={false} />
  </QueryClientProvider>
);
