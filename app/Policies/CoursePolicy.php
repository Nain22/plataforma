<?php

namespace App\Policies;

use App\User;
use App\Course;
use App\Role;

use Illuminate\Auth\Access\HandlesAuthorization;

class CoursePolicy
{
    use HandlesAuthorization;

    public function opt_for_course (User $user, Course $course) {
    	return ! $user->teacher || $user->teacher->id !== $course->teacher_id;
    }

    public function subscribe (User $user) {
        //verifica que el usuario no sea admin y que el usuario no este suscrito a un plan
    	return $user->role_id !== Role::ADMIN && ! $user->subscribed('main');
    }

    public function inscribe (User $user, Course $course) {
        //conprobamos si existe un registro con una linea de codigo
    	return ! $course->students->contains($user->student->id);
    }

}
