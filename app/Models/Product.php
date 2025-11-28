<?php

namespace App\Models;

use App\Traits\CacheManagerTrait;
use App\Traits\StorageTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * @property int $user_id
 * @property string $added_by
 * @property string $name
 * @property string $code
 * @property string $slug
 * @property int $category_id
 * @property int $sub_category_id
 * @property int $sub_sub_category_id
 * @property int $brand_id
 * @property string $unit
 * @property float $weight
 * @property string $weight_unit
 * @property string $digital_product_type
 * @property string $product_type
 * @property string $details
 * @property int $min_qty
 * @property int $published
 * @property float $tax
 * @property string $tax_type
 * @property string $tax_model
 * @property float $unit_price
 * @property int $status
 * @property float $discount
 * @property int $current_stock
 * @property int $minimum_order_qty
 * @property int $free_shipping
 * @property int $request_status
 * @property int $featured_status
 * @property int $refundable
 * @property int $featured
 * @property int $flash_deal
 * @property int $seller_id
 * @property float $purchase_price
 * @property float $shipping_cost
 * @property int $multiply_qty
 * @property float $temp_shipping_cost
 * @property string $thumbnail
 * @property string $thumbnail_storage_type
 * @property string $preview_file
 * @property string $preview_file_storage_type
 * @property string $digital_file_ready
 * @property string $meta_title
 * @property string $meta_description
 * @property string $meta_image
 * @property int $is_shipping_cost_updated
 * @property float $guest_price
 * @property float $vendor_margin
 * @property float $wholesale_price
 * @property float $selling_price
 * @property float $manual_price
 * @property float $company_fee
 * @property float $delivery_fee
 * @property float $user_promo
 * @property string $user_promo_expiry
 * @property float $seller_promo
 * @property string $seller_promo_expiry
 * @property float $promo
 * @property float $bv
 * @property float $pv
 * @property float $dds_ref_bonus
 * @property float $shop_bonus
 * @property float $shop_reference
 * @property float $product_partner_bonus
 * @property float $product_partner_ref_bonus
 * @property float $company_partner_bonus
 * @property float $franchise_bonus
 * @property float $franchise_ref_bonus
 * @property float $city_ref_bonus
 * @property float $leadership_bonus
 * @property float $vendor_ref_bonus
 * @property float $vendor_bonus
 * @property float $bilty_expense
 * @property float $fuel_expense
 * @property float $visit_expense
 * @property float $shipping_expense
 * @property float $office_expense
 * @property float $event_expense
 * @property float $budget_promo
 * @property int $is_pro
 * @property int $is_vip
 * @property int $is_adult
 * @property int $is_verified
 * @property int $is_choice
 * @property int $manual
 */
class Product extends Model
{
    use StorageTrait, CacheManagerTrait;

    protected $fillable = [
        'user_id',
        'added_by',
        'name',
        'code',
        'slug',
        'category_ids',
        'category_id',
        'sub_category_id',
        'sub_sub_category_id',
        'brand_id',
        'unit',
		'weight',
        'weight_unit',
        'digital_product_type',
        'product_type',
        'details',
        'colors',
        'choice_options',
        'variation',
        'digital_product_file_types',
        'digital_product_extensions',
        'unit_price',
        'purchase_price',
        'tax',
        'tax_type',
        'tax_model',
        'discount',
        'discount_type',
        'attributes',
        'current_stock',
        'minimum_order_qty',
        'video_provider',
        'video_url',
        'status',
        'featured_status',
        'featured',
        'request_status',
        'shipping_cost',
        'multiply_qty',
        'color_image',
        'images',
        'thumbnail',
        'thumbnail_storage_type',
        'preview_file',
        'preview_file_storage_type',
        'digital_file_ready',
        'meta_title',
        'meta_description',
        'meta_image',
        'digital_file_ready_storage_type',
        'is_shipping_cost_updated',
        'temp_shipping_cost',
		'vendor_margin',
		'guest_price',
        'wholesale_price',
        'selling_price',
        'manual_price',
        'company_fee',
        'delivery_fee',
        'user_promo',
        'user_promo_expiry',
        'seller_promo',
        'seller_promo_expiry',
        'promo',
        'bv',
        'pv',
        'dds_ref_bonus',
        'shop_bonus',
        'shop_reference',
        'product_partner_bonus',
        'product_partner_ref_bonus',
        'company_partner_bonus',
        'franchise_bonus',
        'franchise_ref_bonus',
        'city_ref_bonus',
        'leadership_bonus',
        'vendor_ref_bonus',
        'vendor_bonus',
        'bilty_expense',
        'fuel_expense',
        'visit_expense',
        'shipping_expense',
        'office_expense',
        'event_expense',
        'budget_promo',
		'budget',
		'royalty_bonus',
		'is_pro',
        'is_vip',
        'is_adult',
        'is_verified',
        'is_choice',
        'manual',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'user_id' => 'integer',
        'added_by' => 'string',
        'name' => 'string',
        'code' => 'string',
        'slug' => 'string',
        'category_id' => 'integer',
        'sub_category_id' => 'integer',
        'sub_sub_category_id' => 'integer',
        'brand_id' => 'integer',
        'unit' => 'string',
		'weight' => 'float',
        'weight_unit' => 'string',
        'digital_product_type' => 'string',
        'product_type' => 'string',
        'details' => 'string',
        'min_qty' => 'integer',
        'published' => 'integer',
        'tax' => 'float',
        'tax_type' => 'string',
        'tax_model' => 'string',
        'unit_price' => 'float',
        'status' => 'integer',
        'discount' => 'float',
        'current_stock' => 'integer',
        'minimum_order_qty' => 'integer',
        'free_shipping' => 'integer',
        'request_status' => 'integer',
        'featured_status' => 'integer',
        'refundable' => 'integer',
        'featured' => 'integer',
        'flash_deal' => 'integer',
        'seller_id' => 'integer',
        'purchase_price' => 'float',
		'vendor_margin' => 'float',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'shipping_cost' => 'float',
        'multiply_qty' => 'integer',
        'temp_shipping_cost' => 'float',
        'thumbnail' => 'string',
        'preview_file' => 'string',
        'digital_file_ready' => 'string',
        'meta_title' => 'string',
        'meta_description' => 'string',
        'meta_image' => 'string',
        'is_shipping_cost_updated' => 'integer',
        'digital_product_file_types' => 'array',
        'digital_product_extensions' => 'array',
        'thumbnail_storage_type' => 'string',
        'digital_file_ready_storage_type' => 'string',
		'guest_price' => 'float',
        'wholesale_price' => 'float',
        'selling_price' => 'float',
        'manual_price' => 'float',
        'company_fee' => 'float',
        'delivery_fee' => 'float',
        'user_promo' => 'float',
        'user_promo_expiry' => 'datetime',
        'seller_promo' => 'float',
        'seller_promo_expiry' => 'datetime',
        'promo' => 'float',
        'bv' => 'float',
        'pv' => 'float',
        'dds_ref_bonus' => 'float',
        'shop_bonus' => 'float',
        'shop_reference' => 'float',
        'product_partner_bonus' => 'float',
        'product_partner_ref_bonus' => 'float',
        'company_partner_bonus' => 'float',
        'franchise_bonus' => 'float',
        'franchise_ref_bonus' => 'float',
        'city_ref_bonus' => 'float',
        'leadership_bonus' => 'float',
        'vendor_ref_bonus' => 'float',
        'vendor_bonus' => 'float',
        'bilty_expense' => 'float',
        'fuel_expense' => 'float',
        'visit_expense' => 'float',
        'shipping_expense' => 'float',
        'office_expense' => 'float',
        'event_expense' => 'float',
        'budget_promo' => 'float',
		'royalty_bonus' => 'float', 
		'budget' => 'float',
		'is_pro' => 'boolean',
        'is_vip' => 'boolean',
        'is_adult' => 'boolean',
        'is_verified' => 'boolean',
        'is_choice' => 'boolean',
        'manual' => 'boolean',
    ];

    protected $appends = ['is_shop_temporary_close', 'thumbnail_full_url', 'preview_file_full_url', 'color_images_full_url', 'meta_image_full_url', 'images_full_url', 'digital_file_ready_full_url'];

    public function translations(): MorphMany
    {
        return $this->morphMany('App\Models\Translation', 'translationable');
    }

    public function scopeActive($query)
    {
        $brandSetting = getWebConfig(name: 'product_brand');
        $digitalProductSetting = getWebConfig(name: 'digital_product');
        $businessMode = getWebConfig(name: 'business_mode');
        $productType = $digitalProductSetting ? ['digital', 'physical'] : ['physical'];

        return $query->when($businessMode == 'single', function ($query) {
            $query->where(['added_by' => 'admin']);
        })
            ->when($brandSetting, function ($query) use ($brandSetting, $productType) {
                if (!in_array('digital', $productType)) {
                    $query->whereHas('brand', function ($query) {
                        $query->where('status', 1);
                    });
                }
            })
            ->when(!$brandSetting, function ($query) {
                $query->whereNull('brand_id')->where('status', 1);
            })
            ->where(['status' => 1])
            ->where(['request_status' => 1])
            ->SellerApproved()
            ->whereIn('product_type', $productType);
    }

    public function scopeSellerApproved($query): void
    {
        $query->whereHas('seller', function ($query) {
            $query->where(['status' => 'approved'])->whereNotIn('vendor_type', ['franchise', 'shop']);
        })->orWhere(function ($query) {
            $query->where(['added_by' => 'admin', 'status' => 1]);
        });
    }

    public function stocks(): HasMany
    {
        return $this->hasMany(ProductStock::class);
    }

    public function clearanceSale(): HasOne
    {
        return $this->hasOne(StockClearanceProduct::class, 'product_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'product_id');
    }

    //old relation: reviews_by_customer
    public function reviewsByCustomer(): HasMany
    {
        return $this->hasMany(Review::class, 'product_id')->where('customer_id', auth('customer')->id())->whereNotNull('product_id')->whereNull('delivery_man_id');
    }

    public function digitalProductAuthors(): HasMany
    {
        return $this->hasMany(DigitalProductAuthor::class, 'product_id');
    }

    public function digitalProductPublishingHouse(): HasMany
    {
        return $this->hasMany(DigitalProductPublishingHouse::class, 'product_id');
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function scopeStatus($query): Builder
    {
        return $query->where('featured_status', 1);
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class, 'seller_id');
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class, 'user_id');
    }

    public function getIsShopTemporaryCloseAttribute($value): int
    {
        $inHouseTemporaryClose = Cache::get(IN_HOUSE_SHOP_TEMPORARY_CLOSE_STATUS) ?? 0;
        if ($this->added_by == 'admin') {
            return $inHouseTemporaryClose ?? 0;
        } elseif ($this->added_by == 'seller') {
            return Cache::remember('product-shop-close-' . $this->id, 3600, function () {
                return $this?->seller?->shop?->temporary_close ?? 0;
            });
        }
        return 0;
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    //old relation: sub_category
    public function subCategory(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'sub_category_id');
    }

    //old relation: sub_sub_category
    public function subSubCategory(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'sub_sub_category_id');
    }

    public function rating(): HasMany
    {
        return $this->hasMany(Review::class)
            ->select(DB::raw('avg(rating) average, product_id'))
            ->whereNull('delivery_man_id')
            ->groupBy('product_id');
    }

    //old relation: order_details
    public function orderDetails(): HasMany
    {
        return $this->hasMany(OrderDetail::class, 'product_id');
    }

    public function seoInfo(): BelongsTo
    {
        return $this->belongsTo(ProductSeo::class, 'id', 'product_id');
    }

    //old relation: order_delivered
    public function orderDelivered(): HasMany
    {
        return $this->hasMany(OrderDetail::class, 'product_id')
            ->where('delivery_status', 'delivered');

    }

    //old relation: wish_list
    public function wishList(): HasMany
    {
        return $this->hasMany(Wishlist::class, 'product_id');
    }

    public function digitalVariation(): HasMany
    {
        return $this->hasMany(DigitalProductVariation::class, 'product_id');
    }

    public function tags(): BelongsToMany
    {
        if (strpos(url()->current(), '/api')) {
            return $this->belongsToMany(Tag::class)->limit(5);
        }
        return $this->belongsToMany(Tag::class);
    }

    //old relation: flash_deal_product
    public function flashDealProducts(): HasMany
    {
        return $this->hasMany(FlashDealProduct::class);
    }

    public function scopeFlashDeal($query, $flashDealID)
    {
        return $query->whereHas('flashDealProducts.flashDeal', function ($query) use ($flashDealID) {
            return $query->where('id', $flashDealID);
        });
    }

    //old relation: compare_list
    public function compareList(): HasMany
    {
        return $this->hasMany(ProductCompare::class);
    }

    public function getNameAttribute($name): string|null
    {
        if (strpos(url()->current(), '/admin') || strpos(url()->current(), '/vendor') || strpos(url()->current(), '/seller')) {
            return $name;
        }
        return $this->translations[0]->value ?? $name;
    }

    public function getDetailsAttribute($detail): string|null
    {
        if (strpos(url()->current(), '/admin') || strpos(url()->current(), '/vendor') || strpos(url()->current(), '/seller')) {
            return $detail;
        }
        return $this->translations[1]->value ?? $detail;
    }

    public function getThumbnailFullUrlAttribute(): string|null|array
    {
        $value = $this->thumbnail;
        return $this->storageLink('product/thumbnail', $value, $this->thumbnail_storage_type ?? 'public');
    }

    public function getPreviewFileFullUrlAttribute(): string|null|array
    {
        $value = $this->preview_file;
        return $this->storageLink('product/preview', $value, $this->preview_file_storage_type ?? 'public');
    }

    public function getMetaImageFullUrlAttribute(): array
    {
        $value = $this->meta_image;
        return $this->storageLink('product/meta', $value, 'public');
    }

    public function getDigitalFileReadyFullUrlAttribute(): array
    {
        $value = $this->digital_file_ready;
        return $this->storageLink('product/digital-product', $value, $this->digital_file_ready_storage_type ?? 'public');
    }

    public function getColorImagesFullUrlAttribute(): array
    {
        $images = [];
        $value = is_array($this->color_image) ? $this->color_image : json_decode($this->color_image);
        if ($value) {
            foreach ($value as $item) {
                $item = (array)$item;
                $images[] = [
                    'color' => $item['color'],
                    'image_name' => $this->storageLink('product', $item['image_name'], $item['storage'] ?? 'public')
                ];
            }
        }
        return $images;
    }

    public function getImagesFullUrlAttribute(): array
    {
        $images = [];
        $value = is_array($this->images) ? $this->images : json_decode($this->images);
        if ($value) {
            foreach ($value as $item) {
                $item = isset($item->image_name) ? (array)$item : ['image_name' => $item, 'storage' => 'public'];
                $images[] = $this->storageLink('product', $item['image_name'], $item['storage'] ?? 'public');
            }
        }
        return $images;
    }

    protected static function boot(): void
    {
        parent::boot();

        static::saved(function ($model) {
            cacheRemoveByType(type: 'products');
        });

        static::deleted(function ($model) {
            cacheRemoveByType(type: 'products');
        });

        static::addGlobalScope('translate', function (Builder $builder) {
            $builder->with(['translations' => function ($query) {
                if (strpos(url()->current(), '/api')) {
                    return $query->where('locale', App::getLocale());
                } else {
                    return $query->where('locale', getDefaultLanguage());
                }
            }, 'reviews' => function ($query) {
                $query->whereNull('delivery_man_id');
            }]);
        });
    }
}