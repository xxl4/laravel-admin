<?php

namespace Nicelizhi\Admin\Auth\Database;

use Nicelizhi\Admin\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class EmpUser extends Model
{
    use DefaultDatetimeFormat;

    protected $fillable = ['user_id', 'emp_name'];

    /**
     * Create a new Eloquent model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $connection = config('admin.database.connection') ?: config('database.default');

        $this->setConnection($connection);

        $this->setTable(config('admin.database.emp_users_table'));

        parent::__construct($attributes);
    }

    /**
     * Detach models from the relationship.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
    }
}
