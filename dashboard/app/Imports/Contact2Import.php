<?php

namespace App\Imports;

use App\Models\Contact2;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ContactImport2 implements ToModel, WithHeadingRow
{

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $profile = null;
        $number = formatNumber($row['number']);

        $contact = Contact2::where("number", $number)->first();

        if(!$contact){
            $contact = new Contact2();
            $contact->id_user = \Auth::id();
        }

        $contact->name = $row['name'];
        $contact->number = $number;
        $contact->save();

        return $contact;
    }

    public function rules(): array
    {
        return [
            'name' => 'required',
            'number' => 'required',
        ];
    }
}
