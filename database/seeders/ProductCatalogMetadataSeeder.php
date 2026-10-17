<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductCatalogMetadataSeeder extends Seeder
{
    public function run(): void
    {
        $catalogCopy = [
            'green-spinach' => [
                'short_description' => 'Tender leafy greens for everyday meals, selected for freshness and packed by the bundle.',
                'description' => "Bring fresh green spinach to your kitchen for quick, nourishing meals. Its tender leaves work well in dal, curries, omelettes, and simple sautés. Rinse thoroughly before cooking and season to taste.\n\nSold by the bundle. Actual appearance may vary with the day’s harvest.",
                'seo_title' => 'Fresh Green Spinach Bundle | Krishoker Jhuri',
                'seo_description' => 'Shop fresh green spinach by the bundle for dal, curries, omelettes, and everyday home cooking. Order online from Krishoker Jhuri.',
                'seo_keywords' => 'fresh spinach, green spinach bundle, leafy greens, buy spinach online, vegetables Bangladesh',
                'original_short' => 'Tender leaves packed with iron and vitamins.',
                'original_description' => 'Fresh spinach harvested in the morning and packed for clean, nutrient-rich meals.',
            ],
            'red-amaranth' => [
                'short_description' => 'Fresh, tender red amaranth leaves, ready for traditional sides, curries, and simple stir-fries.',
                'description' => "Add colour and a distinctive leafy flavour to meals with fresh red amaranth. Cook it as a traditional bhaji, fold it into dal, or lightly sauté it with garlic and seasoning. Rinse well before preparation.\n\nSold by the bundle. Leaf colour and size may vary naturally.",
                'seo_title' => 'Fresh Red Amaranth Bundle | Krishoker Jhuri',
                'seo_description' => 'Buy fresh red amaranth leaves by the bundle. A colourful choice for bhaji, dal, curries, and everyday Bangladeshi meals.',
                'seo_keywords' => 'red amaranth, lal shak, leafy vegetables, fresh greens, buy vegetables online Bangladesh',
                'original_short' => 'Bright and tender leafy greens.',
                'original_description' => 'Highly nutritious greens ideal for curries and soups.',
            ],
            'farm-tomato' => [
                'short_description' => 'Juicy tomatoes for salads, sauces, curries, and everyday cooking. Sold by the kilogram.',
                'description' => "Bring ripe, juicy tomatoes to your table for fresh salads, homemade sauces, soups, and curries. Their balanced flavour makes them easy to use in both quick meals and slow-cooked recipes. Wash before use.\n\nSold by the kilogram. Natural size, shape, and ripeness can vary.",
                'seo_title' => 'Fresh Farm Tomatoes per kg | Krishoker Jhuri',
                'seo_description' => 'Shop juicy farm tomatoes by the kilogram for salads, sauces, soups, and curries. Fresh produce delivered from Krishoker Jhuri.',
                'seo_keywords' => 'farm tomatoes, fresh tomato per kg, buy tomatoes online, vegetables Bangladesh, cooking tomatoes',
                'original_short' => 'Juicy and naturally sweet.',
                'original_description' => 'Picked at peak ripeness for salads, cooking, and sauces.',
            ],
            'bottle-gourd' => [
                'short_description' => 'Mild-flavoured bottle gourd for comforting curries, soups, and light family meals.',
                'description' => "Bottle gourd is a versatile kitchen staple with a mild flavour that pairs beautifully with lentils, fish, and everyday spices. Peel and cut to suit your recipe, then simmer in a curry or add to soup. Wash before preparation.\n\nSold by the kilogram. Size and shape vary with the harvest.",
                'seo_title' => 'Fresh Bottle Gourd per kg | Krishoker Jhuri',
                'seo_description' => 'Buy fresh bottle gourd by the kilogram for curries, soups, and homestyle meals. Order vegetables online from Krishoker Jhuri.',
                'seo_keywords' => 'bottle gourd, lau, fresh lau per kg, vegetables online, Bangladeshi vegetables',
                'original_short' => 'Smooth and mild in flavor.',
                'original_description' => 'A versatile family favorite for curries and stir-fries.',
            ],
            'sweet-mango' => [
                'short_description' => 'A fragrant seasonal mango to enjoy fresh, blend into drinks, or serve with family.',
                'description' => "Enjoy the sweet, fragrant flavour of seasonal mango as a fresh snack, dessert, smoothie, or addition to fruit salad. Serve chilled or use in your favourite recipes.\n\nSold by the kilogram. This is a seasonal fruit; size, colour, and ripeness may vary.",
                'seo_title' => 'Seasonal Sweet Mango per kg | Krishoker Jhuri',
                'seo_description' => 'Shop fragrant seasonal sweet mangoes by the kilogram. Enjoy fresh, in smoothies, or as a dessert from Krishoker Jhuri.',
                'seo_keywords' => 'sweet mango, seasonal mango, mango per kg, buy mango online Bangladesh, fresh fruit',
                'original_short' => 'Naturally ripened fruit.',
                'original_description' => 'A rich, fragrant fruit full of sweetness and vitamins.',
            ],
            'organic-carrot' => [
                'short_description' => 'Crunchy carrots for salads, soups, snacks, and everyday cooking. Sold by the kilogram.',
                'description' => "Add a naturally sweet crunch to meals with fresh carrots. Grate them into salads, roast them as a side, or add them to soups, curries, and mixed vegetables. Wash and peel as desired.\n\nSold by the kilogram. Individual carrots naturally vary in size and shape.",
                'seo_title' => 'Fresh Organic Carrots per kg | Krishoker Jhuri',
                'seo_description' => 'Buy fresh carrots by the kilogram for salads, soups, roasting, and everyday meals. Shop vegetables online at Krishoker Jhuri.',
                'seo_keywords' => 'organic carrots, fresh carrot per kg, buy carrots online, root vegetables, vegetables Bangladesh',
                'original_short' => 'Crunchy and naturally sweet.',
                'original_description' => 'Root vegetables grown in fertile soil with no artificial additives.',
            ],
            'fresh-coriander' => [
                'short_description' => 'Aromatic fresh coriander leaves to finish curries, dals, chutneys, and salads.',
                'description' => "Fresh coriander adds a bright herbal finish to curries, dals, chutneys, and salads. Chop the leaves and tender stems just before serving for a fresh burst of flavour. Rinse well and store refrigerated.\n\nSold by the bundle. Leaf size and appearance may vary.",
                'seo_title' => 'Fresh Coriander Leaves Bundle | Krishoker Jhuri',
                'seo_description' => 'Shop aromatic fresh coriander leaves by the bundle for curries, dal, chutney, and salads. Order online from Krishoker Jhuri.',
                'seo_keywords' => 'fresh coriander, dhania pata, coriander bundle, fresh herbs Bangladesh, buy coriander online',
                'original_short' => 'Fresh leaves for flavor and aroma.',
                'original_description' => 'Coriander adds a bright, fresh lift to curries and salads.',
            ],
            'seasonal-mixed-box' => [
                'short_description' => 'A convenient seasonal selection of fresh produce for family meals and weekly meal planning.',
                'description' => "Make weekly meal planning easier with a curated box of seasonal produce. The selection follows what is available from the harvest, making it a convenient way to bring variety to family meals.\n\nSold as one box. Product selection and quantities may change with seasonal availability.",
                'seo_title' => 'Seasonal Mixed Produce Box | Krishoker Jhuri',
                'seo_description' => 'Order a seasonal mixed produce box with a changing selection of fresh harvest favourites for family meals and weekly cooking.',
                'seo_keywords' => 'seasonal vegetable box, mixed produce box, fresh harvest box Bangladesh, weekly vegetables, family produce box',
                'original_short' => 'A curated weekly harvest bundle.',
                'original_description' => 'The best of the season for family meals and healthy routines.',
            ],
        ];

        foreach ($catalogCopy as $slug => $copy) {
            $product = Product::query()->where('slug', $slug)->first();

            if (! $product) {
                continue;
            }

            $updates = [];

            if (! $product->sku) {
                $updates['sku'] = 'KJ-'.str_pad((string) $product->id, 6, '0', STR_PAD_LEFT);
            }

            if (! $product->short_description || $product->short_description === $copy['original_short']) {
                $updates['short_description'] = $copy['short_description'];
            }

            if (! $product->description || $product->description === $copy['original_description']) {
                $updates['description'] = $copy['description'];
            }

            foreach (['seo_title', 'seo_description', 'seo_keywords'] as $field) {
                if (! $product->{$field}) {
                    $updates[$field] = $copy[$field];
                }
            }

            if ($updates) {
                $product->update($updates);
            }
        }
    }
}
