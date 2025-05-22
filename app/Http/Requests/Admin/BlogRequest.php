<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class BlogRequest extends FormRequest
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
        if ($this->method() == 'PUT') {
            return [
                'title' => "required|max:191|unique:blogs,title,{$this->id},id,deleted_at,NULL",
                'subtitle' => 'required|max:191',
                'cover' => 'nullable|image|mimes:jpg,png,jpeg,gif,bmp,webp|max:4096|dimensions:max_width=4000,max_height=4000',
                'content' => 'required|max:4294967295',
                'status' => 'required|in:Postado,Rascunho,Lixo',
            ];
        } else {
            return [
                'title' => "required|max:191|unique:blogs,title,{$this->id},id,deleted_at,NULL",
                'subtitle' => 'required|max:191',
                'cover' => 'required|image|mimes:jpg,png,jpeg,gif,bmp,webp|max:4096|dimensions:max_width=4000,max_height=4000',
                'content' => 'required|max:4294967295',
                'status' => 'required|in:Postado,Rascunho,Lixo',
            ];
        }
    }
}
