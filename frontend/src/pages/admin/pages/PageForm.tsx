import { useEffect, useState } from 'react';
import { useNavigate, useParams } from 'react-router-dom';
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import { getPage, createPage, updatePage } from '../../../api/pages';
import { getAdminMenu } from '../../../api/menu';
import RichTextEditor from '../../../components/RichTextEditor';
import Input from '../../../components/ui/Input';
import Button from '../../../components/ui/Button';

export default function PageForm() {
  const { id } = useParams<{ id: string }>();
  const isEdit = Boolean(id);
  const navigate = useNavigate();
  const queryClient = useQueryClient();

  const [title, setTitle] = useState('');
  const [body, setBody] = useState('');
  const [status, setStatus] = useState<'draft' | 'published'>('draft');
  const [menuItemId, setMenuItemId] = useState<number | ''>('');
  const [coverImage, setCoverImage] = useState<File | null>(null);
  const [errors, setErrors] = useState<Record<string, string>>({});

  const { data: pageData } = useQuery({
    queryKey: ['page', id],
    queryFn: () => getPage(Number(id)).then(r => r.data.data),
    enabled: isEdit,
  });

  const { data: menuData } = useQuery({
    queryKey: ['admin-menu'],
    queryFn: () => getAdminMenu().then(r => r.data.data),
  });

  useEffect(() => {
    if (pageData) {
      setTitle(pageData.title);
      setBody(pageData.body);
      setStatus(pageData.status);
      setMenuItemId(pageData.menu_item_id);
    }
  }, [pageData]);

  const mutation = useMutation({
    mutationFn: (formData: FormData) =>
      isEdit ? updatePage(Number(id), formData) : createPage(formData),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['pages'] });
      navigate('/admin/pages');
    },
    onError: (err: any) => {
      setErrors(err.response?.data?.errors ?? {});
    },
  });

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    setErrors({});
    const fd = new FormData();
    fd.append('title', title);
    fd.append('body', body);
    fd.append('status', status);
    fd.append('menu_item_id', String(menuItemId));
    if (coverImage) fd.append('cover_image', coverImage);
    mutation.mutate(fd);
  };

  const flatMenuItems = (items: typeof menuData, depth = 0): { id: number; label: string }[] => {
    if (!items) return [];
    return items.flatMap(item => [
      { id: item.id, label: '  '.repeat(depth) + item.title },
      ...flatMenuItems(item.children, depth + 1),
    ]);
  };

  return (
    <div className="max-w-2xl">
      <h1 className="text-2xl font-bold text-gray-900 mb-6">{isEdit ? 'Edit Page' : 'New Page'}</h1>

      <form onSubmit={handleSubmit} className="flex flex-col gap-5">
        <Input
          label="Title"
          value={title}
          onChange={e => setTitle(e.target.value)}
          error={errors.title}
          required
        />

        <div className="flex flex-col gap-1">
          <label className="text-sm font-medium text-gray-700">Body</label>
          <RichTextEditor value={body} onChange={setBody} />
          {errors.body && <p className="text-xs text-red-500">{errors.body}</p>}
        </div>

        <div className="flex flex-col gap-1">
          <label className="text-sm font-medium text-gray-700">Menu Item</label>
          <select
            className="rounded-md border border-gray-300 px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-indigo-500"
            value={menuItemId}
            onChange={e => setMenuItemId(Number(e.target.value))}
            required
          >
            <option value="">Select menu item</option>
            {flatMenuItems(menuData).map(item => (
              <option key={item.id} value={item.id}>{item.label}</option>
            ))}
          </select>
          {errors.menu_item_id && <p className="text-xs text-red-500">{errors.menu_item_id}</p>}
        </div>

        <div className="flex flex-col gap-1">
          <label className="text-sm font-medium text-gray-700">Status</label>
          <select
            className="rounded-md border border-gray-300 px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-indigo-500"
            value={status}
            onChange={e => setStatus(e.target.value as 'draft' | 'published')}
          >
            <option value="draft">Draft</option>
            <option value="published">Published</option>
          </select>
        </div>

        <div className="flex flex-col gap-1">
          <label className="text-sm font-medium text-gray-700">Cover Image</label>
          {pageData?.cover_image_url && !coverImage && (
            <img src={pageData.cover_image_url} alt="Current cover" className="w-32 h-20 object-cover rounded-md mb-1" />
          )}
          <input
            type="file"
            accept="image/*"
            onChange={e => setCoverImage(e.target.files?.[0] ?? null)}
            className="text-sm text-gray-600"
          />
          {errors.cover_image && <p className="text-xs text-red-500">{errors.cover_image}</p>}
        </div>

        <div className="flex gap-3 pt-2">
          <Button type="submit" isLoading={mutation.isPending}>
            {isEdit ? 'Update Page' : 'Create Page'}
          </Button>
          <Button type="button" variant="secondary" onClick={() => navigate('/admin/pages')}>
            Cancel
          </Button>
        </div>
      </form>
    </div>
  );
}
