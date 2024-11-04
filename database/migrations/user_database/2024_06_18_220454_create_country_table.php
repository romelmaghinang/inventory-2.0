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
        Schema::create('country', function (Blueprint $table) {
            $table->id();
            $table->string('abbreviation', 10)->nullable();
            $table->string('name', 50)->nullable();
        });

        DB::table('country')->insert([
            ['abbreviation' => 'Unknown', 'name' => 'Unknown'],
            ['abbreviation' => 'US', 'name' => 'United States'],
            ['abbreviation' => 'CA', 'name' => 'Canada'],
            ['abbreviation' => 'GB', 'name' => 'United Kingdom'],
            ['abbreviation' => 'RU', 'name' => 'Russian Federation'],
            ['abbreviation' => 'CN', 'name' => 'China'],
            ['abbreviation' => 'JP', 'name' => 'Japan'],
            ['abbreviation' => 'KR', 'name' => 'Korea, Republic of'],
            ['abbreviation' => 'KP', 'name' => 'Korea, Democratic People\'s Republic of'],
            ['abbreviation' => 'MX', 'name' => 'Mexico'],
            ['abbreviation' => 'DE', 'name' => 'Germany'],
            ['abbreviation' => 'ES', 'name' => 'Spain'],
            ['abbreviation' => 'FR', 'name' => 'France'],
            ['abbreviation' => 'FI', 'name' => 'Finland'],
            ['abbreviation' => 'IS', 'name' => 'Iceland'],
            ['abbreviation' => 'AU', 'name' => 'Australia'],
            ['abbreviation' => 'AF', 'name' => 'Afghanistan'],
            ['abbreviation' => 'AX', 'name' => 'Aland Islands'],
            ['abbreviation' => 'AL', 'name' => 'Albania'],
            ['abbreviation' => 'DZ', 'name' => 'Algeria'],
            ['abbreviation' => 'AS', 'name' => 'American Samoa'],
            ['abbreviation' => 'AD', 'name' => 'Andorra'],
            ['abbreviation' => 'AO', 'name' => 'Angola'],
            ['abbreviation' => 'AI', 'name' => 'Anguilla'],
            ['abbreviation' => 'AQ', 'name' => 'Antarctica'],
            ['abbreviation' => 'AG', 'name' => 'Antigua and Barbuda'],
            ['abbreviation' => 'AR', 'name' => 'Argentina'],
            ['abbreviation' => 'AM', 'name' => 'Armenia'],
            ['abbreviation' => 'AW', 'name' => 'Aruba'],
            ['abbreviation' => 'AT', 'name' => 'Austria'],
            ['abbreviation' => 'AZ', 'name' => 'Azerbaijan'],
            ['abbreviation' => 'BS', 'name' => 'Bahamas'],
            ['abbreviation' => 'BH', 'name' => 'Bahrain'],
            ['abbreviation' => 'BD', 'name' => 'Bangladesh'],
            ['abbreviation' => 'BB', 'name' => 'Barbados'],
            ['abbreviation' => 'BY', 'name' => 'Belarus'],
            ['abbreviation' => 'BE', 'name' => 'Belgium'],
            ['abbreviation' => 'BZ', 'name' => 'Belize'],
            ['abbreviation' => 'BJ', 'name' => 'Benin'],
            ['abbreviation' => 'BM', 'name' => 'Bermuda'],
            ['abbreviation' => 'BT', 'name' => 'Bhutan'],
            ['abbreviation' => 'BO', 'name' => 'Bolivia'],
            ['abbreviation' => 'BA', 'name' => 'Bosnia and Herzegovina'],
            ['abbreviation' => 'BW', 'name' => 'Botswana'],
            ['abbreviation' => 'BV', 'name' => 'Bouvet Island'],
            ['abbreviation' => 'BR', 'name' => 'Brazil'],
            ['abbreviation' => 'IO', 'name' => 'British Indian Ocean Territory'],
            ['abbreviation' => 'BN', 'name' => 'Brunei Darussalam'],
            ['abbreviation' => 'BG', 'name' => 'Bulgaria'],
            ['abbreviation' => 'BF', 'name' => 'Burkina Faso'],
            ['abbreviation' => 'BI', 'name' => 'Burundi'],
            ['abbreviation' => 'KH', 'name' => 'Cambodia'],
            ['abbreviation' => 'CM', 'name' => 'Cameroon'],
            ['abbreviation' => 'CV', 'name' => 'Cape Verde'],
            ['abbreviation' => 'KY', 'name' => 'Cayman Islands'],
            ['abbreviation' => 'CF', 'name' => 'Central African Republic'],
            ['abbreviation' => 'TD', 'name' => 'Chad'],
            ['abbreviation' => 'CL', 'name' => 'Chile'],
            ['abbreviation' => 'CX', 'name' => 'Christmas Island'],
            ['abbreviation' => 'CC', 'name' => 'Cocos (Keeling) Islands'],
            ['abbreviation' => 'CO', 'name' => 'Colombia'],
            ['abbreviation' => 'KM', 'name' => 'Comoros'],
            ['abbreviation' => 'CG', 'name' => 'Congo'],
            ['abbreviation' => 'CD', 'name' => 'Congo, the Democratic Republic of the'],
            ['abbreviation' => 'CK', 'name' => 'Cook Islands'],
            ['abbreviation' => 'CR', 'name' => 'Costa Rica'],
            ['abbreviation' => 'CI', 'name' => 'Cote D\'Ivoire'],
            ['abbreviation' => 'HR', 'name' => 'Croatia'],
            ['abbreviation' => 'CU', 'name' => 'Cuba'],
            ['abbreviation' => 'CY', 'name' => 'Cyprus'],
            ['abbreviation' => 'CZ', 'name' => 'Czech Republic'],
            ['abbreviation' => 'DK', 'name' => 'Denmark'],
            ['abbreviation' => 'DJ', 'name' => 'Djibouti'],
            ['abbreviation' => 'DM', 'name' => 'Dominica'],
            ['abbreviation' => 'DO', 'name' => 'Dominican Republic'],
            ['abbreviation' => 'EC', 'name' => 'Ecuador'],
            ['abbreviation' => 'EG', 'name' => 'Egypt'],
            ['abbreviation' => 'SV', 'name' => 'El Salvador'],
            ['abbreviation' => 'GQ', 'name' => 'Equatorial Guinea'],
            ['abbreviation' => 'ER', 'name' => 'Eritrea'],
            ['abbreviation' => 'EE', 'name' => 'Estonia'],
            ['abbreviation' => 'ET', 'name' => 'Ethiopia'],
            ['abbreviation' => 'FK', 'name' => 'Falkland Islands (Malvinas)'],
            ['abbreviation' => 'FO', 'name' => 'Faroe Islands'],
            ['abbreviation' => 'FJ', 'name' => 'Fiji'],
            ['abbreviation' => 'GF', 'name' => 'French Guiana'],
            ['abbreviation' => 'PF', 'name' => 'French Polynesia'],
            ['abbreviation' => 'TF', 'name' => 'French Southern Territories'],
            ['abbreviation' => 'GA', 'name' => 'Gabon'],
            ['abbreviation' => 'GM', 'name' => 'Gambia'],
            ['abbreviation' => 'GE', 'name' => 'Georgia'],
            ['abbreviation' => 'GH', 'name' => 'Ghana'],
            ['abbreviation' => 'GI', 'name' => 'Gibraltar'],
            ['abbreviation' => 'GR', 'name' => 'Greece'],
            ['abbreviation' => 'GL', 'name' => 'Greenland'],
            ['abbreviation' => 'GD', 'name' => 'Grenada'],
            ['abbreviation' => 'GP', 'name' => 'Guadeloupe'],
            ['abbreviation' => 'GU', 'name' => 'Guam'],
            ['abbreviation' => 'GT', 'name' => 'Guatemala'],
            ['abbreviation' => 'GG', 'name' => 'Guernsey'],
            ['abbreviation' => 'GN', 'name' => 'Guinea'],
            ['abbreviation' => 'GW', 'name' => 'Guinea-Bissau'],
            ['abbreviation' => 'GY', 'name' => 'Guyana'],
            ['abbreviation' => 'HT', 'name' => 'Haiti'],
            ['abbreviation' => 'HM', 'name' => 'Heard Island and McDonald Islands'],
            ['abbreviation' => 'VA', 'name' => 'Holy See (Vatican City State)'],
            ['abbreviation' => 'HN', 'name' => 'Honduras'],
            ['abbreviation' => 'HK', 'name' => 'Hong Kong'],
            ['abbreviation' => 'HU', 'name' => 'Hungary'],
            ['abbreviation' => 'IS', 'name' => 'Iceland'],
            ['abbreviation' => 'IN', 'name' => 'India'],
            ['abbreviation' => 'ID', 'name' => 'Indonesia'],
            ['abbreviation' => 'IR', 'name' => 'Iran, Islamic Republic of'],
            ['abbreviation' => 'IQ', 'name' => 'Iraq'],
            ['abbreviation' => 'IE', 'name' => 'Ireland'],
            ['abbreviation' => 'IM', 'name' => 'Isle of Man'],
            ['abbreviation' => 'IL', 'name' => 'Israel'],
            ['abbreviation' => 'IT', 'name' => 'Italy'],
            ['abbreviation' => 'JM', 'name' => 'Jamaica'],
            ['abbreviation' => 'JP', 'name' => 'Japan'],
            ['abbreviation' => 'JE', 'name' => 'Jersey'],
            ['abbreviation' => 'JO', 'name' => 'Jordan'],
            ['abbreviation' => 'KZ', 'name' => 'Kazakhstan'],
            ['abbreviation' => 'KE', 'name' => 'Kenya'],
            ['abbreviation' => 'KI', 'name' => 'Kiribati'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
};
