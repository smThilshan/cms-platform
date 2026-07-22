import { createContext, useContext, useEffect, useState } from 'react';
import * as authApi from '../api/auth';
import type { User } from '../types';

interface AuthContextValue {
  user: User | null;
  isLoading: boolean;
  login: (email: string, password: string) => Promise<void>;
  logout: () => Promise<void>;
  hasPrivilege: (key: string) => boolean;
}

const AuthContext = createContext<AuthContextValue | null>(null);

export function AuthProvider({ children }: { children: React.ReactNode }) {
  const [user, setUser] = useState<User | null>(null);
  const [isLoading, setIsLoading] = useState(true);

  useEffect(() => {
    const token = localStorage.getItem('token');
    if (!token) { setIsLoading(false); return; }

    authApi.me()
      .then(res => setUser(res.data.data))
      .catch(() => localStorage.removeItem('token'))
      .finally(() => setIsLoading(false));
  }, []);

  const login = async (email: string, password: string) => {
    const res = await authApi.login(email, password);
    localStorage.setItem('token', res.data.token);
    setUser(res.data.user);
  };

  const logout = async () => {
    await authApi.logout().catch(() => {});
    localStorage.removeItem('token');
    setUser(null);
  };

  const hasPrivilege = (key: string) =>
    user?.privileges.includes(key) ?? false;

  return (
    <AuthContext.Provider value={{ user, isLoading, login, logout, hasPrivilege }}>
      {children}
    </AuthContext.Provider>
  );
}

export function useAuth() {
  const ctx = useContext(AuthContext);
  if (!ctx) throw new Error('useAuth must be used within AuthProvider');
  return ctx;
}
