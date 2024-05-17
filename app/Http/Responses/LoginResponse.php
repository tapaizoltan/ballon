<?php

namespace App\Http\Responses;

use App\Filament\Pages\Checkin;
use Illuminate\Http\RedirectResponse;
use Livewire\Features\SupportRedirects\Redirector;

class LoginResponse extends \Filament\Http\Responses\Auth\LoginResponse
{
    public function toResponse($request): RedirectResponse|Redirector
    {
        if (auth()->user()->hasRole(['admin'])) {
            return parent::toResponse($request);
        }

        return redirect()->to(Checkin::getUrl());
    }
}
