<?php

use App\Services\Utilities\DatabaseService;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoresTable extends Migration
{
    protected $table = "stores";
    protected $comment = "List of stores on other platforms (ie: Shopify)";

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->table, function (Blueprint $table) {

            // Identification
            $table->id();
            $table->unsignedBigInteger('user_id');

            // Setup
            $table->enum('source', ['shopify', 'ecwid', 'shopcity', 'other'])->default('shopify');
            $table->string('source_url', 100)->nullable()->comment('URL used for oauth authentication');
            $table->string('source_access_token', 100)->nullable()->comment('oauth token to make requests with');

            // Description
            $table->string('source_ref_id', 100)->nullable()->comment('id from source site');
            $table->string('name', 200)->nullable();
            $table->string('url', 200)->nullable();

            // Location
            $table->string('address1', 200)->nullable();
            $table->string('address2', 200)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('province', 100)->nullable();
            $table->string('country', 100)->nullable();
            $table->string('postalcode', 20)->nullable();
            $table->decimal('latitude', 10,7)->nullable();
            $table->decimal('longitude', 10,7)->nullable();

            // Contact
            $table->string('phone', 100)->nullable();
            $table->string('email', 100)->nullable();

            // Meta Info
            $table->longText('raw_json')->nullable()->comment('Full JSON response from source');
            $table->timestamp('update_started_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

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
