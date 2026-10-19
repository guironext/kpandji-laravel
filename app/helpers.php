<?php

use App\Models\Content;

if (! function_exists('content')) {
    /** Contenu éditable (admin › Contenu). Les listes sont renvoyées en tableau. */
    function content(string $key, $default = null)
    {
        return Content::get($key, $default);
    }
}

if (! function_exists('cms_url')) {
    /** Transforme un lien saisi (« /showroom », « #offres », « https://… ») en URL. */
    function cms_url(?string $v): string
    {
        $v = trim((string) $v);
        if ($v === '' ) return '#';
        if (preg_match('~^(https?:|mailto:|tel:|#)~i', $v)) return $v;
        if (str_starts_with($v, '/#')) return url('/').substr($v, 1);
        return url($v);
    }
}

if (! function_exists('cms_asset')) {
    /** Fichier image/vidéo : chemin public (« images/logo.png », « storage/cms/… ») ou URL complète. */
    function cms_asset(?string $v): string
    {
        $v = (string) $v;
        return preg_match('~^https?://~i', $v) ? $v : asset($v);
    }
}

if (! function_exists('cms_active')) {
    /** Lien de menu actif pour la page courante. */
    function cms_active(?string $v): bool
    {
        $v = trim((string) $v);
        if ($v === '' || preg_match('~^(https?:|mailto:|tel:|#)~i', $v)) return false;
        $path = trim(parse_url($v, PHP_URL_PATH) ?? '', '/');
        $cur = trim(request()->path(), '/');
        if ($path === '') return $cur === '';
        if ($path === 'showroom' && request()->routeIs('cars.*')) return true;
        return $cur === $path;
    }
}

if (! function_exists('cms_edit')) {
    /** Lien « Modifier cette section » visible uniquement par un administrateur connecté. */
    function cms_edit(string $tab, string $label = 'Modifier cette section', string $top = '.75rem'): string
    {
        $u = auth()->user();
        if (! $u || ! $u->is_admin) return '';
        $href = e(route('admin.content', ['tab' => $tab]));
        return '<a href="'.$href.'" class="kp-cms-edit" style="position:absolute;z-index:40;top:'.$top.';right:.75rem;background:#c9a962;color:#000;font:600 11px/1 system-ui;padding:.45rem .7rem;border-radius:999px;text-decoration:none;letter-spacing:.04em">✎ '.e($label).'</a>';
    }
}
