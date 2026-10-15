<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersManager extends Component
{
    public $name;
    public $email;
    public $password;
    public $role = 'customer'; // القيمة الافتراضية لفورم الإضافة الجديد

    public function saveUser()
    {
        $this->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role'     => 'required|in:admin,manager,driver,customer',
        ]);

        User::create([
            'name'     => $this->name,
            'email'    => $this->email,
            'password' => Hash::make($this->password),
            'role'     => $this->role,
        ]);

        $this->reset(['name', 'email', 'password']);
        $this->role = 'customer';
        
        session()->flash('success', 'تم إنشاء المستخدم وتعيين الصلاحية بنجاح! 👤');
    }

    // دالة لتحديث صلاحية مستخدم محدد فوراً عند تغييرها من الجدول
    public function updateUserRole($userId, $newRole)
    {
        $user = User::find($userId);
        if ($user) {
            $user->role = $newRole;
            $user->save();
            session()->flash('success', "تم تحديث صلاحية المستخدم {$user->name} بنجاح!");
        }
    }

    public function toggleActive($id)
    {
        $user = User::find($id);
        if ($user) {
            $user->is_active = !$user->is_active;
            $user->save();
        }
    }

    public function deleteUser($id)
    {
        if (auth()->id() == $id) {
            session()->flash('error', 'لا يمكنك حذف حسابك الحالي!');
            return;
        }

        User::find($id)?->delete();
        session()->flash('success', 'تم حذف المستخدم بنجاح.');
    }

    public function render()
    {
        return view('livewire.users-manager', [
            'users' => User::latest()->get()
        ]);
    }
}