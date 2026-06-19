<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use App\Models\Company;

use Illuminate\View\Component;

class CompanySelectorModal extends Component
{
    /**
     * Create a new component instance.
     */
      public $companies;

        public $selectedCompanyId;

    public function __construct($selectedCompanyId = null)
    {
         $this->selectedCompanyId = $selectedCompanyId;
          $this->companies = Company::where('status',1)->get();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.company-selector-modal');
    }
}
