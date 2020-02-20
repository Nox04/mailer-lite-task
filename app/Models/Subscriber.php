<?php

namespace App\Models;

use App\Enums\SubscriberState;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Enum\Laravel\HasEnums;

/**
 * @property int id
 * @property SubscriberState state
 */
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

    /**
     * Scope a query to only include users of a given state or states.
     *
     * @param  Builder  $query
     * @param  mixed  $states
     * @return Builder
     */
    public function scopeState($query, $states): Builder
    {
        foreach ($states as $state) {
            $query->orWhere('state', $state);
        }
        return $query;
    }
}
