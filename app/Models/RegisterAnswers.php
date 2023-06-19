<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegisterAnswers extends Model
{
    use HasFactory;
    protected $table = 'register_answers';
    protected $fillable = [
        'question_id',
        'answer',
        'answer_en'
    ];
    public function getAnswerLangAttribute(){
        return app()->getLocale() == 'ar'  ? $this->answer : $this->answer_en;
    }
    public function question()
    {
        return $this->belongsTo(RegisterQuestion::class , 'question_id');
    }
    public function restaurants()
    {
        return $this->hasMany(Restaurant::class , 'answer_id');
    }
}
