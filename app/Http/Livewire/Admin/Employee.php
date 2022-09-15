<?php

namespace App\Http\Livewire\Admin;

use App\Models\Employee as ModelsEmployee;
use Livewire\Component;

class Employee extends Component
{
    public $employees, $name, $email, $phone, $address, $employee_id;
    public $updateMode = false;

    public function render()
    {
        $this->employees = ModelsEmployee::get();
        return view('livewire.admin.employees.employee');
    }

    private function resetInputFields()
    {
        $this->name = '';
        $this->email = '';
        $this->phone = '';
        $this->address = '';
    }

    public function store()
    {
        $validatedData = $this->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:employees,email',
            'phone' => 'required|numeric',
            'address' => 'required|max:100'
        ]);
        ModelsEmployee::create($validatedData);

        session()->flash('message', 'Employee Created Successfully.');

        $this->resetInputFields();
    }

    public function edit($id)
    {
        $employee = ModelsEmployee::findOrFail($id);
        $this->employee_id = $id;
        $this->name = $employee->name;
        $this->email = $employee->email;
        $this->phone = $employee->phone;
        $this->address = $employee->address;

        $this->updateMode = true;
    }

    public function cancel()
    {
        $this->updateMode = false;
        $this->resetInputFields();
    }

    public function update()
    {
        $validatedData = $this->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:employees,email,' . $this->employee_id,
            'phone' => 'required|numeric',
            'address' => 'required|max:100'
        ]);

        $employee = ModelsEmployee::find($this->employee_id);
        $employee->update([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
        ]);

        $this->updateMode = false;

        session()->flash('message', 'Employee Updated Successfully.');
        $this->resetInputFields();
    }

    public function delete($id)
    {
        ModelsEmployee::find($id)->delete();
        session()->flash('message', 'Employee Deleted Successfully.');
    }
}
