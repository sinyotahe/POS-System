<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Category;
use App\Models\Product;
use App\Models\StockIn;
use App\Models\StockInItem;
use App\Models\StockMovement;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StockInSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@pos.test')->first();
        $pusat = Branch::where('code', 'PST')->first();

        $categories = [
            ['name' => 'Makanan'],
            ['name' => 'Minuman'],
            ['name' => 'Snack & Cemilan'],
            ['name' => 'Alat Tulis'],
            ['name' => 'Kebersihan'],
            ['name' => 'Sembako'],
            ['name' => 'Obat & Vitamin'],
            ['name' => 'Peralatan Rumah'],
            ['name' => 'Susu & Bayi'],
            ['name' => 'Rokok & Herbal'],
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }

        $suppliers = [
            ['name' => 'PT Sumber Makmur Sejahtera', 'phone' => '021-5551234', 'address' => 'Jl. Industri Raya No. 45, Jakarta Utara'],
            ['name' => 'CV Berkah Jaya Abadi', 'phone' => '021-5555678', 'address' => 'Jl. Merdeka No. 88, Bandung'],
            ['name' => 'UD Maju Lancar', 'phone' => '0341-5559012', 'address' => 'Jl. Veteran No. 12, Malang'],
            ['name' => 'PT Indah Logistik Utama', 'phone' => '031-5553456', 'address' => 'Jl. Raya Manyar No. 67, Surabaya'],
            ['name' => 'CV Niaga Lestari', 'phone' => '061-5557890', 'address' => 'Jl. Diponegoro No. 23, Medan'],
            ['name' => 'PT Bintang Suplai', 'phone' => '021-5552345', 'address' => 'Jl. Krakatau No. 100, Tangerang'],
        ];

        foreach ($suppliers as $sup) {
            Supplier::create($sup);
        }

        $catMakanan = Category::where('name', 'Makanan')->first()->id;
        $catMinuman = Category::where('name', 'Minuman')->first()->id;
        $catSnack = Category::where('name', 'Snack & Cemilan')->first()->id;
        $catAlatTulis = Category::where('name', 'Alat Tulis')->first()->id;
        $catKebersihan = Category::where('name', 'Kebersihan')->first()->id;
        $catSembako = Category::where('name', 'Sembako')->first()->id;
        $catObat = Category::where('name', 'Obat & Vitamin')->first()->id;
        $catPeralatan = Category::where('name', 'Peralatan Rumah')->first()->id;
        $catSusu = Category::where('name', 'Susu & Bayi')->first()->id;
        $catRokok = Category::where('name', 'Rokok & Herbal')->first()->id;

        $products = [
            // Makanan (20)
            ['category_id' => $catMakanan, 'sku' => 'MKN001', 'barcode' => '8991002101225', 'name' => 'Indomie Goreng', 'cost_price' => 2500, 'sell_price' => 3500, 'minimum_stock' => 20],
            ['category_id' => $catMakanan, 'sku' => 'MKN002', 'barcode' => '8991002101256', 'name' => 'Indomie Kuah Rasa Ayam', 'cost_price' => 2500, 'sell_price' => 3500, 'minimum_stock' => 20],
            ['category_id' => $catMakanan, 'sku' => 'MKN003', 'barcode' => '8991002101348', 'name' => 'Indomie Goreng Jumbo', 'cost_price' => 4000, 'sell_price' => 5500, 'minimum_stock' => 15],
            ['category_id' => $catMakanan, 'sku' => 'MKN004', 'barcode' => '8991002102307', 'name' => 'Mie Sedaap Goreng', 'cost_price' => 2500, 'sell_price' => 3500, 'minimum_stock' => 20],
            ['category_id' => $catMakanan, 'sku' => 'MKN005', 'barcode' => '8991002102314', 'name' => 'Mie Sedaap Kuah Rasa Soto', 'cost_price' => 2500, 'sell_price' => 3500, 'minimum_stock' => 20],
            ['category_id' => $catMakanan, 'sku' => 'MKN006', 'barcode' => '8997013990012', 'name' => 'Beras Ramos 1kg', 'cost_price' => 12000, 'sell_price' => 15000, 'minimum_stock' => 10],
            ['category_id' => $catMakanan, 'sku' => 'MKN007', 'barcode' => '8997013990029', 'name' => 'Beras Setra Wangi 5kg', 'cost_price' => 60000, 'sell_price' => 75000, 'minimum_stock' => 5],
            ['category_id' => $catMakanan, 'sku' => 'MKN008', 'barcode' => '8997021990032', 'name' => 'Telur Ayam 1kg', 'cost_price' => 22000, 'sell_price' => 28000, 'minimum_stock' => 10],
            ['category_id' => $catMakanan, 'sku' => 'MKN009', 'barcode' => '8997021990049', 'name' => 'Minyak Goreng 1L', 'cost_price' => 14000, 'sell_price' => 18000, 'minimum_stock' => 15],
            ['category_id' => $catMakanan, 'sku' => 'MKN010', 'barcode' => '8997021990056', 'name' => 'Tepung Terigu 1kg', 'cost_price' => 8000, 'sell_price' => 11000, 'minimum_stock' => 10],
            ['category_id' => $catMakanan, 'sku' => 'MKN011', 'barcode' => '8997021990063', 'name' => 'Kecap Manis Bango 550ml', 'cost_price' => 14000, 'sell_price' => 18000, 'minimum_stock' => 10],
            ['category_id' => $catMakanan, 'sku' => 'MKN012', 'barcode' => '8997021990070', 'name' => 'Saos Sambal ABC 340ml', 'cost_price' => 11000, 'sell_price' => 14000, 'minimum_stock' => 10],
            ['category_id' => $catMakanan, 'sku' => 'MKN013', 'barcode' => '8997021990087', 'name' => 'Garam Halus Refina 500g', 'cost_price' => 3500, 'sell_price' => 5000, 'minimum_stock' => 15],
            ['category_id' => $catMakanan, 'sku' => 'MKN014', 'barcode' => '8997021990094', 'name' => 'Kaldu Ayam Masako 240gr', 'cost_price' => 9500, 'sell_price' => 12500, 'minimum_stock' => 10],
            ['category_id' => $catMakanan, 'sku' => 'MKN015', 'barcode' => '8997021990100', 'name' => 'Sarden ABC 155gr', 'cost_price' => 6500, 'sell_price' => 9000, 'minimum_stock' => 10],
            ['category_id' => $catMakanan, 'sku' => 'MKN016', 'barcode' => '8997021990117', 'name' => 'Susu Kental Manis Frisian Flag', 'cost_price' => 9000, 'sell_price' => 12000, 'minimum_stock' => 15],
            ['category_id' => $catMakanan, 'sku' => 'MKN017', 'barcode' => '8997021990124', 'name' => 'Kopi Kapal Api 380gr', 'cost_price' => 24000, 'sell_price' => 30000, 'minimum_stock' => 5],
            ['category_id' => $catMakanan, 'sku' => 'MKN018', 'barcode' => '8997021990131', 'name' => 'Teh Celup Tong Tji 25ct', 'cost_price' => 5000, 'sell_price' => 7000, 'minimum_stock' => 10],
            ['category_id' => $catMakanan, 'sku' => 'MKN019', 'barcode' => '8997021990148', 'name' => 'Madurasa 500gr', 'cost_price' => 18000, 'sell_price' => 23500, 'minimum_stock' => 5],
            ['category_id' => $catMakanan, 'sku' => 'MKN020', 'barcode' => '8997021990155', 'name' => 'Corned Beef Pronas 340gr', 'cost_price' => 30000, 'sell_price' => 38000, 'minimum_stock' => 5],
            // Minuman (15)
            ['category_id' => $catMinuman, 'sku' => 'MNM001', 'barcode' => '8996001310112', 'name' => 'Air Mineral Vit 600ml', 'cost_price' => 2000, 'sell_price' => 3000, 'minimum_stock' => 30],
            ['category_id' => $catMinuman, 'sku' => 'MNM002', 'barcode' => '8996001310129', 'name' => 'Air Mineral Aqua 600ml', 'cost_price' => 2500, 'sell_price' => 3500, 'minimum_stock' => 30],
            ['category_id' => $catMinuman, 'sku' => 'MNM003', 'barcode' => '8996001310136', 'name' => 'Air Mineral Le Minerale 600ml', 'cost_price' => 2000, 'sell_price' => 3000, 'minimum_stock' => 30],
            ['category_id' => $catMinuman, 'sku' => 'MNM004', 'barcode' => '8996001310143', 'name' => 'Teh Botol Sosro 500ml', 'cost_price' => 4000, 'sell_price' => 5500, 'minimum_stock' => 20],
            ['category_id' => $catMinuman, 'sku' => 'MNM005', 'barcode' => '8996001310150', 'name' => 'Coca-Cola 390ml', 'cost_price' => 4500, 'sell_price' => 6000, 'minimum_stock' => 20],
            ['category_id' => $catMinuman, 'sku' => 'MNM006', 'barcode' => '8996001310167', 'name' => 'Fanta 390ml', 'cost_price' => 4500, 'sell_price' => 6000, 'minimum_stock' => 20],
            ['category_id' => $catMinuman, 'sku' => 'MNM007', 'barcode' => '8996001310174', 'name' => 'Sprite 390ml', 'cost_price' => 4500, 'sell_price' => 6000, 'minimum_stock' => 20],
            ['category_id' => $catMinuman, 'sku' => 'MNM008', 'barcode' => '8996001310181', 'name' => 'Pulpy Orange 300ml', 'cost_price' => 5000, 'sell_price' => 7000, 'minimum_stock' => 15],
            ['category_id' => $catMinuman, 'sku' => 'MNM009', 'barcode' => '8996001310198', 'name' => 'Mizone 500ml', 'cost_price' => 5500, 'sell_price' => 7500, 'minimum_stock' => 15],
            ['category_id' => $catMinuman, 'sku' => 'MNM010', 'barcode' => '8996001310204', 'name' => 'Pocari Sweat 500ml', 'cost_price' => 6000, 'sell_price' => 8000, 'minimum_stock' => 15],
            ['category_id' => $catMinuman, 'sku' => 'MNM011', 'barcode' => '8996001310211', 'name' => 'Yakult 5btl', 'cost_price' => 10000, 'sell_price' => 13000, 'minimum_stock' => 10],
            ['category_id' => $catMinuman, 'sku' => 'MNM012', 'barcode' => '8996001310228', 'name' => 'Susu UHT Ultra Milk 250ml', 'cost_price' => 6000, 'sell_price' => 8000, 'minimum_stock' => 15],
            ['category_id' => $catMinuman, 'sku' => 'MNM013', 'barcode' => '8996001310235', 'name' => 'Susu UHT Diamond 250ml', 'cost_price' => 5500, 'sell_price' => 7500, 'minimum_stock' => 15],
            ['category_id' => $catMinuman, 'sku' => 'MNM014', 'barcode' => '8996001310242', 'name' => 'Jus Bukit 350ml', 'cost_price' => 5500, 'sell_price' => 7500, 'minimum_stock' => 10],
            ['category_id' => $catMinuman, 'sku' => 'MNM015', 'barcode' => '8996001310259', 'name' => 'Ale-Ale 250ml', 'cost_price' => 1500, 'sell_price' => 2500, 'minimum_stock' => 30],
            // Snack (15)
            ['category_id' => $catSnack, 'sku' => 'SNK001', 'barcode' => '8996002110112', 'name' => 'Chitato 68gr', 'cost_price' => 7000, 'sell_price' => 10000, 'minimum_stock' => 10],
            ['category_id' => $catSnack, 'sku' => 'SNK002', 'barcode' => '8996002110129', 'name' => 'Lays 68gr', 'cost_price' => 7000, 'sell_price' => 10000, 'minimum_stock' => 10],
            ['category_id' => $catSnack, 'sku' => 'SNK003', 'barcode' => '8996002110136', 'name' => 'Qtela 68gr', 'cost_price' => 6500, 'sell_price' => 9000, 'minimum_stock' => 10],
            ['category_id' => $catSnack, 'sku' => 'SNK004', 'barcode' => '8996002110143', 'name' => 'Taro 60gr', 'cost_price' => 6000, 'sell_price' => 8500, 'minimum_stock' => 10],
            ['category_id' => $catSnack, 'sku' => 'SNK005', 'barcode' => '8996002110150', 'name' => 'Cheetos 68gr', 'cost_price' => 6500, 'sell_price' => 9000, 'minimum_stock' => 10],
            ['category_id' => $catSnack, 'sku' => 'SNK006', 'barcode' => '8996002110167', 'name' => 'Oreo 133gr', 'cost_price' => 8000, 'sell_price' => 11000, 'minimum_stock' => 10],
            ['category_id' => $catSnack, 'sku' => 'SNK007', 'barcode' => '8996002110174', 'name' => 'Roma Malkist 135gr', 'cost_price' => 5000, 'sell_price' => 7500, 'minimum_stock' => 10],
            ['category_id' => $catSnack, 'sku' => 'SNK008', 'barcode' => '8996002110181', 'name' => 'Wafer Tango 225gr', 'cost_price' => 12000, 'sell_price' => 16000, 'minimum_stock' => 10],
            ['category_id' => $catSnack, 'sku' => 'SNK009', 'barcode' => '8996002110198', 'name' => 'Beng-Beng 40gr', 'cost_price' => 2500, 'sell_price' => 4000, 'minimum_stock' => 20],
            ['category_id' => $catSnack, 'sku' => 'SNK010', 'barcode' => '8996002110204', 'name' => 'Top 40gr', 'cost_price' => 2500, 'sell_price' => 4000, 'minimum_stock' => 20],
            ['category_id' => $catSnack, 'sku' => 'SNK011', 'barcode' => '8996002110211', 'name' => 'Pilus Garuda 200gr', 'cost_price' => 8000, 'sell_price' => 11000, 'minimum_stock' => 10],
            ['category_id' => $catSnack, 'sku' => 'SNK012', 'barcode' => '8996002110228', 'name' => 'Kacang Garuda 200gr', 'cost_price' => 10000, 'sell_price' => 13500, 'minimum_stock' => 10],
            ['category_id' => $catSnack, 'sku' => 'SNK013', 'barcode' => '8996002110235', 'name' => 'Good Time 150gr', 'cost_price' => 12000, 'sell_price' => 16000, 'minimum_stock' => 10],
            ['category_id' => $catSnack, 'sku' => 'SNK014', 'barcode' => '8996002110242', 'name' => 'Chocolatos 20gr', 'cost_price' => 2000, 'sell_price' => 3000, 'minimum_stock' => 20],
            ['category_id' => $catSnack, 'sku' => 'SNK015', 'barcode' => '8996002110259', 'name' => 'Permen Relaxa 50gr', 'cost_price' => 4000, 'sell_price' => 6000, 'minimum_stock' => 15],
            // Alat Tulis (10)
            ['category_id' => $catAlatTulis, 'sku' => 'ALT001', 'barcode' => '8996003110112', 'name' => 'Buku Tulis Sinar Dunia 38lb', 'cost_price' => 3500, 'sell_price' => 5000, 'minimum_stock' => 20],
            ['category_id' => $catAlatTulis, 'sku' => 'ALT002', 'barcode' => '8996003110129', 'name' => 'Buku Gambar A4', 'cost_price' => 5000, 'sell_price' => 7000, 'minimum_stock' => 15],
            ['category_id' => $catAlatTulis, 'sku' => 'ALT003', 'barcode' => '8996003110136', 'name' => 'Pulpen Standard AE7', 'cost_price' => 2000, 'sell_price' => 3500, 'minimum_stock' => 30],
            ['category_id' => $catAlatTulis, 'sku' => 'ALT004', 'barcode' => '8996003110143', 'name' => 'Pulpen Pilot G2', 'cost_price' => 12000, 'sell_price' => 16000, 'minimum_stock' => 10],
            ['category_id' => $catAlatTulis, 'sku' => 'ALT005', 'barcode' => '8996003110150', 'name' => 'Pensil 2B Faber Castell', 'cost_price' => 3000, 'sell_price' => 5000, 'minimum_stock' => 20],
            ['category_id' => $catAlatTulis, 'sku' => 'ALT006', 'barcode' => '8996003110167', 'name' => 'Penghapus Joyko', 'cost_price' => 2000, 'sell_price' => 3000, 'minimum_stock' => 20],
            ['category_id' => $catAlatTulis, 'sku' => 'ALT007', 'barcode' => '8996003110174', 'name' => 'Penggaris 30cm Joyko', 'cost_price' => 4000, 'sell_price' => 5500, 'minimum_stock' => 15],
            ['category_id' => $catAlatTulis, 'sku' => 'ALT008', 'barcode' => '8996003110181', 'name' => 'Lem Uhu 35gr', 'cost_price' => 8000, 'sell_price' => 11000, 'minimum_stock' => 10],
            ['category_id' => $catAlatTulis, 'sku' => 'ALT009', 'barcode' => '8996003110198', 'name' => 'Gunting Kenko', 'cost_price' => 7000, 'sell_price' => 10000, 'minimum_stock' => 10],
            ['category_id' => $catAlatTulis, 'sku' => 'ALT010', 'barcode' => '8996003110204', 'name' => 'Cutter Joyko', 'cost_price' => 5000, 'sell_price' => 7000, 'minimum_stock' => 10],
            // Kebersihan (10)
            ['category_id' => $catKebersihan, 'sku' => 'KBR001', 'barcode' => '8996004110112', 'name' => 'Sabun Lifebuoy 100gr', 'cost_price' => 4000, 'sell_price' => 5500, 'minimum_stock' => 15],
            ['category_id' => $catKebersihan, 'sku' => 'KBR002', 'barcode' => '8996004110129', 'name' => 'Pasta Gigi Pepsodent 120gr', 'cost_price' => 8000, 'sell_price' => 11000, 'minimum_stock' => 15],
            ['category_id' => $catKebersihan, 'sku' => 'KBR003', 'barcode' => '8996004110136', 'name' => 'Sikat Gigi Formula', 'cost_price' => 4000, 'sell_price' => 6000, 'minimum_stock' => 15],
            ['category_id' => $catKebersihan, 'sku' => 'KBR004', 'barcode' => '8996004110143', 'name' => 'Shampoo Clear 80ml', 'cost_price' => 5000, 'sell_price' => 7000, 'minimum_stock' => 15],
            ['category_id' => $catKebersihan, 'sku' => 'KBR005', 'barcode' => '8996004110150', 'name' => 'Sabun Cuci Piring Sunlight 450ml', 'cost_price' => 8000, 'sell_price' => 11000, 'minimum_stock' => 10],
            ['category_id' => $catKebersihan, 'sku' => 'KBR006', 'barcode' => '8996004110167', 'name' => 'Deterjen Rinso 500gr', 'cost_price' => 11000, 'sell_price' => 14500, 'minimum_stock' => 10],
            ['category_id' => $catKebersihan, 'sku' => 'KBR007', 'barcode' => '8996004110174', 'name' => 'Pewangi Molto 250ml', 'cost_price' => 7000, 'sell_price' => 10000, 'minimum_stock' => 10],
            ['category_id' => $catKebersihan, 'sku' => 'KBR008', 'barcode' => '8996004110181', 'name' => 'Pemutih Bayclin 500ml', 'cost_price' => 5000, 'sell_price' => 7500, 'minimum_stock' => 10],
            ['category_id' => $catKebersihan, 'sku' => 'KBR009', 'barcode' => '8996004110198', 'name' => 'Pembersih Lantai So Klin 750ml', 'cost_price' => 10000, 'sell_price' => 13500, 'minimum_stock' => 10],
            ['category_id' => $catKebersihan, 'sku' => 'KBR010', 'barcode' => '8996004110204', 'name' => 'Tissue Paseo 200ct', 'cost_price' => 6000, 'sell_price' => 9000, 'minimum_stock' => 15],
            // Sembako (10)
            ['category_id' => $catSembako, 'sku' => 'SBK001', 'barcode' => '8996005110112', 'name' => 'Minyak Goreng Minyakita 1L', 'cost_price' => 14000, 'sell_price' => 18000, 'minimum_stock' => 15],
            ['category_id' => $catSembako, 'sku' => 'SBK002', 'barcode' => '8996005110129', 'name' => 'Gula Pasir Gulaku 1kg', 'cost_price' => 13500, 'sell_price' => 17000, 'minimum_stock' => 10],
            ['category_id' => $catSembako, 'sku' => 'SBK003', 'barcode' => '8996005110136', 'name' => 'Tepung Segitiga Biru 1kg', 'cost_price' => 10000, 'sell_price' => 13000, 'minimum_stock' => 10],
            ['category_id' => $catSembako, 'sku' => 'SBK004', 'barcode' => '8996005110143', 'name' => 'Beras Kepala 5kg', 'cost_price' => 55000, 'sell_price' => 68000, 'minimum_stock' => 5],
            ['category_id' => $catSembako, 'sku' => 'SBK005', 'barcode' => '8996005110150', 'name' => 'Kacang Hijau 500gr', 'cost_price' => 12000, 'sell_price' => 15500, 'minimum_stock' => 10],
            ['category_id' => $catSembako, 'sku' => 'SBK006', 'barcode' => '8996005110167', 'name' => 'Bawang Merah 1kg', 'cost_price' => 28000, 'sell_price' => 35000, 'minimum_stock' => 5],
            ['category_id' => $catSembako, 'sku' => 'SBK007', 'barcode' => '8996005110174', 'name' => 'Bawang Putih 1kg', 'cost_price' => 25000, 'sell_price' => 32000, 'minimum_stock' => 5],
            ['category_id' => $catSembako, 'sku' => 'SBK008', 'barcode' => '8996005110181', 'name' => 'Cabai Merah 1kg', 'cost_price' => 35000, 'sell_price' => 45000, 'minimum_stock' => 5],
            ['category_id' => $catSembako, 'sku' => 'SBK009', 'barcode' => '8996005110198', 'name' => 'Telur Bebek 1kg', 'cost_price' => 27000, 'sell_price' => 34000, 'minimum_stock' => 5],
            ['category_id' => $catSembako, 'sku' => 'SBK010', 'barcode' => '8996005110204', 'name' => 'Mie Kering Eko 200gr', 'cost_price' => 6000, 'sell_price' => 8500, 'minimum_stock' => 15],
            // Obat & Vitamin (5)
            ['category_id' => $catObat, 'sku' => 'OBT001', 'barcode' => '8996006110112', 'name' => 'Paracetamol 500mg 10kap', 'cost_price' => 5000, 'sell_price' => 8000, 'minimum_stock' => 10],
            ['category_id' => $catObat, 'sku' => 'OBT002', 'barcode' => '8996006110129', 'name' => 'Antangin JRG 3sachet', 'cost_price' => 4000, 'sell_price' => 6000, 'minimum_stock' => 15],
            ['category_id' => $catObat, 'sku' => 'OBT003', 'barcode' => '8996006110136', 'name' => 'Tolak Angin 6sachet', 'cost_price' => 8000, 'sell_price' => 11000, 'minimum_stock' => 10],
            ['category_id' => $catObat, 'sku' => 'OBT004', 'barcode' => '8996006110143', 'name' => 'Minyak Kayu Putih Cap Lang 30ml', 'cost_price' => 14000, 'sell_price' => 19000, 'minimum_stock' => 10],
            ['category_id' => $catObat, 'sku' => 'OBT005', 'barcode' => '8996006110150', 'name' => 'Betadine 15ml', 'cost_price' => 15000, 'sell_price' => 20000, 'minimum_stock' => 10],
            // Peralatan Rumah (5)
            ['category_id' => $catPeralatan, 'sku' => 'PRT001', 'barcode' => '8996007110112', 'name' => 'Gelas Kaca 250ml 6pcs', 'cost_price' => 25000, 'sell_price' => 35000, 'minimum_stock' => 5],
            ['category_id' => $catPeralatan, 'sku' => 'PRT002', 'barcode' => '8996007110129', 'name' => 'Piring Melamin 20cm', 'cost_price' => 8000, 'sell_price' => 12000, 'minimum_stock' => 10],
            ['category_id' => $catPeralatan, 'sku' => 'PRT003', 'barcode' => '8996007110136', 'name' => 'Sendok Stainless 12pcs', 'cost_price' => 20000, 'sell_price' => 28000, 'minimum_stock' => 5],
            ['category_id' => $catPeralatan, 'sku' => 'PRT004', 'barcode' => '8996007110143', 'name' => 'Ember Plastik 10L', 'cost_price' => 18000, 'sell_price' => 25000, 'minimum_stock' => 5],
            ['category_id' => $catPeralatan, 'sku' => 'PRT005', 'barcode' => '8996007110150', 'name' => 'Lap Pel Magic', 'cost_price' => 12000, 'sell_price' => 17000, 'minimum_stock' => 10],
            // Susu & Bayi (5)
            ['category_id' => $catSusu, 'sku' => 'SUS001', 'barcode' => '8996008110112', 'name' => 'Susu SGM 3+ 400gr', 'cost_price' => 32000, 'sell_price' => 40000, 'minimum_stock' => 5],
            ['category_id' => $catSusu, 'sku' => 'SUS002', 'barcode' => '8996008110129', 'name' => 'Susu Dancow 1+ 400gr', 'cost_price' => 35000, 'sell_price' => 44000, 'minimum_stock' => 5],
            ['category_id' => $catSusu, 'sku' => 'SUS003', 'barcode' => '8996008110136', 'name' => 'Popok Merries M 44ct', 'cost_price' => 70000, 'sell_price' => 88000, 'minimum_stock' => 3],
            ['category_id' => $catSusu, 'sku' => 'SUS004', 'barcode' => '8996008110143', 'name' => 'Tisu Basah Baby Pigeon', 'cost_price' => 18000, 'sell_price' => 24000, 'minimum_stock' => 10],
            ['category_id' => $catSusu, 'sku' => 'SUS005', 'barcode' => '8996008110150', 'name' => 'Minyak Telon Tresno Joy 100ml', 'cost_price' => 22000, 'sell_price' => 29000, 'minimum_stock' => 10],
            // Rokok & Herbal (5)
            ['category_id' => $catRokok, 'sku' => 'ROK001', 'barcode' => '8996009110112', 'name' => 'Sampoerna Kretek 12btg', 'cost_price' => 18000, 'sell_price' => 24000, 'minimum_stock' => 10],
            ['category_id' => $catRokok, 'sku' => 'ROK002', 'barcode' => '8996009110129', 'name' => 'Dji Sam Soe Magnum 12btg', 'cost_price' => 20000, 'sell_price' => 26000, 'minimum_stock' => 10],
            ['category_id' => $catRokok, 'sku' => 'ROK003', 'barcode' => '8996009110136', 'name' => 'Gudang Garam Signature 12btg', 'cost_price' => 18000, 'sell_price' => 24000, 'minimum_stock' => 10],
            ['category_id' => $catRokok, 'sku' => 'ROK004', 'barcode' => '8996009110143', 'name' => 'Surya Pro Mild 12btg', 'cost_price' => 20000, 'sell_price' => 26000, 'minimum_stock' => 10],
            ['category_id' => $catRokok, 'sku' => 'ROK005', 'barcode' => '8996009110150', 'name' => 'Sampoerna Mild 12btg', 'cost_price' => 22000, 'sell_price' => 28000, 'minimum_stock' => 10],
        ];

        $productIds = [];
        foreach ($products as $data) {
            $p = Product::create($data);
            $productIds[] = $p->id;
        }

        $supplierIds = Supplier::pluck('id')->toArray();

        $stockInData = [
            [
                'supplier_index' => 0,
                'invoice' => 'INV-20260526-001',
                'notes' => 'Restok rutin bulan Mei - Makanan & Minuman',
                'items' => [
                    ['product_idx' => 0, 'qty' => 100, 'cost_price' => 2500],
                    ['product_idx' => 1, 'qty' => 100, 'cost_price' => 2500],
                    ['product_idx' => 2, 'qty' => 50, 'cost_price' => 4000],
                    ['product_idx' => 3, 'qty' => 80, 'cost_price' => 2500],
                    ['product_idx' => 4, 'qty' => 80, 'cost_price' => 2500],
                    ['product_idx' => 5, 'qty' => 30, 'cost_price' => 12000],
                    ['product_idx' => 6, 'qty' => 15, 'cost_price' => 60000],
                    ['product_idx' => 7, 'qty' => 40, 'cost_price' => 22000],
                    ['product_idx' => 8, 'qty' => 50, 'cost_price' => 14000],
                    ['product_idx' => 9, 'qty' => 30, 'cost_price' => 8000],
                ],
            ],
            [
                'supplier_index' => 1,
                'invoice' => 'INV-20260526-002',
                'notes' => 'Restok minuman dan snack',
                'items' => [
                    ['product_idx' => 20, 'qty' => 120, 'cost_price' => 2000],
                    ['product_idx' => 21, 'qty' => 100, 'cost_price' => 2500],
                    ['product_idx' => 22, 'qty' => 120, 'cost_price' => 2000],
                    ['product_idx' => 23, 'qty' => 60, 'cost_price' => 4000],
                    ['product_idx' => 24, 'qty' => 50, 'cost_price' => 4500],
                    ['product_idx' => 25, 'qty' => 50, 'cost_price' => 4500],
                    ['product_idx' => 26, 'qty' => 50, 'cost_price' => 4500],
                    ['product_idx' => 35, 'qty' => 60, 'cost_price' => 7000],
                    ['product_idx' => 36, 'qty' => 50, 'cost_price' => 7000],
                    ['product_idx' => 37, 'qty' => 50, 'cost_price' => 6500],
                ],
            ],
            [
                'supplier_index' => 2,
                'invoice' => 'INV-20260526-003',
                'notes' => 'Restok snack dan cemilan',
                'items' => [
                    ['product_idx' => 38, 'qty' => 40, 'cost_price' => 6000],
                    ['product_idx' => 39, 'qty' => 50, 'cost_price' => 6500],
                    ['product_idx' => 40, 'qty' => 60, 'cost_price' => 8000],
                    ['product_idx' => 41, 'qty' => 70, 'cost_price' => 5000],
                    ['product_idx' => 42, 'qty' => 40, 'cost_price' => 12000],
                    ['product_idx' => 43, 'qty' => 100, 'cost_price' => 2500],
                    ['product_idx' => 44, 'qty' => 100, 'cost_price' => 2500],
                    ['product_idx' => 45, 'qty' => 40, 'cost_price' => 8000],
                    ['product_idx' => 46, 'qty' => 30, 'cost_price' => 10000],
                    ['product_idx' => 47, 'qty' => 25, 'cost_price' => 12000],
                ],
            ],
            [
                'supplier_index' => 3,
                'invoice' => 'INV-20260526-004',
                'notes' => 'Restok alat tulis dan kebersihan',
                'items' => [
                    ['product_idx' => 50, 'qty' => 100, 'cost_price' => 3500],
                    ['product_idx' => 51, 'qty' => 60, 'cost_price' => 5000],
                    ['product_idx' => 52, 'qty' => 200, 'cost_price' => 2000],
                    ['product_idx' => 53, 'qty' => 40, 'cost_price' => 12000],
                    ['product_idx' => 54, 'qty' => 80, 'cost_price' => 3000],
                    ['product_idx' => 55, 'qty' => 100, 'cost_price' => 2000],
                    ['product_idx' => 56, 'qty' => 50, 'cost_price' => 4000],
                    ['product_idx' => 60, 'qty' => 80, 'cost_price' => 4000],
                    ['product_idx' => 61, 'qty' => 60, 'cost_price' => 8000],
                    ['product_idx' => 62, 'qty' => 50, 'cost_price' => 4000],
                ],
            ],
            [
                'supplier_index' => 4,
                'invoice' => 'INV-20260526-005',
                'notes' => 'Restok sembako dan kebersihan',
                'items' => [
                    ['product_idx' => 63, 'qty' => 40, 'cost_price' => 5000],
                    ['product_idx' => 64, 'qty' => 50, 'cost_price' => 8000],
                    ['product_idx' => 65, 'qty' => 40, 'cost_price' => 11000],
                    ['product_idx' => 66, 'qty' => 40, 'cost_price' => 7000],
                    ['product_idx' => 67, 'qty' => 40, 'cost_price' => 5000],
                    ['product_idx' => 68, 'qty' => 30, 'cost_price' => 10000],
                    ['product_idx' => 69, 'qty' => 60, 'cost_price' => 6000],
                    ['product_idx' => 70, 'qty' => 50, 'cost_price' => 14000],
                    ['product_idx' => 71, 'qty' => 40, 'cost_price' => 13500],
                    ['product_idx' => 72, 'qty' => 30, 'cost_price' => 10000],
                ],
            ],
            [
                'supplier_index' => 5,
                'invoice' => 'INV-20260526-006',
                'notes' => 'Restok sembako dan bumbu dapur',
                'items' => [
                    ['product_idx' => 73, 'qty' => 15, 'cost_price' => 55000],
                    ['product_idx' => 74, 'qty' => 30, 'cost_price' => 12000],
                    ['product_idx' => 75, 'qty' => 20, 'cost_price' => 28000],
                    ['product_idx' => 76, 'qty' => 20, 'cost_price' => 25000],
                    ['product_idx' => 77, 'qty' => 15, 'cost_price' => 35000],
                    ['product_idx' => 78, 'qty' => 20, 'cost_price' => 27000],
                    ['product_idx' => 10, 'qty' => 30, 'cost_price' => 14000],
                    ['product_idx' => 11, 'qty' => 30, 'cost_price' => 11000],
                    ['product_idx' => 12, 'qty' => 50, 'cost_price' => 3500],
                    ['product_idx' => 13, 'qty' => 30, 'cost_price' => 9500],
                ],
            ],
            [
                'supplier_index' => 0,
                'invoice' => 'INV-20260526-007',
                'notes' => 'Restok makanan tambahan - mie instan dan sarden',
                'items' => [
                    ['product_idx' => 14, 'qty' => 40, 'cost_price' => 6500],
                    ['product_idx' => 15, 'qty' => 50, 'cost_price' => 9000],
                    ['product_idx' => 16, 'qty' => 20, 'cost_price' => 24000],
                    ['product_idx' => 17, 'qty' => 40, 'cost_price' => 5000],
                    ['product_idx' => 18, 'qty' => 15, 'cost_price' => 18000],
                    ['product_idx' => 19, 'qty' => 10, 'cost_price' => 30000],
                    ['product_idx' => 48, 'qty' => 25, 'cost_price' => 2000],
                    ['product_idx' => 49, 'qty' => 30, 'cost_price' => 4000],
                ],
            ],
            [
                'supplier_index' => 1,
                'invoice' => 'INV-20260526-008',
                'notes' => 'Restok minuman dan snack ringan',
                'items' => [
                    ['product_idx' => 27, 'qty' => 40, 'cost_price' => 5000],
                    ['product_idx' => 28, 'qty' => 40, 'cost_price' => 5500],
                    ['product_idx' => 29, 'qty' => 40, 'cost_price' => 6000],
                    ['product_idx' => 30, 'qty' => 25, 'cost_price' => 10000],
                    ['product_idx' => 31, 'qty' => 50, 'cost_price' => 6000],
                    ['product_idx' => 32, 'qty' => 40, 'cost_price' => 5500],
                    ['product_idx' => 33, 'qty' => 30, 'cost_price' => 5500],
                    ['product_idx' => 34, 'qty' => 100, 'cost_price' => 1500],
                ],
            ],
            [
                'supplier_index' => 2,
                'invoice' => 'INV-20260526-009',
                'notes' => 'Restok barang khusus - obat, peralatan rumah, susu',
                'items' => [
                    ['product_idx' => 80, 'qty' => 40, 'cost_price' => 5000],
                    ['product_idx' => 81, 'qty' => 50, 'cost_price' => 4000],
                    ['product_idx' => 82, 'qty' => 30, 'cost_price' => 8000],
                    ['product_idx' => 83, 'qty' => 25, 'cost_price' => 14000],
                    ['product_idx' => 84, 'qty' => 20, 'cost_price' => 15000],
                    ['product_idx' => 85, 'qty' => 10, 'cost_price' => 25000],
                    ['product_idx' => 86, 'qty' => 20, 'cost_price' => 8000],
                    ['product_idx' => 87, 'qty' => 15, 'cost_price' => 20000],
                    ['product_idx' => 88, 'qty' => 15, 'cost_price' => 18000],
                    ['product_idx' => 89, 'qty' => 20, 'cost_price' => 12000],
                ],
            ],
            [
                'supplier_index' => 3,
                'invoice' => 'INV-20260526-010',
                'notes' => 'Restok susu bayi dan rokok',
                'items' => [
                    ['product_idx' => 90, 'qty' => 10, 'cost_price' => 32000],
                    ['product_idx' => 91, 'qty' => 10, 'cost_price' => 35000],
                    ['product_idx' => 92, 'qty' => 8, 'cost_price' => 70000],
                    ['product_idx' => 93, 'qty' => 20, 'cost_price' => 18000],
                    ['product_idx' => 94, 'qty' => 15, 'cost_price' => 22000],
                    ['product_idx' => 95, 'qty' => 25, 'cost_price' => 18000],
                    ['product_idx' => 96, 'qty' => 20, 'cost_price' => 20000],
                    ['product_idx' => 97, 'qty' => 25, 'cost_price' => 18000],
                    ['product_idx' => 98, 'qty' => 20, 'cost_price' => 20000],
                    ['product_idx' => 99, 'qty' => 20, 'cost_price' => 22000],
                ],
            ],
        ];

        foreach ($stockInData as $data) {
            DB::beginTransaction();
            try {
                $stockIn = StockIn::create([
                    'supplier_id' => $supplierIds[$data['supplier_index']],
                    'invoice_number' => $data['invoice'],
                    'notes' => $data['notes'],
                    'created_by' => $admin->id,
                    'branch_id' => $pusat?->id,
                ]);

                foreach ($data['items'] as $item) {
                    $productId = $productIds[$item['product_idx']];
                    $subtotal = $item['qty'] * $item['cost_price'];

                    StockInItem::create([
                        'stock_in_id' => $stockIn->id,
                        'product_id' => $productId,
                        'qty' => $item['qty'],
                        'cost_price' => $item['cost_price'],
                        'subtotal' => $subtotal,
                    ]);

                    $bp = null;
                    if ($pusat) {
                        DB::table('branch_product')->updateOrInsert(
                            ['branch_id' => $pusat->id, 'product_id' => $productId],
                            ['stock' => DB::raw('COALESCE(stock, 0) + '.$item['qty']), 'created_at' => now(), 'updated_at' => now()]
                        );
                        $bp = DB::table('branch_product')
                            ->where('branch_id', $pusat->id)
                            ->where('product_id', $productId)
                            ->first();
                    }
                    $beforeStock = $bp ? (int) $bp->stock - $item['qty'] : 0;
                    $afterStock = $bp ? (int) $bp->stock : $item['qty'];

                    StockMovement::create([
                        'product_id' => $productId,
                        'type' => 'in',
                        'quantity' => $item['qty'],
                        'before_stock' => $beforeStock,
                        'after_stock' => $afterStock,
                        'reference_type' => 'stock_in',
                        'reference_id' => $stockIn->id,
                        'user_id' => $admin->id,
                    ]);
                }

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        }
    }
}
