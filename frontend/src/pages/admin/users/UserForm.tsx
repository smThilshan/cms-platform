import { useEffect, useState } from 'react';
import { useNavigate, useParams } from 'react-router-dom';
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import { getUser, createUser, updateUser } from '../../../api/users';
import { getRoles } from '../../../api/roles';
import Input from '../../../components/ui/Input';
import Button from '../../../components/ui/Button';

export default function UserForm() {
  const { id } = useParams<{ id: string }>();
  const isEdit = Boolean(id);
  const navigate = useNavigate();
  const queryClient = useQueryClient();

  const [name, setName] = useState('');
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [roleId, setRoleId] = useState<number | ''>('');
  const [errors, setErrors] = useState<Record<string, string>>({});

  const { data: userData } = useQuery({
    queryKey: ['user', id],
    queryFn: () => getUser(Number(id)).then(r => r.data.data),
    enabled: isEdit,
  });

  const { data: roles } = useQuery({
    queryKey: ['roles'],
    queryFn: () => getRoles().then(r => r.data.data),
  });

  useEffect(() => {
    if (userData) {
      setName(userData.name);
      setEmail(userData.email);
      setRoleId(userData.role.id);
    }
  }, [userData]);

  const mutation = useMutation({
    mutationFn: (data: Parameters<typeof createUser>[0] | Parameters<typeof updateUser>[1]) =>
      isEdit
        ? updateUser(Number(id), data as Parameters<typeof updateUser>[1])
        : createUser(data as Parameters<typeof createUser>[0]),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['users'] });
      navigate('/admin/users');
    },
    onError: (err: any) => setErrors(err.response?.data?.errors ?? {}),
  });

  const handleSubmit = (e: React.SyntheticEvent<HTMLFormElement>) => {
    e.preventDefault();
    setErrors({});

    const data: Record<string, string | number> = {
      name,
      email,
      role_id: Number(roleId),
    };

    if (password) data.password = password;
    if (!isEdit) data.password = password;

    mutation.mutate(data as any);
  };

  return (
    <div className="max-w-lg">
      <h1 className="text-2xl font-bold text-gray-900 mb-6">{isEdit ? 'Edit User' : 'New User'}</h1>

      <form onSubmit={handleSubmit} className="flex flex-col gap-5">
        <Input
          label="Name"
          value={name}
          onChange={e => setName(e.target.value)}
          error={errors.name}
          required
        />

        <Input
          label="Email"
          type="email"
          value={email}
          onChange={e => setEmail(e.target.value)}
          error={errors.email}
          required
        />

        <Input
          label={isEdit ? 'New Password (leave blank to keep current)' : 'Password'}
          type="password"
          value={password}
          onChange={e => setPassword(e.target.value)}
          error={errors.password}
          required={!isEdit}
          placeholder={isEdit ? 'Leave blank to keep current' : ''}
        />

        <div className="flex flex-col gap-1">
          <label className="text-sm font-medium text-gray-700">Role</label>
          <select
            className="rounded-md border border-gray-300 px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-indigo-500"
            value={roleId}
            onChange={e => setRoleId(Number(e.target.value))}
            required
          >
            <option value="">Select a role</option>
            {roles?.map(role => (
              <option key={role.id} value={role.id}>{role.name}</option>
            ))}
          </select>
          {errors.role_id && <p className="text-xs text-red-500">{errors.role_id}</p>}
        </div>

        <div className="flex gap-3 pt-2">
          <Button type="submit" isLoading={mutation.isPending}>
            {isEdit ? 'Update User' : 'Create User'}
          </Button>
          <Button type="button" variant="secondary" onClick={() => navigate('/admin/users')}>
            Cancel
          </Button>
        </div>
      </form>
    </div>
  );
}
