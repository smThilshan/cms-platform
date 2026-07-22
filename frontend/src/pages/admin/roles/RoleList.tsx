import { Link } from 'react-router-dom';
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import { getRoles, deleteRole } from '../../../api/roles';
import PrivilegeGate from '../../../components/PrivilegeGate';
import Button from '../../../components/ui/Button';

export default function RoleList() {
  const queryClient = useQueryClient();

  const { data, isLoading } = useQuery({
    queryKey: ['roles'],
    queryFn: () => getRoles().then(r => r.data.data),
  });

  const deleteMutation = useMutation({
    mutationFn: deleteRole,
    onSuccess: () => queryClient.invalidateQueries({ queryKey: ['roles'] }),
  });

  if (isLoading) return <div className="text-gray-500">Loading...</div>;

  return (
    <div>
      <div className="flex items-center justify-between mb-6">
        <h1 className="text-2xl font-bold text-gray-900">Roles</h1>
        <PrivilegeGate privilege="roles.create">
          <Link to="/admin/roles/create">
            <Button>New Role</Button>
          </Link>
        </PrivilegeGate>
      </div>

      <div className="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <table className="w-full text-sm">
          <thead className="bg-gray-50 border-b border-gray-200">
            <tr>
              <th className="text-left px-4 py-3 font-medium text-gray-600">Name</th>
              <th className="text-left px-4 py-3 font-medium text-gray-600">Slug</th>
              <th className="text-left px-4 py-3 font-medium text-gray-600">Privileges</th>
              <th className="px-4 py-3" />
            </tr>
          </thead>
          <tbody className="divide-y divide-gray-100">
            {data?.map(role => (
              <tr key={role.id} className="hover:bg-gray-50">
                <td className="px-4 py-3 font-medium text-gray-900">{role.name}</td>
                <td className="px-4 py-3 text-gray-500">{role.slug}</td>
                <td className="px-4 py-3 text-gray-500">{role.privileges.length} privileges</td>
                <td className="px-4 py-3">
                  <div className="flex items-center justify-end gap-2">
                    <PrivilegeGate privilege="roles.edit">
                      <Link to={`/admin/roles/${role.id}/edit`}>
                        <Button variant="secondary" size="sm">Edit</Button>
                      </Link>
                    </PrivilegeGate>
                    <PrivilegeGate privilege="roles.delete">
                      <Button
                        variant="danger"
                        size="sm"
                        onClick={() => confirm(`Delete "${role.name}"?`) && deleteMutation.mutate(role.id)}
                        isLoading={deleteMutation.isPending && deleteMutation.variables === role.id}
                      >
                        Delete
                      </Button>
                    </PrivilegeGate>
                  </div>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </div>
  );
}
