<div>
    <input type="file" wire:model="pdf" class="form-control" accept=".pdf">
    @error('pdf') <span class="text-danger">{{ $message }}</span> @enderror
    <button wire:click="uploadPdf" class="btn btn-primary">Upload PDF</button>
</div>
