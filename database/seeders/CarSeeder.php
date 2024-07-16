<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('cars')->insert([
            'brand' => 'Fiat',
            'model' => '500',
            'car_body' => 'Small Car',
            'engine_type' => 'Gasoline',
            'transmission' => 'Manual',
            'engine_power' => 85,
            'seats' => 4,
            'doors' => 3,
            'suitcases' => 1,
            'price' => 130.00,
            'description' => 'Fiat 500 is an excellent choice for a city car. It is extremely economical, has dynamic driving characteristics and an exceptionally small turning radius. This is our most popular car in this category - perfect for suburban trips or quick errands around town.',
            'image_path' => 'cars/BZNy6gOjPHcfpdbzBmDgu48o808P7oNBliApJGPy.png',
        ]);

        DB::table('cars')->insert([
            'brand' => 'Toyota',
            'model' => 'Yaris',
            'car_body' => 'Small Car',
            'engine_type' => 'Hybrid',
            'transmission' => 'Automatic',
            'engine_power' => 93,
            'seats' => 5,
            'doors' => 5,
            'suitcases' => 2,
            'price' => 150.00,
            'description' => 'Are you looking for a compact yet spacious car to explore the city? Toyota Yaris is an excellent choice. This dynamic and stylish hatchback is easy to park and fits perfectly into crowded streets. With low fuel consumption and a quiet hybrid engine, the Yaris delivers fuel-efficient driving without compromising on performance.',
            'image_path' => 'cars/Cunf6WT6ixBaGTyyaRwe9l0WN2BRIa72rojXYTVe.png',
        ]);

        DB::table('cars')->insert([
            'brand' => 'Smart',
            'model' => 'Forfour',
            'car_body' => 'Small Car',
            'engine_type' => 'Gasoline',
            'transmission' => 'Automatic',
            'engine_power' => 95,
            'seats' => 4,
            'doors' => 5,
            'suitcases' => 2,
            'price' => 135.00,
            'description' => 'The Smart Forfour is a compact and stylish city car, perfect for urban driving. It offers seating for up to four passengers and combines practicality with modern design. This car is fuel-efficient, easy to maneuver, and ideal for navigating through tight city streets. With its comfortable interior and advanced safety features, the Smart Forfour ensures a pleasant and secure driving experience.',
            'image_path' => 'cars/fNY5xpD1TLSNOaTarDGKGjk50i273MMB70EyTGBv.png',
        ]);

        DB::table('cars')->insert([
            'brand' => 'Mercedes-Benz',
            'model' => 'Klasa E',
            'car_body' => 'Coupe',
            'engine_type' => 'Diesel',
            'transmission' => 'Automatic',
            'engine_power' => 170,
            'seats' => 4,
            'doors' => 3,
            'suitcases' => 4,
            'price' => 190.00,
            'description' => 'The Mercedes-Benz E-Class is a premium coupe that epitomizes luxury and performance. It features a spacious and elegantly designed interior, equipped with the latest technology and high-end materials for maximum comfort. The E-Class offers a smooth and powerful driving experience, thanks to its advanced engine options and superior handling.',
            'image_path' => 'cars/jD6HAOnAoSDxMzyiYzkcKFp3bVPzk0twjklZCIVg.png',
        ]);

        DB::table('cars')->insert([
            'brand' => 'BMW',
            'model' => 'Seria 4',
            'car_body' => 'Coupe',
            'engine_type' => 'Gasoline',
            'transmission' => 'Automatic',
            'engine_power' => 306,
            'seats' => 4,
            'doors' => 3,
            'suitcases' => 4,
            'price' => 200.00,
            'description' => 'The BMW 4 Series is a dynamic and stylish coupe that blends sporty performance with luxury. It features a sleek and aerodynamic design, complemented by a high-quality interior equipped with advanced technology and premium materials. The 4 Series offers an exhilarating driving experience with its powerful engines and precise handling.',
            'image_path' => 'cars/wXTrkLglycEWFVOAxywqiaf4myT61S99yEUDJBNU.png',
        ]);

        DB::table('cars')->insert([
            'brand' => 'Audi',
            'model' => 'A5',
            'car_body' => 'Coupe',
            'engine_type' => 'Diesel',
            'transmission' => 'Manual',
            'engine_power' => 190,
            'seats' => 4,
            'doors' => 3,
            'suitcases' => 4,
            'price' => 195.00,
            'description' => 'The Audi A5 is a sophisticated and sporty coupe that combines elegant design with powerful performance. Its exterior features sleek lines and a distinctive grille, while the interior is crafted with high-quality materials and the latest technology for a luxurious driving experience.',
            'image_path' => 'cars/mkQV1wEWDXUZaMakAUkunxtKyD5IqcGk5F4Bj1rj.png',
        ]);

        DB::table('cars')->insert([
            'brand' => 'Mercedes-Benz',
            'model' => 'SLK 250',
            'car_body' => 'Convertible',
            'engine_type' => 'Gasoline',
            'transmission' => 'Automatic',
            'engine_power' => 204,
            'seats' => 2,
            'doors' => 3,
            'suitcases' => 1,
            'price' => 180.00,
            'description' => 'The Mercedes-Benz SLK 250 is a stylish and sporty convertible that offers an exhilarating open-air driving experience. Its sleek design is complemented by a luxurious interior featuring premium materials and advanced technology. The SLK 250 delivers impressive performance with its responsive handling and powerful engine, making every drive a pleasure.',
            'image_path' => 'cars/W2VnUVvG6W6UzalTnFkHijcN7mKk2q7rjMMrjNyI.png',
        ]);

        DB::table('cars')->insert([
            'brand' => 'Opel',
            'model' => 'Cascada',
            'car_body' => 'Convertible',
            'engine_type' => 'Gasoline',
            'transmission' => 'Manual',
            'engine_power' => 170,
            'seats' => 4,
            'doors' => 3,
            'suitcases' => 1,
            'price' => 165.00,
            'description' => 'The Opel Cascada is a sleek and elegant convertible, perfect for enjoying open-air drives. It features a stylish design with a refined interior, equipped with modern technology and comfortable seating. The Cascada offers a smooth and enjoyable driving experience, thanks to its responsive handling and efficient engine options.',
            'image_path' => 'cars/634c06e9B0tFu1oyB2KFotpFHGT3pgDxdBd4SmFX.png',
        ]);

        DB::table('cars')->insert([
            'brand' => 'Audi',
            'model' => 'TT',
            'car_body' => 'Convertible',
            'engine_type' => 'Diesel',
            'transmission' => 'Manual',
            'engine_power' => 184,
            'seats' => 2,
            'doors' => 3,
            'suitcases' => 1,
            'price' => 175.00,
            'description' => 'The Audi TT Convertible is a dynamic and stylish open-top sports car, perfect for those who crave both performance and elegance. It boasts a sleek, aerodynamic design and a high-quality interior with advanced technology and premium materials. The TT Convertible offers a thrilling driving experience with its powerful engine and precise handling.',
            'image_path' => 'cars/3A3a2LdqNUMt3SUHbrzt0YTpw9QGLBWphlJhDcuR.png',
        ]);

        DB::table('cars')->insert([
            'brand' => 'Volkswagen',
            'model' => 'ID.3',
            'car_body' => 'Hatchback',
            'engine_type' => 'Electric',
            'transmission' => 'Automatic',
            'engine_power' => 150,
            'seats' => 5,
            'doors' => 5,
            'suitcases' => 3,
            'price' => 185.00,
            'description' => 'The Volkswagen ID.3 is a cutting-edge electric hatchback that combines sustainability with modern design. It features a spacious and futuristic interior equipped with advanced technology and user-friendly controls. The ID.3 offers a smooth and quiet driving experience with its efficient electric motor and impressive range.',
            'image_path' => 'cars/fRlDmI5GL55RDMwr0Zbs7oAiXWWrVya5okIzbgRK.png',
        ]);

        DB::table('cars')->insert([
            'brand' => 'Kia',
            'model' => 'Rio',
            'car_body' => 'Hatchback',
            'engine_type' => 'Gasoline',
            'transmission' => 'Manual',
            'engine_power' => 100,
            'seats' => 5,
            'doors' => 5,
            'suitcases' => 3,
            'price' => 170.00,
            'description' => 'The Kia Rio is a versatile and efficient hatchback, ideal for both city driving and longer journeys. It features a modern and stylish design with a comfortable interior, equipped with the latest technology and user-friendly controls. The Rio offers a smooth and reliable driving experience, thanks to its fuel-efficient engine and agile handling.',
            'image_path' => 'cars/ZOqgt6Aje3ie35zTh8zRE8pvKO7TL0HUPlIdkDax.png',
        ]);

        DB::table('cars')->insert([
            'brand' => 'Mazda',
            'model' => '3',
            'car_body' => 'Hatchback',
            'engine_type' => 'Gasoline',
            'transmission' => 'Manual',
            'engine_power' => 122,
            'seats' => 5,
            'doors' => 5,
            'suitcases' => 3,
            'price' => 175.00,
            'description' => 'The Mazda 3 is a sleek and sporty hatchback that offers a perfect blend of style, performance, and practicality. Its dynamic design is complemented by a high-quality interior featuring advanced technology and premium materials. The Mazda 3 provides an engaging driving experience with its responsive handling and efficient engine options.',
            'image_path' => 'cars/9aH2gLU8YeLyrkEFiPZ0D1b3CdzCoYgNsJH5CKPl.png',
        ]);

        DB::table('cars')->insert([
            'brand' => 'Toyota',
            'model' => 'Corolla',
            'car_body' => 'Estate Car',
            'engine_type' => 'Hybrid',
            'transmission' => 'Automatic',
            'engine_power' => 140,
            'seats' => 5,
            'doors' => 5,
            'suitcases' => 4,
            'price' => 180.00,
            'description' => 'The Toyota Corolla Wagon is a spacious and reliable vehicle, perfect for families and long trips. It features a sleek design with a comfortable and modern interior, equipped with the latest technology and ample cargo space. The Corolla Wagon offers a smooth and efficient driving experience with its fuel-efficient engine and refined handling.',
            'image_path' => 'cars/a6qKKlTzn0HMJAvzW4hrPrfgYVrFKtYpEsyPUZx3.png',
        ]);

        DB::table('cars')->insert([
            'brand' => 'Skoda',
            'model' => 'Superb',
            'car_body' => 'Estate Car',
            'engine_type' => 'Diesel',
            'transmission' => 'Manual',
            'engine_power' => 150,
            'seats' => 5,
            'doors' => 5,
            'suitcases' => 4,
            'price' => 175.00,
            'description' => 'The Skoda Superb Wagon is a premium and spacious estate car, ideal for families and long journeys. It features an elegant design with a luxurious interior, offering advanced technology and generous cargo space. The Superb Wagon delivers a smooth and powerful driving experience with its efficient engine options and refined handling.',
            'image_path' => 'cars/as1G07vhJIvKgJR45g5FYODKhGsBFD26q9IVnVdo.png',
        ]);

        DB::table('cars')->insert([
            'brand' => 'Volkswagen',
            'model' => 'Arteon',
            'car_body' => 'Estate Car',
            'engine_type' => 'Gasoline',
            'transmission' => 'Automatic',
            'engine_power' => 190,
            'seats' => 5,
            'doors' => 5,
            'suitcases' => 5,
            'price' => 190.00,
            'description' => 'The Volkswagen Arteon Estate is a blend of luxury and performance, featuring a sleek design and spacious interior. It offers advanced technology for comfort and convenience, paired with dynamic driving capabilities from its powerful engines and responsive handling. Safety features are state-of-the-art, ensuring a secure ride.',
            'image_path' => 'cars/LqLIR7SLGonmmeCZmvg0ANuswkfaDrDddlktodjJ.png',
        ]);

        DB::table('cars')->insert([
            'brand' => 'Toyota',
            'model' => 'Camry',
            'car_body' => 'Sedan',
            'engine_type' => 'Hybrid',
            'transmission' => 'Automatic',
            'engine_power' => 178,
            'seats' => 5,
            'doors' => 5,
            'suitcases' => 4,
            'price' => 190.00,
            'description' => 'The Toyota Camry sedan is a hallmark of reliability and comfort, perfect for daily commuting and long-distance travel. It boasts a refined design with a spacious and well-appointed interior, featuring modern technology and user-friendly amenities. The Camry offers a smooth and efficient driving experience, supported by its dependable engine performance.',
            'image_path' => 'cars/BGtfFrdqKXuU5XbvGREMZMcmKQwGdf1NROYAGlb2.png',
        ]);

        DB::table('cars')->insert([
            'brand' => 'Tesla',
            'model' => 'Model 3',
            'car_body' => 'Sedan',
            'engine_type' => 'Electric',
            'transmission' => 'Automatic',
            'engine_power' => 480,
            'seats' => 4,
            'doors' => 5,
            'suitcases' => 3,
            'price' => 215.00,
            'description' => 'The Tesla Model 3 sedan represents the pinnacle of electric vehicle innovation, combining cutting-edge technology with sleek design. Its minimalist interior is crafted with premium materials and equipped with advanced features like Autopilot and a large touchscreen interface. The Model 3 offers exhilarating acceleration and precise handling thanks to its electric powertrain and low center of gravity.',
            'image_path' => 'cars/HamJLgeKQfKny3aAnUKjmS8B6JrmDsoczx4YHovj.png',
        ]);

        DB::table('cars')->insert([
            'brand' => 'Audi',
            'model' => 'A4',
            'car_body' => 'Sedan',
            'engine_type' => 'Gasoline',
            'transmission' => 'Manual',
            'engine_power' => 204,
            'seats' => 5,
            'doors' => 5,
            'suitcases' => 4,
            'price' => 195.00,
            'description' => 'The Audi A4 sedan epitomizes luxury and performance, blending sleek design with advanced technology. Its refined interior offers premium materials and state-of-the-art infotainment systems, ensuring a comfortable and connected driving experience. The A4 sedan delivers dynamic handling and powerful engine options, making every drive enjoyable and responsive.',
            'image_path' => 'cars/orndMWobnCrgUjxs7B4SzmvPrp42ucxC4642I0dn.png',
        ]);

        DB::table('cars')->insert([
            'brand' => 'Jeep',
            'model' => 'Compass',
            'car_body' => 'SUV',
            'engine_type' => 'Gasoline',
            'transmission' => 'Automatic',
            'engine_power' => 177,
            'seats' => 5,
            'doors' => 5,
            'suitcases' => 5,
            'price' => 220.00,
            'description' => 'The Jeep Compass SUV is a versatile and rugged vehicle, perfect for both urban adventures and off-road excursions. It features a bold design with a spacious and comfortable interior, equipped with modern technology and user-friendly controls. The Compass offers a capable driving experience with its powerful engine options and advanced 4x4 capabilities.',
            'image_path' => 'cars/QyZLKiIJdVFiCuBaxBaikNSWTNuz0E4vbSB0NYEc.png',
        ]);

        DB::table('cars')->insert([
            'brand' => 'BMW',
            'model' => 'X4',
            'car_body' => 'SUV',
            'engine_type' => 'Gasoline',
            'transmission' => 'Automatic',
            'engine_power' => 245,
            'seats' => 5,
            'doors' => 5,
            'suitcases' => 5,
            'price' => 230.00,
            'description' => 'The BMW X4 SUV is a dynamic and stylish vehicle that combines sporty performance with luxurious comfort. Its coupe-like design is complemented by a high-quality interior, featuring advanced technology and premium materials. The X4 offers an exhilarating driving experience with powerful engine options and precise handling.',
            'image_path' => 'cars/kHXWLi0m763Y4fSMAqxidQq1jbkO7FNNFqGopoOf.png',
        ]);

        DB::table('cars')->insert([
            'brand' => 'Volvo',
            'model' => 'XC 40',
            'car_body' => 'SUV',
            'engine_type' => 'Diesel',
            'transmission' => 'Manual',
            'engine_power' => 150,
            'seats' => 5,
            'doors' => 5,
            'suitcases' => 6,
            'price' => 225.00,
            'description' => 'The Volvo XC40 SUV is a stylish and compact vehicle that blends Scandinavian design with innovative technology. Its modern exterior is matched by a well-appointed interior, offering premium materials and advanced features for a comfortable and connected driving experience. The XC40 provides responsive handling and efficient engine options, making it ideal for both city driving and long journeys.',
            'image_path' => 'cars/pMq6JmRBL8GjyCJsMda8YK3QDrx59uKh30WI76m4.png',
        ]);

        DB::table('cars')->insert([
            'brand' => 'Dodge',
            'model' => 'Grand Caravan',
            'car_body' => 'Minivan',
            'engine_type' => 'Gasoline',
            'transmission' => 'Automatic',
            'engine_power' => 175,
            'seats' => 7,
            'doors' => 5,
            'suitcases' => 8,
            'price' => 250.00,
            'description' => 'The Dodge Grand Caravan Minivan is a practical and spacious vehicle, perfect for families and group travel. It features a robust design with a roomy interior that offers flexible seating and ample cargo space. Equipped with user-friendly technology and convenience features, the Grand Caravan ensures a comfortable and enjoyable ride for all passengers.',
            'image_path' => 'cars/sfXlPhgIvUbuIDG5nA6mqxgAqStwsC6AnD48qTWs.png',
        ]);

        DB::table('cars')->insert([
            'brand' => 'Mercedes-Benz',
            'model' => 'Vito',
            'car_body' => 'Minivan',
            'engine_type' => 'Diesel',
            'transmission' => 'Manual',
            'engine_power' => 163,
            'seats' => 9,
            'doors' => 5,
            'suitcases' => 9,
            'price' => 260.00,
            'description' => 'The Mercedes-Benz Vito Minivan is a versatile and premium vehicle, ideal for business or family use. It boasts a sophisticated design with a spacious interior, offering flexible seating configurations and generous cargo capacity. Equipped with advanced technology and comfort features, the Vito ensures a pleasant and connected driving experience.',
            'image_path' => 'cars/VUfS6o0QqcOstWrsyzNCA0GYo5ptRK6ywT6mDjZa.png',
        ]);
    }
}
