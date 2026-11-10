<?php

require __DIR__ . '/vendor/autoload.php';

$app = require __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Services\AiBuilder\PageHtmlGenerator;

function callPrivate(object $obj, string $method, ...$args)
{
    $r = new ReflectionMethod($obj, $method);
    $r->setAccessible(true);
    return $r->invokeArgs($obj, $args);
}

function callPrivateStatic(object $obj, string $method, ...$args)
{
    $r = new ReflectionMethod($obj, $method);
    $r->setAccessible(true);
    return $r->invokeArgs(null, $args);
}

$gen = app(PageHtmlGenerator::class);
$failures = 0;

function check(bool $cond, string $label)
{
    global $failures;
    if ($cond) {
        echo "PASS: {$label}\n";
    } else {
        $failures++;
        echo "FAIL: {$label}\n";
    }
}

// ---------------------------------------------------------------------------
// 1. applyTemplateRotation: a footer template assigned to a Contact section
//    (no catalog category) must be DROPPED, not trusted blindly.
// ---------------------------------------------------------------------------
$templates = \App\Models\AiBuilderTemplate::active()->get();
$catalogByCategory = [];
foreach ($templates as $tpl) {
    $cat = strtolower(trim((string) $tpl->category));
    if ($cat === '') continue;
    $catalogByCategory[$cat][] = $tpl->template_id;
}

$sections = [
    ['name' => 'Header', 'type' => 'header'],
    ['name' => 'Hero', 'type' => 'hero'],
    ['name' => 'Contact', 'type' => 'contact'],
    ['name' => 'Footer', 'type' => 'footer'],
];
// AI (mis)assigns a footer template to the Contact section (index 2)
$assignments = [
    1 => 'hero_centered_01',
    2 => 'footer_dark_bottom_glow', // wrong category for contact
    3 => 'footer_dark_01',
];
$result = callPrivate($gen, 'applyTemplateRotation', $assignments, $sections, $templates);
check(!isset($result[2]), 'double-footer: footer template dropped for contact section');
check(isset($result[3]) && in_array($result[3], $catalogByCategory['footer'] ?? [], true), 'double-footer: footer section keeps a footer-category template');
check(isset($result[1]) && in_array($result[1], $catalogByCategory['hero'] ?? [], true), 'double-footer: hero keeps a hero-category template');
echo "  rotation result: " . json_encode($result) . "\n";

// ---------------------------------------------------------------------------
// 2. mergeTemplateProps: AI list shorter than defaults must replace, not leak
// ---------------------------------------------------------------------------
$defaults = ['testimonials' => [
    ['quote' => 'd1', 'author' => 'a1'],
    ['quote' => 'd2', 'author' => 'a2'],
    ['quote' => 'd3', 'author' => 'a3'],
    ['quote' => 'd4', 'author' => 'a4'],
    ['quote' => 'd5', 'author' => 'a5'],
]];
$aiProps = ['testimonials' => [
    ['quote' => 'new1', 'author' => 'b1'],
    ['quote' => 'new2', 'author' => 'b2'],
    ['quote' => 'new3', 'author' => 'b3'],
]];
$merged = callPrivate($gen, 'mergeTemplateProps', $defaults, $aiProps);
check(count($merged['testimonials']) === 3, 'stale-defaults: AI list length respected (3, not 5)');
check($merged['testimonials'][0]['quote'] === 'new1', 'stale-defaults: AI items kept');

// ---------------------------------------------------------------------------
// 3. cta_shader_bg template renders text that follows the dark flag
// ---------------------------------------------------------------------------
$cta = \App\Models\AiBuilderTemplate::where('template_id', 'cta_shader_bg')->first();
check($cta !== null, 'cta_shader_bg template exists');
if ($cta) {
    $src = (string) $cta->component_source;
    check(str_contains($src, "innerDark"), 'cta_shader_bg: uses innerDark flag');
    check(!str_contains($src, "Hardcoded to white"), 'cta_shader_bg: no hardcoded-white comment');

    // Plain role -> background default, dark false -> rendered text must be dark
    $plainProps = callPrivate($gen, 'enforceBackgroundRole', $cta->defaults ?? [], [], 'plain', []);
    check(($plainProps['background']['type'] ?? '') === 'default', 'cta_shader_bg: plain role forces default background');
    check(($plainProps['dark'] ?? null) === false, 'cta_shader_bg: plain role forces dark=false');

    // Decorative role with the shared decorative preset -> background + dark applied
    $shared = [
        'decorative_background' => ['type' => 'gradflow', 'config' => ['color1' => '#111111', 'color2' => '#222222', 'color3' => '#333333']],
        'decorative_background_dark' => true,
    ];
    $decorProps = callPrivate($gen, 'enforceBackgroundRole', $cta->defaults ?? [], [], 'decorative', $shared);
    check(($decorProps['background']['type'] ?? '') === 'gradflow', 'cta_shader_bg: decorative role applies shared preset');
    check(($decorProps['dark'] ?? null) === true, 'cta_shader_bg: decorative role applies dark flag');

    // Render the JSX with plain props and confirm text color derives from innerDark
    $rendered = callPrivate($gen, 'renderTemplateJsx', $cta, $plainProps, 'CTA');
    check(str_contains($rendered, "innerDark ? '#FFFFFF' : 'var(--ds-foreground)'"), 'cta_shader_bg: heading color conditional in output');
    check(str_contains($rendered, 'bg-white/70'), 'cta_shader_bg: light inner card fallback present');
    check(str_contains($rendered, 'bg-zinc-900'), 'cta_shader_bg: dark inner card fallback present');
}

// ---------------------------------------------------------------------------
// 4. Header template background: opacity-on-CSS-var classes must be rewritten
//    to color-mix() so Tailwind actually emits a background rule.
// ---------------------------------------------------------------------------
$header = \App\Models\AiBuilderTemplate::where('template_id', 'header_nav_01')->first();
if ($header) {
    $src = (string) $header->component_source;
    check(!str_contains($src, 'bg-[var(--ds-background)]/70'), 'header: stored template uses color-mix (no broken opacity-on-var class)');
    check(str_contains($src, 'bg-[color:color-mix(in_srgb,var(--ds-background)_70%,transparent)]'), 'header: stored template has working color-mix background');
    $rewritten = callPrivate($gen, 'normalizeImports', "className=\"bg-ds-background/80 text-[var(--ds-muted)]/50\"");
    check(str_contains($rewritten, 'bg-[color:color-mix(in_srgb,var(--ds-background)_80%,transparent)]'), 'header: normalizeImports rewrites theme-class opacity to color-mix');
    check(str_contains($rewritten, 'text-[color:color-mix(in_srgb,var(--ds-muted)_50%,transparent)]'), 'header: normalizeImports rewrites arbitrary-var opacity to color-mix');
}

// ---------------------------------------------------------------------------
// 5. pickDecorativeBackground rotation produces distinct presets
// ---------------------------------------------------------------------------
$seen = [];
for ($i = 0; $i < 8; $i++) {
    $styles = callPrivate($gen, 'pickDecorativeBackground', []);
    $preset = $styles['decorative_background_preset_id'] ?? null;
    if ($preset !== null) {
        $seen[] = $preset;
    }
}
$unique = array_unique($seen);
check(count($unique) > 1, 'background rotation: multiple distinct presets over 8 picks');
echo '  presets seen: ' . implode(', ', array_values($unique)) . "\n";

// ---------------------------------------------------------------------------
// 6. pickRotationCandidate round-robins when EVERY candidate was recently used
//    (the old code locked onto the first catalog template in this situation).
// ---------------------------------------------------------------------------
$probes = [
    'testimonials' => ['testimonial_carousel', 'testimonials_marqueue', 'testimonials_bento'],
    'hero'         => ['hero_split_02', 'hero_centered_01'],
    'features'     => ['features_bento_grid_01', 'features_3_cols', 'features_bento_grid_02', 'features_image_right_list_left', 'features_icon_left'],
];
foreach ($probes as $cat => $cands) {
    $recent = array_reverse($cands); // simulate a saturated history window
    $seen = [];
    for ($i = 0; $i < count($cands) * 2; $i++) {
        $p = callPrivate($gen, 'pickRotationCandidate', null, $cands, $recent, []);
        $seen[$p] = ($seen[$p] ?? 0) + 1;
        $recent = array_values(array_unique(array_merge([$p], $recent)));
    }
    $balanced = count($seen) === count($cands) && max($seen) - min($seen) <= 1;
    check($balanced, "rotation: {$cat} round-robins evenly over 2 cycles");
}


// ---------------------------------------------------------------------------
// 7. Dark-only templates (pricing_dark) must stay dark under the decorative
//    role even when the page preset is light.
// ---------------------------------------------------------------------------
$pd = \App\Models\AiBuilderTemplate::where('template_id', 'pricing_dark')->first();
if ($pd) {
    $lightShared = [
        'decorative_background' => ['type' => 'grid', 'config' => []],
        'decorative_background_dark' => false,
    ];
    $pdProps = callPrivate($gen, 'enforceBackgroundRole', $pd->defaults ?? [], [], 'decorative', $lightShared);
    check(($pdProps['background']['type'] ?? '') === 'dark', 'pricing_dark: dark-only template stays dark under light decorative preset');
    check(($pdProps['dark'] ?? null) === true, 'pricing_dark: dark flag forced true');
}

// ---------------------------------------------------------------------------
// 8. End-to-end: reproduce the Kids Wear double-footer scenario. The AI tried
//    to assign footer_dark_bottom_glow to the Contact section. With the fix,
//    Contact is unassigned, so the assembled LandingPage must render exactly
//    ONE footer.
// ---------------------------------------------------------------------------
$sections2 = [
    ['name' => 'Header', 'type' => 'header'],
    ['name' => 'Hero', 'type' => 'hero'],
    ['name' => 'Features', 'type' => 'features'],
    ['name' => 'Contact', 'type' => 'contact'],
    ['name' => 'Footer', 'type' => 'footer'],
];
$aiAssignments = [
    0 => 'header_nav_01',
    1 => 'hero_split_02',
    2 => 'features_bento_grid_01',
    3 => 'footer_dark_bottom_glow', // wrong: footer template for contact
    4 => 'footer_dark_01',
];
$rotated = callPrivate($gen, 'applyTemplateRotation', $aiAssignments, $sections2, $templates);
check(!isset($rotated[3]), 'e2e: contact section has no template assignment');

$sectionFiles = [];
foreach ($sections2 as $i => $s) {
    $name = $s['name'];
    $tid = $rotated[$i] ?? null;
    $content = "import React from 'react';\nexport default function {$name}() { return <section id=\"{$name}\">{$name}</section>; }\n";
    if ($tid === 'footer_dark_01' && $s['type'] === 'footer') {
        // Simulate a real footer template so the assembled page sees a footer section
        $content = "import React from 'react';\nexport default function {$name}() { return <footer id=\"footer-dark\">footer</footer>; }\n";
    }
    $sectionFiles[] = ['path' => 'src/components/sections/' . $name . '.jsx', 'content' => $content];
}
$codebase = new \App\Services\AiBuilder\DefaultCodebase();
$theme = ['primary' => '#2563eb', 'secondary' => '#06b6d4', 'background' => '#f8fafc', 'text' => '#0f172a'];
$project = $codebase->sectionFiles($theme, 'Test', $sectionFiles);
$landing = null;
foreach ($project as $pf) {
    if (($pf['path'] ?? '') === 'src/pages/LandingPage.jsx') {
        $landing = $pf['content'];
    }
}
check($landing !== null, 'e2e: LandingPage assembled');
if ($landing !== null) {
    $footerImports = preg_match_all('/import Footer\b/', $landing);
    $footerRenders = preg_match_all('/<Footer\s*\/>/', $landing);
    check($footerImports === 1, 'e2e: exactly one Footer import');
    check($footerRenders === 1, 'e2e: exactly one <Footer /> render');
}

// ---------------------------------------------------------------------------
// 9. ThemeApplier must theme only :root, never clobber .section-theme-dark's
//    scoped white-on-dark overrides (otherwise dark sections render dark text).
// ---------------------------------------------------------------------------
$codebase2 = new \App\Services\AiBuilder\DefaultCodebase();
$baseTheme = ['primary' => '#2563eb', 'secondary' => '#06b6d4', 'background' => '#f8fafc', 'text' => '#0f172a'];
$baseFiles = $codebase2->sectionFiles($baseTheme, 'Test', []);
$preset = [
    'palette' => [
        'primary' => '#d21570', 'secondary' => '#8e3ff8', 'background' => '#ffffff',
        'foreground' => '#111111', 'muted' => '#f1f5f9', 'mutedForeground' => '#666666',
        'border' => '#e2e8f0', 'card' => '#ffffff', 'accent' => '#f1f5f9', 'destructive' => '#ef4444',
    ],
    'radius' => 'rounded-xl',
    'buttonStyle' => ['radius' => '12px'],
    'typography' => ['body' => 'Inter', 'heading' => 'Inter', 'googleFonts' => []],
];
$themed = (new \App\Services\AiBuilder\ThemeApplier())->applyPreset($baseFiles, $preset);
$themedCss = null;
foreach ($themed as $tf) {
    if (($tf['path'] ?? '') === 'src/styles.css') { $themedCss = $tf['content']; }
}
check(str_contains((string) $themedCss, '--ds-muted-foreground: #666666;'), 'theme-dark: :root gets the preset muted-foreground');
check(preg_match('/\.section-theme-dark\s*\{[^}]*--ds-foreground:\s*var\(--ds-background\)/s', (string) $themedCss) === 1, 'theme-dark: .section-theme-dark keeps white foreground override');
check(preg_match('/\.section-theme-dark\s*\{[^}]*--ds-muted-foreground:\s*rgba\(255,\s*255,\s*255,\s*0\.7\)/s', (string) $themedCss) === 1, 'theme-dark: .section-theme-dark keeps white muted-foreground');
check(preg_match('/\.section-theme-dark\s*\{[^}]*--ds-muted-foreground:\s*#666666/s', (string) $themedCss) === 0, 'theme-dark: dark block not clobbered with light muted-foreground');

// ---------------------------------------------------------------------------
// 10. backgroundPresetIsDark / isDarkDecorativeConfig sanity
// ---------------------------------------------------------------------------
check(callPrivate($gen, 'backgroundPresetIsDark', ['type' => 'grid']) === false, 'bg-dark: grid is light');
check(callPrivate($gen, 'isDarkDecorativeConfig', ['color1' => ['r' => 8, 'g' => 5, 'b' => 24]]) === true, 'bg-dark: deep silk is dark');
check(callPrivate($gen, 'isDarkDecorativeConfig', ['color1' => '#ffffff', 'color2' => '#f0f0f0', 'color3' => '#e0e0e0']) === false, 'bg-dark: near-white palette is light');

echo "\n";
if ($failures > 0) {
    echo "{$failures} FAILURE(S)\n";
    exit(1);
}
echo "ALL CHECKS PASSED\n";
exit(0);
