<?php

namespace App\Imports;

use App\Models\Contact;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ContactImport implements ToModel, WithHeadingRow
{

    protected $type = 1;

    function __construct($type){
        $this->type = $type;
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $profile = null;
        $number = formatNumber($row['number']);

        $contact = Contact::where("number", $number)->first();

        if(!$contact){
            $contact = new Contact();
            $contact->id_user = \Auth::id();
        }

        $contact->name = $row['name'];
        $contact->info1 = $row['info1'];
        $contact->info2 = $row['info2'];
        $contact->info3 = $row['info3'];
        $contact->number = $number;
        $contact->type = $this->type;
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
