<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\CardParticipant
 *
 * @property int $id
 * @property int $user_id
 * @property int $card_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Card $card
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CardParticipant newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CardParticipant newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CardParticipant query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CardParticipant whereCardId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CardParticipant whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CardParticipant whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CardParticipant whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CardParticipant whereUserId($value)
 * @mixin \Eloquent
 */
class CardParticipant extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Get the participant user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the card of participant.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function card()
    {
        return $this->belongsTo(Card::class);
    }
}
