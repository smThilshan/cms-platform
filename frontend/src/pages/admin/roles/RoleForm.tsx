import { useEffect, useState } from 'react';
import { useNavigate, useParams } from 'react-router-dom';
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import { getRole, createRole, updateRole } from '../../../api/roles';
import { getPrivileges } from '../../../api/privileges';
import Input from '../../../components/ui/Input';
import Button from '../../../components/ui/Button';

export default function RoleForm() {
  const { id } = useParams<{ id: string }>();
  const isEdit = Boolean(id);
  const navigate = useNavigate();
  const queryClient = useQueryClient();

  const [name, setName] = useState('');
  const [slug, setSlug] = useState('');
  const [selectedIds, setSelectedIds] = useState<number[]>([]);
  const [errors, setErrors] = useState<Record<string, string>>({});

  const { data: roleData } = useQuery({
    queryKey: ['role', id],
    queryFn: () => getRole(Number(id)).then(r => r.data.data),
    enabled: isEdit,
  });

  const { data: allPrivileges } = useQuery({
    queryKey: ['privileges'],
    queryFn: () => getPrivileges().then(r => r.data.data),
  });

  useEffect(() => {
    if (roleData) {
      setName(roleData.name);
      setSlug(roleData.slug);
      setSelectedIds(roleData.privileges.map(p => p.id));
    }
  }, [roleData]);

  const mutation = useMutation({
    mutationFn: (data: { name: string; slug: string; privilege_ids: number[] }) =>
      isEdit ? updateRole(Number(id), data) : createRole(data),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['roles'] });
      navigate('/admin/roles');
    },
    onError: (err: any) => setErrors(err.response?.data?.errors ?? {}),
  });

  const togglePrivilege = (privId: number) =>
    setSelectedIds(prev =>
      prev.includes(privId) ? prev.filter(i => i !== privId) : [...prev, privId]
    );

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    setErrors({});
    mutation.mutate({ name, slug, privilege_ids: selectedIds });
  };

  const groups = allPrivileges?.reduce<Record<string, typeof allPrivileges>>((acc, p) => {
    const group = p.key.split('.')[0];
    acc[group] = [...(acc[group] ?? []), p];
    return acc;
  }, {});

  return (
    <div className="max-w-2xl">
      <h1 className="text-2xl font-bold text-gray-900 mb-6">{isEdit ? 'Edit Role' : 'New Role'}</h1>

      <form onSubmit={handleSubmit} className="flex flex-col gap-5">
        <Input label="Name" value={name} onChange={e => setName(e.target.value)} error={errors.name} required />
        <Input label="Slug" value={slug} onChange={e => setSlug(e.target.value)} error={errors.slug} required />

        <div className="flex flex-col gap-2">
          <label className="text-sm font-medium text-gray-700">Privileges</label>
          <div className="border border-gray-200 rounded-xl overflow-hidden divide-y divide-gray-100">
            {Object.entries(groups ?? {}).map(([group, privs]) => (
              <div key={group} className="p-3">
                <p className="text-xs font-semibold text-gray-500 uppercase mb-2">{group}</p>
                <div className="flex flex-wrap gap-2">
                  {privs.map(p => (
                    <label key={p.id} className="flex items-center gap-1.5 cursor-pointer">
                      <input
                        type="checkbox"
                        checked={selectedIds.includes(p.id)}
                        onChange={() => togglePrivilege(p.id)}
                        className="rounded border-gray-300 text-indigo-600"
                      />
                      <span className="text-sm text-gray-700">{p.key}</span>
                    </label>
                  ))}
                </div>
              </div>
            ))}
          </div>
        </div>

        <div className="flex gap-3 pt-2">
          <Button type="submit" isLoading={mutation.isPending}>
            {isEdit ? 'Update Role' : 'Create Role'}
          </Button>
          <Button type="button" variant="secondary" onClick={() => navigate('/admin/roles')}>
            Cancel
          </Button>
        </div>
      </form>
    </div>
  );
}
