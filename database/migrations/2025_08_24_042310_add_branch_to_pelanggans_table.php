<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('pelanggans', 'branch')) {
            Schema::table('pelanggans', function (Blueprint $table) {
                $table->string('branch')->nullable()->after('alamat');
            });
            
            // Set default value untuk data yang sudah ada
            DB::table('pelanggans')->whereNull('branch')->update(['branch' => 'Pusat']);
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('pelanggans', 'branch')) {
            Schema::table('pelanggans', function (Blueprint $table) {
                $table->dropColumn('branch');
            });
        }
    }
};