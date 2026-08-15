<?php

function make_slug(string $text): string
{
    $slug = strtolower(trim($text));
    $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
    $slug = trim($slug, '-');
    return $slug !== '' ? $slug : 'item';
}

/** Appends -2, -3, ... until the slug is unique in $table.slug (ignoring $excludeId on update). */
function unique_slug(string $table, string $base, ?int $excludeId = null): string
{
    $slug = make_slug($base);
    $original = $slug;
    $n = 2;
    while (true) {
        $sql = "SELECT id FROM {$table} WHERE slug = ?" . ($excludeId ? ' AND id != ?' : '');
        $params = $excludeId ? [$slug, $excludeId] : [$slug];
        $stmt = db()->prepare($sql);
        $stmt->execute($params);
        if (!$stmt->fetch()) {
            return $slug;
        }
        $slug = $original . '-' . $n++;
    }
}
