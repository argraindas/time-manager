<?php

namespace App;

use App\Traits\AdjustmentTracking;
use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * App\Card
 *
 * @property int $id
 * @property int $creator_id
 * @property string $name
 * @property string|null $description
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Card newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Card newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Card query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Card whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Card whereCreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Card whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Card whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Card whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Card whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Card whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \App\User $creator
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\CardParticipant[] $participants
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Task[] $tasks
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Card orParticipant(\App\User $user)
 * @property string $uuid
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $adjustments
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Card whereUuid($value)
 */
class Card extends Model
{
    use UsesUuid, AdjustmentTracking;

    const STATUS_OPEN = 'open';
    const STATUS_FINISHED = 'finished';
    const STATUS_CLOSED = 'closed';

    /** @var array  */
    protected $guarded = [];

    /** @var array  */
    protected $casts = [
        'creator_id' => 'int'
    ];

    /** @var array  */
    protected $with = ['creator', 'participants', 'tasks'];

    /**
     * @param Builder $query
     * @param User    $user
     *
     * @return Builder|\Illuminate\Database\Query\Builder
     */
    public function scopeOrParticipant(Builder $query, User $user)
    {
        return $query
            ->select('cards.*')
            ->leftJoin('card_participants AS cp', function($join) use ($user) {
                /** @var \Illuminate\Database\Query\JoinClause $join */
                $join->on('cards.id', '=', 'cp.card_id');
                $join->on('cp.user_id', '=', DB::raw("'".$user->id."'"));
            })
            ->orWhere('cp.user_id', '=', $user->id)
            ->groupBy(['cards.id']);
    }

    /**
     * Users that are available to be assigned to the card
     *
     * @return User[]|\Illuminate\Support\Collection
     */
    public function availableUsers()
    {
        return User::query()->whereNotIn('id', function ($query) {
            /** @var Builder $query */
            return $query->select('cp.user_id')
                ->from('card_participants AS cp')
                ->where('cp.card_id', '=', $this->id);
        })->get();
    }

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
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tasks()
    {
        return $this->hasMany(Task::class)->latest('id');
    }

    /**
     * Card has many participants
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function participants()
    {
        return $this->belongsToMany(User::class, 'card_participants')
            ->as('assignment')
            ->withTimestamps();
    }

    /**
     * @param User $user
     */
    public function assignParticipant(User $user)
    {
        $this->participants()->attach($user);
    }

    /**
     * @param User $user
     */
    public function removeParticipant(User $user)
    {
        $this->participants()->detach($user);
    }

    /**
     * @param array $task
     *
     * @return Model
     */
    public function addTask(array $task)
    {
        return $this->tasks()->create($task);
    }

    /**
     * @param Task $task
     */
    public function removeTask(Task $task)
    {
        $this->tasks()
            ->where('id', $task->id)
            ->delete();
    }

    /**
     * @param $status
     */
    protected function setStatus($status)
    {
        $this->update(['status' => $status]);
    }

    /**
     * Sets card status to finished
     */
    public function finish()
    {
        $this->setStatus(self::STATUS_FINISHED);
    }

    /**
     * @return bool
     */
    public function isFinished()
    {
        return $this->status === self::STATUS_FINISHED;
    }

    /**
     * Sets card status to closed
     */
    public function close()
    {
        $this->setStatus(self::STATUS_CLOSED);
    }

    /**
     * @return bool
     */
    public function isClosed()
    {
        return $this->status === self::STATUS_CLOSED;
    }

    /**
     * Sets card status to open
     */
    public function open()
    {
        $this->setStatus(self::STATUS_OPEN);
    }

    /**
     * @return bool
     */
    public function isOpen()
    {
        return $this->status === self::STATUS_OPEN;
    }
}
