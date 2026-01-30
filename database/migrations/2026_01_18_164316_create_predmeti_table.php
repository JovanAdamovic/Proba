  <?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void  //up() pravi izmene u bazi
    {
       Schema::create('predmeti', function (Blueprint $table) { ////Napravi novu tabelu predmeti.
        $table->engine = 'InnoDB'; //koristi InnoDB engine ,omogućava foreign keys, relacije, cascade, itd.
        $table->id(); //To je primarni ključ predmeta.
        $table->foreignId('profesor_id')->nullable() //nullable() Profesor može biti privremeno bez profesora
              ->constrained('users')->nullOnDelete(); //constrained('users') - profesor mora postojati u tabeli users,
                                                     //nullOnDelete() - Ako se profesor obriše: predmet se NE briše 
                                                    // samo se uklanja profesor
        $table->string('naziv'); //Ime predmeta.
        $table->string('sifra')->unique(); //unique() znači: ne mogu postojati dva ista koda
        $table->unsignedInteger('godina_studija'); //Samo pozitivni brojevi.
        $table->timestamps(); //created_at,updated_at
    });
    }



/*kod mene se
koristi foreign key
i ON DELETE SET NULL
To radi samo u InnoDB engine-u
Ako je MySQL u tom trenutku imao default engine drugačiji (ili si imao stariju konfiguraciju), 
Laravel bi izbacio grešku tipa:
errno 150: Foreign key constraint is incorrectly formed
I tada se skoro uvek uradi:
$table->engine = 'InnoDB'; 
da se MySQL „natera“.*/





    /**
     * Reverse the migrations.
     */
    public function down(): void //down()poništava te izmene
    {
        Schema::dropIfExists('predmeti');
    }
};