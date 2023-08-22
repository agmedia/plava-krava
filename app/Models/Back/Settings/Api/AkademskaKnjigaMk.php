<?php

namespace App\Models\Back\Settings\Api;

use App\Helpers\Helper;
use App\Helpers\Import;
use App\Helpers\ProductHelper;
use App\Models\Back\Catalog\Product\Product;
use App\Models\Back\Catalog\Product\ProductCategory;
use App\Models\Back\Settings\Settings;
use App\Models\Back\TempTable;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use function Livewire\str;

class AkademskaKnjigaMk
{

    /**
     * @var array|null
     */
    protected $request;


    public function process(array $request)
    {
        if ($request) {
            $this->request = $request;

            switch ($this->request['method']) {
                case 'check-products':
                    return $this->checkProductsForImport();
                case 'products':
                    return $this->importNewProducts();
                case 'update-prices-quantities':
                    return $this->importNewProducts();
            }
        }

        return false;
    }


    /**
     * Cca: 25 - 30 sec.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    private function checkProductsForImport()
    {
        TempTable::query()->truncate();

        $limit = '60000';
        $data  = Http::get('http://akademskakniga.mk/api/ASyn/' . $limit);

        $existing = Product::query()->pluck('sku');
        $list     = collect($data->json())
            ->pluck('bookId')
            ->diff($existing)
            ->flatten();

        $for_import = collect($data->json())->whereIn('bookId', $list)->chunk(10000);

        foreach ($for_import->all() as $item_list) {
            $query = [];
            foreach ($item_list as $item) {
                $query[] = [
                    'sku'      => (string) $item['bookId'],
                    'quantity' => $item['inStock'],
                    'price'    => $item['price'],
                    'special'  => $item['priceWithDiscount'],
                ];
            }

            TempTable::query()->insert($query);
        }

        return response()->json(['success' => 1]);
    }


    /**
     * @return int
     */
    private function importNewProducts()
    {
        $count = 0;
        $for_import = TempTable::query()->take(100)->get();

        foreach ($for_import as $item) {
            $exist = Product::query()->where('sku', $item['sku'])->first();

            if ( ! $exist) {
                $import = new Import();
                $data = Http::get('https://akademskakniga.mk/api/Akniga/' . $item['sku'])->json();

                if (is_array($data) && ! empty($data)) {
                    $publisher_id = 0;
                    $author_id = 0;

                    if (isset($data['bookPublisherId']['bookPublisherName'])) {
                        $publisher_id = $import->resolvePublisher($data['bookPublisherId']['bookPublisherName']);
                    }
                    if ($data['author']) {
                        $author_id = $import->resolveAuthor($data['author']);
                    }

                    $id = Product::query()->insertGetId([
                        'author_id'            => $author_id,
                        'publisher_id'         => $publisher_id,
                        'action_id'            => 0,
                        'name'                 => $data['imageAltText'],
                        'sku'                  => $data['bookId'],
                        'polica'               => null,
                        'isbn'                 => $data['ISBN'],
                        'description'          => $data['description'],
                        'slug'                 => Helper::resolveSlug($data, 'imageAltText'),
                        'price'                => $data['price'],
                        'quantity'             => $data['inStock'] ?: 0,
                        'decrease'             => 1,
                        'tax_id'               => config('settings.default_tax_id'),
                        'special'              => null,
                        'special_from'         => null,
                        'special_to'           => null,
                        'meta_title'           => $data['imageAltText'],
                        'meta_description'     => $data['imageAltText'],
                        'pages'                => $data['numberOfPages'],
                        'dimensions'           => null,
                        'origin'               => null,
                        'letter'               => null,
                        'condition'            => null,
                        'binding'              => $data['formaCover'],
                        'year'                 => $data['yearPublished'],
                        'shipping_time'        => '',
                        'youtube_product_url'  => '',
                        'youtube_channel'      => '',
                        'goodreads_author_url' => '',
                        'goodreads_book_url'   => '',
                        'author_web_url'       => '',
                        'serial_web_url'       => '',
                        'wiki_url'             => '',
                        'viewed'               => 0,
                        'sort_order'           => 0,
                        'push'                 => 0,
                        'status'               => $data['inStock'] ? 1 : 0,
                        'created_at'           => Carbon::now(),
                        'updated_at'           => Carbon::now()
                    ]);

                    if ($id) {
                        $image = config('settings.image_default');
                        try {
                            $image = $import->resolveImages($data['imageBigInternet'], $data['imageAltText'], $id);
                        } catch (\ErrorException $e) {
                            Log::info('Image not imported. Product SKU: (' . $data['bookId'] . ') - ' . $data['imageAltText']);
                            Log::info($e->getMessage());
                        }

                        $categories = $import->resolveCategories($data['Kategorii']);

                        ProductCategory::storeData($categories, $id);

                        $product = Product::query()->find($id);

                        $product->update([
                            'image'           => $image,
                            'url'             => ProductHelper::url($product),
                            'category_string' => ProductHelper::categoryString($product)
                        ]);

                        $count++;

                        TempTable::query()->where('sku', $data['bookId'])->delete();
                    }
                }
            }
        }

        return $count;
    }

}
