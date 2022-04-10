<?php
namespace App\Export;

use App\Models\Reseller;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ResellerExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    public function collection()
    {
        return DB::table('resellers')->select('username', 'full_name', 'phone_number', 'point','whatsapp','status')->get();
    }

    public function headings(): array
    {
        return ["Username", "Nama Lengkap", "No HP", "Point", "Wa", "Status"];
    }
}
