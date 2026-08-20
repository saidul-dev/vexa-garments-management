<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = array(
            array('name' => 'LC Waikiki','role' => 'buyer','email' => NULL,'phone' => NULL,'country' => NULL,'image' => NULL,'password' => '$2y$10$Hpt10Q/EYCTW8k8EwMpKaemUBOcRe/el0cIR27Mfh.UZbP1IGFLzi','email_verified_at' => NULL,'remember_token' => NULL,'created_at' => now(),'updated_at' => now()),
            array('name' => 'Line 1','role' => 'customer','email' => NULL,'phone' => NULL,'country' => NULL,'image' => NULL,'password' => '$2y$10$rOgjLKkw3RG5kbDlBB/87OheYIEqb7lMyoZhIKFTmSdaxeYw.LLmC','email_verified_at' => NULL,'remember_token' => NULL,'created_at' => now(),'updated_at' => now()),
            array('name' => 'Line-2','role' => 'customer','email' => NULL,'phone' => NULL,'country' => NULL,'image' => NULL,'password' => '$2y$10$dGqukjKr8Sk4yK1XvArV6OQJUxz4Pa9CnZMCsTvCDDtaPhhoqk60m','email_verified_at' => NULL,'remember_token' => NULL,'created_at' => now(),'updated_at' => now()),
            array('name' => 'COATS','role' => 'supplier','email' => NULL,'phone' => NULL,'country' => NULL,'image' => NULL,'password' => '$2y$10$Cpu3HF./2KeEwsRIdEATi.XCItIY8Bb7T1ZjvLlR5mNd7FRauu3BS','email_verified_at' => NULL,'remember_token' => NULL,'created_at' => now(),'updated_at' => now()),
            array('name' => 'Marlon Marlon Morales','role' => 'merchandiser','email' => 'tuendystore@gmail.com','phone' => '04141930904','country' => NULL,'image' => NULL,'password' => '$2y$10$cuLnh/sZxLFqmiOqjxtMReiF2w02DTvXZ5ArDsMoxSu9bXNEI62tC','email_verified_at' => NULL,'remember_token' => NULL,'created_at' => now(),'updated_at' => now()),
            array('name' => 'joanklin','role' => 'commercial','email' => 'marlonmorales@gmail.com','phone' => '04141930904','country' => NULL,'image' => NULL,'password' => '$2y$10$O7RncaTWkD/oxY5O9.3xIuMFVRsuu6F6hzkwffTXYQVhSNDta7wwK','email_verified_at' => NULL,'remember_token' => NULL,'created_at' => now(),'updated_at' => now()),
            array('name' => 'RGB Textiles','role' => 'customer','email' => 'ehtisamzia1@hotmail.com','phone' => NULL,'country' => 'United Arab Emirates','image' => NULL,'password' => '$2y$10$ak5w8b23F/mnWzi7Lvor5e14ypUWjh3a/wilsYofCSiY5LCAdDep6','email_verified_at' => NULL,'remember_token' => NULL,'created_at' => now(),'updated_at' => now()),
            array('name' => 'NR','role' => 'buyer','email' => 'nr@style.com','phone' => '4234242342','country' => 'United States','image' => NULL,'password' => '$2y$10$LWTlvvYDQhxhn3pwtN/0z.MZ6IIXuy9XlnfqdNBMvz.oDG/CqZeKq','email_verified_at' => NULL,'remember_token' => NULL,'created_at' => now(),'updated_at' => now()),
            array('name' => '000-B2','role' => 'buyer','email' => 'abubokkar728@gmail.com','phone' => '01752220026','country' => 'Colombia','image' => NULL,'password' => '$2y$10$P/4/Gv12Z8m8/3l18A8rA.hJwcq3XdEL1CmBIqMoNkQMqPSvjDF2q','email_verified_at' => NULL,'remember_token' => NULL,'created_at' => now(),'updated_at' => now()),
            array('name' => 'uday reddy','role' => 'merchandiser','email' => 'merchandiser@gmail.com','phone' => NULL,'country' => NULL,'image' => NULL,'password' => '$2y$10$n0d7onT0p7HUKpRXrrLQXOX81CodYuU5hQ20Kuyscu8CfIZ6p9UtC','email_verified_at' => NULL,'remember_token' => NULL,'created_at' => now(),'updated_at' => now())
        );

        User::insert($users);
    }
}
