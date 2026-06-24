import type { AlignmentSupportedOption, HeadingOption, TablePropertiesOptions, ToolbarConfigItem } from "ckeditor5";

/** list font family được sử dụng trong CKEditor */
export const CONFIG_FONT_FAMILY = [
    'default',
    'Arial, Helvetica, sans-serif',
    'Courier New, Courier, monospace',
    'Georgia, serif',
    'Lucida Sans Unicode, Lucida Grande, sans-serif',
    'Tahoma, Geneva, sans-serif',
    'Times New Roman, Times, serif',
    'Trebuchet MS, Helvetica, sans-serif',
    'Verdana, Geneva, sans-serif',
]

export const CONFIG_FONT_SIZE = [9, 10, 11, 12, 13, 14, 15, 16, 18, 20, 22, 24, 36]

export const CONFIG_LINE_HEIGHT = [
    'default',
    { title: '1.0', model: '1' },
    { title: '1.15', model: '1.25' },
    { title: '1.5', model: '1.5' },
    { title: '1.75', model: '1.75' },
    { title: '2.0', model: '2' },
    { title: '2.25', model: '2.25' },
    { title: '2.5', model: '2.5' },
    // { title: '2.75', model: '2.75' },
    // { title: '3.0', model: '3' },
]

export const CONFIG_HEADING : HeadingOption[] = [
    {
        model: 'paragraph',
        title: 'Đoạn thường',
        class: 'ck-heading_paragraph',
    },
    {
        model: 'heading1',
        view: 'h1',
        title: 'Tiêu đề 1',
        class: 'ck-heading_heading1',
    },
    {
        model: 'heading2',
        view: 'h2',
        title: 'Tiêu đề 2',
        class: 'ck-heading_heading2',
    },
    {
        model: 'heading3',
        view: 'h3',
        title: 'Tiêu đề 3',
        class: 'ck-heading_heading3',
    },
    {
        model: 'heading4',
        view: 'h4',
        title: 'Tiêu đề 4',
        class: 'ck-heading_heading4',
    },
    {
        model: 'heading5',
        view: 'h5',
        title: 'Tiêu đề 5',
        class: 'ck-heading_heading5',
    },
    // {
    //     model: 'heading6',
    //     view: 'h6',
    //     title: 'Tiêu đề 6',
    //     class: 'ck-heading_heading6',
    // },
];

export const CONFIG_ALIGNMENT : AlignmentSupportedOption[]= ['left', 'center', 'right', 'justify']

export const CONFIG_DEFAULT_PROPERTIES_TABLE : TablePropertiesOptions = {
    borderStyle: 'solid',
    borderColor: '#000000',
    borderWidth: '1px',
}

export const CONFIG_CONTENT_TOOLBAR_TABLE : ToolbarConfigItem[] = [
    'tableColumn',
    'tableRow',
    'mergeTableCells',
    '|',
    'tableProperties',
    'tableCellProperties',

]
