<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Services\ResponseTransformer;
use DataTables;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    public function store(Request $request)
    {
        try{
            $checkEmail = Customer::where('email',$request->email)->first();
            if($checkEmail){
                return ResponseTransformer::default(null,'Email already used',400);
            }
            $req = $request->except(['password','action','hidden_id']);
            $req['password'] = Hash::make($request->password);
            $response = Customer::create($req);

            return ResponseTransformer::default($response,'Data added',201);
        }catch(\Exception $e){
            return ResponseTransformer::default(null,'Internal Server Error',500);
        }
    }

    public function getCustomerData(){
        try{
            $customer = Customer::orderBy('id','DESC')->get();
            $response = DataTables::of($customer)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        $button = '<a href="javascript:;"><button class="btn btn-warning btn-sm edit" style="float:left;margin-left:30px" id="'.$row['id'].'"><i class="fa fa-pencil"></i> Edit</button></a>';
                        $button .= '<a href="javascript:;"><button class="btn btn-danger btn-sm delete" id="'.$row['id'].'"><i class="fa fa-trash"></i> Delete</button></a>';
                        return $button;
                    })
                    ->make(true);

            return ResponseTransformer::default($response,'Success load data',200);
        }catch(\Exception $e){
            return ResponseTransformer::default(null,'Internal Server Error',500);
        }
    }
    
    public function show($id)
    {
        try{
            $response = Customer::find($id);
            if(!$response){
                return ResponseTransformer::default(null,'Customer not found',404);
            }
            return ResponseTransformer::default($response,'Success load data',200);
        }catch(\Exception $e){
            return ResponseTransformer::default(null,'Internal Server Error',500);
        }
    }

    public function update(Request $request, $id)
    {
        try{
            $checkEmail = Customer::where('email',$request->email)->first();
            if($checkEmail){
                return ResponseTransformer::default(null,'Email telah digunakan',400);
            }
            $req = $request->except(['password','action','hidden_id']);
            if($request->password){
                $req['password'] = Hash::make($request->password);
            }
            $response = Customer::find($id)->update($req);
            return ResponseTransformer::default($response,'Data updated',200);
        }catch(\Exception $e){
            return ResponseTransformer::default(null,'Internal Server Error',500);
        }
    }

    public function destroy($id)
    {
        try{
            $data = Customer::find($id);
            $data->delete();
            return ResponseTransformer::default(null,'Data deleted',204);
        }catch(\Exception $e){
            return ResponseTransformer::default(null,'Internal Server Error',500);
        }
    }
}
