<?php

namespace App\Imports;

use App\Models\Device;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Facades\Auth;
use Dirape\Token\Token;

class DevicesImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    public function model(array $row)
    {
        $profile = null;
        $number = $row['number'];
        $device = Device::where("number", $number)->first();

        if(!$device){
            $device = new Device();
        }

        $device->number = $number;
        $device->name = $row['name'];
        $device->category = $row['category'];
        $device->multidevice = 1;
        $device->status = 0;
        $device->key = (new Token())->Unique('device', 'key', 60);
        $device->id_users = Auth::id();
        $device->save();

        return $device;
    }

    public function rules(): array
    {
        return [
            'number' => 'required',
            'name' => 'required',
            'category' => 'required'
        ];
    }
}
