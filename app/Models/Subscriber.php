<?php

namespace App\Models;

use App\Enums\SubscriberState;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Enum\Laravel\HasEnums;

class Subscriber extends Model
{
    use HasEnums;

    /**
     * Defining account states
     */
    protected $enums = [
        'state' => SubscriberState::class,
    ];
    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @return BelongsToMany
     */
    public function fields(): BelongsToMany
    {
        return $this->belongsToMany(Field::class)->withPivot('value');
    }

    public function setDefaultState(): void
    {
        $this->state = SubscriberState::UNCONFIRMED();
    }
}
