import { CKEditor } from '@ckeditor/ckeditor5-react';
import { ClassicEditor, Bold, Italic, Underline, Link, List, Heading, Paragraph, Essentials } from 'ckeditor5';
import 'ckeditor5/ckeditor5.css';

interface Props {
  value: string;
  onChange: (value: string) => void;
}

export default function RichTextEditor({ value, onChange }: Props) {
  return (
    <CKEditor
      editor={ClassicEditor}
      config={{
        plugins: [Essentials, Paragraph, Heading, Bold, Italic, Underline, Link, List],
        toolbar: ['heading', '|', 'bold', 'italic', 'underline', 'link', 'bulletedList', 'numberedList'],
      }}
      data={value}
      onChange={(_, editor) => onChange(editor.getData())}
    />
  );
}
