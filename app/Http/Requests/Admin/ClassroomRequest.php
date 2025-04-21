<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ClassroomRequest extends FormRequest
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
            'release_date' => $this->release_date ? date('Y-m-d', strtotime($this->release_date)) : null,
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
            'name' => "required|max:191|unique:classrooms,name,{$this->id},id,deleted_at,NULL",
            'status' => 'required|in:Publicado,Rascunho,Suspenso,Cancelado,Arquivado',
            'course_id' => 'required|exists:courses,id',
            'active' => 'nullable|boolean',
            'release_date' => 'nullable|date_format:Y-m-d',
            'link' => 'nullable|url|max:191',
        ];
    }
}
