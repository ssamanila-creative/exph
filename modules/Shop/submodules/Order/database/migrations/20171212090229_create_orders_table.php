<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Pluma\Support\Migration\Migration;
use Phinx\Migration\AbstractMigration;

class CreateOrdersTable extends Migration
{
    /**
     * The table name.
     *
     * @var string
     */
    protected $tablename = 'orders';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if ($this->schema->hasTable($this->tablename)) {
            return;
        }

        $this->schema->create($this->tablename, function (Blueprint $table) {
            $table->increments('id');
            $table->integer('customer_id')->unsigned()->nullable();
            $table->integer('experience_id')->unsigned()->nullable();
            $table->string('total')->nullable();
            $table->string('price')->nullable();
            $table->integer('quantity')->nullable();
            $table->integer('availability_id')->unsigned()->nullable();
            $table->string('payment_id')->nullable();
            $table->string('status')->default('pending')->nullable();
            $table->text('payer_id')->nullable();
            $table->text('token')->nullable();
            $table->text('metadata')->nullable();
            $table->timestamp('purchased_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->schema->dropIfExists($this->tablename);
    }
}
