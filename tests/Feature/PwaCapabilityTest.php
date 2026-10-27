<?php

namespace Tests\Feature;

use App\Models\SiteSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class PwaCapabilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_storefront_pages_link_to_the_pwa_manifest(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('rel="manifest"', false)
            ->assertSee('manifest.webmanifest', false)
            ->assertSee('apple-mobile-web-app-capable', false);
    }

    public function test_manifest_declares_installable_icons_and_offline_assets_exist(): void
    {
        $manifest = json_decode(
            file_get_contents(public_path('manifest.webmanifest')),
            true,
            flags: JSON_THROW_ON_ERROR,
        );

        $this->assertSame('standalone', $manifest['display']);
        $this->assertSame('/', $manifest['start_url']);
        $this->assertSame(2, count($manifest['icons']));

        foreach ($manifest['icons'] as $icon) {
            $iconPath = public_path(ltrim($icon['src'], '/'));
            $this->assertFileExists($iconPath);
            $this->assertSame('image/png', $icon['type']);
            [$expectedWidth, $expectedHeight] = array_map('intval', explode('x', $icon['sizes']));
            [$actualWidth, $actualHeight] = getimagesize($iconPath);
            $this->assertSame([$expectedWidth, $expectedHeight], [$actualWidth, $actualHeight]);
        }

        $this->assertFileExists(public_path('offline.html'));
        $this->assertFileExists(public_path('sw.js'));
    }

    public function test_site_settings_are_cached_and_cache_is_invalidated_when_a_setting_changes(): void
    {
        config(['cache.default' => 'array']);
        Cache::forget('site-settings.values');

        SiteSetting::create(['key' => 'brand_name', 'value' => 'Original Store']);
        $this->assertSame('Original Store', SiteSetting::values()['brand_name']);

        DB::enableQueryLog();
        SiteSetting::values();
        $this->assertCount(0, DB::getQueryLog());

        SiteSetting::set('brand_name', 'Updated Store');
        $this->assertSame('Updated Store', SiteSetting::values()['brand_name']);
    }

    public function test_storefront_and_recent_order_queries_have_database_indexes(): void
    {
        foreach ([
            ['products', 'products_category_active_name_index'],
            ['products', 'products_active_featured_updated_index'],
            ['products', 'products_active_name_index'],
            ['products', 'products_updated_at_index'],
            ['orders', 'orders_created_at_index'],
        ] as [$table, $index]) {
            $this->assertTrue(Schema::hasIndex($table, $index), "Expected {$index} to exist.");
        }
    }
}
