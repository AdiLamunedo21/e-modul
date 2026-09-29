<?php

namespace Database\Seeders;

use App\Models\Module;
use App\Models\PreTest;
use App\Models\PreTestQuestion;
use App\Models\PostTest;
use App\Models\PostTestQuestion;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PythonModuleTestsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cari modul Pemrograman Dasar Python
        $module = Module::where('title', 'like', '%Python%')->first();

        if (!$module) {
            $this->command->error("Modul Pemrograman Dasar Python tidak ditemukan!");
            return;
        }

        DB::transaction(function () use ($module) {
            // ── 1. UPDATE MODUL FLAG ─────────────────────────────────
            $module->update([
                'has_pre_test'  => true,
                'has_post_test' => true,
            ]);

            // ── 2. PRE-TEST SETUP ─────────────────────────────────────
            $preTest = PreTest::firstOrCreate(
                ['module_id' => $module->id],
                [
                    'title'               => 'Pre-test: Pemrograman Dasar Python',
                    'kktp'                => 75,
                    'instructions'        => 'Kerjakan 10 butir soal pre-test berikut secara mandiri untuk mengukur pemahaman awal Anda terhadap konsep dasar pemrograman Python.',
                    'randomize_questions' => false,
                ]
            );

            $preTest->update([
                'title'               => 'Pre-test: Pemrograman Dasar Python',
                'kktp'                => 75,
                'instructions'        => 'Kerjakan 10 butir soal pre-test berikut secara mandiri untuk mengukur pemahaman awal Anda terhadap konsep dasar pemrograman Python.',
                'randomize_questions' => false,
            ]);

            // Hapus pertanyaan pre-test lama jika ada
            $preTest->questions()->delete();

            $preTestQuestions = [
                [
                    'question_text' => "Di bawah ini, manakah aturan penamaan variabel yang valid (benar) menurut sintaksis bahasa Python?",
                    'options' => [
                        'A' => '2_nama_siswa',
                        'B' => 'nama-siswa',
                        'C' => 'nama_siswa',
                        'D' => 'class',
                        'E' => 'nama siswa',
                    ],
                    'correct_answer' => 'C',
                    'score_weight' => 10,
                    'explanation' => "Aturan penamaan variabel di Python: tidak boleh diawali angka (A salah), tidak boleh menggunakan tanda hubung/dash (B salah), tidak boleh menggunakan kata kunci bawaan/reserved keyword seperti 'class' (D salah), dan tidak boleh menggunakan spasi (E salah). Penamaan yang valid adalah menggunakan underscore (nama_siswa).",
                ],
                [
                    'question_text' => "Fungsi bawaan Python yang digunakan untuk membaca input dari pengguna melalui keyboard adalah...",
                    'options' => [
                        'A' => 'read()',
                        'B' => 'scan()',
                        'C' => 'cin()',
                        'D' => 'input()',
                        'E' => 'get()',
                    ],
                    'correct_answer' => 'D',
                    'score_weight' => 10,
                    'explanation' => "Fungsi bawaan di Python untuk membaca masukan pengguna dari keyboard melalui konsol adalah input().",
                ],
                [
                    'question_text' => "Secara default, nilai yang dihasilkan dan dikembalikan oleh fungsi input() di Python selalu memiliki tipe data...",
                    'options' => [
                        'A' => 'int (Integer)',
                        'B' => 'str (String)',
                        'C' => 'float (Desimal)',
                        'D' => 'bool (Boolean)',
                        'E' => 'NoneType',
                    ],
                    'correct_answer' => 'B',
                    'score_weight' => 10,
                    'explanation' => "Semua masukan yang diterima melalui fungsi input() di Python akan selalu dianggap sebagai teks bertipe data string (str), meskipun pengguna memasukkan deretan angka.",
                ],
                [
                    'question_text' => "Perhatikan baris kode berikut:\n\ntinggi = 172.5\n\nTipe data dari variabel 'tinggi' di atas adalah...",
                    'options' => [
                        'A' => 'int',
                        'B' => 'str',
                        'C' => 'float',
                        'D' => 'boolean',
                        'E' => 'list',
                    ],
                    'correct_answer' => 'C',
                    'score_weight' => 10,
                    'explanation' => "Bilangan yang mengandung angka pecahan atau titik desimal di Python dikategorikan ke dalam tipe data float.",
                ],
                [
                    'question_text' => "Manakah dari baris kode berikut yang akan menghasilkan Error (TypeError) saat dijalankan di Python?",
                    'options' => [
                        'A' => '"Nilai: " + "100"',
                        'B' => '"Skor: " + str(85)',
                        'C' => '"Python " * 3',
                        'D' => '"Umur: " + 17',
                        'E' => '10 + 25.5',
                    ],
                    'correct_answer' => 'D',
                    'score_weight' => 10,
                    'explanation' => 'Di Python, operator + tidak dapat menggabungkan string secara langsung dengan integer ("Umur: " + 17). Angka 17 harus dikonversi menjadi string terlebih dahulu menggunakan str(17).',
                ],
                [
                    'question_text' => "Berapakah hasil dari operasi aritmatika 20 % 6 di Python?",
                    'options' => [
                        'A' => '3',
                        'B' => '2',
                        'C' => '3.33',
                        'D' => '1',
                        'E' => '0',
                    ],
                    'correct_answer' => 'B',
                    'score_weight' => 10,
                    'explanation' => "Operator % adalah modulo (sisa pembagian bulat). 20 dibagi 6 menghasilkan 3 dengan sisa 2 (karena 6 * 3 = 18, dan 20 - 18 = 2).",
                ],
                [
                    'question_text' => "Manakah ekspresi logika berikut yang menghasilkan nilai True?",
                    'options' => [
                        'A' => '(5 > 10) and (3 < 8)',
                        'B' => 'not (10 == 10)',
                        'C' => '(4 >= 4) or (2 > 5)',
                        'D' => '(7 != 7) and (1 < 2)',
                        'E' => '(3 == 5) or (8 < 3)',
                    ],
                    'correct_answer' => 'C',
                    'score_weight' => 10,
                    'explanation' => "Pada operator 'or', jika salah satu kondisi bernilai True maka hasil akhirnya bernilai True. Karena (4 >= 4) bernilai True, maka (True or False) menghasilkan True.",
                ],
                [
                    'question_text' => "Dalam struktur percabangan Python, kata kunci (keyword) yang digunakan untuk menuliskan kondisi pilihan alternatif berikutnya setelah 'if' adalah...",
                    'options' => [
                        'A' => 'else if',
                        'B' => 'elseif',
                        'C' => 'elif',
                        'D' => 'case',
                        'E' => 'switch',
                    ],
                    'correct_answer' => 'C',
                    'score_weight' => 10,
                    'explanation' => "Python menggunakan kata kunci 'elif' (singkatan dari else if) untuk percabangan jamak setelah kondisi 'if'.",
                ],
                [
                    'question_text' => "Perhatikan potongan kode berikut:\n\nfor i in range(1, 5):\n    print(i, end=\" \")\n\nOutput yang akan dicetak di layar adalah...",
                    'options' => [
                        'A' => '1 2 3 4 5',
                        'B' => '1 2 3 4',
                        'C' => '0 1 2 3 4',
                        'D' => '2 3 4 5',
                        'E' => '1 5',
                    ],
                    'correct_answer' => 'B',
                    'score_weight' => 10,
                    'explanation' => "Fungsi range(start, stop) pada Python berhenti satu langkah sebelum nilai batas akhir (stop bersifat eksklusif). Jadi range(1, 5) menghasilkan angka 1, 2, 3, dan 4.",
                ],
                [
                    'question_text' => "Kata kunci (keyword) yang wajib digunakan untuk mendeklarasikan atau membuat sebuah fungsi baru di Python adalah...",
                    'options' => [
                        'A' => 'function',
                        'B' => 'func',
                        'C' => 'void',
                        'D' => 'def',
                        'E' => 'define',
                    ],
                    'correct_answer' => 'D',
                    'score_weight' => 10,
                    'explanation' => "Pendefinisian fungsi di Python selalu diawali dengan kata kunci 'def' (define), diikuti nama fungsi dan tanda kurung parameter.",
                ],
            ];

            foreach ($preTestQuestions as $idx => $qData) {
                PreTestQuestion::create([
                    'pre_test_id'        => $preTest->id,
                    'question_text'      => $qData['question_text'],
                    'options'            => $qData['options'],
                    'correct_answer'     => $qData['correct_answer'],
                    'score_weight'       => $qData['score_weight'],
                    'time_limit_seconds' => null,
                    'explanation'        => $qData['explanation'],
                    'order_num'          => $idx + 1,
                ]);
            }

            // ── 3. POST-TEST SETUP ────────────────────────────────────
            $postTest = PostTest::firstOrCreate(
                ['module_id' => $module->id],
                [
                    'title'               => 'Post-test: Pemrograman Dasar Python',
                    'kktp'                => 75,
                    'instructions'        => 'Kerjakan 15 butir soal post-test berikut secara teliti dan mandiri untuk mengevaluasi penguasaan materi pemrograman Python Anda.',
                    'randomize_questions' => false,
                ]
            );

            $postTest->update([
                'title'               => 'Post-test: Pemrograman Dasar Python',
                'kktp'                => 75,
                'instructions'        => 'Kerjakan 15 butir soal post-test berikut secara teliti dan mandiri untuk mengevaluasi penguasaan materi pemrograman Python Anda.',
                'randomize_questions' => false,
            ]);

            // Hapus pertanyaan post-test lama jika ada
            $postTest->questions()->delete();

            $postTestQuestions = [
                [
                    'question_text' => "Perhatikan potongan kode Python berikut:\n\nx = 10\ny = 20\nx, y = y, x + y\nprint(x, y)\n\nOutput yang dihasilkan adalah...",
                    'options' => [
                        'A' => '10 20',
                        'B' => '20 20',
                        'C' => '20 30',
                        'D' => '30 20',
                        'E' => '30 30',
                    ],
                    'correct_answer' => 'C',
                    'score_weight' => 10,
                    'explanation' => "Pada teknik multiple assignment, seluruh nilai di ruas kanan dievaluasi terlebih dahulu sebelum dimasukkan ke ruas kiri. Ruas kanan (y, x + y) bernilai (20, 10 + 20) yaitu (20, 30). Nilai tersebut kemudian ditugaskan ke x dan y, sehingga x = 20 dan y = 30.",
                ],
                [
                    'question_text' => "Perhatikan potongan kode berikut:\n\nteks = \"5\"\npengali = 3\nhasil = teks * pengali\nprint(hasil, type(hasil).__name__)\n\nOutput dari kode tersebut adalah...",
                    'options' => [
                        'A' => '15 int',
                        'B' => '15 str',
                        'C' => '555 int',
                        'D' => '555 str',
                        'E' => 'Error (TypeError)',
                    ],
                    'correct_answer' => 'D',
                    'score_weight' => 10,
                    'explanation' => "Mengalikan tipe data string (str) dengan integer di Python berarti melakukan replikasi string (string repetition). String '5' diulang sebanyak 3 kali menghasilkan '555' dengan tipe data tetap str.",
                ],
                [
                    'question_text' => "Perhatikan kode berikut:\n\nangka1 = \"17\"\nangka2 = int(\"5\")\nhasil = int(angka1) // angka2\nprint(hasil)\n\nOutput yang dihasilkan adalah...",
                    'options' => [
                        'A' => '3.4',
                        'B' => '3',
                        'C' => '2',
                        'D' => '"3"',
                        'E' => 'Error',
                    ],
                    'correct_answer' => 'B',
                    'score_weight' => 10,
                    'explanation' => "int(angka1) menghasilkan 17. Operator // adalah floor division (pembagian bulat ke bawah). 17 dibagi 5 = 3.4, dibulatkan ke bawah menghasilkan bilangan bulat 3.",
                ],
                [
                    'question_text' => "Berapakah hasil dari eksekusi kode ekspresi aritmatika berikut?\n\nhasil = 2 + 3 * 4 ** 2 // 8 - 1\nprint(hasil)",
                    'options' => [
                        'A' => '7',
                        'B' => '9',
                        'C' => '11',
                        'D' => '15',
                        'E' => '23',
                    ],
                    'correct_answer' => 'A',
                    'score_weight' => 10,
                    'explanation' => "Berdasarkan hierarki prioritas operator: 1) Pangkat (**): 4 ** 2 = 16. 2) Perkalian (*): 3 * 16 = 48. 3) Pembagian bulat (//): 48 // 8 = 6. 4) Penjumlahan & Pengurangan (+, -): 2 + 6 - 1 = 7.",
                ],
                [
                    'question_text' => "Perhatikan kode evaluasi kondisi logika berikut:\n\np = 10\nq = 5\nr = 20\n\nkondisi = (p > q) and not (r < p) or (q == 5 and r == 10)\nprint(kondisi)\n\nNilai boolean yang tercetak adalah...",
                    'options' => [
                        'A' => 'True',
                        'B' => 'False',
                        'C' => 'None',
                        'D' => '0',
                        'E' => 'Error',
                    ],
                    'correct_answer' => 'A',
                    'score_weight' => 10,
                    'explanation' => "Evaluasi ekspresi: (p > q) -> True. (r < p) bernilai False -> not (False) -> True. Bagian kiri: True and True -> True. Bagian kanan: (q == 5 and r == 10) bernilai False. Terakhir: True or False menghasilkan True.",
                ],
                [
                    'question_text' => "Perhatikan kode penentuan predikat nilai berikut:\n\nnilai = 78\n\nif nilai >= 85:\n    predikat = \"A\"\nelif nilai >= 75:\n    predikat = \"B\"\nelif nilai >= 65:\n    predikat = \"C\"\nelse:\n    predikat = \"D\"\n\nprint(predikat)\n\nOutput yang ditampilkan adalah...",
                    'options' => [
                        'A' => 'A',
                        'B' => 'B',
                        'C' => 'C',
                        'D' => 'D',
                        'E' => 'Tidak ada output',
                    ],
                    'correct_answer' => 'B',
                    'score_weight' => 10,
                    'explanation' => "Kondisi pertama (nilai >= 85) bernilai False. Program lanjut ke kondisi kedua (elif nilai >= 75) yang bernilai True karena 78 >= 75. Maka predikat = 'B' dieksekusi dan blok percabangan selesai.",
                ],
                [
                    'question_text' => "Berapakah output dari perulangan berikut?\n\ntotal = 0\nfor i in range(1, 10, 2):\n    total += i\nprint(total)",
                    'options' => [
                        'A' => '20',
                        'B' => '24',
                        'C' => '25',
                        'D' => '30',
                        'E' => '45',
                    ],
                    'correct_answer' => 'C',
                    'score_weight' => 10,
                    'explanation' => "range(1, 10, 2) menghasilkan deret ganjil: 1, 3, 5, 7, 9. Jumlah total = 1 + 3 + 5 + 7 + 9 = 25.",
                ],
                [
                    'question_text' => "Perhatikan perulangan while berikut:\n\nn = 1\nwhile n < 15:\n    n *= 3\nprint(n)\n\nOutput akhir saat perulangan selesai adalah...",
                    'options' => [
                        'A' => '9',
                        'B' => '12',
                        'C' => '15',
                        'D' => '27',
                        'E' => 'Infinite Loop',
                    ],
                    'correct_answer' => 'D',
                    'score_weight' => 10,
                    'explanation' => "Iterasi 1: n = 1 * 3 = 3. Iterasi 2: n = 3 * 3 = 9. Iterasi 3: n = 9 * 3 = 27. Pada iterasi 4, kondisi (27 < 15) bernilai False sehingga loop berhenti dan mencetak nilai akhir 27.",
                ],
                [
                    'question_text' => "Perhatikan kode berikut:\n\nhasil = 0\nfor x in range(1, 6):\n    if x == 4:\n        break\n    hasil += x\nprint(hasil)\n\nOutput yang dihasilkan adalah...",
                    'options' => [
                        'A' => '6',
                        'B' => '10',
                        'C' => '15',
                        'D' => '4',
                        'E' => '0',
                    ],
                    'correct_answer' => 'A',
                    'score_weight' => 10,
                    'explanation' => "x = 1 (hasil = 1), x = 2 (hasil = 3), x = 3 (hasil = 6). Ketika x = 4, kondisi terpenuhi dan perintah 'break' menghentikan seluruh perulangan seketika. Nilai akhir hasil adalah 6.",
                ],
                [
                    'question_text' => "Perhatikan potongan kode perulangan berikut:\n\njumlah = 0\nfor i in range(1, 6):\n    if i % 2 == 0:\n        continue\n    jumlah += i\nprint(jumlah)\n\nOutput dari kode di atas adalah...",
                    'options' => [
                        'A' => '15',
                        'B' => '9',
                        'C' => '6',
                        'D' => '5',
                        'E' => '0',
                    ],
                    'correct_answer' => 'B',
                    'score_weight' => 10,
                    'explanation' => "Perintah 'continue' melewati iterasi jika i genap (i % 2 == 0). Angka yang ditambahkan hanya angka ganjil: 1, 3, dan 5. Maka jumlah = 1 + 3 + 5 = 9.",
                ],
                [
                    'question_text' => "Perhatikan fungsi Python di bawah ini:\n\ndef hitung(a, b):\n    total = a + b\n\nhasil = hitung(4, 6)\nprint(hasil)\n\nOutput yang akan tampil di konsol adalah...",
                    'options' => [
                        'A' => '10',
                        'B' => '0',
                        'C' => 'None',
                        'D' => 'Error',
                        'E' => 'total',
                    ],
                    'correct_answer' => 'C',
                    'score_weight' => 10,
                    'explanation' => "Fungsi hitung() tidak menyertakan pernyataan 'return'. Di Python, fungsi yang tidak mengembalikan nilai secara eksplisit akan otomatis mengembalikan nilai default 'None'.",
                ],
                [
                    'question_text' => "Perhatikan pendefinisian fungsi dengan nilai default berikut:\n\ndef hitung_diskon(harga, diskon=0.1):\n    return int(harga - (harga * diskon))\n\nprint(hitung_diskon(100000, 0.2))\nprint(hitung_diskon(50000))\n\nOutput berturut-turut yang dihasilkan adalah...",
                    'options' => [
                        'A' => '80000 dan 50000',
                        'B' => '80000 dan 45000',
                        'C' => '90000 dan 45000',
                        'D' => '100000 dan 45000',
                        'E' => '80000 dan 40000',
                    ],
                    'correct_answer' => 'B',
                    'score_weight' => 10,
                    'explanation' => "Pemanggilan 1: diskon = 0.2 menimpa nilai default -> 100.000 - 20.000 = 80000. Pemanggilan 2: diskon tidak diisi, menggunakan default 0.1 (10%) -> 50.000 - 5.000 = 45000.",
                ],
                [
                    'question_text' => "Perhatikan kode mengenai lingkup variabel (scope) berikut:\n\nx = 10\n\ndef ubah_nilai():\n    x = 25\n    return x\n\nubah_nilai()\nprint(x)\n\nBerapakah nilai x yang dicetak pada baris terakhir?",
                    'options' => [
                        'A' => '25',
                        'B' => '10',
                        'C' => '35',
                        'D' => 'None',
                        'E' => 'Error (UnboundLocalError)',
                    ],
                    'correct_answer' => 'B',
                    'score_weight' => 10,
                    'explanation' => "Variabel x = 25 di dalam fungsi berstatus sebagai variabel lokal. Variabel global x di luar fungsi tidak terpengaruh dan tetap bernilai 10 karena tidak menggunakan kata kunci 'global'.",
                ],
                [
                    'question_text' => "Perhatikan fungsi berikut yang mengembalikan lebih dari satu nilai:\n\ndef hitung_persegi_panjang(p, l):\n    luas = p * l\n    keliling = 2 * (p + l)\n    return luas, keliling\n\na, b = hitung_persegi_panjang(5, 3)\nprint(f\"L={a}, K={b}\")\n\nOutput yang dihasilkan adalah...",
                    'options' => [
                        'A' => 'L=15, K=16',
                        'B' => 'L=16, K=15',
                        'C' => 'L=8, K=16',
                        'D' => 'L=(15, 16), K=None',
                        'E' => 'Error: too many values to unpack',
                    ],
                    'correct_answer' => 'A',
                    'score_weight' => 10,
                    'explanation' => "Fungsi mengembalikan tuple berisi luas (5 * 3 = 15) dan keliling (2 * (5 + 3) = 16). Melalui unpacking 'a, b', nilai a = 15 dan b = 16 sehingga menghasilkan 'L=15, K=16'.",
                ],
                [
                    'question_text' => "Perhatikan program fungsi integrasi berikut:\n\ndef hitung_genap(batas):\n    total = 0\n    for i in range(1, batas + 1):\n        if i % 2 == 0:\n            total += i\n    return total\n\nhasil = hitung_genap(6)\nprint(hasil)\n\nOutput dari program di atas adalah...",
                    'options' => [
                        'A' => '6',
                        'B' => '9',
                        'C' => '12',
                        'D' => '21',
                        'E' => '36',
                    ],
                    'correct_answer' => 'C',
                    'score_weight' => 10,
                    'explanation' => "Fungsi dipanggil dengan parameter batas = 6. range(1, 7) menghasilkan angka 1 s.d. 6. Percabangan if i % 2 == 0 menyaring bilangan genap: 2, 4, 6. Penjumlahan: 2 + 4 + 6 = 12.",
                ],
            ];

            foreach ($postTestQuestions as $idx => $qData) {
                PostTestQuestion::create([
                    'post_test_id'       => $postTest->id,
                    'question_text'      => $qData['question_text'],
                    'options'            => $qData['options'],
                    'correct_answer'     => $qData['correct_answer'],
                    'score_weight'       => $qData['score_weight'],
                    'time_limit_seconds' => null,
                    'explanation'        => $qData['explanation'],
                    'order_num'          => $idx + 1,
                ]);
            }
        });
    }
}
