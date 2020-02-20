<?php

namespace App\Models;

use App\Enums\SubscriberState;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Enum\Laravel\HasEnums;

/**
 * Class Subscriber
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

    /**
     * Set the subscriber's state to default state.
     */
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

    /**
     * Scope a query to sort the results using given criteria.
     *
     * @param  Builder  $query
     * @param  array  $sortingCriteria
     * @return Builder
     */
    public function scopeSortResponse($query, $sortingCriteria): Builder
    {
        if(isset($sortingCriteria) && array_key_exists('by', $sortingCriteria)) {
            $direction = $sortingCriteria->desc ? 'desc' : 'asc';
            return $query->orderBy($sortingCriteria->by, $direction);
        } else {
            return $query->orderBy('id', 'desc');
        }

    }
}
