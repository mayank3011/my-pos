<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;

class EmployeeController extends Controller
{
    public function AllEmployee()
    {
        $employee = Employee::latest()->get();
        return view('backend.employee.all_employee', compact('employee'));
    }

    public function StoreEmployee(Request $request)
    {
        $validateData = $request->validate([
            'name' => 'required|max:200',
            'email' => 'required|unique:employees|max:200',
            'phone' => 'required|max:200',
            'address' => 'required|max:400',
            'salary' => 'required|max:200',
            'vacation' => 'required|max:200',
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
            $directory = public_path('upload/employee/');

            // ✅ Ensure the directory exists
            if (!File::exists($directory)) {
                File::makeDirectory($directory, 0755, true, true);
            }

            $manager = new ImageManager(new Driver());
            $img = $manager->read($image)->resize(300, 300);
            $img->save($directory . $name_gen);

            $save_url = 'upload/employee/' . $name_gen;
        } else {
            $save_url = null;
        }

        Employee::insert([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'experience' => $request->experience,
            'salary' => $request->salary,
            'vacation' => $request->vacation,
            'city' => $request->city,
            'image' => $save_url,
            'created_at' => Carbon::now(),
        ]);

        return redirect()->route('all.employee')->with([
            'message' => 'Employee Inserted Successfully',
            'alert-type' => 'success'
        ]);
    }

    public function AddEmployee()
    {
        return view('backend.employee.add_employee');
    }

    public function DeleteEmployee($id)
    {
        $employee_img = Employee::findOrFail($id);
        $img = $employee_img->image;

        if (File::exists(public_path($img))) {
            File::delete(public_path($img));
        }

        Employee::findOrFail($id)->delete();

        return redirect()->back()->with([
            'message' => 'Employee Deleted Successfully',
            'alert-type' => 'success'
        ]);
    }

    public function EditEmployee($id)
    {
        $employee = Employee::findOrFail($id);
        return view('backend.employee.edit_employee', compact('employee'));
    }

    public function UpdateEmployee(Request $request)
    {
        $employee_id = $request->id;

        if ($request->file('image')) {
            $image = $request->file('image');
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
            $directory = public_path('upload/employee/');

            // ✅ Ensure the directory exists
            if (!File::exists($directory)) {
                File::makeDirectory($directory, 0755, true, true);
            }

            $manager = new ImageManager(new Driver());
            $img = $manager->read($image)->resize(300, 300);
            $img->save($directory . $name_gen);

            $save_url = 'upload/employee/' . $name_gen;

            Employee::findOrFail($employee_id)->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'experience' => $request->experience,
                'salary' => $request->salary,
                'vacation' => $request->vacation,
                'city' => $request->city,
                'image' => $save_url,
                'created_at' => Carbon::now(),
            ]);

            return redirect()->route('all.employee')->with([
                'message' => 'Employee Updated Successfully',
                'alert-type' => 'success'
            ]);
        } else {
            Employee::findOrFail($employee_id)->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'experience' => $request->experience,
                'salary' => $request->salary,
                'vacation' => $request->vacation,
                'city' => $request->city,
                'created_at' => Carbon::now(),
            ]);

            return redirect()->route('all.employee')->with([
                'message' => 'Employee Updated Successfully',
                'alert-type' => 'success'
            ]);
        }
    }
}
