<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('state', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10)->nullable();
            $table->string('countryConstID', 10)->nullable();
            $table->string('name', 30);
        });

       DB::table('state')->insert([
        ['code' => 'ZZ', 'countryConstID' => '1', 'name' => 'Unknown'],
        ['code' => 'AL', 'countryConstID' => '2', 'name' => 'Alabama'],
        ['code' => 'AK', 'countryConstID' => '2', 'name' => 'Alaska'],
        ['code' => 'AZ', 'countryConstID' => '2', 'name' => 'Arizona'],
        ['code' => 'AR', 'countryConstID' => '2', 'name' => 'Arkansas'],
        ['code' => 'CA', 'countryConstID' => '2', 'name' => 'California'],
        ['code' => 'CO', 'countryConstID' => '2', 'name' => 'Colorado'],
        ['code' => 'CT', 'countryConstID' => '2', 'name' => 'Connecticut'],
        ['code' => 'DE', 'countryConstID' => '2', 'name' => 'Delaware'],
        ['code' => 'DC', 'countryConstID' => '2', 'name' => 'District of Columbia'],
        ['code' => 'FL', 'countryConstID' => '2', 'name' => 'Florida'],
        ['code' => 'GA', 'countryConstID' => '2', 'name' => 'Georgia'],
        ['code' => 'GU', 'countryConstID' => '2', 'name' => 'Guam'],
        ['code' => 'HI', 'countryConstID' => '2', 'name' => 'Hawaii'],
        ['code' => 'ID', 'countryConstID' => '2', 'name' => 'Idaho'],
        ['code' => 'IL', 'countryConstID' => '2', 'name' => 'Illinois'],
        ['code' => 'IN', 'countryConstID' => '2', 'name' => 'Indiana'],
        ['code' => 'IA', 'countryConstID' => '2', 'name' => 'Iowa'],
        ['code' => 'KS', 'countryConstID' => '2', 'name' => 'Kansas'],
        ['code' => 'KY', 'countryConstID' => '2', 'name' => 'Kentucky'],
        ['code' => 'LA', 'countryConstID' => '2', 'name' => 'Louisiana'],
        ['code' => 'ME', 'countryConstID' => '2', 'name' => 'Maine'],
        ['code' => 'MD', 'countryConstID' => '2', 'name' => 'Maryland'],
        ['code' => 'MA', 'countryConstID' => '2', 'name' => 'Massachusetts'],
        ['code' => 'MI', 'countryConstID' => '2', 'name' => 'Michigan'],
        ['code' => 'MN', 'countryConstID' => '2', 'name' => 'Minnesota'],
        ['code' => 'MS', 'countryConstID' => '2', 'name' => 'Mississippi'],
        ['code' => 'MO', 'countryConstID' => '2', 'name' => 'Missouri'],
        ['code' => 'MT', 'countryConstID' => '2', 'name' => 'Montana'],
        ['code' => 'NE', 'countryConstID' => '2', 'name' => 'Nebraska'],
        ['code' => 'NV', 'countryConstID' => '2', 'name' => 'Nevada'],
        ['code' => 'NH', 'countryConstID' => '2', 'name' => 'New Hampshire'],
        ['code' => 'NJ', 'countryConstID' => '2', 'name' => 'New Jersey'],
        ['code' => 'NM', 'countryConstID' => '2', 'name' => 'New Mexico'],
        ['code' => 'NY', 'countryConstID' => '2', 'name' => 'New York'],
        ['code' => 'NC', 'countryConstID' => '2', 'name' => 'North Carolina'],
        ['code' => 'ND', 'countryConstID' => '2', 'name' => 'North Dakota'],
        ['code' => 'MP', 'countryConstID' => '2', 'name' => 'Northern Mariana Islands'],
        ['code' => 'OH', 'countryConstID' => '2', 'name' => 'Ohio'],
        ['code' => 'OK', 'countryConstID' => '2', 'name' => 'Oklahoma'],
        ['code' => 'OR', 'countryConstID' => '2', 'name' => 'Oregon'],
        ['code' => 'PA', 'countryConstID' => '2', 'name' => 'Pennsylvania'],
        ['code' => 'RI', 'countryConstID' => '2', 'name' => 'Rhode Island'],
        ['code' => 'SC', 'countryConstID' => '2', 'name' => 'South Carolina'],
        ['code' => 'SD', 'countryConstID' => '2', 'name' => 'South Dakota'],
        ['code' => 'TN', 'countryConstID' => '2', 'name' => 'Tennessee'],
        ['code' => 'TX', 'countryConstID' => '2', 'name' => 'Texas'],
        ['code' => 'UT', 'countryConstID' => '2', 'name' => 'Utah'],
        ['code' => 'VT', 'countryConstID' => '2', 'name' => 'Vermont'],
        ['code' => 'VA', 'countryConstID' => '2', 'name' => 'Virginia'],
        ['code' => 'VI', 'countryConstID' => '2', 'name' => 'Virgin Islands, U.S.'],
        ['code' => 'WA', 'countryConstID' => '2', 'name' => 'Washington'],
        ['code' => 'WV', 'countryConstID' => '2', 'name' => 'West Virginia'],
        ['code' => 'WI', 'countryConstID' => '2', 'name' => 'Wisconsin'],
        ['code' => 'WY', 'countryConstID' => '2', 'name' => 'Wyoming'],
        ['code' => 'xx', 'countryConstID' => '2', 'name' => 'Unknown State'],
        ['code' => 'AA', 'countryConstID' => '2', 'name' => 'U.S. Armed Forces - Americas'],
        ['code' => 'AE', 'countryConstID' => '2', 'name' => 'U.S. Armed Forces - Europe'],
        ['code' => 'AP', 'countryConstID' => '2', 'name' => 'U.S. Armed Forces - Pacific'],
        ['code' => 'AB', 'countryConstID' => '3', 'name' => 'Alberta'],
        ['code' => 'BC', 'countryConstID' => '3', 'name' => 'British Columbia'],
        ['code' => 'MB', 'countryConstID' => '3', 'name' => 'Manitoba'],
        ['code' => 'NB', 'countryConstID' => '3', 'name' => 'New Brunswick'],
        ['code' => 'NL', 'countryConstID' => '3', 'name' => 'Newfoundland'],
        ['code' => 'NT', 'countryConstID' => '3', 'name' => 'Northwest Territories'],
        ['code' => 'NS', 'countryConstID' => '3', 'name' => 'Nova Scotia'],
        ['code' => 'NU', 'countryConstID' => '3', 'name' => 'Nunavut'],
        ['code' => 'ON', 'countryConstID' => '3', 'name' => 'Ontario'],
        ['code' => 'PE', 'countryConstID' => '3', 'name' => 'Prince Edward Island'],
        ['code' => 'QC', 'countryConstID' => '3', 'name' => 'Quebec'],
        ['code' => 'SK', 'countryConstID' => '3', 'name' => 'Saskatchewan'],
        ['code' => 'YT', 'countryConstID' => '3', 'name' => 'Yukon'],
        ['code' => 'ACT', 'countryConstID' => '16', 'name' => 'Australian Capital Territory'],
        ['code' => 'NSW', 'countryConstID' => '16', 'name' => 'New South Wales'],
        ['code' => 'NT', 'countryConstID' => '16', 'name' => 'Northern Territory'],
        ['code' => 'QLD', 'countryConstID' => '16', 'name' => 'Queensland'],
        ['code' => 'SA', 'countryConstID' => '16', 'name' => 'South Australia'],
        ['code' => 'TAS', 'countryConstID' => '16', 'name' => 'Tasmania'],
        ['code' => 'VIC', 'countryConstID' => '16', 'name' => 'Victoria'],
        ['code' => 'WA', 'countryConstID' => '16', 'name' => 'Western Australia'],
        ['code' => 'MC', 'countryConstID' => '10', 'name' => 'Mexico City'],
    ]); 
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('state');
    }
};