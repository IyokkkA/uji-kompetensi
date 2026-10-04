<?php

namespace Database\Seeders;

use App\Models\ActivityLog;
use App\Models\NutritionLog;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::firstOrCreate(['email' => 'admin@nutridiet.id'], [
            'name' => 'Admin NutriDiet',
            'password' => Hash::make('admin123'),
            'goal' => 'maintenance',
            'is_admin' => true,
            'status' => 'active',
        ]);

        $names = [
            ['Ayu Lestari', 'ayu.lestari@gmail.com', 'weight_loss'],
            ['Budi Santoso', 'budi.santoso@gmail.com', 'healthy_bulk'],
            ['Citra Dewi', 'citra.dewi@gmail.com', 'weight_loss'],
            ['Dimas Pratama', 'dimas.pratama@gmail.com', 'maintenance'],
            ['Eka Putri', 'eka.putri@gmail.com', 'weight_loss'],
            ['Fajar Ramadhan', 'fajar.ramadhan@gmail.com', 'healthy_bulk'],
            ['Gita Sari', 'gita.sari@gmail.com', 'maintenance'],
            ['Hendra Gunawan', 'hendra.gunawan@gmail.com', 'weight_loss'],
            ['Intan Permata', 'intan.permata@gmail.com', 'healthy_bulk'],
            ['Joko Susilo', 'joko.susilo@gmail.com', 'maintenance'],
            ['Kartika Nabila', 'kartika.nabila@gmail.com', 'weight_loss'],
            ['Lukman Hakim', 'lukman.hakim@gmail.com', 'healthy_bulk'],
            ['Maya Anggraini', 'maya.anggraini@gmail.com', 'maintenance'],
            ['Nadia Rahma', 'nadia.rahma@gmail.com', 'weight_loss'],
            ['Oka Pradana', 'oka.pradana@gmail.com', 'healthy_bulk'],
            ['Putri Wulandari', 'putri.wulan@gmail.com', 'maintenance'],
            ['Rendi Saputra', 'rendi.saputra@gmail.com', 'weight_loss'],
            ['Sinta Amelia', 'sinta.amelia@gmail.com', 'healthy_bulk'],
            ['Taufik Hidayat', 'taufik.hidayat@gmail.com', 'maintenance'],
            ['Wulan Febrianti', 'wulan.febri@gmail.com', 'weight_loss'],
            ['Yoga Aditya', 'yoga.aditya@gmail.com', 'healthy_bulk'],
            ['Zahra Aulia', 'zahra.aulia@gmail.com', 'maintenance'],
            ['Rina Marlina', 'rina.marlina@gmail.com', 'weight_loss'],
            ['Agus Setiawan', 'agus.setiawan@gmail.com', 'healthy_bulk'],
            ['Dewi Kusuma', 'dewi.kusuma@gmail.com', 'maintenance'],
        ];

        $foods = [
            ['Nasi + Ayam Bakar + Lalapan', 520, 32, 55, 14],
            ['Oatmeal + Pisang + Madu', 340, 10, 62, 7],
            ['Salad Sayur + Telur Rebus', 280, 16, 22, 12],
            ['Nasi + Ikan Lele Goreng + Sambal', 610, 34, 60, 22],
            ['Smoothie Alpukat + Susu', 310, 9, 30, 18],
            ['Mie Ayam Bakso', 480, 22, 58, 16],
            ['Gado-gado', 420, 14, 48, 20],
            ['Soto Ayam + Nasi', 450, 28, 50, 12],
            ['Tempe Bacem + Nasi Merah', 390, 18, 58, 8],
            ['Roti Gandum + Selai Kacang', 320, 12, 36, 15],
        ];

        $activities = ['Jogging Pagi', 'Gym - Upper Body', 'Yoga 30 Menit', 'Bersepeda Santai', 'Jalan Cepat 5K', 'HIIT 20 Menit', 'Renang', 'Badminton'];

        foreach ($names as $i => [$name, $email, $goal]) {
            $user = User::firstOrCreate(['email' => $email], [
                'name' => $name,
                'phone' => '0812' . str_pad((string) (1000000 + $i * 137913), 7, '0', STR_PAD_LEFT),
                'password' => Hash::make('password123'),
                'goal' => $goal,
                'height_cm' => rand(155, 180),
                'weight_kg' => rand(480, 950) / 10,
                'target_calories' => $goal === 'healthy_bulk' ? 2300 : ($goal === 'weight_loss' ? 1650 : 1850),
                'water_target' => rand(2000, 3000),
                'status' => $i % 9 === 8 ? 'inactive' : 'active',
                'created_at' => now()->subDays(rand(1, 90)),
            ]);

            // 7 hari log nutrisi + aktivitas
            for ($d = 6; $d >= 0; $d--) {
                $date = now()->subDays($d)->toDateString();
                $meals = rand(2, 3);
                for ($m = 0; $m < $meals; $m++) {
                    $f = $foods[array_rand($foods)];
                    NutritionLog::create([
                        'user_id' => $user->id,
                        'log_date' => $date,
                        'meal_type' => ['breakfast', 'lunch', 'dinner'][$m % 3],
                        'food_name' => $f[0],
                        'calories' => $f[1] + rand(-40, 60),
                        'protein_g' => $f[2],
                        'carbs_g' => $f[3],
                        'fat_g' => $f[4],
                        'water_ml' => $m === 0 ? rand(500, 900) : rand(300, 700),
                    ]);
                }
                if (rand(0, 10) > 3) {
                    ActivityLog::create([
                        'user_id' => $user->id,
                        'log_date' => $date,
                        'activity_type' => $activities[array_rand($activities)],
                        'duration_min' => rand(20, 90),
                        'calories_burned' => rand(120, 450),
                        'steps' => rand(2000, 12000),
                    ]);
                }
            }
        }
    }
}
