<?php

use App\Services\Utilities\DatabaseService;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateErrorLogTable extends Migration
{
    protected $table = "error_log";
    protected $comment = "Error messages";

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->table, function (Blueprint $table) {

            $table->id();
            $table->string('event', 100)->comment('Description of event that caused error');
            $table->string('message', 5000)->nullable()->comment('Error message');
            $table->text('data')->nullable()->comment('Supporting info in JSON format');
            $table->timestamp('created_at')->useCurrent();

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
