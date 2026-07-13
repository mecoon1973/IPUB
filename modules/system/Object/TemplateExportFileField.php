<?php

namespace Modules\System\Object;

/**
 * Trường path file template khi upload (khớp {@see resources/ts/modules/system/type/TemplateExport.ts}).
 */
final class TemplateExportFileField
{
    public const PATH_FILE_TEMPLATE = 'path_file_template';
    public const PATH_FILE_TEMPLATE_DOC = 'path_file_template_doc';

    public static function all(): array
    {
        return [
            self::PATH_FILE_TEMPLATE,
            self::PATH_FILE_TEMPLATE_DOC,
        ];
    }

    private function __construct() {}
}
