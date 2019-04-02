<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        ini_set('memory_limit', '-1');


        //eliminamos los directorios
        Storage::deleteDirectory('courses');
        Storage::deleteDirectory('users');

        //creamos los directorios
        Storage::makeDirectory('courses');
        Storage::makeDirectory('users');

        //estos seras valores que pasamos y no se crearan por las factorias
        factory(\App\Role::class, 1)->create(['name' => 'admin']);
        factory(\App\Role::class, 1)->create(['name' => 'teacher']);
        factory(\App\Role::class, 1)->create(['name' => 'student']);

        factory(\App\User::class, 1)->create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('secret'),
            'role_id' => \App\Role::ADMIN
            ])//RELACIONAMOS CON ESTUDIANTE
            ->each(function(\App\User $u){
                factory(\App\Student::class, 1)->create(['user_id' => $u->id]);
            });

            factory(\App\User::class , 50)->create()
                ->each(function(\app\User $u){
                    factory(\App\Student::class , 1)->create(['user_id' => $u->id]);
                });

            factory(\App\User::class , 10)->create()
                ->each(function(\app\User $u){
                    factory(\App\Student::class , 1)->create(['user_id' => $u->id]);
                    factory(\App\Teacher::class , 1)->create(['user_id' => $u->id]);
                });

            factory(\App\Level::class , 1)->create(['name' => 'Beginner']);
            factory(\App\Level::class , 1)->create(['name' => 'Intermediate']);
            factory(\App\Level::class , 1)->create(['name' => 'Advanced']);
            factory(\App\Category::class , 5)->create();

            factory(\App\Course::class , 50)
                ->create()
                ->each(function (\App\Course $c){
                    //cada ves que se cree un curso se creara 2 metas y 4 requerimientos
                    $c->goals()->saveMany(factory(\App\Goal::class , 2)->create());
                    $c->requirements()->saveMany(factory(\App\Requirement::class , 4)->create());
                });
    }
}
