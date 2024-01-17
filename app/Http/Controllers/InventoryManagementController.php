<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Validator;

class InventoryManagementController extends Controller
{
    public function InventoryAdd(Request $request){
        $validate =Validator::make($request->all(), [
            'inventoryName' => 'required',
            'quantity' => 'required'
        ]);

        if($validate->fails()){
            return response()->json($validate->errors(), 400);
        }
        $inventory = array(
            'inventoryName' => $request->inventoryName,
            'status' => 1,
            'createdAt' => date('Y-m-d h:i:s')
        );
        $inventoryExists= DB::table('inventory')->where('inventoryName', $request->inventoryName)->first();
        if($inventoryExists){
            $inventory['quantity'] = $inventoryExists->quantity+($request->quantity);
            $updateInventory = DB::table('inventory')->where('inventoryName', $request->inventoryName)->update($inventory);
            if($updateInventory){
                return response() ->json(['message' => 'Item updated Successfully'], 200);
            }else{
                return response()-> json("Oops! something went wrong", 400);
            }
        }else{
            $inventory['quantity'] = $request->quantity;
            $savedData=DB::table('inventory')->insert($inventory);
            if($savedData){
                return response() ->json(['message' => 'Item Registered Successfully'], 200);
            }else{
                return response() ->json("Oops! Something Went Wrong", 400);
            }

        }
    }
    
    public function InventoryList(){
        $inventoryList =DB::table('inventory')->where('status', 1)->get();
        if($inventoryList){
            return response() ->json(['inventoryList' => $inventoryList], 200);
        }else{
            return response() ->json('Oops! something went wrong', 400);
        }
    }

    public function removeInventory(Request $request){
        $removeInventory =DB::table('inventory')->where('InventoryId', $request->InventoryId)->update(['status'=> 0]);
        if($removeInventory){
            return response()->json('Successfully deleted Inventory', 200);
        }else{
            return response()->json('Ooops! something went wrong', 400);
        }
    }

    public function consumableItemList(){
        $inventoryList =DB::table('inventory')->select(["InventoryId", "inventoryName", "quantity"])->where('quantity', '>', 0)->get();
        if($inventoryList){
            return response() ->json(['inventoryList' => $inventoryList], 200);
        }else{
            return response() ->json('Oops! something went wrong', 400);
        }
    }

    public function AddQuantity(Request $request){
        $validate = Validator::make($request->all(), [
            'quantity' => 'required'
        ]);

        if($validate-> fails()){
            return response()-> json($validate->errors(), 400);
        }
        $currQty= DB::table('inventory') ->where('inventoryId', $request->itemId)->first();
        $newQty=$currQty->quantity+($request->quantity);
            $quantityConsumed= DB::table('inventory')-> where('inventoryId', $request->itemId)->update(['quantity' => $newQty, 'updatedAt' =>date('Y-m-d h:i:s')]);
            if($quantityConsumed){
                return response() ->json(['Quantity updated Successfully'], 200);
            }else{
                return response() ->json('Ooops! something went wrong', 400);
            }
    }

    public function updateQuantity(Request $request){
        $validate = Validator::make($request->all(), [
            'quantity' => 'required'
        ]);

        if($validate-> fails()){
            return response()-> json($validate->errors(), 400);
        }
        $currQty= DB::table('inventory') ->where('inventoryId', $request->itemId)->first();

        $consumedItem= array(
            'itemsid' =>  $request ->itemId,
            'consumedQauntity' => $request ->quantity,
            'markedByUserId' => $request-> userId,
            'markedAt'  => date('Y-m-d h:i:s')
        );

        $savedConsumedItem = DB::table('consumeditems')->insert($consumedItem);
        if($savedConsumedItem){
            $remQty=$currQty->quantity-($request->quantity);
            $quantityConsumed= DB::table('inventory')-> where('inventoryId', $request->itemId)->update(['quantity' => $remQty, 'updatedAt' =>date('Y-m-d h:i:s')]);
            if($quantityConsumed){
                return response() ->json(['Quantity updated Successfully'], 200);
            }else{
                return response() ->json('Ooops! something went wrong', 400);
            }
        }else{
            return response() ->json('Ooops! something went wrong', 400);
        }
    }

    public function ConsumedItemList(){
        $consumedItems= DB::table('consumeditems')->join('inventory', 'inventory.InventoryId', 'consumeditems.itemsId')->join('users', 'users.id', 'consumeditems.markedByUserId')->select([
            'inventory.inventoryName', 'consumeditems.consumedQauntity', 'users.name', 'consumeditems.markedAt', 'inventory.quantity as remainingQauntity'
        ])->orderBy('markedAt', $direction="desc")->get();
        if($consumedItems){
            return response()->json(['consumedItems' => $consumedItems], 200);
        }else{
            return response()->json('Ooops! something went wrong', 400);
        }
    }

    public function addOffices(Request $request){
        $validate = Validator::make($request->all(), [
            'officeCapacity' => 'required',
            'officeHead' => 'required',
            'address' =>  'required',
            'provinceId' => 'required',
            'districtId' => 'required'
        ]);

        if($validate-> fails()){
            return response()-> json($validate->errors(), 400);
        }

        $officesData = array(
            'officeCapacity' => $request-> officeCapacity,
            'officeHead' => $request-> officeHead,
            'address' =>  $request-> address,
            'provinceId' => $request-> provinceId,
            'districtId' => $request-> districtId,
            'status' => 1,
            'createdAt' => date('Y-d-m h:i:s')
        );

        $saveOffices = DB::table('offices')->insert($officesData);
        if($saveOffices){
            return response()->json(['message' => 'Item Registered Successfully'], 200);
        }else{
            return response()->json('Ooops! something went wrong', 400);
        }
    }

    public function officesList(){
        $officesList = DB::table('offices') ->join('district', 'offices.districtId', 'district.id')->join('province', 'offices.provinceId', 'province.id')->select(['province.name as province', 'district.name as district', 'offices.officeCapacity', 'offices.address', 'offices.officeHead'])->get();
        if($officesList){
            return response()-> json(['officesList' => $officesList], 200);
        }else{
            return response()->json('Ooops! something went wrong', 400);
        }
    }

    
    public function addExpenses(Request $request){
        $validate= Validator::make($request->all(), [
            'expenseTitle' =>'required',
            'expenseFor' =>'required',
            'expenseAmount' =>'required',
            'month' =>'required',
            'day' =>'required',
            'otherdetails' =>'required'
        ]);
        if($validate->fails()){
            return response()->json($validate->errors(), 400);
        }
        $expenseData= array(
            'expenseTitle' =>$request-> expenseTitle,
            'expenseFor' =>$request-> expenseFor,
            'expenseAmount' =>$request-> expenseAmount,
            'month' =>$request-> month,
            'day' =>$request-> day,
            'date' =>date('Y-m-d h:i:m'),
            'otherdetails' =>$request-> otherdetails,
            'status' => 1,
        );
        $savedData=DB::table('expenses')->insert($expenseData);
        if($savedData){
            return response()->json(['message' => 'Item Registered Successfully'], 200);
        }else{
            return response()->json('Ooops! something went wrong', 400);
        }
    }
    
    public function expensesList(){
        $expenseList=DB::table('expenses')->get();
        if($expenseList){
            return response()->json(['expenseList'=>$expenseList], 200);
        }else{
            return response()->json('Ooops! something went wrong', 400);
        }
    }


    public function addCars(Request $request){
        $validate =Validator::make($request->all(), [
            'carName' => 'required',
            'model' => 'required',
            'registrationNo' => 'required',
            'otherDetails' => 'required',
            'image' => 'required'
        ]);
       if($validate->fails()){
        return response()-> json($validate->errors(), 400);
       }

       $fileName=time() .".".$request->file('image')->getClientOriginalExtension();
       $request->file('image')->storeAs('public/uploads', $fileName);
       $carData=array(
        'carName' => $request->carName,
            'model' => $request->model,
            'registrationNo' => $request->registrationNo,
            'otherDetails' => $request->otherDetails,
            'image' => $fileName,
            'status' => 1,
            'createdAt' => date('Y-m-d h:i:s')
       );
       $savedCars= DB::table('cars')->insert($carData);
       if($savedCars){
        return response()->json(['message' => 'Item Registered Successfully'], 200);
       }else{
        return response()->json('Ooops! something went wrong', 400);
       }
    }

    public function carList(){
        $carList=DB::table('cars')->get();
        $path = 'http://146.71.76.22/sopstudentnewbackend/public/userimage/';
        if($carList){
            return response()->json(['carList'=>$carList, "path" => $path], 200);
        }else{
            return response()->json('Ooops! something went wrong', 400);
        }
    }

}
