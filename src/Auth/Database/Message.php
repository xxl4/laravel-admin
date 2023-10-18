<?php

namespace Nicelizhi\Admin\Auth\Database;

use Nicelizhi\Admin\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Menu.
 *
 * @property int $id
 *
 * @method where($parent_id, $id)
 */
class Message extends Model
{
    use DefaultDatetimeFormat;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['pid', 'title', 'sender_id','receiver_id'];

    /**
     * Create a new Eloquent model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $connection = config('admin.database.connection') ?: config('database.default');

        $this->setConnection($connection);

        $this->setTable(config('admin.database.messages_table'));

        parent::__construct($attributes);
    }

    /**
     * belongs to users.
     *
     * @return BelongsTo
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(config('admin.database.users_model'), "sender_id", "id");
    }
    /**
     * belongs to users.
     *
     * @return BelongsTo
     */
    public function receiver(): BelongsTo
    {
        return $this->belongsTo(config('admin.database.users_model'), "receiver_id", "id");
    }

}
