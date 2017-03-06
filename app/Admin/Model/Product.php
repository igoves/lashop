<?php

namespace App\Admin\Model;

use Illuminate\Database\Eloquent\Model;
use Intervention\Image\Facades\Image;

class Product extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'desc',
        'image',
        'cost',
        'cat_id',
        'meta_desc',
        'meta_key',
    ];
    protected $hidden = [
        'created_at',
        'updated_at'
    ];

//    public function setImage($field, $image)
//    {
//        parent::setImage($field, $image);
//        $file = $this->$field;
//        if ( ! $file->exists()) return;
//        $path = $file->getFullPath();
//        Image::make($path)->resize(640, 480)->save();
//    }


//    public function contacts()
//    {
//        return $this->belongsToMany(Contact::class);
//    }
//    public function scopeWithContact($query, $contactId)
//    {
//        $query->whereHas('contacts', function ($q) use ($contactId) {
//            $q->where('contact_id', $contactId);
//        });
//    }
//    public function setContactIdAttribute($contactId)
//    {
//        $this->save();
//        $contact = Contact::find($contactId);
//        $this->contacts()->attach($contact);
//    }
}