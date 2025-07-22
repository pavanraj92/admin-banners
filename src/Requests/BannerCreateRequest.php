<?php

namespace admin\banners\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BannerCreateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [                   
            'title' => 'required|string|min:3|max:255|unique:banners,title',
            'sub_title' => 'required|string|max:255',
            'button_title' => 'required|string|max:255',
            'button_url' => 'required|string|max:255',
            'sort_order' => 'required|numeric|min:0|max:2147483647|unique:banners,sort_order',
            'description' => 'required|string|min:3|max:65535',
            'image' => 'required|image',
            'status' => 'required|in:0,1',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
