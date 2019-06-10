<?php

namespace App;

use App\Traits\AdjustmentTracking;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Task
 *
 * @property int $id
 * @property int $creator_id
 * @property string $name
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Task newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Task newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Task query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Task whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Task whereCreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Task whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Task whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Task whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Task whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Task whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int $card_id
 * @property-read \App\Card $card
 * @property-read \App\User $creator
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Task whereCardId($value)
 */
class Task extends Model
{
    use AdjustmentTracking;

    const STATUS_NEW = 'new';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_DONE = 'done';
    const STATUS_REJECTED = 'rejected';

    /** @var array  */
    protected $guarded = [];

    /** @var array  */
    protected $casts = [
        'creator_id' => 'int'
    ];

    /**
     * A task belongs to a creator.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    /**
     * A task belongs to a card.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function card()
    {
        return $this->belongsTo(Card::class);
    }

    /**
     * @param $status
     */
    protected function setStatus($status)
    {
        $this->update(['status' => $status]);
    }

    /**
     * Sets task status to new
     */
    public function new()
    {
        $this->setStatus(self::STATUS_NEW);
    }

    /**
     * @return bool
     */
    public function isNew()
    {
        return $this->status === self::STATUS_NEW;
    }

    /**
     * Sets task status to in progress
     */
    public function inProgress()
    {
        $this->setStatus(self::STATUS_IN_PROGRESS);
    }

    /**
     * @return bool
     */
    public function isInProgress()
    {
        return $this->status === self::STATUS_IN_PROGRESS;
    }

    /**
     * Sets task status to done
     */
    public function done()
    {
        $this->setStatus(self::STATUS_DONE);
    }

    /**
     * @return bool
     */
    public function isDone()
    {
        return $this->status === self::STATUS_DONE;
    }

    /**
     * Sets task status to rejected
     */
    public function rejected()
    {
        $this->setStatus(self::STATUS_REJECTED);
    }

    /**
     * @return bool
     */
    public function isRejected()
    {
        return $this->status === self::STATUS_REJECTED;
    }
}
