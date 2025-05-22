<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CheatSheetRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {

        return [
            'title' => "required|max:191|unique:cheat_sheets,title,{$this->id},id,deleted_at,NULL",
            'content' => 'required|max:4294967295',
            'cheat_sheet_category_id' => 'required|exists:cheat_sheet_categories,id',
            'status' => 'required|in:Postado,Rascunho,Lixo',
        ];
    }
}
