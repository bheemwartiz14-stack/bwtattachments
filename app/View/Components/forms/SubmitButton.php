<?php

namespace App\View\Components\Forms;

use Illuminate\View\Component;

class SubmitButton extends Component
{
    public string $text;

    public function __construct(string $text)
    {
        $this->text = $text;
    }

    public function render()
    {
        return view('components.forms.submit-button');
    }
}