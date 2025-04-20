<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CourseCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => "required|max:191|unique:category_courses,name,{$this->id},id,deleted_at,NULL",
            'description' => 'required|max:10000',
            'cover' => 'required_if:cover,null|image|mimes:jpg,png,jpeg,gif,bmp,webp|max:4096|dimensions:max_width=4000,max_height=4000',
        ];
    }
}
