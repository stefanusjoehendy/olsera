<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\item;
use App\Models\pajak;
use Illuminate\Http\Request;
use Illuminate\Validation\Validator;

use function PHPUnit\Framework\isNull;

class ItemTaxDataController extends Controller
{
    public function __construct()
    {
        $this->item = new item();
        $this->pajak = new pajak();
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $itemData = item::with('pajak')->get();
        return ['data' => $itemData];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rulesData = array(
            'nama'  => 'required'
        );
        $rulesDataPajak = array(
            '*.nama'  => 'required',
        );
        
        $validItem = Validator($request->all()['data'], $rulesData);
        $validPajak = Validator($request->all()['data']['pajak'], $rulesDataPajak);

        if ($validItem->fails()){
            return $validItem->errors();
        }
        if ($validPajak->fails()){
            return $validPajak->errors();
        }
        $itemno = $this->item->CreateItem(collect($request->all()['data']));
        $pajak = collect($request->all()['data']['pajak']);
        for($i=0;$i<count($pajak);$i++){
            $this->pajak->CreateTax($itemno,$pajak[$i]);
        }
        return $itemno;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rulesData = array(
            'nama'  => 'required'
        );
        $rulesDataPajak = array(
            '*.nama'  => 'required'
        );
        
        $validItem = Validator($request->all()['data'], $rulesData);
        $validPajak = Validator($request->all()['pajak'], $rulesDataPajak);

        if ($validItem->fails()){
            return $validItem->errors();
        }
        if ($validPajak->fails()){
            return $validPajak->errors();
        }

        $data = item::find($id);
        if (is_null($data)){
            return response()->json(['text' => 'Data Not Found', 'status' => 404 ]);
        }
        $this->item->UpdateItem($id, collect($request->all()['data']));

        $dataPajak = collect($request->all()['pajak']);
        for($i=0;$i<count($dataPajak);$i++){
            $detailPajak = $this->pajak->GetDataPajakByID($dataPajak[$i]['item_id'], $dataPajak[$i]['id']);
            
            if($dataPajak[$i]['deleteflag'] == 1){
                // Hapus Data
                
                if (is_null($detailPajak->get(0))){
                    return response()->json(['text' => 'Data Not Found1', 'status' => 404 ]);
                }
                else {
                    if (collect($detailPajak->get(0))['item_id'] == $id){
                        $this->pajak->DeletePajakDetailByDetailNo($dataPajak[$i]['id'], $id);
                    }
                }
            } 
            if($dataPajak[$i]['id'] != 0){ 
                // Update Data
                if (is_null($detailPajak->get(0))){
                    return response()->json(['text' => 'Data Not Found2', 'status' => 404 ]);
                }
                else {
                    if (collect($detailPajak->get(0))['item_id'] == $id){
                        $this->pajak->UpdatePajakDetail($dataPajak[$i]['id'], $dataPajak[$i]);
                    }
                }
            }
            else{
                // Insert Data
                $this->pajak->InsertPajakDetail($id, $dataPajak[$i]);
            }
        }
        return 'success';
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = item::find($id);
        if (is_null($data)){
            return response()->json(['text' => 'Data Not Found', 'status' => 404 ]);
        }
        else {
            $this->pajak->DeletePajakByItemNo($id);
            $this->item->DeleteItem($id);
            return response()->json(['text' => 'Success', 'status' => 200 ]);
        }
    }
}
