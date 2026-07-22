import { useState } from 'react';
import { Link, Outlet } from 'react-router-dom';
import { useQuery } from '@tanstack/react-query';
import { getPublicMenu } from '../../api/menu';

export default function PublicLayout() {
  const [mobileNavOpen, setMobileNavOpen] = useState(false);

  const { data: menu } = useQuery({
    queryKey: ['public-menu'],
    queryFn: () => getPublicMenu().then(r => r.data.data),
  });

  return (
    <div className="min-h-screen flex flex-col">
      <header className="bg-white border-b border-gray-200 shadow-sm sticky top-0 z-20">
        <div className="max-w-5xl mx-auto px-4 py-4">

          {/* Top row: logo + hamburger + admin link */}
          <div className="flex items-center justify-between">
            <Link to="/" className="text-xl font-bold text-indigo-700">CMS</Link>

            {/* Desktop nav */}
            <nav className="hidden md:flex items-center gap-1">
              {menu?.map(item => (
                <div key={item.id} className="relative group">
                  <Link
                    to={item.pages?.[0] ? `/${item.pages[0].slug}` : '#'}
                    className="px-3 py-1.5 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-100 transition-colors"
                  >
                    {item.title}
                  </Link>
                  {item.children?.length > 0 && (
                    <div className="absolute top-full left-0 bg-white border border-gray-200 rounded-md shadow-lg hidden group-hover:block min-w-40 z-10">
                      {item.children.map(child => (
                        <Link
                          key={child.id}
                          to={child.pages?.[0] ? `/${child.pages[0].slug}` : '#'}
                          className="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50"
                        >
                          {child.title}
                        </Link>
                      ))}
                    </div>
                  )}
                </div>
              ))}
            </nav>

            <div className="flex items-center gap-2">
              <Link to="/login" className="hidden md:block text-sm text-indigo-600 hover:underline font-medium">
                Admin
              </Link>

              {/* Hamburger */}
              <button
                onClick={() => setMobileNavOpen(o => !o)}
                className="md:hidden p-1.5 rounded-md text-gray-600 hover:bg-gray-100"
                aria-label="Toggle menu"
              >
                {mobileNavOpen ? (
                  <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" />
                  </svg>
                ) : (
                  <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4 6h16M4 12h16M4 18h16" />
                  </svg>
                )}
              </button>
            </div>
          </div>

          {/* Mobile nav dropdown */}
          {mobileNavOpen && (
            <nav className="md:hidden mt-3 pt-3 border-t border-gray-100 flex flex-col gap-1">
              {menu?.map(item => (
                <div key={item.id}>
                  <Link
                    to={item.pages?.[0] ? `/${item.pages[0].slug}` : '#'}
                    className="block px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-100"
                    onClick={() => setMobileNavOpen(false)}
                  >
                    {item.title}
                  </Link>
                  {item.children?.map(child => (
                    <Link
                      key={child.id}
                      to={child.pages?.[0] ? `/${child.pages[0].slug}` : '#'}
                      className="block px-6 py-2 text-sm text-gray-500 hover:bg-gray-50"
                      onClick={() => setMobileNavOpen(false)}
                    >
                      {child.title}
                    </Link>
                  ))}
                </div>
              ))}
              <Link
                to="/login"
                className="block px-3 py-2 text-sm font-medium text-indigo-600"
                onClick={() => setMobileNavOpen(false)}
              >
                Admin
              </Link>
            </nav>
          )}
        </div>
      </header>

      <main className="flex-1 max-w-5xl mx-auto px-4 py-8 w-full">
        <Outlet />
      </main>

      <footer className="bg-gray-50 border-t border-gray-200 text-center text-sm text-gray-400 py-6">
        CMS Platform
      </footer>
    </div>
  );
}
