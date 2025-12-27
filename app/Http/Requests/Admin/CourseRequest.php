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
            'is_promotional' => ! ($this->is_promotional == null),
            'price' => str_replace(',', '.', str_replace('.', '', str_replace('R$ ', '', $this->price))),
            'promotional_price' => str_replace(',', '.', str_replace('.', '', str_replace('R$ ', '', $this->promotional_price))),
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
            'total_hours' => 'required|integer|min:1|max:10000',
            'cover' => 'required_if:cover,null|image|mimes:jpg,png,jpeg,gif,bmp,webp|max:4096|dimensions:max_width=4000,max_height=4000',
            'pdf_file' => 'nullable|file|mimes:pdf|max:51200',
            'status' => 'required|in:Planejamento,Previsto,Em Andamento,Concluído,Disponível,Sob Demanda,Suspenso,Cancelado,Arquivado',
            'active' => 'nullable|boolean',
            'sales_link' => 'nullable|url|max:191',
            'price' => 'required|numeric|min:0|max:999999.99',
            'coupon_code' => 'nullable|string|max:191',
            'promotional_price' => 'nullable|numeric|min:0|max:999999.99',
            'is_promotional' => 'nullable|boolean',
        ];
    }
}
