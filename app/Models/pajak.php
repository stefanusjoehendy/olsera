<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class pajak extends Model
{
    use HasFactory;
    protected $table = "pajak";
    protected $fillable = [
        'item_id',
        'nama',
        'rate',
    ];

    public function Item(){
    	return $this->belongsTo(item::class);
    }

    public function GetDataPajakByID($itemno, $id){
        return DB::table('pajak')
            ->where('item_id', $itemno)
            ->where('id', $id)
            ->get()
            ;
    }

    public function CreateTax($itemno,$data){
        return DB::table('pajak')->insert(
            [
                'item_id' => $itemno,
                'nama' => $data['nama'],
                'rate' => $data['rate'],
            ]
        );
    }

    public function DeletePajakDetailByDetailNo($itemno, $id){
        DB::table('pajak')
            ->where('id', $itemno)
            ->where('item_id', $id)
            ->delete();
    }

    public function UpdatePajakDetail($pajakNo, $data){
        DB::table('pajak')
            ->where('id', $pajakNo)
            ->update(
                [
                    'nama' =>  $data['nama'],
                    'rate' =>  $data['rate']
                ]
            );
    }

    public function InsertPajakDetail ($itemno, $data){
        DB::table('pajak')->insert(
            [
                'item_id' => $itemno,
                'nama' =>  $data['nama'],
                'rate' =>  $data['rate']
            ]
        );
    }

    public function DeletePajakByItemNo($itemno){
        DB::table('pajak')
            ->where('item_id', $itemno)
            ->delete();
    }
}
