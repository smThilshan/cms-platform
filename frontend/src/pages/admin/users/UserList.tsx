import { useState } from 'react';
import { Link } from 'react-router-dom';
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import { getUsers, deleteUser } from '../../../api/users';
import { useAuth } from '../../../contexts/AuthContext';
import PrivilegeGate from '../../../components/PrivilegeGate';
import Button from '../../../components/ui/Button';

export default function UserList() {
  const [page, setPage] = useState(1);
  const { user: authUser } = useAuth();
  const queryClient = useQueryClient();

  const { data, isLoading } = useQuery({
    queryKey: ['users', page],
    queryFn: () => getUsers(page).then(r => r.data),
  });

  const deleteMutation = useMutation({
    mutationFn: deleteUser,
    onSuccess: () => queryClient.invalidateQueries({ queryKey: ['users'] }),
  });

  const handleDelete = (id: number, name: string) => {
    if (!confirm(`Delete user "${name}"?`)) return;
    deleteMutation.mutate(id);
  };

  if (isLoading) return <div className="text-gray-500">Loading...</div>;

  return (
    <div>
      <div className="flex items-center justify-between mb-6">
        <h1 className="text-xl md:text-2xl font-bold text-gray-900">Users</h1>
        <PrivilegeGate privilege="users.create">
          <Link to="/admin/users/create">
            <Button>New User</Button>
          </Link>
        </PrivilegeGate>
      </div>

      <div className="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div className="overflow-x-auto">
          <table className="w-full text-sm">
            <thead className="bg-gray-50 border-b border-gray-200">
              <tr>
                <th className="text-left px-4 py-3 font-medium text-gray-600">Name</th>
                <th className="text-left px-4 py-3 font-medium text-gray-600 hidden sm:table-cell">Email</th>
                <th className="text-left px-4 py-3 font-medium text-gray-600">Role</th>
                <th className="px-4 py-3" />
              </tr>
            </thead>
            <tbody className="divide-y divide-gray-100">
              {data?.data.map(u => (
                <tr key={u.id} className="hover:bg-gray-50">
                  <td className="px-4 py-3 font-medium text-gray-900">
                    {u.name}
                    {u.id === authUser?.id && (
                      <span className="ml-2 text-xs text-indigo-500 font-normal">(you)</span>
                    )}
                  </td>
                  <td className="px-4 py-3 text-gray-500 hidden sm:table-cell">{u.email}</td>
                  <td className="px-4 py-3">
                    <span className="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700">
                      {u.role.name}
                    </span>
                  </td>
                  <td className="px-4 py-3">
                    <div className="flex items-center justify-end gap-2">
                      <PrivilegeGate privilege="users.edit">
                        <Link to={`/admin/users/${u.id}/edit`}>
                          <Button variant="secondary" size="sm">Edit</Button>
                        </Link>
                      </PrivilegeGate>
                      <PrivilegeGate privilege="users.delete">
                        {u.id !== authUser?.id && (
                          <Button
                            variant="danger"
                            size="sm"
                            onClick={() => handleDelete(u.id, u.name)}
                            isLoading={deleteMutation.isPending && deleteMutation.variables === u.id}
                          >
                            Delete
                          </Button>
                        )}
                      </PrivilegeGate>
                    </div>
                  </td>
                </tr>
              ))}
              {data?.data.length === 0 && (
                <tr>
                  <td colSpan={4} className="px-4 py-8 text-center text-gray-400">No users found.</td>
                </tr>
              )}
            </tbody>
          </table>
        </div>
      </div>

      {data && data.meta.last_page > 1 && (
        <div className="flex justify-end gap-2 mt-4">
          <Button variant="secondary" size="sm" disabled={page === 1} onClick={() => setPage(p => p - 1)}>
            Previous
          </Button>
          <span className="text-sm text-gray-500 flex items-center">{page} / {data.meta.last_page}</span>
          <Button variant="secondary" size="sm" disabled={page === data.meta.last_page} onClick={() => setPage(p => p + 1)}>
            Next
          </Button>
        </div>
      )}
    </div>
  );
}
