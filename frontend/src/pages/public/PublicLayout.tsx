import { Link, Outlet } from 'react-router-dom';
import { useQuery } from '@tanstack/react-query';
import { getPublicMenu } from '../../api/menu';

export default function PublicLayout() {
  const { data: menu } = useQuery({
    queryKey: ['public-menu'],
    queryFn: () => getPublicMenu().then(r => r.data.data),
  });

  return (
    <div className="min-h-screen flex flex-col">
      <header className="bg-white border-b border-gray-200 shadow-sm">
        <div className="max-w-5xl mx-auto px-4 py-4 flex items-center gap-8">
          <Link to="/" className="text-xl font-bold text-indigo-700">CMS</Link>
          <nav className="flex items-center gap-1">
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
          <div className="ml-auto">
            <Link to="/login" className="text-sm text-indigo-600 hover:underline font-medium">Admin</Link>
          </div>
        </div>
      </header>

      <main className="flex-1 max-w-5xl mx-auto px-4 py-10 w-full">
        <Outlet />
      </main>

      <footer className="bg-gray-50 border-t border-gray-200 text-center text-sm text-gray-400 py-6">
        CMS Platform
      </footer>
    </div>
  );
}
