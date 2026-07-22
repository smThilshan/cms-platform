import { useState } from 'react';
import { Link } from 'react-router-dom';
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import { getPages, deletePage } from '../../../api/pages';
import PrivilegeGate from '../../../components/PrivilegeGate';
import Button from '../../../components/ui/Button';

export default function PageList() {
  const [page, setPage] = useState(1);
  const queryClient = useQueryClient();

  const { data, isLoading } = useQuery({
    queryKey: ['pages', page],
    queryFn: () => getPages(page).then(r => r.data),
  });

  const deleteMutation = useMutation({
    mutationFn: deletePage,
    onSuccess: () => queryClient.invalidateQueries({ queryKey: ['pages'] }),
  });

  const handleDelete = (id: number, title: string) => {
    if (!confirm(`Delete "${title}"?`)) return;
    deleteMutation.mutate(id);
  };

  if (isLoading) return <div className="text-gray-500">Loading...</div>;

  return (
    <div>
      <div className="flex items-center justify-between mb-6">
        <h1 className="text-2xl font-bold text-gray-900">Pages</h1>
        <PrivilegeGate privilege="pages.create">
          <Link to="/admin/pages/create">
            <Button>New Page</Button>
          </Link>
        </PrivilegeGate>
      </div>

      <div className="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <table className="w-full text-sm">
          <thead className="bg-gray-50 border-b border-gray-200">
            <tr>
              <th className="text-left px-4 py-3 font-medium text-gray-600">Title</th>
              <th className="text-left px-4 py-3 font-medium text-gray-600">Slug</th>
              <th className="text-left px-4 py-3 font-medium text-gray-600">Status</th>
              <th className="px-4 py-3" />
            </tr>
          </thead>
          <tbody className="divide-y divide-gray-100">
            {data?.data.map(p => (
              <tr key={p.id} className="hover:bg-gray-50">
                <td className="px-4 py-3 font-medium text-gray-900">{p.title}</td>
                <td className="px-4 py-3 text-gray-500">{p.slug}</td>
                <td className="px-4 py-3">
                  <span className={`inline-flex px-2 py-0.5 rounded-full text-xs font-medium ${
                    p.status === 'published'
                      ? 'bg-green-100 text-green-700'
                      : 'bg-gray-100 text-gray-600'
                  }`}>
                    {p.status}
                  </span>
                </td>
                <td className="px-4 py-3">
                  <div className="flex items-center justify-end gap-2">
                    <PrivilegeGate privilege="pages.edit">
                      <Link to={`/admin/pages/${p.id}/edit`}>
                        <Button variant="secondary" size="sm">Edit</Button>
                      </Link>
                    </PrivilegeGate>
                    <PrivilegeGate privilege="pages.delete">
                      <Button
                        variant="danger"
                        size="sm"
                        onClick={() => handleDelete(p.id, p.title)}
                        isLoading={deleteMutation.isPending && deleteMutation.variables === p.id}
                      >
                        Delete
                      </Button>
                    </PrivilegeGate>
                  </div>
                </td>
              </tr>
            ))}
            {data?.data.length === 0 && (
              <tr>
                <td colSpan={4} className="px-4 py-8 text-center text-gray-400">No pages yet.</td>
              </tr>
            )}
          </tbody>
        </table>
      </div>

      {data && data.meta.last_page > 1 && (
        <div className="flex justify-end gap-2 mt-4">
          <Button variant="secondary" size="sm" disabled={page === 1} onClick={() => setPage(p => p - 1)}>
            Previous
          </Button>
          <span className="text-sm text-gray-500 flex items-center">
            {page} / {data.meta.last_page}
          </span>
          <Button variant="secondary" size="sm" disabled={page === data.meta.last_page} onClick={() => setPage(p => p + 1)}>
            Next
          </Button>
        </div>
      )}
    </div>
  );
}
