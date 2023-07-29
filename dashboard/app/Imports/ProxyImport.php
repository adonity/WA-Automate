<?php

namespace App\Imports;

use App\Models\Proxy;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ProxyImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $profile = null;
        $host = $row['host'];
        $proxy = Proxy::where("host", $host)->first();

        if(!$proxy){
            $proxy = new Proxy();
        }

        $proxy->host = $host;
        $proxy->port = $row['port'];
        $proxy->username = $row['username'];
        $proxy->password = $row['password'];
        $proxy->expired = $row['expired'];
        $proxy->save();

        return $proxy;
    }

    public function rules(): array
    {
        return [
            'host' => 'required',
            'port' => 'required',
        ];
    }
}
