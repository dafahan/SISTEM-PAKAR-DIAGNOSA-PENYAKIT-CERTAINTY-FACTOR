<?php

use App\Models\Calculation;
use App\Models\Disease;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('calculations', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreignIdFor(Disease::class);
            $table->float('value');
            $table->timestamps();
        });

        Schema::create('calculation_details', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Calculation::class)
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreignIdFor(Disease::class)
                ->constrained()
                ->restrictOnDelete()
                ->cascadeOnUpdate();
            $table->float('value');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calculations');
        Schema::dropIfExists('calculation_details');
    }
};
