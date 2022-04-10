<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SupplierExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return DB::table('users')->select('username', 'full_name', 'phone_number', 'email', 'point','whatsapp','status')->get();
    }

    public function headings(): array
    {
        return ["Username", "Nama Lengkap", "No HP", "Email" ,"Point", "Wa", "Status"];
    }
}
