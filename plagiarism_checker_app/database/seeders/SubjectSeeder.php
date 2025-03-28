<?php

namespace Database\Seeders;

use App\Models\Subject;
use App\Traits\SeedValidator;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    use SeedValidator;

    private array $subjects = [
        // Engineering & Technology
        [
            'name' => 'Công nghệ thông tin',
            'code' => 'CNTT',
            'description' => 'Chuyên ngành về lập trình, phát triển phần mềm, cơ sở dữ liệu và hệ thống thông tin',
        ],
        [
            'name' => 'Kỹ thuật phần mềm',
            'code' => 'KTPM',
            'description' => 'Chuyên sâu về quy trình phát triển phần mềm, kiểm thử và đảm bảo chất lượng',
        ],
        [
            'name' => 'Khoa học máy tính',
            'code' => 'KHMT',
            'description' => 'Nghiên cứu về thuật toán, trí tuệ nhân tạo và khoa học dữ liệu',
        ],

        // Business & Economics
        [
            'name' => 'Quản trị kinh doanh',
            'code' => 'QTKD',
            'description' => 'Đào tạo về quản lý, marketing và phát triển doanh nghiệp',
        ],
        [
            'name' => 'Tài chính ngân hàng',
            'code' => 'TCNH',
            'description' => 'Chuyên ngành về tài chính, ngân hàng và thị trường chứng khoán',
        ],
        [
            'name' => 'Kế toán kiểm toán',
            'code' => 'KTKT',
            'description' => 'Đào tạo về kế toán, kiểm toán và phân tích tài chính',
        ],

        // Medical & Healthcare
        [
            'name' => 'Y đa khoa',
            'code' => 'YDK',
            'description' => 'Đào tạo bác sĩ đa khoa và chuyên khoa',
        ],
        [
            'name' => 'Dược học',
            'code' => 'DH',
            'description' => 'Nghiên cứu về dược phẩm và phát triển thuốc',
        ],
        [
            'name' => 'Điều dưỡng',
            'code' => 'DD',
            'description' => 'Đào tạo về chăm sóc sức khỏe và hỗ trợ y tế',
        ],

        // Arts & Design
        [
            'name' => 'Thiết kế đồ họa',
            'code' => 'TKDH',
            'description' => 'Chuyên về thiết kế hình ảnh và truyền thông thị giác',
        ],
        [
            'name' => 'Kiến trúc',
            'code' => 'KT',
            'description' => 'Thiết kế kiến trúc và quy hoạch đô thị',
        ],
        [
            'name' => 'Thiết kế nội thất',
            'code' => 'TKNT',
            'description' => 'Thiết kế và trang trí không gian nội thất',
        ],
    ];

    public function run(): void
    {
        if ($this->isSkipSeed(Subject::class)) {
            return;
        }

        collect($this->subjects)->each(fn ($subject) => Subject::create($subject));
    }
}
