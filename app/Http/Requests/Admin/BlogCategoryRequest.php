<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class BlogCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
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
            'title' => "required|max:191|unique:blog_categories,title,{$this->id},id,deleted_at,NULL",
            'description' => 'required|max:191',
            'cover' => 'required_if:cover,null|image|mimes:jpg,png,jpeg,gif,bmp,webp|max:4096|dimensions:max_width=4000,max_height=4000',
        ];
    }
}
