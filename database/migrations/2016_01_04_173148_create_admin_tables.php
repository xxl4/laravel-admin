<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminTables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function getConnection()
    {
        return config('admin.database.connection') ?: config('database.default');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('admin.database.users_table'), function (Blueprint $table) {
            $table->increments('id');
            $table->string('username', 190)->unique();
            $table->string('password', 60);
            $table->string('name');
            $table->string('avatar')->nullable();
            $table->string('remember_token', 100)->nullable();
            $table->timestamps();
        });

        Schema::create(config('admin.database.roles_table'), function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50)->unique();
            $table->string('slug', 50)->unique();
            $table->timestamps();
        });

        Schema::create(config('admin.database.permissions_table'), function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50)->unique();
            $table->string('slug', 50)->unique();
            $table->string('http_method')->nullable();
            $table->text('http_path')->nullable();
            $table->timestamps();
        });

        Schema::create(config('admin.database.menu_table'), function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id')->default(0);
            $table->integer('order')->default(0);
            $table->string('title', 50);
            $table->string('icon', 50);
            $table->string('uri')->nullable();
            $table->string('permission')->nullable();

            $table->timestamps();
        });

        Schema::create(config('admin.database.role_users_table'), function (Blueprint $table) {
            $table->integer('role_id');
            $table->integer('user_id');
            $table->index(['role_id', 'user_id']);
            $table->timestamps();
        });

        Schema::create(config('admin.database.role_permissions_table'), function (Blueprint $table) {
            $table->integer('role_id');
            $table->integer('permission_id');
            $table->index(['role_id', 'permission_id']);
            $table->timestamps();
        });

        Schema::create(config('admin.database.user_permissions_table'), function (Blueprint $table) {
            $table->integer('user_id');
            $table->integer('permission_id');
            $table->index(['user_id', 'permission_id']);
            $table->timestamps();
        });

        Schema::create(config('admin.database.role_menu_table'), function (Blueprint $table) {
            $table->integer('role_id');
            $table->integer('menu_id');
            $table->index(['role_id', 'menu_id']);
            $table->timestamps();
        });

        Schema::create(config('admin.database.role_emp_table'), function (Blueprint $table) {
            $table->integer('role_id');
            $table->integer('emp_id');
            $table->index(['role_id', 'emp_id']);
            $table->timestamps();
        });

        Schema::create(config('admin.database.operation_log_table'), function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('path');
            $table->string('method', 10);
            $table->string('ip');
            $table->text('input');
            $table->index('user_id');
            $table->timestamps();
        });

        Schema::create(config('admin.database.apps_table'), function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',200);
            $table->string('desc', 200);
            $table->string('code', 100);
            $table->string('ver', 20);
            $table->string('vernumber', 12);
            $table->timestamps();
        });

        // emp table
        Schema::create(config('admin.database.emp_table'), function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id')->default(0);
            $table->integer('order')->default(0);
            $table->string('title', 50);
            $table->string('full_name', 100);
            $table->string('view_code', 50);
            $table->string('area_code', 50)->nullable();
            $table->string('remarks', 200)->nullable();
            $table->integer('status')->default(1);
            $table->index('status');
            $table->timestamps();
        });
        // emp user table
        Schema::create(config('admin.database.emp_users_table'), function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('epm_name',20);
            $table->string('epm_name_en',20)->nullable();
            $table->string('office_code',20)->nullable();
            $table->string('office_name',50)->nullable();
            $table->string('compnay_code',20)->nullable();
            $table->string('compnay_name',50)->nullable();
            $table->integer("status")->default(1);
            $table->string("remarks", 100)->nullable();
            $table->timestamps();
        });

        // message_table
        Schema::create(config('admin.database.messages_table'), function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sender_id');
            $table->integer('receiver_id');
            $table->integer("pid")->default(0)->comment("message pid");
            $table->tinyInteger("readed")->default(0);
            $table->tinyInteger("deleted")->default(0);
            $table->string('title',150);
            $table->text('message',150);
            $table->dateTime("read_date")->comment("read date");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(config('admin.database.users_table'));
        Schema::dropIfExists(config('admin.database.roles_table'));
        Schema::dropIfExists(config('admin.database.permissions_table'));
        Schema::dropIfExists(config('admin.database.menu_table'));
        Schema::dropIfExists(config('admin.database.user_permissions_table'));
        Schema::dropIfExists(config('admin.database.role_users_table'));
        Schema::dropIfExists(config('admin.database.role_permissions_table'));
        Schema::dropIfExists(config('admin.database.role_menu_table'));
        Schema::dropIfExists(config('admin.database.operation_log_table'));
        Schema::dropIfExists(config('admin.database.emp_table'));
        Schema::dropIfExists(config('admin.database.emp_users_table'));
        Schema::dropIfExists(config('admin.database.role_emp_table'));
        Schema::dropIfExists(config('admin.database.apps_table'));
        Schema::dropIfExists(config('admin.database.messages_table'));
    }
}
