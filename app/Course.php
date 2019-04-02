<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Course
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Course newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Course newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Course query()
 * @mixin \Eloquent
 */
class Course extends Model
{
    const PUBLISHED = 1;
    const PENDING = 2;
    const REJECTED = 3;

    protected $withCount = ['reviews' , 'students'];

    public function pathAttachment (){
        return "/images/courses/" . $this->picture;
    }

    public function getRouteKeyName() {
        return 'slug';
    }

    public function category (){
        return $this->belongsTo(Category::class)->select('id' , 'name');
    }

    public function goals (){
        return $this->hasMany(Goal::class)->select('id' , 'course_id' , 'goal');
    }

    public function level (){
        return $this->belongsTo(Level::class)->select('id' , 'name');
    }

    public function reviews (){
        return $this->hasMany(Review::class)->select('id' , 'user_id' , 'course_id' , 'rating' , 'comment' , 'created_at');
    }

    public function requirements (){
        return $this->hasMany(Requirement::class)->select('id' , 'course_id' , 'requirement');
    }
    //relacion muchos a muchos
    public function students (){
        return $this->belongsToMany(Student::class);
    }

    public function teacher (){
        return $this->belongsTo(Teacher::class);
    }

    //el nombre rating es importante si se cambia tambien debes cambiar al momento que imprime
    //getCostomRatingAttribute => costom_rating
    public function getRatingAttribute (){
        return $this->reviews->avg('rating');
    }

    //cursos relacionados
    public function relatedCourses (){
        return Course::with('reviews')->whereCategoryId($this->category->id)
            ->where('id', '!=' , $this->id)
            ->latest()
            ->limit(6)
            ->get();
    }

}
