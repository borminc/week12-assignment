<?php

namespace App\Http\Controllers;

use App\Models\ToDoItem;
use Illuminate\Http\Request;

class ToDoItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $public_items = ToDoItem::where('status', 'public')->get();
        $public_items->load('user');
        return response()->json($public_items, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addItem(Request $request)
    {
        $inputs = $request->validate([
            'name' => 'required|string',
            'done' => 'integer',
            'status' => 'string'
        ]);

        $item = new ToDoItem();
        $item->name = $inputs['name'];
        $item->done = isset($inputs['done']) ? $inputs['done'] : 0;
        $item->status = isset($inputs['status']) ? $inputs['status'] : 'public';
        $item->user_id = auth()->user()->id;
        $item->save();

        return response()->json([
            'message' => 'Successfully added new item: ' . $request->name
        ]);        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ToDoItem  $toDoItem
     * @return \Illuminate\Http\Response
     */
    public function listUserItems()
    {
        $items = auth()->user()->toDoItems;
        return response()->json($items, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ToDoItem  $toDoItem
     * @return \Illuminate\Http\Response
     */
    public function editItem(Request $request, $id)
    {
        $inputs = $request->validate([
            'name' => 'string',
            'done' => 'integer',
            'status' => 'string'
        ]);

        $item = ToDoItem::findOrFail($id);

        if (auth()->user()->id !== $item->user->id) {
            return response()->json([
                'error' => 'Unauthorized'
            ], 401); 
        }

        if (isset($inputs['name'])) {
            $item->name = $inputs['name'];
        }
        if (isset($inputs['done'])) {
            $item->done = $inputs['done'];
        }
        if (isset($inputs['status'])) {
            $item->status = $inputs['status'];
        }

        $item->save();
        return response()->json([
            'message' => 'Successfully edited item: ' . $item->name
        ]);  
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ToDoItem  $toDoItem
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item = ToDoItem::findOrFail($id);

        if (auth()->user()->id !== $item->user->id) {
            return response()->json([
                'error' => 'Unauthorized'
            ], 401); 
        }
        
        $name = $item->name;
        $item->delete(); 

        return response()->json([
            'message' => 'Successfully deleted item: ' . $name
        ]);  
    }
}
