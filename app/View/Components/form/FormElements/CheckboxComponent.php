<?php

namespace App\View\Components\form\FormElements;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CheckboxComponent extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that repr.esent the component.
     */
    public function render(): View|Closure|string
    {
        return view('admin.components.form.form-elements.checkbox-component');
    }
}
