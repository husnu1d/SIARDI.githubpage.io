<?php

namespace App\Livewire;

use Livewire\Component;

class PdfUpload extends Component
{
    public function render()
    {
        return view('livewire.pdf-upload');
    }
    public function uploadPdf() { $this->pdf->store('pdfs'); }
    // Add your logic here for handling the uploaded PDF }
}
