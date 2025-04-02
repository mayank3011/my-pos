<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Group;
use Carbon\Carbon;


class GroupController extends Controller
{
    public function AllGroup()
    {
        $group = Group::latest()->get();
        return view('backend.group.all_group', compact('group'));
    } // End Method
    public function StoreGroup(Request $request)
    {
        Group::insert([
            'group_name' => $request->group_name,
            'created_at' => Carbon::now(),
        ]);
        $notification = array(
            'message' => 'Group Inserted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.group')->with($notification);
    } // End Method
    public function EditGroup($id)
    {
        $group = Group::findOrFail($id);
        return view('backend.group.edit_group', compact('group'));
    } // End Method

    public function UpdateGroup(Request $request)
    {
        $group_id = $request->id;

        Group::findOrFail($group_id)->update([
            'group_name' => $request->group_name,
            'created_at' => Carbon::now(),
        ]);

        $notification = array(
            'message' => 'Group Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.group')->with($notification);
    } // End Method

    public function DeleteGroup($id)
    {
        Group::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Group Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    } // End Method
}
