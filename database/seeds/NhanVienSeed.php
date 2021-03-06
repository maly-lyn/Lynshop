<?php

use Illuminate\Database\Seeder;

class NhanVienSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Khai bao thu vien faker
        $faker = Faker\Factory::create();
        for ($i=0; $i<20; $i++){
            $nhanVien = [
                [
                    'nv_hoten' => $faker->name,
                    'nv_sdt' => $faker->phoneNumber,
                    'username' => 'maly'.rand(1,999),
                    'password' => Hash::make(123),
                    'q_id' => 2
                ]
                ];
            $addNhanVien = DB::table('nhanvien')->insert($nhanVien);
        }
    }
}
