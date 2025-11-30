<?php

namespace App\Http\Controllers\Admin\Product;

use App\Contracts\Repositories\AttributeRepositoryInterface;
use App\Contracts\Repositories\AuthorRepositoryInterface;
use App\Contracts\Repositories\BannerRepositoryInterface;
use App\Contracts\Repositories\BrandRepositoryInterface;
use App\Contracts\Repositories\CartRepositoryInterface;
use App\Contracts\Repositories\CategoryRepositoryInterface;
use App\Contracts\Repositories\ColorRepositoryInterface;
use App\Contracts\Repositories\DealOfTheDayRepositoryInterface;
use App\Contracts\Repositories\DigitalProductAuthorRepositoryInterface;
use App\Contracts\Repositories\DigitalProductVariationRepositoryInterface;
use App\Contracts\Repositories\FlashDealProductRepositoryInterface;
use App\Contracts\Repositories\ProductRepositoryInterface;
use App\Contracts\Repositories\CustomerRepositoryInterface;
use App\Contracts\Repositories\ProductSeoRepositoryInterface;
use App\Contracts\Repositories\PublishingHouseRepositoryInterface;
use App\Contracts\Repositories\RestockProductCustomerRepositoryInterface;
use App\Contracts\Repositories\RestockProductRepositoryInterface;
use App\Contracts\Repositories\ReviewRepositoryInterface;
use App\Contracts\Repositories\StockClearanceProductRepositoryInterface;
use App\Contracts\Repositories\StockClearanceSetupRepositoryInterface;
use App\Contracts\Repositories\TranslationRepositoryInterface;
use App\Contracts\Repositories\VendorRepositoryInterface;
use App\Contracts\Repositories\WishlistRepositoryInterface;
use App\Enums\ViewPaths\Admin\Product;
use App\Models\Product as ProductModel;
use App\Enums\WebConfigKey;
use App\Events\ProductRequestStatusUpdateEvent;
use App\Exports\ProductListExport;
use App\Exports\RestockProductListExport;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\ProductDenyRequest;
use App\Http\Requests\ProductAddRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Repositories\DigitalProductPublishingHouseRepository;
use App\Services\ProductService;
use App\Traits\FileManagerTrait;
use App\Traits\ProductTrait;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use App\Models\Formula;

class ProductController extends BaseController
{
    use ProductTrait;

    use FileManagerTrait {
        delete as deleteFile;
        update as updateFile;
    }

    public function __construct(
        private readonly AuthorRepositoryInterface                  $authorRepo,
        private readonly DigitalProductAuthorRepositoryInterface    $digitalProductAuthorRepo,
        private readonly DigitalProductPublishingHouseRepository    $digitalProductPublishingHouseRepo,
        private readonly PublishingHouseRepositoryInterface         $publishingHouseRepo,
        private readonly CategoryRepositoryInterface                $categoryRepo,
        private readonly BrandRepositoryInterface                   $brandRepo,
        private readonly ProductRepositoryInterface                 $productRepo,
        private readonly CustomerRepositoryInterface                $customerRepo,
        private readonly RestockProductRepositoryInterface          $restockProductRepo,
        private readonly RestockProductCustomerRepositoryInterface  $restockProductCustomerRepo,
        private readonly DigitalProductVariationRepositoryInterface $digitalProductVariationRepo,
        private readonly StockClearanceProductRepositoryInterface   $stockClearanceProductRepo,
        private readonly StockClearanceSetupRepositoryInterface     $stockClearanceSetupRepo,
        private readonly ProductSeoRepositoryInterface              $productSeoRepo,
        private readonly VendorRepositoryInterface                  $sellerRepo,
        private readonly ColorRepositoryInterface                   $colorRepo,
        private readonly AttributeRepositoryInterface               $attributeRepo,
        private readonly TranslationRepositoryInterface             $translationRepo,
        private readonly CartRepositoryInterface                    $cartRepo,
        private readonly WishlistRepositoryInterface                $wishlistRepo,
        private readonly FlashDealProductRepositoryInterface        $flashDealProductRepo,
        private readonly DealOfTheDayRepositoryInterface            $dealOfTheDayRepo,
        private readonly ReviewRepositoryInterface                  $reviewRepo,
        private readonly BannerRepositoryInterface                  $bannerRepo,
        private readonly ProductService                             $productService,
    )
    {
    }

    /**
     * @param Request|null $request
     * @param string|null $type
     * @return View Index function is the starting point of a controller
     * Index function is the starting point of a controller
     */
	
	public function bulkUpdate(Request $request)
	{
		try {
			$updatedProductCount = 0;
	
			foreach ($request->products as $productData) {
				// Find product by ID
				$product = ProductModel::find($productData['id']);
	
				if ($product) {

                    // -- Start: Budget Calculation Logic --
                    $purchasePrice = $productData['purchase_price'] ?? null;
                    $unitPrice = $productData['unit_price'] ?? null;

                    // Check if prices are valid for budget calculation
                    if (isset($purchasePrice, $unitPrice) && is_numeric($purchasePrice) && is_numeric($unitPrice) && $purchasePrice > 0 && $unitPrice > 0) {
                        $budget = $unitPrice - $purchasePrice;
                        // Only update budget if there is a non-negative margin
                        if ($budget >= 0) {
                            $product->budget = $budget;
                        }
                    }
                    // -- End: Budget Calculation Logic --

					// Update all other fields for the original product
					$product->unit_price = $productData['unit_price'] ?? $product->unit_price;
					$product->weight = $productData['weight'] ?? $product->weight;
                    $product->weight_unit = $productData['weight_unit'] ?? $product->weight_unit;
					$product->purchase_price = $productData['purchase_price'] ?? $product->purchase_price;
					$product->vendor_margin = $productData['vendor_margin'] ?? $product->vendor_margin;
					$product->guest_price = $productData['guest_price'] ?? $product->guest_price;
					$product->wholesale_price = $productData['wholesale_price'] ?? $product->wholesale_price;
					$product->selling_price = $productData['selling_price'] ?? $product->selling_price;
					$product->manual_price = $productData['manual_price'] ?? $product->manual_price;
					$product->company_fee = $productData['company_fee'] ?? $product->company_fee;
					$product->delivery_fee = $productData['delivery_fee'] ?? $product->delivery_fee;
					$product->user_promo = $productData['user_promo'] ?? $product->user_promo;
					$product->user_promo_expiry = $productData['user_promo_expiry'] ?? $product->user_promo_expiry;
					$product->seller_promo = $productData['seller_promo'] ?? $product->seller_promo;
					$product->seller_promo_expiry = $productData['seller_promo_expiry'] ?? $product->seller_promo_expiry;
					$product->promo = $productData['promo'] ?? $product->promo;
					$product->bv = $productData['bv'] ?? $product->bv;
					$product->pv = $productData['pv'] ?? $product->pv;
					$product->dds_ref_bonus = $productData['dds_ref_bonus'] ?? $product->dds_ref_bonus;
					$product->shop_bonus = $productData['shop_bonus'] ?? $product->shop_bonus;
					$product->shop_reference = $productData['shop_reference'] ?? $product->shop_reference;
					$product->product_partner_bonus = $productData['product_partner_bonus'] ?? $product->product_partner_bonus;
					$product->product_partner_ref_bonus = $productData['product_partner_ref_bonus'] ?? $product->product_partner_ref_bonus;
					$product->company_partner_bonus = $productData['company_partner_bonus'] ?? $product->company_partner_bonus;
					$product->franchise_bonus = $productData['franchise_bonus'] ?? $product->franchise_bonus;
					$product->franchise_ref_bonus = $productData['franchise_ref_bonus'] ?? $product->franchise_ref_bonus;
					$product->city_ref_bonus = $productData['city_ref_bonus'] ?? $product->city_ref_bonus;
					$product->leadership_bonus = $productData['leadership_bonus'] ?? $product->leadership_bonus;
					$product->vendor_ref_bonus = $productData['vendor_ref_bonus'] ?? $product->vendor_ref_bonus;
					$product->vendor_bonus = $productData['vendor_bonus'] ?? $product->vendor_bonus;
					$product->bilty_expense = $productData['bilty_expense'] ?? $product->bilty_expense;
					$product->fuel_expense = $productData['fuel_expense'] ?? $product->fuel_expense;
					$product->visit_expense = $productData['visit_expense'] ?? $product->visit_expense;
					$product->shipping_expense = $productData['shipping_expense'] ?? $product->shipping_expense;
					$product->office_expense = $productData['office_expense'] ?? $product->office_expense;
					$product->event_expense = $productData['event_expense'] ?? $product->event_expense;
					$product->budget_promo = $productData['budget_promo'] ?? $product->budget_promo;
					$product->royalty_bonus = $productData['royalty_bonus'] ?? $product->royalty_bonus;
					$product->current_stock = $productData['current_stock'] ?? $product->current_stock;
					$product->recently_sold = $productData['recently_sold'] ?? $product->recently_sold;
	
					// Save updated product
					$product->save();
					$updatedProductCount++;
	
					// Find and update related products where admin_id matches this product's ID
					$relatedProducts = ProductModel::where('admin_id', $product->id)->get();
					foreach ($relatedProducts as $relatedProduct) {
						$relatedProduct->unit_price = $product->unit_price;
                        $relatedProduct->budget = $product->budget;
						$relatedProduct->weight = $product->weight;
                        $relatedProduct->weight_unit = $product->weight_unit;
                        $relatedProduct->purchase_price = $product->purchase_price;
                        $relatedProduct->vendor_margin = $product->vendor_margin;
                        $relatedProduct->guest_price = $product->guest_price;
                        $relatedProduct->wholesale_price = $product->wholesale_price;
                        $relatedProduct->selling_price = $product->selling_price;
                        $relatedProduct->manual_price = $product->manual_price;
                        $relatedProduct->company_fee = $product->company_fee;
                        $relatedProduct->delivery_fee = $product->delivery_fee;
                        $relatedProduct->user_promo = $product->user_promo;
                        $relatedProduct->user_promo_expiry = $product->user_promo_expiry;
                        $relatedProduct->seller_promo = $product->seller_promo;
                        $relatedProduct->seller_promo_expiry = $product->seller_promo_expiry;
                        $relatedProduct->promo = $product->promo;
                        $relatedProduct->bv = $product->bv;
                        $relatedProduct->pv = $product->pv;
                        $relatedProduct->dds_ref_bonus = $product->dds_ref_bonus;
                        $relatedProduct->shop_bonus = $product->shop_bonus;
                        $relatedProduct->shop_reference = $product->shop_reference;
                        $relatedProduct->product_partner_bonus = $product->product_partner_bonus;
                        $relatedProduct->product_partner_ref_bonus = $product->product_partner_ref_bonus;
                        $relatedProduct->company_partner_bonus = $product->company_partner_bonus;
                        $relatedProduct->franchise_bonus = $product->franchise_bonus;
                        $relatedProduct->franchise_ref_bonus = $product->franchise_ref_bonus;
                        $relatedProduct->city_ref_bonus = $product->city_ref_bonus;
                        $relatedProduct->leadership_bonus = $product->leadership_bonus;
                        $relatedProduct->vendor_ref_bonus = $product->vendor_ref_bonus;
                        $relatedProduct->vendor_bonus = $product->vendor_bonus;
                        $relatedProduct->bilty_expense = $product->bilty_expense;
                        $relatedProduct->fuel_expense = $product->fuel_expense;
                        $relatedProduct->visit_expense = $product->visit_expense;
                        $relatedProduct->shipping_expense = $product->shipping_expense;
                        $relatedProduct->office_expense = $product->office_expense;
                        $relatedProduct->event_expense = $product->event_expense;
                        $relatedProduct->budget_promo = $product->budget_promo;
                        $relatedProduct->royalty_bonus = $product->royalty_bonus;
                        $relatedProduct->recently_sold = $product->recently_sold;

                        // Sync images
                        $relatedProduct->images = $product->images;
                        $relatedProduct->thumbnail = $product->thumbnail;
                        $relatedProduct->thumbnail_storage_type = $product->thumbnail_storage_type;
                        $relatedProduct->meta_image = $product->meta_image;
	
						// Save updated related product
						$relatedProduct->save();
					}
				}
			}
	
			// Create a grammatically correct success message
			$message = $updatedProductCount . ' ' . ($updatedProductCount > 1 ? 'products' : 'product') . ' and their related vendor products have been updated successfully.';
			
			// Add success message to session using ToastMagic
			ToastMagic::success($message);
	
			// Return success response
			return response()->json([
				'success' => true,
				'message' => $message
			]);
	
		} catch (\Exception $e) {
			return response()->json([
				'success' => false,
				'message' => 'Error updating products: ' . $e->getMessage()
			]);
		}
	}
	
    public function index(Request|null $request, string $type = null): View
    {
        return $this->getListView(request: $request, type: ($type == 'vendor' ? 'seller' : 'in_house'));
    }

    public function getAddView(): View
    {
        $categories = $this->categoryRepo->getListWhere(filters: ['position' => 0], dataLimit: 'all');
        $brands = $this->brandRepo->getListWhere(dataLimit: 'all');
        $brandSetting = getWebConfig(name: 'product_brand');
        $digitalProductSetting = getWebConfig(name: 'digital_product');
        $colors = $this->colorRepo->getList(orderBy: ['name' => 'desc'], dataLimit: 'all');
        $attributes = $this->attributeRepo->getList(orderBy: ['name' => 'desc'], dataLimit: 'all');
        $languages = getWebConfig(name: 'pnc_language') ?? null;
        $defaultLanguage = $languages[0];
        $digitalProductFileTypes = ['audio', 'video', 'document', 'software'];
        $digitalProductAuthors = $this->authorRepo->getListWhere(dataLimit: 'all');
        $publishingHouseList = $this->publishingHouseRepo->getListWhere(dataLimit: 'all');

        return view('admin-views.product.add.index', compact('categories', 'brands', 'brandSetting', 'digitalProductSetting', 'colors', 'attributes', 'languages', 'defaultLanguage', 'digitalProductFileTypes', 'digitalProductAuthors', 'publishingHouseList'));
    }

    public function add(ProductAddRequest $request, ProductService $service): JsonResponse|RedirectResponse
    {
        if ($request->ajax()) {
            return response()->json([], 200);
        }

        $dataArray = $service->getAddProductData(request: $request, addedBy: 'admin');
        $savedProduct = $this->productRepo->add(data: $dataArray);
        $this->productRepo->addRelatedTags(request: $request, product: $savedProduct);
        $this->translationRepo->add(request: $request, model: 'App\Models\Product', id: $savedProduct->id);
        $this->updateProductAuthorAndPublishingHouse(request: $request, product: $savedProduct);

        $digitalFileArray = $service->getAddProductDigitalVariationData(request: $request, product: $savedProduct);
        foreach ($digitalFileArray as $digitalFile) {
            $this->digitalProductVariationRepo->add(data: $digitalFile);
        }

        $this->productSeoRepo->add(data: $service->getProductSEOData(request: $request, product: $savedProduct, action: 'add'));

        updateSetupGuideCacheKey(key: 'add_new_product');
        ToastMagic::success(translate('product_added_successfully'));
        return redirect()->route('admin.products.list', ['in_house']);
    }

    public function updateProductAuthorAndPublishingHouse(object|array $request, object|array $product): void
    {
        if ($request['product_type'] == 'digital') {
            if ($request->has('authors')) {
                $authorIds = [];
                foreach ($request['authors'] as $author) {
                    $authorId = $this->authorRepo->updateOrCreate(params: ['name' => $author], value: ['name' => $author]);
                    $authorIds[] = $authorId?->id;
                }

                foreach ($authorIds as $author) {
                    $productAuthorData = ['author_id' => $author, 'product_id' => $product->id];
                    $this->digitalProductAuthorRepo->updateOrCreate(params: $productAuthorData, value: $productAuthorData);
                }

                $this->digitalProductAuthorRepo->deleteWhereNotIn(filters: ['product_id' => $product->id], whereNotIn: ['author_id' => $authorIds]);
            } else {
                $this->digitalProductAuthorRepo->delete(params: ['product_id' => $product->id]);
            }

            if ($request->has('publishing_house')) {
                $publishingHouseIds = [];
                foreach ($request['publishing_house'] as $publishingHouse) {
                    $publishingHouseId = $this->publishingHouseRepo->updateOrCreate(params: ['name' => $publishingHouse], value: ['name' => $publishingHouse]);
                    $publishingHouseIds[] = $publishingHouseId?->id;
                }

                foreach ($publishingHouseIds as $publishingHouse) {
                    $publishingHouseData = ['publishing_house_id' => $publishingHouse, 'product_id' => $product->id];
                    $this->digitalProductPublishingHouseRepo->updateOrCreate(params: $publishingHouseData, value: $publishingHouseData);
                }
                $this->digitalProductPublishingHouseRepo->deleteWhereNotIn(filters: ['product_id' => $product->id], whereNotIn: ['publishing_house_id' => $publishingHouseIds]);
            } else {
                $this->digitalProductPublishingHouseRepo->delete(params: ['product_id' => $product->id]);
            }
        } else {
            $this->digitalProductAuthorRepo->delete(params: ['product_id' => $product->id]);
            $this->digitalProductPublishingHouseRepo->delete(params: ['product_id' => $product->id]);
        }
    }

    public function getListView(Request $request, string $type): View
    {
        $filters = [
            'added_by' => $type,
            'status' => $request['status'],
            'request_status' => $request['request_status'],
            'seller_id' => $request['seller_id'],
            'brand_id' => $request['brand_id'],
            'category_id' => $request['category_id'],
            'sub_category_id' => $request['sub_category_id'],
            'sub_sub_category_id' => $request['sub_sub_category_id'],
        ];

        $paginationLimit = getWebConfig(name: WebConfigKey::PAGINATION_LIMIT) ? getWebConfig(name: WebConfigKey::PAGINATION_LIMIT) : 25;
        $products = $this->productRepo->getListWhere(orderBy: ['id' => 'desc'], searchValue: $request['searchValue'], filters: $filters, relations: ['clearanceSale' => function ($query) {
            return $query->active();
        }], dataLimit: $paginationLimit);
        $sellers = $this->sellerRepo->getByStatusExcept(status: 'pending', relations: ['shop'], paginateBy: $paginationLimit);
        $brands = $this->brandRepo->getListWhere(filters: ['status' => 1], dataLimit: 'all');
        $categories = $this->categoryRepo->getListWhere(filters: ['position' => 0], dataLimit: 'all');
        $subCategory = $this->categoryRepo->getFirstWhere(params: ['id' => $request['sub_category_id']]);
        $subSubCategory = $this->categoryRepo->getFirstWhere(params: ['id' => $request['sub_sub_category_id']]);
		$formulas = Formula::all();
        return view(Product::LIST[VIEW], compact('products', 'sellers', 'brands',
            'categories', 'subCategory', 'subSubCategory', 'filters', 'type', 'formulas'));
    }

    public function getUpdateView(string|int $id): View|RedirectResponse
    {
        $product = $this->productRepo->getFirstWhereWithoutGlobalScope(params: ['id' => $id], relations: ['digitalVariation', 'translations', 'seoInfo', 'digitalProductAuthors.author', 'digitalProductPublishingHouse.publishingHouse']);

        if (!$product) {
            ToastMagic::error(translate('product_not_found') . '!');
            return redirect()->route('admin.products.list', ['in_house']);
        }
        $productAuthorIds = $this->productService->getProductAuthorsInfo(product: $product)['ids'];
        $productPublishingHouseIds = $this->productService->getProductPublishingHouseInfo(product: $product)['ids'];

        $product['colors'] = json_decode($product['colors']);
        $categories = $this->categoryRepo->getListWhere(filters: ['position' => 0], dataLimit: 'all');
        $brands = $this->brandRepo->getListWhere(dataLimit: 'all');
        $brandSetting = getWebConfig(name: 'product_brand');
        $digitalProductSetting = getWebConfig(name: 'digital_product');
        $languages = getWebConfig(name: 'pnc_language') ?? null;
        $colors = $this->colorRepo->getList(orderBy: ['name' => 'desc'], dataLimit: 'all');
        $attributes = $this->attributeRepo->getList(orderBy: ['name' => 'desc'], dataLimit: 'all');
        $defaultLanguage = $languages[0];
        $digitalProductFileTypes = ['audio', 'video', 'document', 'software'];
        $digitalProductAuthors = $this->authorRepo->getListWhere(dataLimit: 'all');
        $publishingHouseList = $this->publishingHouseRepo->getListWhere(dataLimit: 'all');

        return view('admin-views.product.update.index', compact('product', 'categories', 'brands', 'brandSetting', 'digitalProductSetting', 'colors', 'attributes', 'languages', 'defaultLanguage', 'digitalProductFileTypes', 'digitalProductAuthors', 'publishingHouseList', 'productAuthorIds', 'productPublishingHouseIds'));
    }

    public function update(ProductUpdateRequest $request, ProductService $service, string|int $id): JsonResponse|RedirectResponse
    {
        if ($request->ajax()) {
            return response()->json([], 200);
        }

        $product = $this->productRepo->getFirstWhereWithoutGlobalScope(params: ['id' => $id], relations: ['digitalVariation', 'seoInfo']);
        $dataArray = $service->getUpdateProductData(request: $request, product: $product, updateBy: 'admin');
        $this->updateProductAuthorAndPublishingHouse(request: $request, product: $product);

        $this->productRepo->update(id: $id, data: $dataArray);
        $this->productRepo->addRelatedTags(request: $request, product: $product);
        $this->translationRepo->update(request: $request, model: 'App\Models\Product', id: $id);

        self::getDigitalProductUpdateProcess($request, $product);

        $this->productSeoRepo->updateOrInsert(
            params: ['product_id' => $product['id']],
            data: $service->getProductSEOData(request: $request, product: $product, action: 'update')
        );

        $updatedProduct = $this->productRepo->getFirstWhere(params: ['id' => $product['id']]);
        $this->updateRestockRequestListAndNotify(product: $product, updatedProduct: $updatedProduct);
        $this->updateStockClearanceProduct(product: $updatedProduct);

        updateSetupGuideCacheKey(key: 'add_new_product');
        ToastMagic::success(translate('product_updated_successfully'));
        return redirect()->route(Product::VIEW[ROUTE], ['addedBy' => $product['added_by'], 'id' => $product['id']]);
    }

    public function updateStockClearanceProduct($product): void
    {
        $config = $this->stockClearanceSetupRepo->getFirstWhere(params: [
            'setup_by' => $product['added_by'] == 'admin' ? $product['added_by'] : 'vendor',
            'shop_id' => $product['added_by'] == 'admin' ? 0 : $product?->seller?->shop?->id,
        ]);
        $stockClearanceProduct = $this->stockClearanceProductRepo->getFirstWhere(params: ['product_id' => $product['id']]);

        if ($config && $config['discount_type'] == 'product_wise' && $stockClearanceProduct && $stockClearanceProduct['discount_type'] == 'flat') {
            $minimumPrice = $product['unit_price'];
            foreach ((json_decode($product['variation'], true) ?? []) as $variation) {
                if ($variation['price'] < $minimumPrice) {
                    $minimumPrice = $variation['price'];
                }
            }

            if ($minimumPrice < $stockClearanceProduct['discount_amount']) {
                $this->stockClearanceProductRepo->updateByParams(params: ['product_id' => $product['id']], data: ['is_active' => 0]);
            }
        }
    }

    public function getDigitalProductUpdateProcess($request, $product): void
    {
        if ($request->has('digital_product_variant_key') && !$request->hasFile('digital_file_ready')) {
            $getAllVariation = $this->digitalProductVariationRepo->getListWhere(filters: ['product_id' => $product['id']]);
            $getAllVariationKey = $getAllVariation->pluck('variant_key')->toArray();
            $getRequestVariationKey = $request['digital_product_variant_key'];
            $differenceFromDB = array_diff($getAllVariationKey, $getRequestVariationKey);
            $differenceFromRequest = array_diff($getRequestVariationKey, $getAllVariationKey);
            $newCombinations = array_merge($differenceFromDB, $differenceFromRequest);

            foreach ($newCombinations as $newCombination) {
                if (in_array($newCombination, $request['digital_product_variant_key'])) {
                    $uniqueKey = strtolower(str_replace('-', '_', $newCombination));

                    $fileItem = null;
                    if ($request['digital_product_type'] == 'ready_product') {
                        $fileItem = $request->file('digital_files.' . $uniqueKey);
                    }
                    $uploadedFile = '';
                    if ($fileItem) {
                        $uploadedFile = $this->fileUpload(dir: 'product/digital-product/', format: $fileItem->getClientOriginalExtension(), file: $fileItem);
                    }
                    $this->digitalProductVariationRepo->add(data: [
                        'product_id' => $product['id'],
                        'variant_key' => $request->input('digital_product_variant_key.' . $uniqueKey),
                        'sku' => $request->input('digital_product_sku.' . $uniqueKey),
                        'price' => currencyConverter(amount: $request->input('digital_product_price.' . $uniqueKey)),
                        'file' => $uploadedFile,
                    ]);
                }
            }

            foreach ($differenceFromDB as $variation) {
                $variation = $this->digitalProductVariationRepo->getFirstWhere(params: ['product_id' => $product['id'], 'variant_key' => $variation]);
                if ($variation) {
                    $this->digitalProductVariationRepo->delete(params: ['id' => $variation['id']]);
                }
            }

            foreach ($getAllVariation as $variation) {
                if (in_array($variation['variant_key'], $request['digital_product_variant_key'])) {
                    $uniqueKey = strtolower(str_replace('-', '_', $variation['variant_key']));

                    $fileItem = null;
                    if ($request['digital_product_type'] == 'ready_product') {
                        $fileItem = $request->file('digital_files.' . $uniqueKey);
                    }
                    $uploadedFile = $variation['file'] ?? '';
                    $variation = $this->digitalProductVariationRepo->getFirstWhere(params: ['product_id' => $product['id'], 'variant_key' => $variation['variant_key']]);
                    if ($fileItem) {
                        $uploadedFile = $this->fileUpload(dir: 'product/digital-product/', format: $fileItem->getClientOriginalExtension(), file: $fileItem);
                    }
                    $this->digitalProductVariationRepo->updateByParams(params: ['product_id' => $product['id'], 'variant_key' => $variation['variant_key']], data: [
                        'variant_key' => $request->input('digital_product_variant_key.' . $uniqueKey),
                        'sku' => $request->input('digital_product_sku.' . $uniqueKey),
                        'price' => currencyConverter(amount: $request->input('digital_product_price.' . $uniqueKey)),
                        'file' => $uploadedFile,
                    ]);
                }

                if ($request['product_type'] == 'physical' || $request['digital_product_type'] == 'ready_after_sell') {
                    $variation = $this->digitalProductVariationRepo->getFirstWhere(params: ['product_id' => $product['id'], 'variant_key' => $variation['variant_key']]);
                    if ($variation && $variation['file']) {
                        $this->digitalProductVariationRepo->updateByParams(params: ['id' => $variation['id']], data: ['file' => '']);
                    }
                    if ($request['product_type'] == 'physical') {
                        $variation->delete();
                    }
                }
            }
        } else {
            $this->digitalProductVariationRepo->delete(params: ['product_id' => $product['id']]);
        }
    }

    public function getView(string $addedBy, string|int $id): View|RedirectResponse
    {
        $productActive = $this->productRepo->getFirstWhere(params: ['id' => $id], relations: ['digitalVariation', 'seoInfo']);
        if (!$productActive) {
            ToastMagic::error(translate('product_not_found') . '!');
            return redirect()->route('admin.products.list', ['in_house']);
        }
        $isActive = $this->productRepo->getWebFirstWhereActive(params: ['id' => $id]);
        $relations = ['category', 'brand', 'reviews', 'rating', 'orderDetails', 'orderDelivered', 'digitalVariation', 'seoInfo', 'clearanceSale' => function ($query) {
            return $query->active();
        }];
        $product = $this->productRepo->getFirstWhereWithoutGlobalScope(params: ['id' => $id], relations: $relations);
        $product['priceSum'] = $product?->orderDelivered->sum('price');
        $product['qtySum'] = $product?->orderDelivered->sum('qty');
        $product['discountSum'] = $product?->orderDelivered->sum('discount');
        $productColors = [];
        $colors = json_decode($product['colors']);
        foreach ($colors as $color) {
            $getColor = $this->colorRepo->getFirstWhere(params: ['code' => $color]);
            if ($getColor) {
                $productColors[$getColor['name']] = $colors;
            }
        }

        $reviews = $this->reviewRepo->getListWhere(filters: ['product_id' => ['product_id' => $id], 'whereNull' => ['column' => 'delivery_man_id']], relations: ['customer', 'reply'], dataLimit: getWebConfig(name: 'pagination_limit'));
        return view(Product::VIEW[VIEW], compact('product', 'reviews', 'productActive', 'productColors', 'addedBy', 'isActive'));
    }

    public function getSkuCombinationView(Request $request, ProductService $service): JsonResponse
    {
        $product = $this->productRepo->getFirstWhere(params: ['id' => $request['product_id']], relations: ['digitalVariation', 'seoInfo']);
        $combinationView = $service->getSkuCombinationView(request: $request, product: $product);
        return response()->json(['view' => $combinationView]);
    }

    public function getDigitalVariationCombinationView(Request $request, ProductService $service): JsonResponse
    {
        $product = $this->productRepo->getFirstWhere(params: ['id' => $request['product_id']], relations: ['digitalVariation', 'seoInfo']);
        $combinationView = $service->getDigitalVariationCombinationView(request: $request, product: $product);
        return response()->json(['view' => $combinationView]);
    }

    public function deleteDigitalVariationFile(Request $request, ProductService $service): JsonResponse
    {
        $variation = $this->digitalProductVariationRepo->getFirstWhere(params: ['product_id' => $request['product_id'], 'variant_key' => $request['variant_key']]);
        if ($variation) {
            $this->deleteFile(filePath: '/product/digital-product/' . $variation['file']);
            $this->digitalProductVariationRepo->updateByParams(params: ['id' => $variation['id']], data: ['file' => null]);
            return response()->json([
                'status' => 1,
                'message' => translate('delete_successful')
            ]);
        }
        return response()->json([
            'status' => 0,
            'message' => translate('delete_unsuccessful')
        ]);
    }

    public function manualStatusUpdate(Request $request): JsonResponse
    {
        $product = $this->productRepo->getFirstWhere(['id' => $request->id]);
        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => translate('Product_not_found'),
            ]);
        }
            $this->productRepo->update($request->id, ['is_verified' => $status]);
            return response()->json(['status' => true, 'message' => translate('verified_status_updated_successfully')]);
        }
        return response()->json(['status' => false, 'message' => translate('Product_not_found')]);
    }

    public function updateChoiceStatus(Request $request): JsonResponse
    {
        $product = $this->productRepo->getFirstWhere(['id' => $request->id]);
        if ($product) {
            $status = $request->has('is_choice') ? 1 : 0;
            $this->productRepo->update($request->id, ['is_choice' => $status]);
            return response()->json(['status' => true, 'message' => translate('choice_status_updated_successfully')]);
        }
        return response()->json(['status' => false, 'message' => translate('Product_not_found')]);
    }

    public function updateProStatus(Request $request): JsonResponse
    {
        $product = $this->productRepo->getFirstWhere(['id' => $request->id]);
        if ($product) {
            $status = $request->has('is_pro') ? 1 : 0;
            $this->productRepo->update($request->id, ['is_pro' => $status]);
            return response()->json(['status' => true, 'message' => translate('pro_status_updated_successfully')]);
        }
        return response()->json(['status' => false, 'message' => translate('Product_not_found')]);
    }

    public function updateVipStatus(Request $request): JsonResponse
    {
        $product = $this->productRepo->getFirstWhere(['id' => $request->id]);
        if ($product) {
            $status = $request->has('is_vip') ? 1 : 0;
            $this->productRepo->update($request->id, ['is_vip' => $status]);
            return response()->json(['status' => true, 'message' => translate('vip_status_updated_successfully')]);
        }
        return response()->json(['status' => false, 'message' => translate('Product_not_found')]);
    }

    public function updateAdultStatus(Request $request): JsonResponse
    {
        $product = $this->productRepo->getFirstWhere(['id' => $request->id]);
        if ($product) {
            $status = $request->has('is_adult') ? 1 : 0;
            $this->productRepo->update($request->id, ['is_adult' => $status]);
            return response()->json(['status' => true, 'message' => translate('adult_status_updated_successfully')]);
        }
        return response()->json(['status' => false, 'message' => translate('Product_not_found')]);
    }
    
    public function updateFeaturedStatus(Request $request): JsonResponse
    {
        $status = $request['status'];
        $productId = $request['id'];
        $product = $this->productRepo->getFirstWhere(params: ['id' => $productId]);
        return response()->json([
            'result' => $products
        ]);
    }
}

        $success = 1;
        if ($status == 1) {
            $success = $product->added_by == 'seller' && ($product['request_status'] == 0 || $product['request_status'] == 2) ? 0 : 1;
        }
        $updateData = ['status' => $status];
        $data = $success ? $this->productRepo->update(id: $productId, data: $updateData) : null;

        return response()->json([
            'status' => $success,
            'data' => $data,
            'message' => $success ? translate("status_updated_successfully") : translate("status_updated_failed") . ' ' . translate("Product_must_be_approved"),
        ], 200);
    }

    public function deleteImage(Request $request, ProductService $service): RedirectResponse
    {
        $this->deleteFile(filePath: '/product/' . $request['image']);
        $product = $this->productRepo->getFirstWhere(params: ['id' => $request['id']]);

        if (count(json_decode($product['images'])) < 2) {
            ToastMagic::warning(translate('you_can_not_delete_all_images'));
            return back();
        }

        $imageProcessing = $service->deleteImage(request: $request, product: $product);

        $updateData = [
            'images' => json_encode($imageProcessing['images']),
            'color_image' => json_encode($imageProcessing['color_images']),
        ];
        $this->productRepo->update(id: $request['id'], data: $updateData);

        ToastMagic::success(translate('product_image_removed_successfully'));
        return back();
    }

    public function getCategories(Request $request, ProductService $service): JsonResponse
    {
        $parentId = $request['parent_id'];
        $filter = ['parent_id' => $parentId];
        $categories = $this->categoryRepo->getListWhere(filters: $filter, dataLimit: 'all');
        $dropdown = $service->getCategoryDropdown(request: $request, categories: $categories);

        $childCategories = '';
        if (count($categories) == 1) {
            $subCategories = $this->categoryRepo->getListWhere(filters: ['parent_id' => $categories[0]['id']], dataLimit: 'all');
            $childCategories = $service->getCategoryDropdown(request: $request, categories: $subCategories);
        }

        return response()->json([
            'select_tag' => $dropdown,
            'sub_categories' => count($categories) == 1 ? $childCategories : '',
        ]);
    }

    public function exportList(Request $request, string $type): BinaryFileResponse
    {
        $filters = [
            'added_by' => $type == 'in-house' ? 'in_house' : $type,
            'request_status' => $request['status'],
            'seller_id' => $request['seller_id'],
            'brand_id' => $request['brand_id'],
            'category_id' => $request['category_id'],
            'sub_category_id' => $request['sub_category_id'],
            'sub_sub_category_id' => $request['sub_sub_category_id'],
        ];

        $products = $this->productRepo->getListWhere(orderBy: ['id' => 'desc'], searchValue: $request['searchValue'], filters: $filters, dataLimit: 'all');

        $category = (!empty($request['category_id']) && $request->has('category_id')) ? $this->categoryRepo->getFirstWhere(params: ['id' => $request['category_id']]) : 'all';
        $subCategory = (!empty($request->sub_category_id) && $request->has('sub_category_id')) ? $this->categoryRepo->getFirstWhere(params: ['id' => $request['sub_category_id']]) : 'all';
        $subSubCategory = (!empty($request->sub_sub_category_id) && $request->has('sub_sub_category_id')) ? $this->categoryRepo->getFirstWhere(params: ['id' => $request['sub_sub_category_id']]) : 'all';
        $brand = (!empty($request->brand_id) && $request->has('brand_id')) ? $this->brandRepo->getFirstWhere(params: ['id' => $request->brand_id]) : 'all';
        $seller = (!empty($request->seller_id) && $request->has('seller_id')) ? $this->sellerRepo->getFirstWhere(params: ['id' => $request->seller_id]) : '';
        $data = [
            'products' => $products,
            'category' => $category,
            'sub_category' => $subCategory,
            'sub_sub_category' => $subSubCategory,
            'brand' => $brand,
            'searchValue' => $request['searchValue'],
            'type' => $request->type ?? '',
            'seller' => $seller,
            'status' => $request->status ?? '',
        ];
        return Excel::download(new ProductListExport($data), ucwords($request['type']) . '-' . 'product-list.xlsx');
    }

    public function getBarcodeView(Request $request, string|int $id): View|RedirectResponse
    {
        if ($request['limit'] > 270) {
            ToastMagic::warning(translate('you_can_not_generate_more_than_270_barcode'));
            return back();
        }
        $product = $this->productRepo->getFirstWhere(params: ['id' => $id]);
        $rangeData = range(1, $request->limit ?? 4);
        $barcodes = array_chunk($rangeData, 24);
        return view(Product::BARCODE_VIEW[VIEW], compact('product', 'barcodes'));
    }

    public function getStockLimitListView(Request $request, string $type): View
    {
        $stockLimit = getWebConfig(name: 'stock_limit');
        $sortOrderQty = $request['sortOrderQty'];
        $searchValue = $request['searchValue'];
        $withCount = ['orderDetails'];
        $status = $request['status'];
        $filters = [
            'added_by' => $type,
            'product_type' => 'physical',
            'request_status' => $request['status'],
        ];

        $orderBy = [];
        if ($sortOrderQty == 'quantity_asc') {
            $orderBy = ['current_stock' => 'asc'];
        } else if ($sortOrderQty == 'quantity_desc') {
            $orderBy = ['current_stock' => 'desc'];
        } elseif ($sortOrderQty == 'order_asc') {
            $orderBy = ['order_details_count' => 'asc'];
        } elseif ($sortOrderQty == 'order_desc') {
            $orderBy = ['order_details_count' => 'desc'];
        } elseif ($sortOrderQty == 'default') {
            $orderBy = ['id' => 'asc'];
        }
        $products = $this->productRepo->getStockLimitListWhere(orderBy: $orderBy, searchValue: $searchValue, filters: $filters, withCount: $withCount, dataLimit: getWebConfig(name: WebConfigKey::PAGINATION_LIMIT));
        return view(Product::STOCK_LIMIT[VIEW], compact('products', 'searchValue', 'status', 'sortOrderQty', 'stockLimit'));
    }

    public function delete(string|int $id, ProductService $service): RedirectResponse
    {
        $product = $this->productRepo->getFirstWhere(params: ['id' => $id]);

        if ($product) {
            $this->translationRepo->delete(model: 'App\Models\Product', id: $id);
            $this->cartRepo->delete(params: ['product_id' => $id]);
            $this->wishlistRepo->delete(params: ['product_id' => $id]);
            $this->flashDealProductRepo->delete(params: ['product_id' => $id]);
            $this->dealOfTheDayRepo->delete(params: ['product_id' => $id]);
            $service->deleteImages(product: $product);
            $this->productRepo->delete(params: ['id' => $id]);
            $bannerIds = $this->bannerRepo->getListWhere(filters: ['resource_type' => 'product', 'resource_id' => $product['id']])->pluck('id');
            $bannerIds->map(function ($bannerId) {
                $this->bannerRepo->update(id: $bannerId, data: ['published' => 0, 'resource_id' => null]);
            });
            ToastMagic::success(translate('product_removed_successfully'));
        } else {
            ToastMagic::error(translate('invalid_product'));
        }

        return back();
    }

    public function deleteRestock(string|int $id): RedirectResponse
    {
        $this->restockProductRepo->delete(params: ['id' => $id]);
        $this->restockProductCustomerRepo->delete(params: ['restock_product_id' => $id]);
        ToastMagic::success(translate('product_restock_removed_successfully'));
        return back();
    }

    public function getVariations(Request $request): JsonResponse
    {
        $product = $this->productRepo->getFirstWhere(params: ['id' => $request['id']]);
        $restockId = $request['restock_id'];
        $restockVariants = $this->restockProductRepo->getListWhereBetween(filters: ['product_id' => $request['id']])?->pluck('variant')->toArray() ?? [];

        return response()->json([
            'view' => view(Product::GET_VARIATIONS[VIEW], compact('product', 'restockId', 'restockVariants'))->render()
        ]);
    }

    public function updateQuantity(Request $request): RedirectResponse
    {
        $variations = [];
        $stockCount = $request['current_stock'];
        if ($request->has('type')) {
            foreach ($request['type'] as $key => $str) {
                $item = [];
                $item['type'] = $str;
                $item['price'] = currencyConverter(amount: abs($request['price_' . str_replace('.', '_', $str)]));
                $item['sku'] = $request['sku_' . str_replace('.', '_', $str)];
                $item['qty'] = abs($request['qty_' . str_replace('.', '_', $str)]);
                $variations[] = $item;
            }
        }
        $dataArray = [
            'current_stock' => $stockCount,
            'variation' => json_encode($variations),
        ];

        if ($stockCount >= 0) {
            $product = $this->productRepo->getFirstWhere(params: ['id' => $request['product_id']]);
            $this->productRepo->updateByParams(params: ['id' => $request['product_id']], data: $dataArray);
            $updatedProduct = $this->productRepo->getFirstWhere(params: ['id' => $request['product_id']]);
            $this->updateRestockRequestListAndNotify(product: $product, updatedProduct: $updatedProduct);

            ToastMagic::success(translate('product_quantity_updated_successfully'));
            return back();
        }
        ToastMagic::warning(translate('product_quantity_can_not_be_less_than_0_'));
        return back();
    }

    public function getBulkImportView(): View
    {
        return view(Product::BULK_IMPORT[VIEW]);
    }

    public function importBulkProduct(Request $request, ProductService $service): RedirectResponse
    {
        $dataArray = $service->getImportBulkProductData(request: $request, addedBy: 'admin');
        if (!$dataArray['status']) {
            ToastMagic::error($dataArray['message']);
            return back();
        }

        $this->productRepo->addArray(data: $dataArray['products']);
        ToastMagic::success($dataArray['message']);
        return back();
    }

    public function updatedProductList(Request $request): View
    {
        $filters = [
            'added_by' => 'seller',
            'is_shipping_cost_updated' => 0,
        ];
        $searchValue = $request['searchValue'];

        $products = $this->productRepo->getListWhere(orderBy: ['id' => 'desc'], searchValue: $searchValue, filters: $filters, dataLimit: getWebConfig(name: WebConfigKey::PAGINATION_LIMIT));
        return view(Product::UPDATED_PRODUCT_LIST[VIEW], compact('products', 'searchValue'));
    }

    public function updatedShipping(Request $request): JsonResponse
    {
        $product = $this->productRepo->getFirstWhere(params: ['id' => $request['id']]);
        $dataArray = ['is_shipping_cost_updated' => $request['status']];
        if ($request['status'] == 1) {
            $dataArray += [
                'shipping_cost' => $product['temp_shipping_cost']
            ];
        }
        $this->productRepo->update(id: $request['id'], data: $dataArray);

        return response()->json(['message' => translate('status_updated_successfully')], 200);
    }

    public function deny(ProductDenyRequest $request): JsonResponse
    {
        $dataArray = [
            'request_status' => 2,
            'status' => 0,
            'denied_note' => $request['denied_note'],
        ];
        $this->productRepo->update(id: $request['id'], data: $dataArray);
        $product = $this->productRepo->getFirstWhereWithoutGlobalScope(params: ['id' => $request['id']]);
        $vendor = $this->sellerRepo->getFirstWhere(params: ['id' => $product['user_id']]);
        if ($vendor['cm_firebase_token']) {
            ProductRequestStatusUpdateEvent::dispatch('product_request_rejected_message', 'seller', $vendor['app_language'] ?? getDefaultLanguage(), $vendor['cm_firebase_token']);
        }
        return response()->json(['message' => translate('product_request_denied') . '.']);
    }

    public function approveStatus(Request $request): JsonResponse
    {
        $product = $this->productRepo->getFirstWhereWithoutGlobalScope(params: ['id' => $request['id']]);
        $dataArray = [
            'request_status' => ($product['request_status'] == 0) ? 1 : 0
        ];
        $this->productRepo->update(id: $request['id'], data: $dataArray);
        $vendor = $this->sellerRepo->getFirstWhere(params: ['id' => $product['user_id']]);
        if ($vendor['cm_firebase_token']) {
            ProductRequestStatusUpdateEvent::dispatch('product_request_approved_message', 'seller', $vendor['app_language'] ?? getDefaultLanguage(), $vendor['cm_firebase_token']);
        }
        return response()->json(['message' => translate('product_request_approved') . '.']);
    }

    public function getSearchedProductsView(Request $request): JsonResponse
    {
        $searchValue = $request['searchValue'] ?? null;
        $products = $this->productRepo->getListWhere(
            searchValue: $searchValue,
            filters: [
                'added_by' => 'in_house',
                'status' => 1,
                'category_id' => $request['category_id'],
                'code' => $request['name'],
            ],
            dataLimit: getWebConfig(name: 'pagination_limit')
        );
        return response()->json([
            'count' => $products->count(),
            'result' => view(Product::SEARCH[VIEW], compact('products'))->render(),
        ]);
    }

    public function getProductGalleryView(Request $request): View
    {
        $searchValue = $request['searchValue'];
        $filters = [
            'added_by' => ($request['vendor_id'] == 'in_house' || $request['added_by'] == 'in_house' || auth('admin')->check()) ? 'in_house' : '',
            'searchValue' => $searchValue,
            'request_status' => 1,
            'product_search_type' => 'product_gallery',
            'seller_id' => ($request['vendor_id'] == 'in_house' || $request['added_by'] == 'in_house') ? '' : $request['vendor_id'],
            'brand_id' => $request['brand_id'],
            'category_id' => $request['category_id'],
        ];
        $products = $this->productRepo->getListWhere(orderBy: ['id' => 'desc'], searchValue: $request['searchValue'], filters: $filters, dataLimit: getWebConfig(name: WebConfigKey::PAGINATION_LIMIT));
        $products->map(function ($product) {
            if ($product->product_type == 'physical' && count(json_decode($product->choice_options)) > 0 || count(json_deocde($product->colors)) > 0) {
                $colorName = [];
                $colorsCollection = collect(json_decode($product->colors));
                $colorsCollection->map(function ($color) use (&$colorName) {
                    $colorName[] = $this->colorRepo->getFirstWhere(['code' => $color])->name;
                });
                $product['colorsName'] = $colorName;
            }
        });
        $vendors = $this->sellerRepo->getListWhere(filters: ['status' => 'approved'], relations: ['shop'], dataLimit: 'all');
        $brands = $this->brandRepo->getListWhere(filters: ['status' => 1], dataLimit: 'all');
        $categories = $this->categoryRepo->getListWhere(filters: ['position' => 0], dataLimit: 'all');
        return view(Product::PRODUCT_GALLERY[VIEW], compact('products', 'vendors', 'brands', 'categories', 'searchValue'));
    }

    public function getStockLimitStatus(Request $request, string $type): JsonResponse
    {
        $filters = [
            'added_by' => $type,
            'product_type' => 'physical',
            'request_status' => $request['status'],
        ];
        $products = $this->productRepo->getStockLimitListWhere(filters: $filters, dataLimit: 'all');
        if ($products->count() == 1) {
            $product = $products->first();
            $thumbnail = getStorageImages(path: $product->thumbnail_full_url, type: 'backend-product');
            return response()->json(['status' => 'one_product', 'product_count' => 1, 'product' => $product, 'thumbnail' => $thumbnail]);
        } else {
            return response()->json(['status' => 'multiple_product', 'product_count' => $products->count()]);
        }

    }

    public function getMultipleProductDetailsView(Request $request): JsonResponse
    {
        $selectedProducts = $this->productRepo->getListWhere(
            filters: [
                'productIds' => $request['productIds'],
            ],
            dataLimit: 'all'
        );
        return response()->json([
            'result' => view(Product::MULTIPLE_PRODUCT_DETAILS[VIEW], compact('selectedProducts'))->render(),
        ]);
    }

    public function deletePreviewFile(Request $request): JsonResponse
    {
        $product = $this->productRepo->getFirstWhereWithoutGlobalScope(params: ['id' => $request['product_id']]);
        $this->productService->deletePreviewFile(product: $product);
        $this->productRepo->update(id: $request['product_id'], data: ['preview_file' => null]);
        return response()->json([
            'status' => 1,
            'message' => translate('Preview_file_deleted')
        ]);
    }

    public function getRequestRestockListView(Request $request): View|RedirectResponse
    {
        $filters = [
            'added_by' => 'in_house',
            'brand_id' => $request['brand_id'],
            'category_id' => $request['category_id'],
            'sub_category_id' => $request['sub_category_id'],
        ];

        $startDate = '';
        $endDate = '';
        if (isset($request['restock_date']) && !empty($request['restock_date'])) {
            $dates = explode(' - ', $request['restock_date']);
            $startDate = Carbon::createFromFormat('d M Y', $dates[0])->startOfDay();
            $endDate = Carbon::createFromFormat('d M Y', $dates[1])->endOfDay();
        }
        $restockProducts = $this->restockProductRepo->getListWhereBetween(
            orderBy: ['updated_at' => 'desc'],
            searchValue: $request['searchValue'],
            filters: $filters,
            relations: ['product'],
            whereBetween: 'created_at',
            whereBetweenFilters: $startDate && $endDate ? [$startDate, $endDate] : [],
            dataLimit: getWebConfig(name: WebConfigKey::PAGINATION_LIMIT),
        );
        $brands = $this->brandRepo->getListWhere(filters: ['status' => 1], dataLimit: 'all');
        $categories = $this->categoryRepo->getListWhere(filters: ['position' => 0], dataLimit: 'all');
        $subCategory = $this->categoryRepo->getFirstWhere(params: ['id' => $request['sub_category_id']]);
        $totalRestockProducts = $this->restockProductRepo->getListWhere(filters: $filters, dataLimit: 'all')->count();
        return view(Product::REQUEST_RESTOCK_LIST[VIEW], compact('restockProducts', 'brands',
            'categories', 'subCategory', 'filters', 'totalRestockProducts'));
    }

    public function exportRestockList(Request $request): BinaryFileResponse
    {
        $filters = [
            'added_by' => 'in_house',
            'brand_id' => $request['brand_id'],
            'category_id' => $request['category_id'],
            'sub_category_id' => $request['sub_category_id'],
        ];

        $startDate = '';
        $endDate = '';
        if (isset($request['restock_date']) && !empty($request['restock_date'])) {
            $dates = explode(' - ', $request['restock_date']);
            $startDate = Carbon::createFromFormat('m/d/Y', $dates[0])->startOfDay();
            $endDate = Carbon::createFromFormat('m/d/Y', $dates[1])->endOfDay();
        }

        $restockProducts = $this->restockProductRepo->getListWhereBetween(
            orderBy: ['updated_at' => 'desc'],
            searchValue: $request['searchValue'],
            filters: $filters,
            relations: ['product'],
            whereBetween: 'created_at',
            whereBetweenFilters: $startDate && $endDate ? [$startDate, $endDate] : [],
            dataLimit: 'all',
        );
        $brand = (!empty($request->brand_id) && $request->has('brand_id')) ? $this->brandRepo->getFirstWhere(params: ['id' => $request->brand_id]) : 'all';
        $category = (!empty($request['category_id']) && $request->has('category_id')) ? $this->categoryRepo->getFirstWhere(params: ['id' => $request['category_id']]) : 'all';
        $subCategory = (!empty($request->sub_category_id) && $request->has('sub_category_id')) ? $this->categoryRepo->getFirstWhere(params: ['id' => $request['sub_category_id']]) : 'all';

        $data = [
            'products' => $restockProducts,
            'category' => $category,
            'subCategory' => $subCategory,
            'brand' => $brand,
            'searchValue' => $request['searchValue'],
            'startDate' => $startDate,
            'endDate' => $endDate,
        ];
        return Excel::download(new RestockProductListExport($data), 'restock-product-list.xlsx');
    }

	public function applyBulkFormula(Request $request)
	{
		$request->validate([
			'formula_id' => 'required|exists:formulas,id',
			'apply_scope' => 'required|in:all,current_page',
		]);
	
		$formula = Formula::findOrFail($request->formula_id);
		$productIds = $request->input('product_ids') ? json_decode($request->input('product_ids'), true) : null;
	
		// Start query, filtering out products with manual = 1
		$query = ProductModel::where('manual', 0);
	
		if ($request->apply_scope === 'current_page' && $productIds) {
			$query->whereIn('id', $productIds);
		}
	
		$query->chunk(100, function ($products) use ($formula) {
			foreach ($products as $product) {
				// -- Start: Enhanced Validation --
                // Skip if unit_price or purchase_price is not a valid, positive number
                if (empty($product->unit_price) || empty($product->purchase_price) || !is_numeric($product->unit_price) || !is_numeric($product->purchase_price) || $product->unit_price <= 0 || $product->purchase_price <= 0) {
                    continue;
                }
                // -- End: Enhanced Validation --

				$priceDifference = $product->unit_price - $product->purchase_price;
				// Skip if there is no positive margin
				if ($priceDifference <= 0) continue;
	
				$increasedUnitPrice = $product->unit_price * 1.30;
				$updatedValues = [
					'budget'               => $priceDifference, // Set budget to the price difference
					'guest_price'          => $increasedUnitPrice,
					'manual_price'         => $increasedUnitPrice,
					'bv'                   => ($priceDifference * $formula->bv) / 100,
					'pv'                   => (($priceDifference * $formula->pv) / 100) / 68,
					'dds_ref_bonus'        => ($priceDifference * $formula->dds_ref_bonus) / 100,
					'shop_bonus'           => ($priceDifference * $formula->shop_bonus) / 100,
					'shop_reference'       => ($priceDifference * $formula->shop_reference) / 100,
					'franchise_bonus'      => ($priceDifference * $formula->franchise_bonus) / 100,
					'franchise_ref_bonus'  => ($priceDifference * $formula->franchise_ref_bonus) / 100,
					'city_ref_bonus'       => ($priceDifference * $formula->city_ref_bonus) / 100,
					'leadership_bonus'     => ($priceDifference * $formula->leadership_bonus) / 100,
					'promo'                => ($priceDifference * $formula->promo) / 100,
					'user_promo'           => ($priceDifference * $formula->user_promo) / 100,
					'seller_promo'         => ($priceDifference * $formula->seller_promo) / 100,
					'shipping_expense'     => ($priceDifference * $formula->shipping_expense) / 100,
					'bilty_expense'        => ($priceDifference * $formula->bilty_expense) / 100,
					'office_expense'       => ($priceDifference * $formula->office_expense) / 100,
					'event_expense'        => ($priceDifference * $formula->event_expense) / 100,
					'fuel_expense'         => ($priceDifference * $formula->fuel_expense) / 100,
					'visit_expense'        => ($priceDifference * $formula->visit_expense) / 100,
					'company_partner_bonus'=> ($priceDifference * $formula->company_partner_bonus) / 100,
					'product_partner_bonus'=> ($priceDifference * $formula->product_partner_bonus) / 100,
					'budget_promo'         => ($priceDifference * $formula->budget_promo) / 100,
					'product_partner_ref_bonus' => ($priceDifference * $formula->product_partner_ref_bonus) / 100,
					'vendor_ref_bonus'     => ($priceDifference * $formula->vendor_ref_bonus) / 100,
					'royalty_bonus'        => ($priceDifference * $formula->royalty_bonus) / 100,
				];
	
				// Update the admin product
				$product->update($updatedValues);
	
                // Add image data to the updated values for vendor products
                $updatedValues['images'] = $product->images;
                $updatedValues['thumbnail'] = $product->thumbnail;
                $updatedValues['thumbnail_storage_type'] = $product->thumbnail_storage_type;
                $updatedValues['meta_image'] = $product->meta_image;
                $updatedValues['purchase_price'] = $product->purchase_price;
                $updatedValues['unit_price'] = $product->unit_price;
				$updatedValues['weight'] = $product->weight;
                $updatedValues['weight_unit'] = $product->weight_unit;

				// Update related vendor products (linked via admin_id)
				ProductModel::where('admin_id', $product->id)->update($updatedValues);

			}
		});
	
		$message = $request->apply_scope === 'all' 
			? 'Formula applied to all non-manual products and related vendor products successfully!'
			: 'Formula applied to current page non-manual products and related vendor products successfully!';
		
		ToastMagic::success($message);
	
		return response()->json([
			'success' => true,
			'message' => $message,
		]);
	}
	
	public function reviewProductsStatus(Request $request): JsonResponse
	{
		$fieldsToCheck = [
			'purchase_price', 'unit_price', 'bv', 'pv', 'dds_ref_bonus', 'shop_bonus', 'shop_reference',
			'franchise_bonus', 'franchise_ref_bonus', 'city_ref_bonus', 'leadership_bonus', 'promo', 'user_promo',
			'seller_promo', 'shipping_expense', 'bilty_expense', 'office_expense', 'event_expense', 'fuel_expense',
			'visit_expense', 'company_partner_bonus', 'product_partner_bonus', 'budget_promo',
			'product_partner_ref_bonus', 'vendor_ref_bonus', 'royalty_bonus'
		];

		// Get all active products from the database
		$activeProducts = ProductModel::where('status', 1)->get();
		$missingDataProducts = [];

		foreach ($activeProducts as $product) {
			$missingFields = [];
			foreach ($fieldsToCheck as $field) {
				if (is_null($product->$field) || $product->$field == 0) {
					$missingFields[] = ucwords(str_replace('_', ' ', $field));
				}
			}

			if (!empty($missingFields)) {
				$missingDataProducts[] = [
					'id' => $product->id,
					'name' => $product->name,
					'missing_fields' => implode(', ', $missingFields)
				];
			}
		}

		if (empty($missingDataProducts)) {
			return response()->json(['success' => true, 'message' => 'All active products are complete. No action needed.']);
		}

		return response()->json(['success' => true, 'products' => $missingDataProducts]);
	}

	public function deactivateIncompleteProducts(Request $request): JsonResponse
	{
		$productIds = $request->input('product_ids');

		if (empty($productIds)) {
			return response()->json(['success' => false, 'message' => 'No products selected for deactivation.']);
		}

		ProductModel::whereIn('id', $productIds)->update(['status' => 0]);

		ToastMagic::success(count($productIds) . ' products have been deactivated due to incomplete data.');
		return response()->json(['success' => true, 'message' => 'Products deactivated successfully.']);
	}

    public function getSearchedProducts(Request $request): JsonResponse
    {
        $searchValue = $request['name'];
        $products = $this->productRepo->getListWhere(
            orderBy: ['name' => 'asc'],
            searchValue: $searchValue,
            filters: ['status' => 1],
            dataLimit: 20
        );

        return response()->json([
            'result' => $products
        ]);
    }
}