<?php

use App\Services\Utilities\DatabaseService;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    protected $table = "products";
    protected $comment = "List of all products in client stores";

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->table, function (Blueprint $table) {

            $table->id();
            $table->unsignedBigInteger('store_id');
            $table->string('source_ref_id', 100)->nullable(); // $product->id
            $table->string('source_url', 200)->nullable();
            $table->string('name', 200); // $product->title
            $table->string('sku', 100)->nullable(); // $variant->sku
            $table->text('description')->nullable(); // $product->body_html
            $table->string('tags', 500)->nullable(); // $product->product_type + $product->tags
            $table->string('price', 50)->nullable(); // $variable->price

            $table->longText('raw_json')->nullable()->comment('Full JSON returned by source');
            $table->timestamps();

            // Indexes

        });

        (new DatabaseService())->setTableComment($this->table, $this->comment);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->table);
    }
}

// migration.create.stub
