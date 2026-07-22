import { useRef, useState, useCallback } from 'react';

interface Props {
  currentUrl?: string | null;
  onChange: (file: File | null) => void;
  error?: string;
}

export default function ImagePicker({ currentUrl, onChange, error }: Props) {
  const inputRef = useRef<HTMLInputElement>(null);
  const [preview, setPreview] = useState<string | null>(null);
  const [isDragging, setIsDragging] = useState(false);

  const handleFile = (file: File | null) => {
    if (!file) return;
    onChange(file);
    const reader = new FileReader();
    reader.onload = e => setPreview(e.target?.result as string);
    reader.readAsDataURL(file);
  };

  const handleDrop = useCallback((e: React.DragEvent) => {
    e.preventDefault();
    setIsDragging(false);
    const file = e.dataTransfer.files[0];
    if (file?.type.startsWith('image/')) handleFile(file);
  }, []);

  const handleRemove = () => {
    setPreview(null);
    onChange(null);
    if (inputRef.current) inputRef.current.value = '';
  };

  const displayImage = preview ?? currentUrl;

  return (
    <div className="flex flex-col gap-1">
      <label className="text-sm font-medium text-gray-700">Cover Image</label>

      {displayImage ? (
        <div className="relative w-full rounded-xl overflow-hidden border border-gray-200 bg-gray-50 group">
          <img
            src={displayImage}
            alt="Cover preview"
            className="w-full h-48 object-cover"
          />
          <div className="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-3">
            <button
              type="button"
              onClick={() => inputRef.current?.click()}
              className="bg-white text-gray-800 text-sm font-medium px-4 py-2 rounded-lg hover:bg-gray-100 transition-colors"
            >
              Change image
            </button>
            <button
              type="button"
              onClick={handleRemove}
              className="bg-red-500 text-white text-sm font-medium px-4 py-2 rounded-lg hover:bg-red-600 transition-colors"
            >
              Remove
            </button>
          </div>
        </div>
      ) : (
        <div
          onClick={() => inputRef.current?.click()}
          onDragOver={e => { e.preventDefault(); setIsDragging(true); }}
          onDragLeave={() => setIsDragging(false)}
          onDrop={handleDrop}
          className={`flex flex-col items-center justify-center gap-2 w-full h-40 rounded-xl border-2 border-dashed cursor-pointer transition-colors ${
            isDragging
              ? 'border-indigo-400 bg-indigo-50'
              : 'border-gray-300 bg-gray-50 hover:border-indigo-400 hover:bg-indigo-50'
          }`}
        >
          <div className="text-3xl text-gray-300">🖼</div>
          <p className="text-sm font-medium text-gray-500">
            Drag & drop or <span className="text-indigo-600">browse</span>
          </p>
          <p className="text-xs text-gray-400">PNG, JPG, WEBP up to 10MB</p>
        </div>
      )}

      <input
        ref={inputRef}
        type="file"
        accept="image/*"
        className="hidden"
        onChange={e => handleFile(e.target.files?.[0] ?? null)}
      />

      {error && <p className="text-xs text-red-500">{error}</p>}
    </div>
  );
}
