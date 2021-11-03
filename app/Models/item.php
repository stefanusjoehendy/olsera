<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class item extends Model
{
    use HasFactory;
    protected $table = "item";
    protected $fillable = [
        'nama',
    ];

    public function Pajak(){
    	return $this->hasMany(pajak::class);
    }

    public function CreateItem($data){
        return DB::table('item')->insertGetId(
            [
                'nama' => $data['nama'],
            ]
        );
    }

    public function UpdateItem($id, $data){
        DB::table('item')
        ->where('id', $id)
        ->update(
            [
                'nama' => $data['nama'],
            ]
        );
    }

    public function DeleteItem($invno){
        DB::table('item')
            ->where('id', $invno)
            ->delete();
    }


}
