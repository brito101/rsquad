<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CourseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return true;
    }

    public function prepareForValidation()
    {
        $this->merge([
            'active' => ! ($this->active == null),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => "required|max:191|unique:courses,name,{$this->id},id,deleted_at,NULL",
            'description' => 'required|max:4294967295',
            'cover' => 'required_if:cover,null|image|mimes:jpg,png,jpeg,gif,bmp,webp|max:4096|dimensions:max_width=4000,max_height=4000',
            'status' => 'required|in:Planejamento,Previsto,Em Andamento,Concluído,Disponível,Sob Demanda,Suspenso,Cancelado,Arquivado',
            'active' => 'nullable|boolean',
            'sales_link' => 'nullable|url|max:191',
        ];
    }
}
