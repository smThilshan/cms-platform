import { NavLink, Outlet, useNavigate } from 'react-router-dom';
import { useAuth } from '../../contexts/AuthContext';
import PrivilegeGate from '../../components/PrivilegeGate';
import Button from '../../components/ui/Button';

const navLink = ({ isActive }: { isActive: boolean }) =>
  `flex items-center gap-2 px-3 py-2 rounded-md text-sm font-medium transition-colors ${
    isActive ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-gray-100'
  }`;

export default function AdminLayout() {
  const { user, logout } = useAuth();
  const navigate = useNavigate();

  const handleLogout = async () => {
    await logout();
    navigate('/login');
  };

  return (
    <div className="min-h-screen flex bg-gray-50">
      <aside className="w-56 bg-white border-r border-gray-200 flex flex-col">
        <div className="px-4 py-5 border-b border-gray-200">
          <span className="text-lg font-bold text-indigo-700">CMS Platform</span>
        </div>

        <nav className="flex-1 p-3 flex flex-col gap-1">
          <PrivilegeGate privilege="pages.list">
            <NavLink to="/admin/pages" className={navLink}>Pages</NavLink>
          </PrivilegeGate>
          <PrivilegeGate privilege="menu.list">
            <NavLink to="/admin/menu" className={navLink}>Menu</NavLink>
          </PrivilegeGate>
          <PrivilegeGate privilege="roles.list">
            <NavLink to="/admin/roles" className={navLink}>Roles</NavLink>
          </PrivilegeGate>
          <PrivilegeGate privilege="privileges.list">
            <NavLink to="/admin/privileges" className={navLink}>Privileges</NavLink>
          </PrivilegeGate>
        </nav>

        <div className="p-3 border-t border-gray-200">
          <p className="text-xs text-gray-500 truncate mb-2">{user?.name} · {user?.role.name}</p>
          <Button variant="secondary" size="sm" className="w-full justify-center" onClick={handleLogout}>
            Logout
          </Button>
        </div>
      </aside>

      <main className="flex-1 p-8 overflow-y-auto">
        <Outlet />
      </main>
    </div>
  );
}
