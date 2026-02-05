<?php

namespace App\Core\Content;

use App\Models\ContentMeta;

class MetaService
{
    public function getAll(int $siteId, int $contentId): array
    {
        return ContentMeta::query()
            ->where('site_id', $siteId)
            ->where('content_id', $contentId)
            ->pluck('meta_value', 'meta_key')
            ->toArray();
    }

    public function setMany(int $siteId, int $contentId, array $pairs): void
    {
        // Limpia keys vacías y normaliza
        $clean = [];
        foreach ($pairs as $k => $v) {
            $k = trim((string)$k);
            if ($k === '') continue;
            $clean[$k] = is_null($v) ? null : (string)$v;
        }

        // Guardar/actualizar
        foreach ($clean as $key => $val) {
            ContentMeta::updateOrCreate(
                ['site_id' => $siteId, 'content_id' => $contentId, 'meta_key' => $key],
                ['meta_value' => $val]
            );
        }

        // Opcional: borrar metas que ya no vienen
        ContentMeta::query()
            ->where('site_id', $siteId)
            ->where('content_id', $contentId)
            ->whereNotIn('meta_key', array_keys($clean))
            ->delete();
    }
}
