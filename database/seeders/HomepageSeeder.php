<?php

namespace Database\Seeders;

use App\Enums\CategoryType;
use App\Enums\ContentType;
use App\Enums\MediaType;
use App\Enums\StatusType;
use App\Models\Homepage;
use App\Models\Media;
use Illuminate\Database\Seeder;

class HomepageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1
        Media::create([
            'file_name' => '52ce9e9b-9dcb-4508-be15-b05c05622b21-ed036462-40ae-4d4b-b95b-24f1ddad0c0e.jpg',
            'code' => 'code1',
            'file_size' => 23,
            'media_type' => MediaType::IMAGE,
            'category_type' => CategoryType::MAIN_CONTENT,
        ]);

        // 2
        Media::create([
            'file_name' => '52ce9e9b-9dcb-4508-be15-b05c05622b21-ed036462-40ae-4d4b-b95b-24f1ddad0c0e.png',
            'code' => 'code2',
            'file_size' => 13,
            'media_type' => MediaType::IMAGE,
            'category_type' => ContentType::FEATURED_IMAGE,
        ]);
        Homepage::create([
            'title'             => 'Kata-kata menarik untuk calon user agar mau daftar',
            'description_1'     => 'Kata-kata menarik kedua untuk calon user, disini agak lebih ditonjolkan 1 Point penting yang bisa menarik minat calon user.',
            'description_2'     => 'Kata-kata pendorong untuk user, biasanya tidak terlalu dibaca, jadi kemungkinan kata katanya lebih ke dorongan mendaftar untuk klik link diatas.',
            'button_text'       => 'Mulai Jualan Sekarang',
            'content_type'      => ContentType::MAIN_CONTENT,
            'status'            => StatusType::DRAFT,
            'media_id'          => 1,
        ]);

        Homepage::create([
            'content_type'      => ContentType::FEATURED_IMAGE,
            'status'            => StatusType::DRAFT,
            'media_id'          => 2,
        ]);

        Homepage::create([
            'title' => 'Fitur utama dari aplikasi malmora yang pertama, agar user lebih tergiur lagi daftar.',
            'description_1' => 'Disini dijelaskan mengenai fiturnya, gimana cara kerjanya, atau manfaat yang bisa dirasakan si user agar lebih yakin lagi untuk daftar sebagai reseller. Tidak harus ada 3 fitur, tpi idealnya 3 fitur utama yang ditampilkan, dan maksimal 4 baris.',
            'status' => StatusType::DRAFT,
            'content_type'      => ContentType::FEATURED,
        ]);
        Homepage::create([
            'title' => 'Fitur utama dari aplikasi malmora yang kedua, agar user lebih tergiur lagi daftar.',
            'description_1' => 'Disini dijelaskan mengenai fiturnya, gimana cara kerjanya, atau manfaat yang bisa dirasakan si user agar lebih yakin lagi untuk daftar sebagai reseller. Tidak harus ada 3 fitur, tpi idealnya 3 fitur utama yang ditampilkan, dan maksimal 4 baris.',
            'status' => StatusType::DRAFT,
            'content_type'      => ContentType::FEATURED,
        ]);
        Homepage::create([
            'title' => 'Fitur utama dari aplikasi malmora yang ketiga, agar user lebih tergiur lagi daftar.',
            'description_1' => 'Disini dijelaskan mengenai fiturnya, gimana cara kerjanya, atau manfaat yang bisa dirasakan si user agar lebih yakin lagi untuk daftar sebagai reseller. Tidak harus ada 3 fitur, tpi idealnya 3 fitur utama yang ditampilkan, dan maksimal 4 baris.',
            'status' => StatusType::DRAFT,
            'content_type'      => ContentType::FEATURED,
        ]);
    }
}
