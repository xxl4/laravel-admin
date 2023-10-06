<?php

namespace Nicelizhi\Admin\Auth\Database;

use Nicelizhi\Admin\Traits\DefaultDatetimeFormat;
use Nicelizhi\Admin\Traits\ModelTree;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\DB;

/**
 * Class Apps.
 *
 * @property int $id
 *
 * @method where($parent_id, $id)
 */
class Apps extends Model
{
    use DefaultDatetimeFormat;
    use ModelTree {
        ModelTree::boot as treeBoot;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['parent_id', 'order', 'title', 'full_name', 'view_code'];

    /**
     * Create a new Eloquent model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $connection = config('admin.database.connection') ?: config('database.default');

        $this->setConnection($connection);

        $this->setTable(config('admin.database.apps_table'));

        parent::__construct($attributes);
    }
}
