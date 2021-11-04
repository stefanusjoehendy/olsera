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

    public function GetListItemTax(){
        $data = item::select('item.id', 'item.nama')
        ->join('pajak', 'item.id', '=', 'pajak.item_id')
        ->groupBy('item.id', 'item.nama')
        ->with(['pajak' => function($q){
            $q->select('id', 'item_id', 'nama', DB::raw('concat(CAST(rate*100 as DECIMAL(3,0)), "%") as rate'));
        }])
        ->get();
        return $data;
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
