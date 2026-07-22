import { useParams } from 'react-router-dom';
import { useQuery } from '@tanstack/react-query';
import { getPublicPage } from '../../api/pages';

export default function PublicPage() {
  const { slug } = useParams<{ slug: string }>();

  const { data, isLoading, isError } = useQuery({
    queryKey: ['public-page', slug],
    queryFn: () => getPublicPage(slug!).then(r => r.data.data),
    enabled: Boolean(slug),
  });

  if (isLoading) return <div className="text-gray-400">Loading...</div>;
  if (isError) return <div className="text-gray-500">Page not found.</div>;
  if (!data) return null;

  return (
    <article className="max-w-prose">
      {data.cover_image_url && (
        <img
          src={data.cover_image_url}
          alt={data.title}
          className="w-full h-56 object-cover rounded-xl mb-8"
        />
      )}
      <h1 className="text-4xl font-bold text-gray-900 mb-6">{data.title}</h1>
      <div
        className="prose prose-gray max-w-none"
        dangerouslySetInnerHTML={{ __html: data.body }}
      />
    </article>
  );
}
