<?php

namespace Database\Seeders;

use App\Models\EventSetting;
use App\Models\Sponsor;
use App\Models\Tenant;
use App\Models\TicketOrder;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'admin@galaksi.sch.id'],
            [
                'name' => 'Admin Galaksi',
                'password' => 'password',
                'is_admin' => true,
            ]
        );

        EventSetting::query()->firstOrCreate([], [
            'event_year' => '2027',
            'event_date_label' => 'Sabtu, 16 Januari 2027',
            'open_gate_label' => '07.00 WIB',
            'venue_name' => 'Graha Cakrawala',
            'venue_city' => 'Universitas Negeri Malang',
            'ticket_form_url' => '/tiket',
            'tenant_url' => '/tenant',
            'sponsor_url' => '/sponsor',
        ]);

        if (TicketOrder::query()->count() === 0) {
            TicketOrder::query()->insert([
                [
                    'order_code' => 'GLK-2027-001',
                    'buyer_name' => 'Aisyah Rahmawati',
                    'buyer_email' => 'aisyah.rahma@example.com',
                    'buyer_phone' => '081234567890',
                    'category' => 'VIP',
                    'quantity' => 2,
                    'total_amount' => 300000,
                    'status' => 'confirmed',
                    'notes' => 'Wali murid SD kelas 4',
                    'created_at' => now()->subDays(3),
                    'updated_at' => now()->subDays(3),
                ],
                [
                    'order_code' => 'GLK-2027-002',
                    'buyer_name' => 'Budi Santoso',
                    'buyer_email' => 'budi.santoso@example.com',
                    'buyer_phone' => '081345678901',
                    'category' => 'Keluarga',
                    'quantity' => 4,
                    'total_amount' => 400000,
                    'status' => 'confirmed',
                    'notes' => 'Paket keluarga 4 kursi',
                    'created_at' => now()->subDays(2),
                    'updated_at' => now()->subDays(2),
                ],
                [
                    'order_code' => 'GLK-2027-003',
                    'buyer_name' => 'Citra Lestari',
                    'buyer_email' => 'citra.lestari@example.com',
                    'buyer_phone' => '085612345678',
                    'category' => 'Reguler',
                    'quantity' => 3,
                    'total_amount' => 225000,
                    'status' => 'pending',
                    'notes' => 'Menunggu verifikasi transfer',
                    'created_at' => now()->subDay(),
                    'updated_at' => now()->subDay(),
                ],
                [
                    'order_code' => 'GLK-2027-004',
                    'buyer_name' => 'Dimas Pratama',
                    'buyer_email' => 'dimas.pratama@example.com',
                    'buyer_phone' => '082198765432',
                    'category' => 'Reguler',
                    'quantity' => 1,
                    'total_amount' => 75000,
                    'status' => 'cancelled',
                    'notes' => 'Batal karena bentrok jadwal',
                    'created_at' => now()->subHours(10),
                    'updated_at' => now()->subHours(8),
                ],
            ]);
        }

        if (Tenant::query()->count() === 0) {
            Tenant::query()->insert([
                [
                    'business_name' => 'Kedai Kopi Nusantara',
                    'owner_name' => 'Rizky Maulana',
                    'phone' => '081299887766',
                    'email' => 'kopinusantara@example.com',
                    'category' => 'Kuliner',
                    'booth_code' => 'A-01',
                    'status' => 'approved',
                    'notes' => 'Butuh 1 stopkontak tambahan',
                    'created_at' => now()->subDays(4),
                    'updated_at' => now()->subDays(3),
                ],
                [
                    'business_name' => 'Pustaka Anak Alam',
                    'owner_name' => 'Nadia Kusuma',
                    'phone' => '081377665544',
                    'email' => 'pustaka.alam@example.com',
                    'category' => 'Edukasi',
                    'booth_code' => 'B-03',
                    'status' => 'approved',
                    'notes' => 'Booth buku & alat peraga',
                    'created_at' => now()->subDays(2),
                    'updated_at' => now()->subDay(),
                ],
                [
                    'business_name' => 'Kriya Kayu Malang',
                    'owner_name' => 'Hendra Wijaya',
                    'phone' => '085744332211',
                    'email' => 'kriyamalang@example.com',
                    'category' => 'Kriya & Seni',
                    'booth_code' => null,
                    'status' => 'pending',
                    'notes' => 'Menunggu alokasi nomor booth',
                    'created_at' => now()->subHours(6),
                    'updated_at' => now()->subHours(6),
                ],
            ]);
        }

        if (Sponsor::query()->count() === 0) {
            Sponsor::query()->insert([
                [
                    'company_name' => 'Bank Syariah Mandiri Malang',
                    'contact_person' => 'Fajar Nugroho',
                    'phone' => '081122334455',
                    'email' => 'fajar.n@example.com',
                    'tier' => 'Platinum',
                    'contribution_amount' => 25000000,
                    'status' => 'confirmed',
                    'notes' => 'Logo di panggung utama & backdrop',
                    'created_at' => now()->subDays(5),
                    'updated_at' => now()->subDays(4),
                ],
                [
                    'company_name' => 'Penerbit Cahaya Ilmu',
                    'contact_person' => 'Siti Aminah',
                    'phone' => '081233445566',
                    'email' => 'siti@cahayailmu.example.com',
                    'tier' => 'Gold',
                    'contribution_amount' => 10000000,
                    'status' => 'confirmed',
                    'notes' => 'Support goodie bag peserta',
                    'created_at' => now()->subDays(3),
                    'updated_at' => now()->subDays(2),
                ],
                [
                    'company_name' => 'Air Mineral Segar Alami',
                    'contact_person' => 'Teguh Prasetyo',
                    'phone' => '081566778899',
                    'email' => 'teguh@segaralami.example.com',
                    'tier' => 'Partner',
                    'contribution_amount' => 3500000,
                    'status' => 'prospect',
                    'notes' => 'Follow up proposal minggu depan',
                    'created_at' => now()->subDay(),
                    'updated_at' => now()->subDay(),
                ],
            ]);
        }
    }
}
