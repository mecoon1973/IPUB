import 'ckeditor5/ckeditor5.css';

import { CKEditor } from '@ckeditor/ckeditor5-react';
import { ClassicEditor } from '@ckeditor/ckeditor5-editor-classic';

import { Essentials } from '@ckeditor/ckeditor5-essentials';
import {
    Bold,
    Italic,
    Underline,
    Strikethrough,
    Subscript,
    Superscript,
} from '@ckeditor/ckeditor5-basic-styles';
import { Paragraph } from '@ckeditor/ckeditor5-paragraph';
import { Heading } from '@ckeditor/ckeditor5-heading';
import { List } from '@ckeditor/ckeditor5-list';
import { Alignment } from '@ckeditor/ckeditor5-alignment';
import { Indent, IndentBlock } from '@ckeditor/ckeditor5-indent';
import { Font } from '@ckeditor/ckeditor5-font';
import {
    Table,
    TableToolbar,
    TableProperties,
    TableCellProperties,
} from '@ckeditor/ckeditor5-table';
import { LineHeight } from '@rickx/ckeditor5-line-height';
import React from 'react';
import { CONFIG_ALIGNMENT, CONFIG_CONTENT_TOOLBAR_TABLE, CONFIG_DEFAULT_PROPERTIES_TABLE, CONFIG_FONT_FAMILY, CONFIG_FONT_SIZE, CONFIG_HEADING, CONFIG_LINE_HEIGHT } from './configCKEditor';

interface ComponentCKEditorProps {
    data: string;
    onChange: (data: string) => void;
}

export const ComponentCKEditor = React.memo((props: ComponentCKEditorProps) => {
    const { data, onChange } = props;
    return (
        <CKEditor
            editor={ClassicEditor}
            data={data}
            onChange={(_event, editor) => {
                onChange(editor.getData());
            }}
            config={{
                // Bắt buộc từ CKEditor 5.44+ khi cài qua npm theo giấy phép GPL.
                // Xem: https://ckeditor.com/docs/ckeditor5/latest/getting-started/licensing/license-key-and-activation.html
                licenseKey: window.CKEDITOR_GLOBAL_LICENSE_KEY || 'GPL',
                plugins: [
                    Essentials,
                    Bold,
                    Italic,
                    Underline,
                    Strikethrough,
                    Subscript,
                    Superscript,
                    Paragraph,
                    Heading,
                    List,
                    Alignment,
                    Indent,
                    IndentBlock,
                    Font,
                    Table,
                    TableToolbar,
                    TableProperties,
                    TableCellProperties,
                    LineHeight,
                ],
                toolbar: [
                    'bold',
                    'italic',
                    'underline',
                    'strikethrough',
                    '|',
                    'heading',
                    '|',
                    'subscript',
                    'superscript',
                    '|',
                    'numberedList',
                    'bulletedList',
                    '|',
                    'outdent',
                    'indent',
                    '|',
                    'insertTable',
                    '|',
                    'alignment',
                    '|',
                    'fontFamily',
                    'fontSize',
                    'lineHeight',
                    'fontColor',
                    '|',
                    'undo',
                    'redo',
                ],
                heading: {
                    options: CONFIG_HEADING,
                },
                alignment: {
                    options: CONFIG_ALIGNMENT,
                },
                fontFamily: {
                    supportAllValues: true,
                    options: CONFIG_FONT_FAMILY,
                },
                fontSize: {
                    options: CONFIG_FONT_SIZE,
                    supportAllValues: true,
                },
                lineHeight: {
                    options: CONFIG_LINE_HEIGHT,
                    supportAllValues: true,
                },
                table: {
                    contentToolbar: CONFIG_CONTENT_TOOLBAR_TABLE,
                    tableProperties: {
                        defaultProperties: CONFIG_DEFAULT_PROPERTIES_TABLE,
                    },
                    tableCellProperties: {
                        defaultProperties: CONFIG_DEFAULT_PROPERTIES_TABLE,
                    },
                },
            }}
        />
    );
});
