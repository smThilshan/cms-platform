import { useQuery } from '@tanstack/react-query';
import { getPrivileges } from '../../../api/privileges';

export default function PrivilegeList() {
  const { data, isLoading } = useQuery({
    queryKey: ['privileges'],
    queryFn: () => getPrivileges().then(r => r.data.data),
  });

  if (isLoading) return <div className="text-gray-500">Loading...</div>;

  const groups = data?.reduce<Record<string, typeof data>>((acc, p) => {
    const group = p.key.split('.')[0];
    acc[group] = [...(acc[group] ?? []), p];
    return acc;
  }, {});

  return (
    <div>
      <h1 className="text-2xl font-bold text-gray-900 mb-6">Privileges</h1>
      <div className="flex flex-col gap-4">
        {Object.entries(groups ?? {}).map(([group, privs]) => (
          <div key={group} className="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div className="px-4 py-3 border-b border-gray-100 bg-gray-50">
              <h2 className="text-sm font-semibold text-gray-700 uppercase">{group}</h2>
            </div>
            <table className="w-full text-sm">
              <tbody className="divide-y divide-gray-100">
                {privs.map(p => (
                  <tr key={p.id} className="hover:bg-gray-50">
                    <td className="px-4 py-2.5 font-mono text-indigo-700">{p.key}</td>
                    <td className="px-4 py-2.5 text-gray-500">{p.description}</td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        ))}
      </div>
    </div>
  );
}
