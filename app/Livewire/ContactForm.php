<?php

namespace App\Livewire;

use App\Models\TerraRequest as CitizenRequest;
use Illuminate\Support\Str;
use Livewire\Component;

class ContactForm extends Component
{
    public string $name = '';
    public string $email = '';
    public string $service = 'Je ne sais pas';
    public string $message = '';
    public bool $sent = false;

    public function mount(): void
    {
        if (auth()->check()) {
            $this->name = auth()->user()->name;
            $this->email = auth()->user()->email;
        }
    }

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:80',
            'email' => 'required|email|max:120',
            'service' => 'required|string|max:60',
            'message' => 'required|string|min:10|max:2000',
        ];
    }

    protected function messages(): array
    {
        return [
            'required' => 'Ce champ est obligatoire.',
            'email' => 'Saisissez une adresse e-mail valide.',
            'message.min' => 'Décrivez votre demande en au moins 10 caractères.',
        ];
    }

    public function send(): void
    {
        $data = $this->validate();

        CitizenRequest::query()->create([
            'request_code' => 'TN-'.Str::upper(Str::random(10)),
            'requester_name' => $data['name'],
            'requester_type' => 'citizen',
            'requester_email' => $data['email'],
            'service' => $data['service'],
            'message_public' => $data['message'],
            'user_id' => auth()->id(),
            'status' => 'pending',
        ]);

        $this->sent = true;
        $this->reset('message');
    }

    public function render()
    {
        return view('livewire.contact-form');
    }
}
