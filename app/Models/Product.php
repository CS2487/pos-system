<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Product
 *
 * This class represents a Product in the POS system.
 * كل كائن (Object) من هذا الكلاس يمثل منتجًا واحدًا في النظام.
 *
 * OOP Concepts applied:
 * - Class
 * - Inheritance
 * - Encapsulation
 * - Traits
 * - ORM (Eloquent)
 * - Relationships
 */
class Product extends Model
{
    /**
     * Trait: HasFactory
     *
     * Used to generate fake data for testing and seeding.
     * يُستخدم لإنشاء بيانات تجريبية (Products) بسهولة.
     */
    use HasFactory;

    /** 
     * $fillable (Encapsulation + Security)
     *
     * English:
     * The $fillable array acts as a SECURITY GUARD  for the model.
     * It protects the model from Mass Assignment vulnerability.
     *
     * Arabic:
     * هذا الحارس يمنع المستخدم من تمرير حقول غير مسموحة
     * عند استخدام create() أو update().
     *
     * Example:
     * Product::create($request->all());
     *
     * Only these fields are allowed to be filled.
     */
    protected $fillable = [
        'category_id',
        'name',
        'sku',
        'price',
        'cost_price',
        'stock',
        'min_stock',
        'description',
        'image',
    ];

    /**
     * $casts (Data Type Control)
     *
     * English:
     * Automatically converts database values into proper PHP types.
     *
     * Arabic:
     * يضمن أن البيانات دائمًا بنوعها الصحيح
     * (مهم جدًا في أنظمة POS لتفادي أخطاء الحساب).
     *
     * price  → decimal with 2 digits
     * stock  → integer
     */
    protected $casts = [
        'price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'stock' => 'integer',
        'min_stock' => 'integer',
    ];

    /**
     * Relationship: Category
     *
     * ORM Concept:
     * belongsTo Relationship
     *
     * English:
     * A Product belongs to ONE Category.
     *
     * Arabic:
     * كل منتج ينتمي إلى تصنيف واحد فقط.
     *
     * Database:
     * products.category_id → categories.id
     *
     * Usage:
     * $product->category->name;
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relationship: Order Items
     *
     * ORM Concept:
     * hasMany Relationship
     *
     * English:
     * A Product can appear in MANY order items.
     *
     * Arabic:
     * نفس المنتج يمكن أن يُباع عدة مرات في طلبات مختلفة.
     *
     * Database:
     * order_items.product_id → products.id
     *
     * Usage:
     * $product->orderItems;
     */
    public function orderItems()
    {
        return $this->hasMany(\App\Models\OrderItem::class);
    }

    /**
     * Summary (Learning Notes)
     *
     * - This class is an OOP representation of the products table.
     * - Eloquent ORM converts PHP code into SQL automatically.
     * - Relationships allow easy navigation between tables.
     *
     * Key Takeaways:
     * $fillable      → Protects against Mass Assignment
     * $casts         → Controls data types
     * belongsTo      → One-to-Many (inverse)
     * hasMany        → One-to-Many
     * Model          → Gives database superpowers (CRUD)
     */
    
}
