<?php

namespace App\Exports;

use App\Models\EventSeminarApplication;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class EventSeminarApplicationExport implements FromCollection,WithHeadings,WithMapping
{
    private $model;

    public function __construct($model)
    {
        $this->model = $model;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->model;
       
    }
    /**
     * Returns headers for report
     * @return array
     */
    public function headings(): array {
        return [
            'ID', 
            '会員番号',
            '名前',
            'メールアドレス', 
            'ProAttend Partner'
        ];
    }
 
    public function map($model): array {
        return [
            $model->member->id, 
            $model->member->member_number, 
            $model->member->name_kanji, 
            $model->member->email, 
            $model->member->is_partner, 
        ];
    }
}
