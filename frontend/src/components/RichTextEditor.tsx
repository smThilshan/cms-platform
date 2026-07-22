import { CKEditor } from '@ckeditor/ckeditor5-react';
import {
  ClassicEditor,
  Bold, Italic, Underline, Strikethrough,
  Link, List, ListProperties,
  Heading, Paragraph, Essentials,
  BlockQuote, HorizontalLine,
  Undo,
} from 'ckeditor5';
import 'ckeditor5/ckeditor5.css';
import './editor.css';

interface Props {
  value: string;
  onChange: (value: string) => void;
}

export default function RichTextEditor({ value, onChange }: Props) {
  return (
    <div className="border border-gray-300 rounded-md overflow-hidden">
      <CKEditor
        editor={ClassicEditor}
        config={{
          licenseKey: 'GPL',
          plugins: [
            Essentials, Paragraph, Heading,
            Bold, Italic, Underline, Strikethrough,
            Link, List, ListProperties,
            BlockQuote, HorizontalLine, Undo,
          ],
          toolbar: [
            'heading', '|',
            'bold', 'italic', 'underline', 'strikethrough', '|',
            'link', 'bulletedList', 'numberedList', '|',
            'blockQuote', 'horizontalLine', '|',
            'undo', 'redo',
          ],
        }}
        data={value}
        onChange={(_, editor) => onChange(editor.getData())}
      />
    </div>
  );
}
