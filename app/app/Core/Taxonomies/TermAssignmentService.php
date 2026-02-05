<?php

namespace App\Core\Taxonomies;

use App\Models\Taxonomy;
use App\Models\TermRelation;

class TermAssignmentService
{
    public function setForContent(int $siteId, int $contentId, string $taxonomyKey, array $termIds): void
    {
        $taxonomy = Taxonomy::query()
            ->where('site_id', $siteId)
            ->where('taxonomy_key', $taxonomyKey)
            ->first();

        if (!$taxonomy) return;

        $termIds = array_values(array_unique(array_map('intval', $termIds)));

        // Borrar relaciones previas solo de este taxonomy
        $termIdsInTax = $taxonomy->terms()->pluck('id')->toArray();

        TermRelation::query()
            ->where('site_id', $siteId)
            ->where('content_id', $contentId)
            ->whereIn('term_id', $termIdsInTax)
            ->delete();

        // Insert nuevas
        foreach ($termIds as $tid) {
            TermRelation::updateOrCreate(
                ['site_id' => $siteId, 'content_id' => $contentId, 'term_id' => $tid],
                []
            );
        }
    }
}
