<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Category;
use Faker\Factory as FakerFactory;
use GuzzleHttp\Client;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class BooksSeeder extends Seeder
{
    public function run()
    {
        $pathFolder = 'public/storage/image/thumbnail/books';
        if (!File::exists($pathFolder)) {
            File::makeDirectory($pathFolder, 0755, true);
        }

        $bookTitles = [
            'Nhà giả kim (The Alchemist)',
            "Harry Potter và Hòa Bình (Harry Potter and the Philosopher's Stone)",
            'Chúa tể của những chiếc nhẫn (The Lord of the Rings)',
            'Một ngàn và một đêm (One Thousand and One Nights)',
            'Nguồn gốc của loài (The Origin of Species)',
            'Đắc nhân tâm (How to Win Friends and Influence People)',
            'Chiến tranh và hòa bình (War and Peace)',
            'Cuốn theo chiều gió (Gone with the Wind)',
            'To Kill a Mockingbird',
            'Ngục tù tinh thần (The Catcher in the Rye)',
            'Thiên đàng đã mất (Paradise Lost)',
            'Cô gái mất tích (The Girl with the Dragon Tattoo)',
            'Harry Potter và hòa hòa hảo hảo (Harry Potter and the Order of the Phoenix)',
            'Hòa bình vĩnh viễn (A Tale of Two Cities)',
            "Điệu valse mùa hè (Summer of '69)",
            'Cuốn sách của Thượng Đế (The Book Thief)',
            'Harry Potter và hoàng tử lai (Harry Potter and the Half-Blood Prince)',
            'Harry Potter và một nửa ngọc trai (Harry Potter and the Deathly Hallows)',
            'Chuyến tàu cuối cùng (The Last Train to Key West)',
            'Cuộc phiêu lưu của Sherlock Holmes (The Adventures of Sherlock Holmes)',
            'Harry Potter và hòa bình vĩnh viễn (Harry Potter and the Goblet of Fire)',
            'Bốn trạng nguyên (Four Fundamental Concepts of Psychoanalysis)',
            'Kỳ nghỉ (Vacation)',
            'Thất bại cuối cùng (The Last Time I Lied)',
            'Sapiens: Lược sử loài người (Sapiens: A Brief History of Humankind)',
        ];

        $categoryIds = collect(Category::all()->pluck('id')->toArray());

        $faker = FakerFactory::create();

        foreach ($bookTitles as $index => $bookTitle) {
            try {
                $pathFolder = 'storage/app/public/image/thumbnail/books/';
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
                        Book::create([
                            'isbn' => $faker->isbn10,
                            'book_code' => Str::random(10),
                            'id_category' => $categoryIds->random(),
                            'title' => $bookTitle,
                            'author' => $faker->name,
                            'publication_year' => $faker->numberBetween(1900, 2023),
                            'publisher' => $faker->company,
                            'price' => random_int(1, 30) * 50000,
                            'thumbnail' => 'storage/image/thumbnail/books/' . $nameImage,
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
