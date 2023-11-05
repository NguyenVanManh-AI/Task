<?php

namespace Database\Seeders;

use App\Models\Category;
use Database\Factories\FakeImageFactory;
use GuzzleHttp\Client;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class CategoriesSeeder extends Seeder
{

    public function run()
    {
        $pathFolder = 'public/storage/image/thumbnail/categories';
        if (!File::exists($pathFolder)) {
            File::makeDirectory($pathFolder, 0755, true);
        }

        $categoryNames = [
            'Văn học',
            'Khoa học',
            'Thiếu nhi',
            'Du lịch',
            'Nấu ăn',
            'Sức khỏe',
            'Kinh doanh',
            'Tài chính',
            'Tôn giáo',
            'Hài kịch',
            'Truyện tranh',
            'Khoa học viễn tưởng',
            'Học ngoại ngữ',
            'Chính trị',
            'Tôn giáo',
        ];

        foreach ($categoryNames as $index => $categoryName) {
            try {
                $pathFolder = 'storage/app/public/image/thumbnail/categories/';
                if (!File::exists($pathFolder)) {
                    File::makeDirectory($pathFolder, 0755, true);
                }
                $client = new Client;
                while (true) {
                    $response = $client->get('https://picsum.photos/200/200');
                    $imageContent = $response->getBody()->getContents();
                    $nameImage = uniqid() . '.jpg';
                    $thumbnail = $pathFolder . $nameImage;
                    if (file_put_contents($thumbnail, $imageContent)) {
                        Category::create([
                            'name' => $categoryName,
                            'thumbnail' => 'storage/image/thumbnail/categories/' . $nameImage,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                        break;
                    }
                }
            } catch (\Exception $e) {
            }
        }
    }
}
