import { useState } from 'react';
import { NavLink, Outlet, useNavigate } from 'react-router-dom';
import { useAuth } from '../../contexts/AuthContext';
import PrivilegeGate from '../../components/PrivilegeGate';
import Button from '../../components/ui/Button';

const navLink = ({ isActive }: { isActive: boolean }) =>
  `flex items-center gap-2 px-3 py-2 rounded-md text-sm font-medium transition-colors ${
    isActive ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-gray-100'
  }`;

function SidebarContent({ onNavClick }: { onNavClick?: () => void }) {
  const { user, logout } = useAuth();
  const navigate = useNavigate();

  const handleLogout = async () => {
    await logout();
    navigate('/login');
  };

  return (
    <div className="flex flex-col h-full">
      <div className="px-4 py-5 border-b border-gray-200">
        <span className="text-lg font-bold text-indigo-700">CMS Platform</span>
      </div>

      <nav className="flex-1 p-3 flex flex-col gap-1">
        <PrivilegeGate privilege="pages.list">
          <NavLink to="/admin/pages" className={navLink} onClick={onNavClick}>Pages</NavLink>
        </PrivilegeGate>
        <PrivilegeGate privilege="menu.list">
          <NavLink to="/admin/menu" className={navLink} onClick={onNavClick}>Menu</NavLink>
        </PrivilegeGate>
        <PrivilegeGate privilege="roles.list">
          <NavLink to="/admin/roles" className={navLink} onClick={onNavClick}>Roles</NavLink>
        </PrivilegeGate>
        <PrivilegeGate privilege="privileges.list">
          <NavLink to="/admin/privileges" className={navLink} onClick={onNavClick}>Privileges</NavLink>
        </PrivilegeGate>
        <PrivilegeGate privilege="users.list">
          <NavLink to="/admin/users" className={navLink} onClick={onNavClick}>Users</NavLink>
        </PrivilegeGate>
      </nav>

      <div className="p-3 border-t border-gray-200">
        <p className="text-xs text-gray-500 truncate mb-2">{user?.name} · {user?.role.name}</p>
        <Button variant="secondary" size="sm" className="w-full justify-center" onClick={handleLogout}>
          Logout
        </Button>
      </div>
    </div>
  );
}

export default function AdminLayout() {
  const [drawerOpen, setDrawerOpen] = useState(false);

  return (
    <div className="min-h-screen flex bg-gray-50">

      {/* Desktop sidebar */}
      <aside className="hidden md:flex md:flex-col w-56 bg-white border-r border-gray-200 fixed h-full z-20">
        <SidebarContent />
      </aside>

      {/* Mobile drawer overlay */}
      {drawerOpen && (
        <div
          className="fixed inset-0 bg-black/40 z-30 md:hidden"
          onClick={() => setDrawerOpen(false)}
        />
      )}

      {/* Mobile drawer */}
      <aside
        className={`fixed top-0 left-0 h-full w-64 bg-white border-r border-gray-200 z-40 transform transition-transform duration-300 md:hidden ${
          drawerOpen ? 'translate-x-0' : '-translate-x-full'
        }`}
      >
        <SidebarContent onNavClick={() => setDrawerOpen(false)} />
      </aside>

      {/* Main content — offset by sidebar width on desktop */}
      <div className="flex-1 flex flex-col md:ml-56">

        {/* Mobile top bar */}
        <header className="md:hidden flex items-center gap-3 bg-white border-b border-gray-200 px-4 py-3 sticky top-0 z-20">
          <button
            onClick={() => setDrawerOpen(true)}
            className="p-1.5 rounded-md text-gray-600 hover:bg-gray-100"
            aria-label="Open menu"
          >
            <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4 6h16M4 12h16M4 18h16" />
            </svg>
          </button>
          <span className="text-base font-bold text-indigo-700">CMS Platform</span>
        </header>

        <main className="flex-1 p-4 md:p-8 overflow-x-hidden overflow-y-auto">
          <Outlet />
        </main>
      </div>
    </div>
  );
}
