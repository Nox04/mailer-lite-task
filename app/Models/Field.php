<?php

namespace App\Models;

use App\Enums\FieldType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Enum\Laravel\HasEnums;

/**
 * @property int id
 * @property FieldType type
 */
class Field extends Model
{
    use HasEnums;

    /**
     * Defining account states
     */
    protected $enums = [
        'type' => FieldType::class,
    ];

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @return BelongsToMany
     */
    public function subscribers(): BelongsToMany
    {
        return $this->belongsToMany(Subscriber::class);
    }
}
